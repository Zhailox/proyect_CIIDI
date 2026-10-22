import sys
import json
import time
import traceback
import warnings

# Ignorar advertencias para que no ensucien el stdout JSON
warnings.filterwarnings("ignore")

# Las dependencias se importan de forma protegida: si faltan, se devuelve un JSON
# de error legible para que PHP pueda informar el motivo y activar el respaldo.
try:
    import numpy as np
    import sklearn
    from sklearn.linear_model import LinearRegression
    from sklearn.preprocessing import PolynomialFeatures
    from sklearn.ensemble import RandomForestRegressor
    from sklearn.pipeline import make_pipeline
    from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
    DEPS_ERROR = None
except Exception as _e:
    DEPS_ERROR = "%s: %s" % (type(_e).__name__, _e)

# Año académico = 3 periodos trimestrales  ->  2 años = 6 periodos, 1 año = 3 periodos
HIST_WINDOWS = {"2y": 6, "1y": 3}
BASE_MODELS = ("linear", "poly", "rf")
VALID_MODELS = ("linear", "poly", "rf", "auto")
MODEL_LABELS = {
    "linear": "Regresión Lineal Múltiple",
    "poly": "Regresión Polinómica (Grado 2)",
    "rf": "Random Forest Regressor",
    "auto": "Auto-ML (Mejor Ajuste por Línea)",
}


def emit(obj):
    """ Escribe el resultado como una única línea JSON en stdout """
    sys.stdout.write(json.dumps(obj))
    sys.stdout.write("\n")
    sys.stdout.flush()


def impute_nulls(y):
    """ Funcion de imputacion de valores nulos para series temporales """
    mean_val = np.nanmean(y) if not np.all(np.isnan(y)) else 0
    return np.where(np.isnan(y), mean_val, y)


def rf_estimators(max_epochs):
    """ Random Forest no tiene 'epochs': se mapean a n_estimators con un tope razonable """
    try:
        n = int(max_epochs)
    except (TypeError, ValueError):
        n = 150
    return max(10, min(n, 500))


def build_model(kind, n_estimators):
    if kind == "poly":
        return make_pipeline(PolynomialFeatures(degree=2, include_bias=False), LinearRegression())
    if kind == "rf":
        return RandomForestRegressor(n_estimators=n_estimators, random_state=42, n_jobs=1)
    return LinearRegression()


def fit_predict(kind, y, pred_steps, n_estimators):
    """ Entrena el modelo indicado y devuelve (proyeccion, rmse, mae, r2) """
    n = len(y)
    if n < 2 or np.all(y == y[0]):
        flat = float(y[-1]) if n >= 1 else 0.0
        return np.full(pred_steps, max(0.0, flat)), 0.0, 0.0, 1.0

    X = np.arange(n).reshape(-1, 1)
    model = build_model(kind, n_estimators)
    model.fit(X, y)

    # Proyectar ciclos futuros
    X_future = np.arange(n, n + pred_steps).reshape(-1, 1)
    raw_preds = model.predict(X_future)
    y_pred_train = model.predict(X)

    # Regularización preventiva en Polinómica para evitar explosión cuadrática irreal en horizontes largos
    if kind == "poly":
        hist_max = float(np.max(y))
        cap_upper = max(hist_max * 2.5 + 5.0, 15.0)
        raw_preds = np.minimum(raw_preds, cap_upper)

    predictions = np.maximum(0.0, raw_preds)
    y_pred_train = np.maximum(0.0, y_pred_train)

    # Extraer indicadores de error y bondad de ajuste sobre el histórico
    rmse = float(np.sqrt(mean_squared_error(y, y_pred_train)))
    mae = float(mean_absolute_error(y, y_pred_train))
    try:
        var_y = float(np.var(y))
        if var_y < 1e-9:
            r2 = 1.0 if rmse < 1e-6 else 0.0
        else:
            r2 = float(max(0.0, min(1.0, r2_score(y, y_pred_train))))
    except Exception:
        r2 = 0.0

    return predictions, rmse, mae, r2


def choose_auto_model(candidate_results, y, n_estimators):
    """
    Selecciona el modelo más fiable mediante Validación Walk-Forward (Out-of-Sample
    sobre el último periodo real) combinada con el RMSE de entrenamiento.
    """
    if len(y) < 4 or np.count_nonzero(y) <= 1:
        return "linear"

    y_train = y[:-1]
    y_val = float(y[-1])

    scores = {}
    for kind, res in candidate_results.items():
        rmse_in = res["rmse"]
        try:
            val_pred, _, _, _ = fit_predict(kind, y_train, 1, n_estimators)
            val_err = abs(float(val_pred[0]) - y_val)
        except Exception:
            val_err = rmse_in

        penalty = 1.0 if kind == "linear" else (1.06 if kind == "poly" else 1.15)
        scores[kind] = (0.65 * val_err + 0.35 * rmse_in) * penalty

    return min(scores, key=scores.get)


def handle_trends(input_file):
    """ Pipeline de Regresion de Series Temporales (Proyeccion de Volumen y Tendencias) """
    t0 = time.time()
    with open(input_file, "r", encoding="utf-8") as f:
        data = json.load(f)

    if not isinstance(data, dict):
        data = {"areas": data}

    areas = data.get("areas", [])
    labels = data.get("labels") or []
    pred_steps = max(1, min(int(data.get("pred_steps", 4) or 4), 24))

    historial_base = data.get("historial_base", "all")
    ml_model_type = data.get("ml_model", "linear")
    if ml_model_type not in VALID_MODELS:
        ml_model_type = "linear"
    n_estimators = rf_estimators(data.get("max_epochs", 150))
    compare_models = bool(data.get("compare_models", True))

    if not areas:
        emit({"error": "No data provided in input"})
        return

    total_len = len(labels) if labels else max(len(a.get("history", [])) for a in areas)
    window = HIST_WINDOWS.get(historial_base)
    window = total_len if window is None else min(window, total_len)
    trimmed_labels = labels[-window:] if labels else []

    results = {
        "type": "trends_projection",
        "engine": "python",
        "engine_detail": "Python %s · scikit-learn %s" % (sys.version.split()[0], sklearn.__version__),
        "model_used": ml_model_type,
        "model_label": MODEL_LABELS[ml_model_type],
        "history_terms": trimmed_labels,
        "areas": [],
        "metrics": {},
        "params": {
            "historial_base": historial_base if historial_base in HIST_WINDOWS else "all",
            "points_used": window,
            "pred_steps": pred_steps,
            "n_estimators": n_estimators if ml_model_type == "rf" else None,
        },
    }

    rmses = {m: [] for m in BASE_MODELS}
    maes = {m: [] for m in BASE_MODELS}
    r2s = {m: [] for m in BASE_MODELS}
    selected_rmses = []
    selected_maes = []
    selected_r2s = []
    total_projects_analyzed = 0

    for area in areas:
        name = area.get("name", area.get("area", "Unknown"))
        history = list(area.get("history", []))

        if window and len(history) > window:
            history = history[-window:]

        if len(history) == 0:
            continue

        y = impute_nulls(np.array(history, dtype=float))
        total_hist_area = int(np.sum(y))
        total_projects_analyzed += total_hist_area

        candidate_results = {}
        for kind in BASE_MODELS:
            pred, rmse, mae, r2 = fit_predict(kind, y, pred_steps, n_estimators)
            candidate_results[kind] = {
                "pred": pred,
                "rmse": rmse,
                "mae": mae,
                "r2": r2,
            }
            rmses[kind].append(rmse)
            maes[kind].append(mae)
            r2s[kind].append(r2)

        best_kind = choose_auto_model(candidate_results, y, n_estimators)
        chosen_kind = best_kind if ml_model_type == "auto" else ml_model_type
        chosen = candidate_results[chosen_kind]

        selected_pred = chosen["pred"]
        selected_rmse = chosen["rmse"]
        selected_mae = chosen["mae"]
        selected_r2 = chosen["r2"]

        selected_rmses.append(selected_rmse)
        selected_maes.append(selected_mae)
        selected_r2s.append(selected_r2)

        capacity = float(area.get("capacity", 25)) or 25.0
        peak_pred = float(np.max(selected_pred)) if len(selected_pred) > 0 else 0.0
        last_pred = float(selected_pred[-1]) if len(selected_pred) > 0 else 0.0
        saturation_index = min(100.0, max(0.0, (max(last_pred, peak_pred) / capacity) * 100.0))

        ci_lower = np.maximum(0.0, np.round(selected_pred - selected_rmse, 2)).tolist()
        ci_upper = np.round(selected_pred + selected_rmse, 2).tolist()

        results["areas"].append({
            "id_linea": area.get("id_linea"),
            "name": name,
            "id_carrera": area.get("id_carrera"),
            "carrera_nombre": area.get("carrera_nombre", "General"),
            "history": history,
            "total_historico": total_hist_area,
            "promedio_historico": round(float(np.mean(y)), 2),
            "ultimo_trimestre": int(y[-1]) if len(y) > 0 else 0,
            "adoption_curve": np.round(selected_pred, 2).tolist(),
            "ci_lower": ci_lower,
            "ci_upper": ci_upper,
            "capacity": capacity,
            "saturation_index": round(saturation_index, 2),
            "rmse": round(selected_rmse, 4),
            "mae": round(selected_mae, 4),
            "r2": round(selected_r2, 4),
            "model_applied": chosen_kind,
            "model_applied_label": MODEL_LABELS.get(chosen_kind, chosen_kind),
            "best_model": best_kind,
            "best_model_label": MODEL_LABELS.get(best_kind, best_kind),
        })

    def mean_or_zero(values):
        return round(float(np.mean(values)) if values else 0.0, 4)

    global_rmse = mean_or_zero(selected_rmses)
    global_mae = mean_or_zero(selected_maes)
    global_r2 = mean_or_zero(selected_r2s)

    coverage_score = min(1.0, float(window) / 6.0) * 35.0
    volume_score = min(1.0, float(total_projects_analyzed) / 20.0) * 25.0
    fit_score = max(0.0, min(1.0, global_r2 if global_r2 > 0 else (1.0 / (1.0 + global_rmse)))) * 40.0
    confidence_score = round(min(99.0, max(15.0, coverage_score + volume_score + fit_score)), 1)

    rmse_by_model = {m: mean_or_zero(rmses[m]) for m in BASE_MODELS}
    best_global_model = min(rmse_by_model, key=rmse_by_model.get) if rmse_by_model else "linear"

    results["metrics"]["global_rmse"] = global_rmse
    results["metrics"]["global_mae"] = global_mae
    results["metrics"]["global_r2"] = global_r2
    results["metrics"]["confidence_score"] = confidence_score
    results["metrics"]["total_projects_analyzed"] = total_projects_analyzed
    results["metrics"]["best_global_model"] = best_global_model
    results["metrics"]["best_global_model_label"] = MODEL_LABELS.get(best_global_model, best_global_model)
    if compare_models:
        results["metrics"]["rmse_by_model"] = rmse_by_model
        results["metrics"]["mae_by_model"] = {m: mean_or_zero(maes[m]) for m in BASE_MODELS}
        results["metrics"]["r2_by_model"] = {m: mean_or_zero(r2s[m]) for m in BASE_MODELS}
    results["metrics"]["elapsed_ms"] = int((time.time() - t0) * 1000)

    emit(results)


if __name__ == "__main__":
    if len(sys.argv) < 3:
        emit({"error": "Argumentos insuficientes"})
        sys.exit(1)

    if DEPS_ERROR is not None:
        emit({
            "error": "Dependencias de Python no disponibles (numpy / scikit-learn)",
            "details": DEPS_ERROR,
            "python": sys.executable,
        })
        sys.exit(3)

    task = sys.argv[1]
    input_file = sys.argv[2]

    try:
        if task == "trends":
            handle_trends(input_file)
        else:
            emit({"error": "Tarea desconocida: %s" % task})
    except Exception as e:
        emit({
            "error": "Error interno en pipeline ML",
            "details": str(e),
            "trace": traceback.format_exc()
        })
        sys.exit(2)
