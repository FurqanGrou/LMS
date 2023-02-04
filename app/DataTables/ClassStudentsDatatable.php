<?php

namespace App\DataTables;

use App\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ClassStudentsDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        $class_number = $this->class_number;

        $students = User::query()
            ->select(['*', DB::raw('TIME_FORMAT(login_time, "%h:%i:%p") as login_time'), DB::raw('TIME_FORMAT(exit_time, "%h:%i:%p") as exit_time')])
            ->where('class_number', '=', $class_number);

        if (auth()->guard('admin_web')->check()){
            $student_name = 'admins.students.btn.student_name';
        }else{
            $student_name = 'teachers.reports.btn.student_name';
        }

        return datatables($students)
            ->addColumn('student_name', $student_name)
            ->addColumn('mail_status', 'teachers.reports.btn.mail_status')
            ->addColumn('monthly_mail_status', 'teachers.reports.btn.monthly_mail_status')
            ->addColumn('monthly_avg', 'teachers.reports.btn.monthly_avg')
            ->rawColumns([
                'student_name',
                'mail_status',
                'monthly_mail_status',
                'monthly_avg',
            ])->with('class_number', $class_number);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return '';
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
                            [ 25, 50, 75, -1 ],
                            [ '25', '50', '75', 'Show all' ]
                        ],
                        'buttons' => [
                            ['extend' => 'pageLength', 'className' => 'dataTables_length'],
                        ],
//                        'language' => ['url' => 'http://cdn.datatables.net/plug-ins/1.10.12/i18n/Arabic.json']
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
                ->data('student_number')
                ->title('رقم الطالب')
                ->className('font-weight-bold black'),
            Column::make('student_name')
                ->data('student_name')
                ->title('اسم الطالب')
                ->className('black'),
            Column::make('login_time')
                ->data('login_time')
                ->title('وقت الدخول')
                ->className('std-name font-weight-bold black'),
            Column::make('exit_time')
                ->data('exit_time')
                ->title('وقت الخروج')
                ->className('std-name font-weight-bold black'),
//            Column::make('status')
//                ->data('status')
//                ->title('الحالة'),
            Column::make('mail_status')
                ->data('mail_status')
                ->title('حالة الارسال')
                ->className('black'),
            Column::make('monthly_mail_status')
                ->data('monthly_mail_status')
                ->title('حالة ارسال الشهري')
                ->className('black'),
            Column::make('monthly_avg')
                ->data('monthly_avg')
                ->title('النسبة الشهرية ' . getReportMonth())
                ->className('black'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Ads_' . date('YmdHis');
    }
}
