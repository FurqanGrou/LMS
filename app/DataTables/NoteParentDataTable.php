<?php

namespace App\DataTables;

use App\NoteParent;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\ButtonsServiceProvider;

class NoteParentDataTable extends DataTable
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
            ->addColumn('gender', 'admins.note_parents.btn.gender')
            ->addColumn('edit', 'admins.note_parents.btn.edit')
            ->rawColumns([
                'gender',
                'edit',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param NoteParent $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $notes_to_parents_ar = ['الطالب غائب', 'دوام 3 أيام', 'نشاط لا صفي'];
//        $notes_to_parents_en = ['Absent Student', '3 days work', 'Extracurricular Activity'];

        return NoteParent::query()->whereNotIn('text', $notes_to_parents_ar);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('note_parents_datatable_table')
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
//                            ['text' => 'اضافة مشرف جديد <i class="la la-plus"></i>', 'className' => 'btn btn-warning', 'action' => 'function(){
//                                window.location.href= "' . route('admins.admins.create') . '";
//                            }'],
                            ['extend' => 'pageLength', 'className' => 'dataTables_length'],
//                            ['extend' => 'csv', 'title' => $this->filename(), 'className' => 'btn btn-primary', 'text' => '<i class="la la-file"></i> CSV File'],
//                            ['extend' => 'excel', 'title' => $this->filename(), 'className' => 'btn btn-info', 'text' => '<i class="la la-print"></i> Export Excel'],
                        ],
                        'language' => ['url' => 'http://cdn.datatables.net/plug-ins/1.10.12/i18n/Arabic.json']
                    ])
                    ->buttons([]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')
                ->title('#'),
            Column::make('text')
                ->title('النص عربي')
                ->width('350'),
            Column::make('text_en')
                ->title('النص انجليزي')
                ->width('350'),
            Column::computed('gender')
                ->title('القسم')
                ->exportable(false)
                ->printable(false)
                ->width(80),
            Column::make('updated_at')
                ->title('تاريخ التحديث'),
            Column::computed('edit')
                ->title('تعديل')
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
        return 'note_parents_' . date('YmdHis');
    }
}
