<?php

namespace App\DataTables;

use App\Admin;
use App\AlertMessage;
use App\DropoutStudent;
use App\Report;
use App\User;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\ButtonsServiceProvider;

class DropoutStudentDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        $alert_messages = AlertMessage::query()->get();

        return datatables()
            ->eloquent($query)
            ->addColumn('student_name', function ($user){
                return view('admins.dropout_student.btn.student_name', compact('user'));
            })
            ->addColumn('send_alert_message', function($user) use ($alert_messages){
                return view('admins.dropout_student.btn.send_alert_message', compact('alert_messages', 'user'));
            })->rawColumns([
                'student_name',
                'send_alert_message',
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
        // student must be have class_number
        return DropoutStudent::query()
            ->join('users', 'users.id', '=', 'dropout_students.student_id')
            ->select('users.id', 'users.name', 'users.student_number')
            ->whereNotNull('users.class_number')
            ->distinct();
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
            Column::computed('student_name')
                ->title('اسم الطالب')
                ->exportable(false)
                ->printable(false),
            Column::computed('send_alert_message')
                ->title('رسالة التنبيه')
                ->exportable(false)
                ->printable(false),
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
