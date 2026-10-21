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
    from sklearn.metrics import mean_squared_error
    DEPS_ERROR = None
except Exception as _e:  # ImportError u otros fallos de carga binaria
    DEPS_ERROR = "%s: %s" % (type(_e).__name__, _e)

# Año académico = 3 trimestres  ->  2 años = 6 trimestres, 1 año = 3 trimestres
HIST_WINDOWS = {"2y": 6, "1y": 3}
VALID_MODELS = ("linear", "poly", "rf")
MODEL_LABELS = {
    "linear": "Regresión Lineal",
    "poly": "Regresión Polinómica (Grado 2)",
    "rf": "Random Forest Regressor",
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
        n = 100
    return max(10, min(n, 300))


def build_model(kind, n_estimators):
    if kind == "poly":
        return make_pipeline(PolynomialFeatures(degree=2, include_bias=False), LinearRegression())
    if kind == "rf":
        return RandomForestRegressor(n_estimators=n_estimators, random_state=42, n_jobs=1)
    return LinearRegression()


def fit_predict(kind, y, pred_steps, n_estimators):
    """ Entrena el modelo indicado y devuelve (proyeccion, rmse_entrenamiento) """
    n = len(y)
    if n < 2:
        # Con un solo punto ningún regresor es informativo: proyección plana
        flat = float(y[0]) if n == 1 else 0.0
        return np.full(pred_steps, max(0.0, flat)), 0.0

    X = np.arange(n).reshape(-1, 1)
    model = build_model(kind, n_estimators)
    model.fit(X, y)

    # Proyectar ciclos futuros (trimestres)
    X_future = np.arange(n, n + pred_steps).reshape(-1, 1)
    predictions = np.maximum(0, model.predict(X_future))  # No hay demandas negativas

    # Extraer indicadores de error (ajuste sobre el histórico)
    y_pred_train = model.predict(X)
    rmse = float(np.sqrt(mean_squared_error(y, y_pred_train)))
    return predictions, rmse


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

    # Advanced Controls Integration
    historial_base = data.get("historial_base", "all")
    ml_model_type = data.get("ml_model", "linear")
    if ml_model_type not in VALID_MODELS:
        ml_model_type = "linear"
    n_estimators = rf_estimators(data.get("max_epochs", 500))
    compare_models = bool(data.get("compare_models", True))

    if not areas:
        emit({"error": "No data provided in input"})
        return

    # Longitud de la serie global (todas las áreas están alineadas a las mismas etiquetas)
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

    rmses = {m: [] for m in VALID_MODELS}
    models_to_run = VALID_MODELS if compare_models else (ml_model_type,)

    for area in areas:
        name = area.get("name", area.get("area", "Unknown"))
        history = list(area.get("history", []))

        # Apply historial_base bounds ('2y' = últimos 6 trimestres, '1y' = últimos 3)
        if window and len(history) > window:
            history = history[-window:]

        if len(history) == 0:
            continue

        y = impute_nulls(np.array(history, dtype=float))

        selected_pred, selected_rmse = None, 0.0
        for kind in models_to_run:
            pred, rmse = fit_predict(kind, y, pred_steps, n_estimators)
            rmses[kind].append(rmse)
            if kind == ml_model_type:
                selected_pred, selected_rmse = pred, rmse

        # Calcular el indice de saturacion de recursos
        capacity = float(area.get("capacity", 150)) or 150.0
        saturation_index = min(100.0, max(0.0, (float(selected_pred[-1]) / capacity) * 100))

        results["areas"].append({
            "name": name,
            "history": history,
            "adoption_curve": np.round(selected_pred, 2).tolist(),
            "saturation_index": round(saturation_index, 2),
            "rmse": round(selected_rmse, 4),
        })

    def mean_or_zero(values):
        return round(float(np.mean(values)) if values else 0.0, 4)

    results["metrics"]["global_rmse"] = mean_or_zero(rmses[ml_model_type])
    if compare_models:
        results["metrics"]["rmse_by_model"] = {m: mean_or_zero(rmses[m]) for m in VALID_MODELS}
    results["metrics"]["elapsed_ms"] = int((time.time() - t0) * 1000)

    # Salida en estructura JSON serializada
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
