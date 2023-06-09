<?php

namespace App\DataTables\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PorRolDataTable extends DataTable
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
            return view('usuarios.action',['user'=>$user])->render();
        })
        ->rawColumns(['action','foto']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User/PorRolDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        if($this->rol){
            return $model->role($this->rol)->where('id','!=',Auth::user()->id);
        }else{
            return $model->where('id','!=',Auth::user()->id)->where('estado','INACTIVO');
        }
        
        
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                   // ->setTableId('users-table')
                   ->columns($this->getColumns())
                   ->minifiedAjax()
                   // ->dom('Bfrtip')
                   // ->orderBy(1)
                   // ->buttons(
                   //     Button::make('create'),
                   //     Button::make('export'),
                   //     Button::make('print'),
                   //     Button::make('reset'),
                   //     Button::make('reload')
                   // );
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
                ->addClass('text-center'),
            Column::make('foto')->searchable(false),
            Column::make('apellidos'),
            Column::make('nombres'),
            Column::make('email'),
            Column::make('documento'),
            Column::make('telefono')->title('Teléfono'),
            Column::make('cuidad'),
            Column::make('direccion')->title('Dirección'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'User_PorRol_' . date('YmdHis');
    }
}
