<?php

namespace App\DataTables\OrdenMovilizacion;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SolicitanteDataTable extends DataTable
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
        ->editColumn('foto',function($user){
            return view('usuarios.foto',['user'=>$user])->render();
        })
        ->addColumn('action', function($user){
            return view('movilizacion.calendar.conductorSolicitante',['user'=>$user])->render();
        })->rawColumns(['foto','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\OrdenMovilizacion/Solicitante $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->role('Operador');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('solicitante-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ajax(['data' => 'function(d) { d.table = "solicitante"; }'])
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
                    ->title('Acción')
                    ->searchable(false)
                    ->addClass('text-center'),
            Column::make('foto')->searchable(false),
            Column::make('nombres')->title('Apellidos & Nombres'),
            Column::make('documento')->title('# Documento'),
            Column::make('email'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'OrdenMovilizacion_Solicitante_' . date('YmdHis');
    }
}
