@extends('teachers.layouts.master')

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

<form class="form" method="POST" action="{{ route('teachers.request_services.meetings.store') }}">

    @csrf

    <div class="form-body">
        <h4 class="form-section"><i class="ft-lock"></i>طلب الخدمة - طلب اجتماع مع مراقب</h4>

        <div class="row">
            <div class="col-md-4">
                {{-- teacher_name--}}
                <div class="form-group">
                    <label> اسم المعلم المراقب:</label>
                    <br>
                    <select name="teacher_id" class="select2" required>
                        <option>-</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                {{-- meeting status--}}
                <div class="form-group">
                    <label> حالة الاجتماع:</label>
                    <br>
                    <select name="status" class="select2" id="meeting_status" required>
                        <option>-</option>
                        <option value="مستعجل">مستعجل</option>
                        <option value="غير مستعجل">غير مستعجل</option>
                    </select>
                    <small id="status_note" class="form-text text-muted d-none"></small>
                </div>
            </div>

            <div class="col-md-4">
                {{-- favorite_time--}}
                <div class="form-group">
                    <label for="favorite_time">الوقت المناسب للاجتماع عند المعلم:</label>
                    <textarea name="favorite_time" class="form-control" id="favorite_time" rows="5" required>{{ old('favorite_time') }}</textarea>
                    <small class="form-text text-muted">عزيزنا المعلم.. نظراً لانشغال كافةالمراقبين والمراقبات نرجو منكم كتابة أكثر من خيار مناسب لكم؛لإتاحة الفرصة للمراقب لاختيار  الموعدالمناسب له من بين المواعيد المقترحة. مع الشكر</small>
                </div>
            </div>

            <div class="col-md-6">
                {{-- reason--}}
                <div class="form-group">
                    <label for="reason" style="margin-bottom: 40px">سبب الاجتماع:</label>
                    <textarea name="reason" class="form-control" rows="5" id="reason" required>{{ old('reason') }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                {{-- description--}}
                <div class="form-group">
                    <label for="description">وصف الاجتماع:</label>
                    <small class="form-text text-muted">عزيزنا المعلم.. حفاظاً على وقتكم ورغبة في استثمار الوقت والجهد بأفضل طريقة ممكنة نرجو منكم كتابة نبذة عن الاجتماع بحيث يستطيع المراقب دراسة الحالة وإفادتكم بنتيجتها في الاجتماع، سائلين الله لكم السداد والتوفيق.</small>
                    <textarea name="description" class="form-control" rows="5" id="description">{{ old('description') }}</textarea>
                </div>
            </div>

        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="la la-check-square-o"></i> ارسال
        </button>
        <button type="reset" class="btn btn-warning mr-1">
            <i class="ft-x"></i> إلغاء
        </button>
    </div>

</form>

@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $(document).on('change', 'select#meeting_status', function (e) {
                if ($(this).val() == 'مستعجل'){
                    $('#status_note').removeClass('d-none');
                    $('#status_note').html("نفيدكم بأنه سيتم إجراء الاجتماع معكم خلال 48 ساعة");
                }else{
                    $('#status_note').removeClass('d-none');
                    $('#status_note').html(" نفيدكم بأنه سيتم إجراء الاجتماع معكم خلال (3 - 5) أيام بحد أقصى");
                }
            });

            $(document).on('change', 'select#meeting_status', function (e) {
                if ($(this).val() == 'مستعجل'){
                    $('#status_note').removeClass('d-none');
                    $('#status_note').html(" نفيدكم بأنه سيتم إجراء الاجتماع معكم خلال 48 ساعة");
                }else{
                    $('#status_note').removeClass('d-none');
                    $('#status_note').html(" نفيدكم بأنه سيتم إجراء الاجتماع معكم خلال (3 - 5) أيام بحد أقصى");
                }
            });

        });
    </script>
@endpush
