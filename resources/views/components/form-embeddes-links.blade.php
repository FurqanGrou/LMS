@foreach($forms as $form)
    <li>
        <a class="menu-item" href="{{ route('teachers.form_service.show', $form->id) }}" data-i18n="nav.templates.vert.classic_menu">{{ $form->title }}</a>
    </li>
@endforeach
