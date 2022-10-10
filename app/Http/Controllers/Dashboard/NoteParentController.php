<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\NoteParentDataTable;
use App\Http\Controllers\Controller;
use App\NoteParent;
use Illuminate\Http\Request;

class NoteParentController extends Controller
{
    public function index(NoteParentDataTable $noteParent)
    {
        return $noteParent->render('admins.note_parents.index');
    }

    public function create()
    {
        return view('admins.note_parents.create');
    }

    public function edit(NoteParent $noteParent)
    {
        return view('admins.note_parents.edit', ['noteParent' => $noteParent]);
    }

    public function update(Request $request, NoteParent $noteParent)
    {
        $rule = [
            'text'     => 'required|string',
            'text_en'  => 'required|string',
        ];

        $messages = [
            'text.required'    => 'يجب التأكد من إدخال نص الرسالة بالعربية',
            'text_en.required' => 'يجب التأكد من إدخال نص الرسالة بالانجليزية',
        ];

        $this->validate($request, $rule, $messages);

        $noteParent->update([
            'text'    => $request->text,
            'text_en' => $request->text_en,
        ]);

        session()->flash('success', 'تم تحديث البيانات بنجاح');

        return redirect(route('admins.note-parents.edit', $noteParent->id));
    }

    public function store(Request $request)
    {
        $rule = [
            'text'     => 'required|string',
            'text_en'  => 'required|string',
            'section'  => 'required|in:1,2|not_in:0',
        ];

        $messages = [
            'text.required'    => 'يجب التأكد من إدخال نص الرسالة بالعربية',
            'text_en.required' => 'يجب التأكد من إدخال نص الرسالة بالانجليزية',
            'section.required' => 'يجب التأكد من اختيار القسم المناسب',
            'section.in' => 'يجب التأكد من اختيار القسم المناسب',
            'section.not_in' => 'يجب التأكد من اختيار القسم المناسب',
        ];

        $this->validate($request, $rule, $messages);

        $section = $request->section == '1' ? 'male' : 'female';

        $notes_count = NoteParent::query()->where('gender', '=', $section)->count();

        if ($notes_count >= 25){
            session()->flash('error', 'نأسف، لا يمكن إضافة ملاحظات جديدة لهذا القسم بسبب الوصول للحد المسموح به');
            return redirect(route('admins.note-parents.create'));
        }

        NoteParent::query()->create([
            'text'    => $request->text,
            'text_en' => $request->text_en,
            'gender'  => $section,
        ]);

        session()->flash('success', 'تم ادخال البيانات بنجاح');

        return redirect(route('admins.note-parents.index'));
    }
}
