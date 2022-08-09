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

}
