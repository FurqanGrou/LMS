<?php

namespace App\DataTables;

use App\Admin;
use App\Report;
use App\User;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\ButtonsServiceProvider;

class AbsenceDatatable extends DataTable
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
            ->addColumn('checkbox', 'admins.absences.btn.checkbox')
            ->rawColumns([
                'checkbox',
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
        $date = Carbon::today();
        $date = Carbon::createFromDate($date->year, $date->month, $date->day)->format('l d-m-Y');

        if(isset(request()->date_filter)){
            $date = new Carbon(request()->date_filter);
            $date = $date->format('l d-m-Y');
        }

        $absences = User::join('reports', 'users.id', '=', 'reports.student_id')
                        ->where('reports.date', '=', $date)
                        ->whereNotIn('reports.absence', [0, -1])
                        ->select(['reports.id', 'users.student_number', 'reports.student_id', 'reports.absence', 'users.name']);
        return $absences;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('absencedatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
//                    ->dom('Bfrtip')
//                    ->parameters([$this->getBuilderParameters()]);
                    ->parameters([
//                        'dom' => 'Bfrtip',
                        'lengthMenu' => [
                            [ 10, 25, 50, -1 ],
                            [ '10', '25', '50', 'Show all' ]
                        ],
//                        'buttons' => [
//                            ['text' => 'اضافة مشرف جديد <i class="la la-plus"></i>', 'className' => 'btn btn-warning', 'action' => 'function(){
//                                window.location.href= "' . route('admins.admins.create') . '";
//                            }'],
////                            ['text' => 'حذف المشرفين المحددين <i class="la la-trash"></i>', 'className' => 'btn btn-danger btn-delete'],
//                            ['extend' => 'pageLength', 'className' => 'dataTables_length'],
//                            ['extend' => 'csv', 'title' => $this->filename(), 'className' => 'btn btn-primary', 'text' => '<i class="la la-file"></i> CSV File'],
//                            ['extend' => 'excel', 'title' => $this->filename(), 'className' => 'btn btn-info', 'text' => '<i class="la la-print"></i> Export Excel'],
//                        ],
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
            Column::make('student_number')
                ->title('رقم الطالب'),
            Column::make('name')
                ->title('اسم الطالب'),
            Column::computed('checkbox')
                ->title('غياب بعذر')
                ->exportable(false)
                ->printable(false)
                ->width(50),
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
