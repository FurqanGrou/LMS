<?php

namespace App\DataTables;

use App\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\ButtonsServiceProvider;

class UserDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        return datatables($query)
            ->addColumn('student_name', 'admins.students.btn.student_name')
            ->addColumn('mail_status', 'admins.students.btn.mail_status')
            ->rawColumns([
                'student_name',
                'mail_status',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\AdDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $students = User::query();

        if(!isHasUserType('super_admin')){
            $students = $students->where('study_type', '=', getUserType() == 'iksab' ? 1 : 0 );
        }

//        $classes = Classes::join('classes_teachers', 'classes.class_number', '=', 'classes_teachers.class_number')
//            ->join('teachers', 'teachers.teacher_number', '=', 'classes_teachers.teacher_number')
//            ->where('teachers.teacher_number', '=', auth()->user()->teacher_number)
//            ->select(['classes_teachers.teacher_number', 'classes.title']);


//        $categories = Ad::select('id', 'is_featured', 'title', 'description', 'phone', 'price', 'currency_id', 'is_active', 'created_at')->orderBy('id', 'desc');
        return $students;
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
                'language' => ['url' => 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Arabic.json']
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
            Column::make('name')
                ->data('student_name')
                ->title('اسم الطالب')
                ->className('std-name'),
            Column::make('login_time')
                ->data('login_time')
                ->title('وقت الدخول'),
            Column::make('exit_time')
                ->data('exit_time')
                ->title('وقت الخروج'),
            Column::make('mail_status')
                ->data('mail_status')
                ->title('حالة الارسال')
                ->exportable(false)
                ->printable(false)
                ->orderable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Students_' . date('YmdHis');
    }
}
