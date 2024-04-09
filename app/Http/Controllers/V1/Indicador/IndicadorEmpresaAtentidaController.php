<?php

namespace App\Http\Controllers\V1\Indicador;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;

class IndicadorEmpresaAtentidaController extends Controller
{
    public function __invoke()
    {
        $empresas = Empresa::query()
            ->select(
                'entidades.nombre as nodo',
                'proyectos.codigo_proyecto as codigo_proyecto',
                'fases.nombre as fase actual',
                DB::raw("DATE_FORMAT(proyectos.fecha_inicio,'%Y-%m-%d')  as `Fecha de inicio del proyecto`"),
                DB::raw("DAYOFMONTH(proyectos.fecha_inicio)  as `Día de inicio del proyecto`"),
                DB::raw("MONTHNAME(proyectos.fecha_inicio)  as `Mes de inicio del proyecto`"),
                DB::raw("YEAR(proyectos.fecha_inicio)  as `Año de inicio del proyecto`")
            )
            ->selectRaw("if(`fases`.`nombre` = 'Finalizado' OR `fases`.`nombre` = 'Cancelado', `proyectos`.`fecha_cierre`,'El proyecto no está finalizado') AS `Fecha de finalización del proyecto`, if(`fases`.`nombre` = 'Finalizado' OR `fases`.`nombre` = 'Cancelado', DAYOFMONTH(`proyectos`.`fecha_cierre`),'El proyecto no está finalizado') AS `Día de finalización del proyecto`, if(`fases`.`nombre` = 'Finalizado' OR `fases`.`nombre` = 'Cancelado', YEAR(`proyectos`.`fecha_cierre`),'El proyecto no está finalizado') AS `Año de finalización del proyecto`, if(`fases`.`nombre` = 'Finalizado' OR `fases`.`nombre` = 'Cancelado', MONTHNAME(`proyectos`.`fecha_cierre`),'El proyecto no está finalizado') AS `Mes de finalización del proyecto`, if(`empresas`.`codigo_ciiu` IS NULL,'No registra', `empresas`.`codigo_ciiu`) AS `codigo_ciiu`, CONCAT(`ciudades`.`nombre`,' (', `departamentos`.`nombre`,')') AS `ciudad`")
            ->addSelect(
                'sectores.nombre as sector',
                'tamanhos_empresas.nombre as tamaño empresa',
                'tipos_empresas.nombre as tipo empresa'
            )

            ->leftJoin('sedes', 'sedes.empresa_id', '=', 'empresas.id')
            ->leftJoin('ciudades', 'ciudades.id', '=', 'sedes.ciudad_id')
            ->leftJoin('departamentos', 'departamentos.id', '=', 'ciudades.departamento_id')
            ->join('sectores', 'sectores.id', '=', 'empresas.sector_id')
            ->join('tipos_empresas', 'tipos_empresas.id', '=', 'empresas.tipoempresa_id')
            ->join('tamanhos_empresas', 'tamanhos_empresas.id', '=', 'empresas.tamanhoempresa_id')
            ->join('propietarios', function ($join) {
                $join->on('propietarios.propietario_id', '=', 'sedes.id')
                    ->where('propietarios.propietario_type', '=', Sede::class);
            })
            ->join('proyectos', 'proyectos.id', '=', 'propietarios.proyecto_id')
            ->join('fases', 'fases.id', '=', 'proyectos.fase_id')
            ->join('nodos', 'nodos.id', '=', 'proyectos.fase_id')
            ->join('entidades', 'entidades.id', '=', 'nodos.entidad_id')
            ->groupBy(DB::raw("YEAR(proyectos.fecha_inicio)"), 'empresas.nit')
            ->orderBy('empresas.nit')
            ->get();
        return response()->json([
            'data' => $empresas
        ]);
    }
}
