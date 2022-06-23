<form action="{{ route('admins.dropout.student.send.alert') }}" method="POST">
    @csrf

    <label class="col-6">
        <input type="hidden" value="{{ $user->id }}" name="student_id">
        <select name="alert_message" class="w-100">
            @foreach($alert_messages as $alert_message)
                <option value="{{ $alert_message->id }}">{{ \Illuminate\Support\Str::limit($alert_message->content, 20) }}</option>
            @endforeach
        </select>
    </label>
    <label class="col-6">
        <input type="submit" value="ارسال" class="w-100 btn btn-danger">
    </label>
</form>
