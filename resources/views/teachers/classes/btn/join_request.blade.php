<input type="checkbox" name="join_request" id="switchery01"
       data-color="danger" class="toggle-join-request switchery"
       data-class-number="{{ $class_number }}"
       data-request-status="{{ $request_class ? true : false }}"
        {{ $request_class ? 'checked' : '' }}
/>
