<?php

namespace App\DataTables;

use App\Models\Lectura;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LecturaDataTable extends DataTable
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
            ->editColumn('orden_movilizacion_id',function($lec){
                return $lec->ordenMovilizacion->numero;
            })
            ->editColumn('created_at',function($lec){
                return $lec->created_at;
            })
            ->addColumn('vehiculo', function($lec){
                return $lec->ordenMovilizacion->vehiculo->numero_movil_placa;
            })
            ->addColumn('estado_orden_movilizacion',function($lec){
                return $lec->ordenMovilizacion->estado;
            })
            ->filterColumn('orden_movilizacion_id',function($query,$keyword){
                $query->whereHas('ordenMovilizacion',function($query) use ($keyword){
                    $query->whereRaw('numero like ?',["%{$keyword}%"]);
                });
            })
            
            ->addColumn('action', function($lec){
                return view('lecturas.action',['lec'=>$lec])->render();
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Lectura $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Lectura $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('lectura-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters($this->getBuilderParameters());
                    // ->dom('Bfrtip')
                    // ->orderBy(1)
                    // ->buttons(
                    //     Button::make('create'),
                    //     Button::make('export'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // );
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
                  ->title('Acción')
                  ->addClass('text-center'),
            Column::make('created_at')->title('Fecha'),
            Column::make('orden_movilizacion_id')->title('N° O.M'),
            Column::computed('vehiculo')->title('Vehículo'),            
            
            Column::computed('estado_orden_movilizacion')->title('Estado O.M'),
            Column::make('estado'),
            Column::make('descripcion')->title('Descripción'),
            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Lectura_' . date('YmdHis');
    }
}
