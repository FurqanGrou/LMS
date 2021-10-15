<?php

namespace App\DataTables;

use App\Teacher;
use App\Classes;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\ButtonsServiceProvider;

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

        $students = User::where('class_number', '=', $class_number)->get();

        return datatables($students)
            ->addColumn('student_name', 'teachers.reports.btn.student_name')
            ->addColumn('mail_status', 'teachers.reports.btn.mail_status')
            ->rawColumns([
                'student_name',
                'mail_status',
            ])->with('class_number', $class_number);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\AdDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

//        $classes = Classes::join('classes_teachers', 'classes.class_number', '=', 'classes_teachers.class_number')
//            ->join('teachers', 'teachers.teacher_number', '=', 'classes_teachers.teacher_number')
//            ->where('teachers.teacher_number', '=', auth()->user()->teacher_number)
//            ->select(['classes_teachers.teacher_number', 'classes.title']);


//        $categories = Ad::select('id', 'is_featured', 'title', 'description', 'phone', 'price', 'currency_id', 'is_active', 'created_at')->orderBy('id', 'desc');
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
//                    ->parameters([$this->getBuilderParameters()]);
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'lengthMenu' => [
                            [ 25, 50, 75, -1 ],
                            [ '25', '50', '75', 'Show all' ]
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
            Column::make('student_number')
                ->data('student_number')
                ->title('رقم الطالب'),
            Column::make('student_name')
                ->data('student_name')
                ->title('اسم الطالب'),
            Column::make('login_time')
                ->data('login_time')
                ->title('وقت الدخول')
                ->className('std-name'),
//            Column::make('status')
//                ->data('status')
//                ->title('الحالة'),
            Column::make('mail_status')
                ->data('mail_status')
                ->title('حالة الارسال'),
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
