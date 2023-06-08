<?php

namespace App\DataTables\OrdenMovilizacion\Control;

use App\Models\OrdenMovilizacion;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AprobarDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('vehiculo_id',function($om){
                return $om->vehiculo->numero_movil_placa;
            })
            ->editColumn('conductor_id',function($om){
                return $om->conductor->apellidos_nombres??'';
            })
            ->editColumn('solicitante_id',function($om){
                return $om->solicitante->apellidos_nombres??'';
            })
            ->editColumn('autorizado_id',function($om){
                return $om->autorizado->apellidos_nombres??'';
            })

            ->filterColumn('vehiculo_id',function($query,$keyword){
                $query->whereHas('vehiculo',function($query) use($keyword){
                    $query->whereRaw("concat(numero_movil,' ',placa) like ?",["%{$keyword}%"]);
                });
            })
            ->editColumn('estado',function($om){
                return view('movilizacion.estadoLista',['om'=>$om])->render();
            })
            ->filterColumn('conductor_id',function($query,$keyword){
                $query->whereHas('conductor',function($query) use($keyword){
                    $query->whereRaw("concat(apellidos,' ',nombres) like ?",["%{$keyword}%"]);
                });
            })
            ->addColumn('action', function($om){
                return view('movilizacion.control.aprobarCheck',['om'=>$om])->render();
            })
            ->rawColumns(['action','estado']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\OrdenMovilizacion/Control/Aprobar $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OrdenMovilizacion $model)
    {
        return $model->newQuery()->where('estado','SOLICITADO');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('ordenmovilizacion-listadoordenmovilizacion-table-aprobar')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->title('Aceptar')
                  ->addClass('text-center'),
            Column::make('numero')->title('# O.M'),
            Column::make('vehiculo_id')->title('Vehículo'),
            Column::make('conductor_id')->title('Conductor'),
            Column::make('fecha_salida'),
            Column::make('fecha_retorno'),
            Column::make('estado'),
            Column::make('numero_ocupantes')->title('# ocupantes')->searchable(false),
            Column::make('procedencia'),
            Column::make('destino'),
            Column::make('comision_cumplir'),
            
            Column::make('autorizado_id')->title('Autorizado')->searchable(false),
            Column::make('solicitante_id')->title('Solicitante')->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'OrdenMovilizacion/Control/Aprobar_' . date('YmdHis');
    }
}
