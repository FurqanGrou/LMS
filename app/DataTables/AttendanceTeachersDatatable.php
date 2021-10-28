<?php

namespace App\DataTables;

use App\Service;
use App\Teacher;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AttendanceTeachersDatatable extends DataTable
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
            ->eloquent($query);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\RequestServiceDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(RequestServiceDatatable $model)
    {
        $teacher_attendances = Teacher::with('attendances')->where('teachers.id', '=', auth()->user()->id);

        return $teacher_attendances;
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
            Column::make('attendances.action_code')
                    ->data('action_code')
                    ->title('رمز البصمة'),
            Column::make('attendances.type')
                    ->data('type')
                    ->title('نوع البصمة'),
            Column::make('attendances.period')
                    ->data('period')
                    ->title('الفترة'),
            Column::make('attendances.created_at')
                    ->data('created_at')
                    ->title('التوقيت'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'RequestService_' . date('YmdHis');
    }
}
