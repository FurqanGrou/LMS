<?php

namespace App\DataTables;

use App\Classes;
use App\Teacher;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ClassesDataTable extends DataTable
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
            ->addColumn('zoom_link', 'admins.classes.btn.zoom_link')
            ->addColumn('period', 'admins.classes.btn.period')
            ->addColumn('title', 'admins.classes.btn.title')
            ->addColumn('students_count', 'admins.classes.btn.students_count')
            ->rawColumns([
                'zoom_link',
                'period',
                'title',
                'students_count',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\ClassesDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $classes = Classes::join('classes_teachers', 'classes.class_number', '=', 'classes_teachers.class_number')
            ->join('teachers', 'teachers.email', '=', 'classes_teachers.teacher_email')
            ->select(['classes.class_number', 'classes.title', 'classes.zoom_link', 'classes.path', 'classes.period', 'teachers.name as teacher_name']);


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
            Column::make('zoom_link')
                ->data('zoom_link')
                ->title('رابط الحلقة'),
            Column::make('students_count')
                ->data('students_count')
                ->title('عدد الطلاب'),
            Column::make('path')
                ->data('path')
                ->title('المسار'),
            Column::make('period')
                ->data('period')
                ->title('الفترة'),
            Column::make('teachers.name')
                ->data('teacher_name')
                ->title('اسم المعلم'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Classes_' . date('YmdHis');
    }
}
