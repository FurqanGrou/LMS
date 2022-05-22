<label class="switch">
    <input type="checkbox" class="toggle-absence-type" {{ $absence == -2 ? 'checked' : '' }}>
    <span class="slider round"></span>

    <input type="hidden" class="report_id" value="{{ $id }}">
    <input type="hidden" id="report_date_input" value="{{ $report_created_at }}">
    <input type="hidden" id="report_student_id" value="{{ $student_id }}">
</label>
