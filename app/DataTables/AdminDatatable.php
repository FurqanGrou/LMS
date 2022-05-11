<?php

namespace App\DataTables;

use App\Admin;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\ButtonsServiceProvider;

class AdminDatatable extends DataTable
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
            ->addColumn('checkbox', 'admins.admins.btn.checkbox')
            ->addColumn('edit', 'admins.admins.btn.edit')
            ->addColumn('delete', 'admins.admins.btn.delete')
            ->addColumn('user_type', 'admins.admins.btn.user_type')
            ->rawColumns([
                'checkbox',
                'edit',
                'delete',
                'user_type',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\Admin $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $admins = Admin::select('id', 'name', 'email', 'user_type', 'created_at');
        if(!isHasUserType('super_admin')){
            $admins = $admins->where('user_type', '=', getUserType());
        }

        return $admins;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('admindatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
//                    ->parameters([$this->getBuilderParameters()]);
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'lengthMenu' => [
                            [ 10, 25, 50, -1 ],
                            [ '10', '25', '50', 'Show all' ]
                        ],
                        'buttons' => [
                            ['text' => 'اضافة مشرف جديد <i class="la la-plus"></i>', 'className' => 'btn btn-warning', 'action' => 'function(){
                                window.location.href= "' . route('admins.admins.create') . '";
                            }'],
//                            ['text' => 'حذف المشرفين المحددين <i class="la la-trash"></i>', 'className' => 'btn btn-danger btn-delete'],
                            ['extend' => 'pageLength', 'className' => 'dataTables_length'],
                            ['extend' => 'csv', 'title' => $this->filename(), 'className' => 'btn btn-primary', 'text' => '<i class="la la-file"></i> CSV File'],
                            ['extend' => 'excel', 'title' => $this->filename(), 'className' => 'btn btn-info', 'text' => '<i class="la la-print"></i> Export Excel'],
                        ],
                        'language' => ['url' => 'http://cdn.datatables.net/plug-ins/1.10.12/i18n/Arabic.json']
                    ])
                    ->buttons([
//                        Button::make('csv'),
                    ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
//            Column::computed('<input type="checkbox" class="check_all" onclick="check_all()" />')
//                ->exportable(false)
//                ->printable(false)
//                ->data('checkbox')
//                ->title('<input type="checkbox" class="check_all" onclick="check_all()" />')
//                ->width(10),
            Column::make('id')
                ->title('#'),
            Column::make('name')
                ->title('اسم المشرف'),
            Column::make('email')
                ->title('بريد المشرف'),
            Column::computed('user_type')
                ->title('المؤسسة')
                ->width(50),
            Column::make('created_at')
                ->title('تاريخ الأضافة'),
            Column::computed('edit')
                ->title('تعديل')
                ->exportable(false)
                ->printable(false)
                ->width(50),
            Column::computed('delete')
                ->title('حذف')
                ->exportable(false)
                ->printable(false)
                ->width(50)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Admin_' . date('YmdHis');
    }
}
