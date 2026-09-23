<?php
// modules/SuperAdmin/controllers/ReportesController.php
require_once __DIR__ . '/../models/ReportesModel.php';
require_once CORE_PATH . 'Security/Auth.php';

class ReportesController {

    private ReportesModel $model;

    public function __construct() {
        if (!Auth::check() || (int)Auth::usuario()['nivel'] !== 0) {
            header('Location: ?ruta=inicio');
            exit;
        }
        $this->model = new ReportesModel();
    }

    public function index(): array {
        $resumen = $this->model->obtenerResumenGeneral();
        $dominioSeleccionado = $_GET['dominio'] ?? 'pst';
        $filtros = $_GET;

        $lineasInvestigacion = $this->model->obtenerLineasInvestigacion();
        $estadisticasGrafico = $this->model->obtenerEstadisticasGrafico($dominioSeleccionado, $_GET['agrupar'] ?? 'trayecto');

        return [
            'resumen' => $resumen,
            'dominio' => $dominioSeleccionado,
            'grafico_stats' => $estadisticasGrafico,
            'lineas' => $lineasInvestigacion,
            'filtros' => $filtros
        ];
    }

    public function exportarCSV(): void {
        $dominio = $_GET['dominio'] ?? 'pst';
        $filtros = $_GET;
        $columnasSeleccionadas = $_GET['columnas'] ?? [];

        $filename = "reporte_" . $dominio . "_" . date('Y-m-d_H-i') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        switch ($dominio) {
            case 'vinculacion':
                $datos = $this->model->obtenerReporteVinculacion($filtros);
                $headers = ['ID', 'Empresa', 'Contacto', 'Teléfono', 'Correo', 'Problema Planteado', 'Estado', 'Fecha'];
                fputcsv($output, $headers);
                foreach ($datos as $r) {
                    fputcsv($output, [$r['id'], $r['nombre_empresa'], $r['contacto_persona'], $r['telefono'], $r['correo'], $r['titulo_problematica'], $r['estado'], $r['fecha_creacion']]);
                }
                break;

            case 'usuarios':
                $datos = $this->model->obtenerReporteUsuarios($filtros);
                $headers = ['ID', 'Cédula', 'Nombre Completo', 'Correo', 'Rol', 'Estado'];
                fputcsv($output, $headers);
                foreach ($datos as $r) {
                    fputcsv($output, [$r['id'], $r['cedula'], $r['nombre'], $r['correo'], $r['rol'], $r['activo'] ? 'Activo' : 'Suspendido']);
                }
                break;

            case 'logs':
                $datos = $this->model->obtenerReporteLogs($filtros);
                $headers = ['ID', 'Fecha', 'Nivel', 'Acción / Mensaje', 'IP', 'Responsable'];
                fputcsv($output, $headers);
                foreach ($datos as $r) {
                    fputcsv($output, [$r['id'], $r['fecha'], $r['nivel'], $r['mensaje'], $r['ip'], $r['responsable']]);
                }
                break;

            case 'pst':
            default:
                $datos = $this->model->obtenerReportePST($filtros);
                
                // Mapeo dinámico de columnas comprobadas
                $todasColumnas = [
                    'titulo' => 'Título del Proyecto',
                    'carrera' => 'Carrera / PNF',
                    'nivel_academico' => 'Nivel Académico',
                    'trayecto' => 'Trayecto',
                    'anio_publicacion' => 'Año de Publicación',
                    'comunidad_beneficiada' => 'Comunidad Beneficiada',
                    'linea_investigacion' => 'Línea de Investigación',
                    'fecha_defensa' => 'Fecha Defensa',
                    'obj_general' => 'Objetivo General',
                    'resumen' => 'Resumen'
                ];

                $colsExportar = [];
                if (!empty($columnasSeleccionadas)) {
                    foreach ($columnasSeleccionadas as $cKey) {
                        if (isset($todasColumnas[$cKey])) {
                            $colsExportar[$cKey] = $todasColumnas[$cKey];
                        }
                    }
                }
                if (empty($colsExportar)) {
                    $colsExportar = [
                        'titulo' => 'Título del Proyecto',
                        'carrera' => 'Carrera / PNF',
                        'nivel_academico' => 'Nivel Académico',
                        'trayecto' => 'Trayecto',
                        'anio_publicacion' => 'Año',
                        'comunidad_beneficiada' => 'Comunidad Beneficiada',
                        'linea_investigacion' => 'Línea Investigación'
                    ];
                }

                fputcsv($output, array_values($colsExportar));
                foreach ($datos as $r) {
                    $rowCSV = [];
                    foreach (array_keys($colsExportar) as $k) {
                        $rowCSV[] = $r[$k] ?? '';
                    }
                    fputcsv($output, $rowCSV);
                }
                break;
        }

        fclose($output);
        exit;
    }

    public function exportarPDF(): void {
        $dominio = $_GET['dominio'] ?? 'pst';
        $tipoGrafico = $_GET['tipo_grafico'] ?? 'bar';
        $agrupar = $_GET['agrupar'] ?? 'trayecto';
        $filtros = $_GET;
        $columnasSeleccionadas = $_GET['columnas'] ?? [];

        // Opciones de personalización
        $tituloReporte = !empty($_GET['titulo_custom']) ? trim($_GET['titulo_custom']) : null;
        $subtituloReporte = !empty($_GET['subtitulo_custom']) ? trim($_GET['subtitulo_custom']) : 'Centro de Investigación, Innovación y Desarrollo Integral (CIIDI) - UPTTMBI';
        $mostrarInsignias = isset($_GET['mostrar_insignias']) ? (bool)$_GET['mostrar_insignias'] : true;
        $notasPie = !empty($_GET['notas_pie']) ? trim($_GET['notas_pie']) : '';

        $datos = [];
        $columnasMap = [];

        switch ($dominio) {
            case 'vinculacion':
                if (!$tituloReporte) $tituloReporte = "REPORTE DE VINCULACIÓN Y SECTOR PRODUCTIVO";
                $datos = $this->model->obtenerReporteVinculacion($filtros);
                $columnasMap = [
                    'nombre_empresa' => 'Empresa',
                    'contacto_persona' => 'Contacto',
                    'titulo_problematica' => 'Problema Planteado',
                    'estado' => 'Estado',
                    'fecha_creacion' => 'Fecha'
                ];
                break;

            case 'usuarios':
                if (!$tituloReporte) $tituloReporte = "REPORTE GENERAL DE USUARIOS DEL SISTEMA";
                $datos = $this->model->obtenerReporteUsuarios($filtros);
                $columnasMap = [
                    'cedula' => 'Cédula',
                    'nombre' => 'Nombre Completo',
                    'correo' => 'Correo',
                    'rol' => 'Rol',
                    'activo' => 'Estado'
                ];
                break;

            case 'logs':
                if (!$tituloReporte) $tituloReporte = "REPORTE DE AUDITORÍA Y ERRORES TÉCNICOS";
                $datos = $this->model->obtenerReporteLogs($filtros);
                $columnasMap = [
                    'id' => 'ID',
                    'fecha' => 'Fecha',
                    'nivel' => 'Nivel',
                    'mensaje' => 'Mensaje / Error',
                    'ip' => 'IP'
                ];
                break;

            case 'pst':
            default:
                if (!$tituloReporte) $tituloReporte = "REPORTE DE PRODUCCIÓN SOCIO-TECNOLÓGICA (PST)";
                $datos = $this->model->obtenerReportePST($filtros);

                $todasColumnas = [
                    'titulo' => 'Título del Proyecto',
                    'carrera' => 'Carrera / PNF',
                    'nivel_academico' => 'Nivel',
                    'trayecto' => 'Trayecto',
                    'anio_publicacion' => 'Año',
                    'comunidad_beneficiada' => 'Comunidad Beneficiada',
                    'linea_investigacion' => 'Línea de Investigación',
                    'fecha_defensa' => 'Fecha Defensa',
                    'obj_general' => 'Objetivo General'
                ];

                if (!empty($columnasSeleccionadas)) {
                    foreach ($columnasSeleccionadas as $cKey) {
                        if (isset($todasColumnas[$cKey])) {
                            $columnasMap[$cKey] = $todasColumnas[$cKey];
                        }
                    }
                }

                if (empty($columnasMap)) {
                    $columnasMap = [
                        'titulo' => 'Título del Proyecto',
                        'nivel_academico' => 'Nivel / Trayecto',
                        'anio_publicacion' => 'Año',
                        'comunidad_beneficiada' => 'Comunidad Beneficiada',
                        'linea_investigacion' => 'Línea de Investigación'
                    ];
                }
                break;
        }

        $columnas = array_values($columnasMap);
        $estadisticasGrafico = $this->model->obtenerEstadisticasGrafico($dominio, $agrupar);

        include __DIR__ . '/../views/imprimir_reporte_pdf.php';
        exit;
    }

    public function exportarJSON(): void {
        $dominio = $_GET['dominio'] ?? 'pst';
        $filtros = $_GET;

        $filename = "reporte_" . $dominio . "_" . date('Y-m-d_H-i') . ".json";

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $datos = [];
        switch ($dominio) {
            case 'vinculacion':
                $datos = $this->model->obtenerReporteVinculacion($filtros);
                break;
            case 'usuarios':
                $datos = $this->model->obtenerReporteUsuarios($filtros);
                break;
            case 'logs':
                $datos = $this->model->obtenerReporteLogs($filtros);
                break;
            case 'pst':
            default:
                $datos = $this->model->obtenerReportePST($filtros);
                break;
        }

        $exportData = [
            'institucion' => 'Centro de Investigación, Innovación y Desarrollo Integral (CIIDI)',
            'subtitulo' => 'UPTTMBI - Mario Briceño Iragorry',
            'dominio' => $dominio,
            'fecha_generacion' => date('Y-m-d H:i:s'),
            'emisor' => Auth::usuario()['nombre'] ?? 'Administrador',
            'total_registros' => count($datos),
            'registros' => $datos
        ];

        echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
