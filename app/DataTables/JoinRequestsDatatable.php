<?php

namespace App\DataTables;

use App\Classes;
use App\JoinRequest;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class JoinRequestsDatatable extends DataTable
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
            ->addColumn('students_count', 'admins.classes.btn.students_count')
            ->addColumn('respond_request', 'admins.classes.btn.respond_request')
            ->rawColumns([
                'students_count',
                'respond_request',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\JoinRequestsDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(JoinRequestsDatatable $model)
    {
        $classes = Classes::join('classes_teachers', 'classes.class_number', '=', 'classes_teachers.class_number')
                ->join('teachers as main_teachers', 'main_teachers.email', '=', 'classes_teachers.teacher_email')
                ->join('join_requests', 'join_requests.class_number', '=', 'classes_teachers.class_number')
                ->join('teachers as spare_teachers', 'spare_teachers.email', '=', 'join_requests.teacher_email')
                ->select(['classes.class_number', 'classes.title', 'main_teachers.name as teacher_name', 'main_teachers.email as teacher_email', 'spare_teachers.name as spare_teacher_name', 'spare_teachers.email as spare_teacher_email'])
                ->where('classes_teachers.role', '=', 'main');

        return $classes;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('classesdatatable-table')
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
                    ['extend' => 'pageLength', 'className' => 'dataTables_length'],
                    ['extend' => 'csv', 'title' => $this->filename(), 'className' => 'btn btn-primary', 'text' => '<i class="la la-file"></i> CSV File'],
                    ['extend' => 'excel', 'title' => $this->filename(), 'className' => 'btn btn-info', 'text' => '<i class="la la-print"></i> Export Excel'],
                ],
                'language' => [
                    'url' => 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Arabic.json'
                ]
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
            Column::make('classes.title')
                ->data('title')
                ->title('اسم الحلقة'),
            Column::make('main_teachers.name')
                ->data('teacher_name')
                ->title('اسم المعلم'),
            Column::make('students_count')
                ->data('students_count')
                ->title('عدد الطلاب')
                ->orderable(false),
            Column::make('spare_teachers.name')
                ->data('spare_teacher_name')
                ->title('مقدم الطلب'),
            Column::make('respond_request')
                ->data('respond_request')
                ->title('الاستجابة للطلب')
                ->orderable(false)
                ->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'JoinRequests_' . date('YmdHis');
    }
}
