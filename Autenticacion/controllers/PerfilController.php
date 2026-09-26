<?php
// modules/Autenticacion/controllers/PerfilController.php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../../Investigaciones/models/InvestigacionModel.php';

class PerfilController {

    public function mostrarDashboard() {
        Auth::requierePrivilegioMinimo(998);
    
        $usuario = Auth::usuario();
        $primerNombre = explode(' ', trim($usuario['nombre']))[0];
        $usuario['primer_nombre'] = $primerNombre;
    
        $invModel = new InvestigacionModel();
    
        // Inicializar métricas
        $metricas = [
            'proyectos_activos' => 0,
            'proyectos_total'   => 0,
            'postulaciones_totales' => 0,
            'postulaciones_pendientes' => 0,
            'postulaciones_aceptadas' => 0,
            'tasa_aceptacion' => 0.0,
            'mis_postulaciones' => []
        ];
    
        // Mis investigaciones (si es profesor o rango superior)
        if (!empty($usuario['id'])) {
            $mis = $invModel->obtenerMisInvestigaciones((int)$usuario['id']);
            $metricas['proyectos_total'] = count($mis);
            $metricas['proyectos_activos'] = count(array_filter($mis, function($i){
                return isset($i['estado']) && strtolower($i['estado']) === 'abierta';
            }));
            $metricas['postulaciones_pendientes'] = array_sum(array_map(function($i){
                return (int)($i['postulantes_pendientes'] ?? 0);
            }, $mis));
    
            $postulaciones = $invModel->obtenerPostulantesDeMiProyecto((int)$usuario['id']);
            $metricas['postulaciones_totales'] = count($postulaciones);
            $metricas['postulaciones_aceptadas'] = count(array_filter($postulaciones, function($p){
                return isset($p['estado']) && strtolower($p['estado']) === 'aceptado';
            }));
    
            if ($metricas['postulaciones_totales'] > 0) {
                $metricas['tasa_aceptacion'] = round(100 * $metricas['postulaciones_aceptadas'] / $metricas['postulaciones_totales'], 1);
            }
        }
    
        // Mis postulaciones como estudiante
        $misPost = $invModel->obtenerMisPostulaciones((int)$usuario['id']);
        $metricas['mis_postulaciones'] = $misPost;
        $metricas['mis_postulaciones_aceptadas'] = count(array_filter($misPost, function($p){
            return isset($p['estado']) && strtolower($p['estado']) === 'aceptado';
        }));
        $metricas['mis_postulaciones_pendientes'] = count(array_filter($misPost, function($p){
            return isset($p['estado']) && strtolower($p['estado']) === 'pendiente';
        }));
    
        // Métricas de Cursos
        require_once __DIR__ . '/../../Cursos/models/CursoModel.php';
        $cursoModel = new CursoModel();
        $resultadoCursos = $cursoModel->listarCursos(['id_docente' => (int)$usuario['id']], 1, 999);
        $misCursos = $resultadoCursos['cursos'] ?? [];
        
        $metricas['cursos'] = [
            'total'      => count($misCursos),
            'publicados' => count(array_filter($misCursos, function($c){ return $c['estado'] === 'publicado'; })),
            'borradores' => count(array_filter($misCursos, function($c){ return $c['estado'] === 'borrador'; })),
            'archivados' => count(array_filter($misCursos, function($c){ return $c['estado'] === 'archivado'; }))
        ];
    
        return [
            'usuarioActual' => $usuario,
            'metricas' => $metricas
        ];
    }
}