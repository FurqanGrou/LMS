<?php

namespace App\DataTables;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\ButtonsServiceProvider;

class StudentDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables(auth()->user());
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function query()
    {
        return auth()->user();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('adsdatatable-table')
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
            Column::make('name')
                ->data('name')
                ->title('الاسم'),
            Column::make('student_number')
                ->data('student_number')
                ->title('رقم الطالب'),
            Column::make('class_number')
                ->data('class_number')
                ->title('رقم الحلقة'),
            Column::make('language')
                ->data('language')
                ->title('اللغة'),
            Column::make('status')
                ->data('status')
                ->title('الحالة')
                ->width('2'),
            Column::make('section')
                ->data('section')
                ->title('القسم')
                ->width('2'),
            Column::make('login_time')
                ->data('login_time')
                ->title('وقت الدخول'),
            Column::make('exit_time')
                ->data('exit_time')
                ->title('وقت الخروج'),
            Column::make('path')
                ->data('path')
                ->title('المسار')
                ->width('2'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'students_' . date('YmdHis');
    }
}
