@extends('admins.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">جميع الطلبات المقدمة</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>

                @include('teachers.partials.success')

                <div class="card-content collapse show">
                    <div class="card-body card-admins">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                            <div class="box-body">
                                {!! $dataTable->table(['class' => 'table table-striped table-bordered dataTable', 'id' => 'DataTables_Table_4', 'style' => 'width:100%']) !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')

        {!! $dataTable->scripts() !!}

        <script>
            $(function() {

                $(document).on('change', '.toggle-respond-request', function (e) {

                    e.preventDefault();

                    swal({
                        title: "هل أنت متأكد من الموافقة على الطلب؟",
                        text: "بعد الضغط على تأكيد لن يمكنك التراجع عن عملية الموافقة!",
                        icon: "warning",
                        buttons: {
                            cancel: {
                                text: "لا، إلغاء!",
                                value: null,
                                visible: true,
                                className: "btn-danger",
                                closeModal: false,
                            },
                            confirm: {
                                text: "نعم، موافقة!",
                                value: true,
                                visible: true,
                                className: "btn-success",
                                closeModal: false
                            }
                        }
                    }).then((isConfirm) => {
                        let tr = $(this).closest('tr');
                        let class_number = $(this).data('class-number');
                        let teacher_email = $(this).data('teacher-email');

                        if (isConfirm) {
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url: '{{ route('admins.classes.respond_request') }}',
                                data: {
                                    'class_number': class_number,
                                    'teacher_email': teacher_email,
                                },
                                success: function(data){
                                    tr.remove();
                                }
                            });
                            swal("تتم الموافقة...!", "تتم حالياً عملية الموافقة", "info");
                        } else {
                            $(this).prop('checked', false);
                            swal("يتم الإلغاء!", "تم إلغاء عملية الموافقة", "error");
                        }
                    });
                });

            })
        </script>

    @endpush

@endsection
