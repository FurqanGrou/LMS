<label class="switch">
    <input type="checkbox" class="toggle-absence-type" {{ $absence == -2 ? 'checked' : '' }}>
    <span class="slider round"></span>

    <input type="hidden" class="report_id" value="{{ $id }}">
</label>
