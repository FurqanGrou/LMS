<form action="{{ route('admins.dropout.student.send.alert') }}" method="POST" class="text-center form-send-mail">
    @csrf

    <label class="col-8">
        <input type="hidden" value="{{ $user->id }}" name="student_id" class="student_id">
        <select name="alert_message" class="w-100 alert-message">
            @foreach($alert_messages as $alert_message)
                <option value="{{ $alert_message->id }}" title="{{ $alert_message->content }}" class="text-center">{{ \Illuminate\Support\Str::limit($alert_message->content, 50) }}</option>
            @endforeach
        </select>
    </label>
    <label class="col-4">
        <input type="submit" value="ارسال" class="w-100 btn btn-danger btn-send-mail">
    </label>
</form>
