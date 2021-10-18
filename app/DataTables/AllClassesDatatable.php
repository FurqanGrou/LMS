<?php

namespace App\DataTables;

use App\Classes;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AllClassesDatatable extends DataTable
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
            ->addColumn('zoom_link', 'teachers.classes.btn.zoom_link')
            ->addColumn('period', 'teachers.classes.btn.period')
            ->addColumn('students_count', 'teachers.classes.btn.students_count')
            ->addColumn('join_request', 'teachers.classes.btn.join_request')
            ->rawColumns([
                'zoom_link',
                'period',
                'students_count',
                'join_request',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\AllClassesDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AllClassesDatatable $model)
    {
        $classes = Classes::join('classes_teachers', 'classes.class_number', '=', 'classes_teachers.class_number')
            ->join('teachers', 'teachers.email', '=', 'classes_teachers.teacher_email')
            ->leftJoin('join_requests', 'join_requests.class_number', 'classes_teachers.class_number')
            ->select(['join_requests.class_number as request_class' ,'classes.class_number', 'classes.title', 'classes.zoom_link', 'classes.path', 'classes.period', 'teachers.name as teacher_name'])
            ->where('classes_teachers.role', '=', 'main')
            ->where('classes_teachers.teacher_email', '!=', auth()->user()->email);

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
            Column::make('students_count')
                ->data('students_count')
                ->title('عدد الطلاب')
                ->orderable(false),
            Column::make('path')
                ->data('path')
                ->title('المسار'),
            Column::make('period')
                ->data('period')
                ->title('الفترة'),
            Column::make('teachers.name')
                ->data('teacher_name')
                ->title('اسم المعلم'),
            Column::make('join_request')
                ->data('join_request')
                ->title('الانضمام')
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
        return 'AllClasses_' . date('YmdHis');
    }
}
