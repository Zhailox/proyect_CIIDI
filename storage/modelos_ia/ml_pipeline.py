import sys
import json
import traceback
import numpy as np
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error
import warnings

# Ignorar advertencias para que no ensucien el stdout JSON
warnings.filterwarnings("ignore")

def impute_nulls(y):
    """ Función de imputación de valores nulos para series temporales """
    # Reemplaza NaN por el promedio de la serie, o 0 si está vacía
    mean_val = np.nanmean(y) if not np.isnan(np.nanmean(y)) else 0
    return np.where(np.isnan(y), mean_val, y)

def handle_trends(input_file):
    """ Pipeline de Regresión de Series Temporales (Proyección de Volumen y Tendencias) """
    with open(input_file, 'r', encoding='utf-8') as f:
        data = json.load(f)
        
    results = {"type": "trends_projection", "areas": [], "metrics": {}}
    rmses = []
    
    areas = data.get('areas', []) if isinstance(data, dict) else data
    pred_steps = data.get('pred_steps', 4) if isinstance(data, dict) else 4
    
    if not areas:
        print(json.dumps({"error": "No data provided in input"}))
        return
        
    for area in areas:
        name = area.get('area', 'Unknown')
        history = area.get('history', [])
        
        if len(history) == 0:
            continue
            
        # Convertir a float para poder manejar nulos (np.nan)
        y = np.array(history, dtype=float)
        y = impute_nulls(y) # Preprocesamiento e imputación
        
        X = np.arange(len(y)).reshape(-1, 1)
        
        # Algoritmo de tendencias temporales
        model = LinearRegression()
        model.fit(X, y)
        
        # Proyectar ciclos futuros (trimestres)
        X_future = np.arange(len(y), len(y) + pred_steps).reshape(-1, 1)
        predictions = model.predict(X_future)
        
        # Extraer indicadores de error
        y_pred_train = model.predict(X)
        rmse = np.sqrt(mean_squared_error(y, y_pred_train))
        rmses.append(rmse)
        
        # Calcular el índice de saturación de recursos
        capacity = float(area.get('capacity', 150))
        saturation_index = min(100.0, max(0.0, (predictions[-1] / capacity) * 100))
        
        results["areas"].append({
            "name": name,
            "history": history,
            "adoption_curve": np.round(predictions, 2).tolist(),
            "saturation_index": round(saturation_index, 2)
        })
        
    results["metrics"]["global_rmse"] = round(float(np.mean(rmses)) if rmses else 0.0, 4)
    
    # Salida en estructura JSON serializada
    print(json.dumps(results))

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print(json.dumps({"error": "Argumentos insuficientes"}))
        sys.exit(1)
        
    task = sys.argv[1]
    input_file = sys.argv[2]
    
    try:
        if task == "trends":
            handle_trends(input_file)
        else:
            print(json.dumps({"error": f"Tarea desconocida: {task}"}))
    except Exception as e:
        print(json.dumps({
            "error": "Error interno en pipeline ML",
            "details": str(e),
            "trace": traceback.format_exc()
        }))
