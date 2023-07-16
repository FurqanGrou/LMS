<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Teacher;
use Illuminate\Http\Request;
use App\DataTables\AdminDatatable;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdminDatatable $admin)
    {
        return $admin->render('admins.admins.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admins.admins.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rule = [
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
            'employee_number' => 'required|unique:admins,employee_number',
            'last_4_id' => 'required|digits:4|unique:admins,last_4_id',
            'section'   => 'required|in:male,female',
            'user_type'   => isHasUserType('super_admin') ? 'required|string|in:super_admin,furqan_group,iksab,egypt' : 'sometimes|string',
        ];

        $messages = [
            'name.required' => 'يجب التأكد من إدخال الأسم بالكامل',
            'name.string' => 'يجب التأكد من إدخال الأسم بالكامل بشكل صحيح',
            'email.required' => 'يجب التأكد من إدخال البريد الإلكتروني',
            'email.email' => 'يجب التأكد من إدخال البريد الإلكتروني',
            'email.unique' => 'البريد الإلكتروني المدخل مستخدم مسبقا',
            'password.required' => 'يجب التأكد من إدخال كلمة المرور',
            'password.min' => 'يجب أن تكون كلمة المرور 6 حروف على الأقل ',
            'employee_number.required' => 'يجب التأكد من إدخال رقم الموظف',
            'employee_number.unique' => 'رقم الموظف المدخل مستخدم مسبقا',
            'last_4_id.required' => 'يجب التأكد من إدخال أخر 4 ارقام من الهوية',
            'last_4_id.digits' => 'رقم الهوية المدخل غير صالح',
            'last_4_id.unique' => 'رقم الهوية المدخل مستخدم مسبقا',
            'section.required' => 'يجب التأكد من إدخال القسم',
            'section.in' => 'يجب التأكد من إدخال القسم',
            'user_type.required' => 'يجب التأكد من اختيار المؤسسة المناسبة',
            'user_type.in' => 'يجب التأكد من اختيار المؤسسة المناسبة',
        ];

        $adminData = $this->validate($request, $rule, $messages);

        $adminData['password'] = bcrypt($request->password);
        $adminData['user_type'] = isHasUserType('super_admin') ? $request->user_type : auth()->user()->user_type;

        Admin::create($adminData);

        session()->flash('success', 'تمت الاضافة بنجاح');

        return redirect()->route('admins.admins.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(Admin $admin)
    {
        return view('admins.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Admin $admin)
    {
        $rule = [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:admins,email,' . $admin->id,
            'employee_number' => 'required|unique:admins,employee_number,' . $admin->id,
            'last_4_id' => 'required|digits:4|unique:admins,last_4_id,' . $admin->id,
            'password' => 'sometimes|nullable|min:6',
            'section'   => 'required|in:male,female',
            'user_type'   => isHasUserType('super_admin') ? 'required|string|in:super_admin,furqan_group,iksab,egypt' : 'sometimes|string',
        ];

        $messages = [
            'name.required'  => 'يجب التأكد من إدخال الأسم بالكامل',
            'name.string'    => 'يجب التأكد من إدخال الأسم بالكامل بشكل صحيح',
            'email.required' => 'يجب التأكد من إدخال البريد الإلكتروني',
            'email.email'    => 'يجب التأكد من إدخال البريد الإلكتروني',
            'email.unique'   => 'البريد الإلكتروني المدخل مستخدم مسبقاً لموظف آخر',
            'password.required' => 'يجب التأكد من إدخال كلمة المرور',
            'password.min'      => 'يجب أن تكون كلمة المرور 6 حروف على الأقل ',
            'employee_number.required' => 'يجب التأكد من إدخال رقم الموظف',
            'employee_number.unique' => 'رقم الموظف المدخل مستخدم مسبقاَ لموظف آخر',
            'last_4_id.unique' => 'أخر 4 ارقام من الهوية مدخلة هي مستخدمة بالفعل لموظف آخر',
            'last_4_id.required' => 'يجب التأكد من إدخال أخر 4 ارقام من الهوية',
            'last_4_id.digits' => 'رقم الهوية المدخل غير صالح',
            'section.required' => 'يجب التأكد من إدخال القسم',
            'section.in' => 'يجب التأكد من إدخال القسم',
            'user_type.required' => 'يجب التأكد من اختيار المؤسسة المناسبة',
            'user_type.in' => 'يجب التأكد من اختيار المؤسسة المناسبة',
        ];

        $adminData = $this->validate($request, $rule, $messages);

        if ($request->password){
            $adminData['password'] = bcrypt($request->password);
            $admin->update($adminData);
        }else{
            $admin->update([
                'employee_number'  => $adminData['employee_number'],
                'section'  => $adminData['section'],
                'last_4_id'  => $adminData['last_4_id'],
                'name'  => $adminData['name'],
                'email' => $adminData['email'],
                'user_type' => isHasUserType('super_admin') ? $adminData['user_type'] : $admin->user_type,
            ]);
        }

        session()->flash('success', 'تم تحديث البيانات بنجاح');

        return redirect()->route('admins.admins.edit', $admin->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        Admin::find($id)->delete();
        session()->flash('success', trans('تم الحذف بنجاح'));
        return redirect()->route('admins.admins.index');
    }

    public function deleteAll() {
        if (is_array(request('item'))) {
            Admin::destroy(request('item'));
        } else {
            Admin::find(request('item'))->delete();
        }
        session()->flash('success', 'تم الحذف بنجاح');
        return redirect(route('admins.admins.index'));
    }

    public function disableTeachersLogin(Request $request)
    {
        DB::beginTransaction();
        try {
            Teacher::query()->update(['status' => $request->status]);
            DB::commit();
        }catch (Throwable $e){
            DB::rollBack();
            return response()->json(['status' => 'Expectation Failed'], 417);
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function changeSendMonthlyReportStatus()
    {
        clearCache();
        $status_previous = env('ENABLE_PREVIOUS_MONTH');
        $status_current  = env('ENABLE_MONTHLY_SEND');

        return view('admins.settings.change_monthly_send', ['status_previous' => $status_previous, 'status_current' => $status_current]);
    }

    public function changeSendMonthlyReportStatusUpdate(Request $request)
    {
        changeEnvironmentVariable("ENABLE_PREVIOUS_MONTH", $request->previous_status);
//        changeEnvironmentVariable("ENABLE_MONTHLY_SEND", $request->current_status);

        return response()->json(['status' => 'success'], 200);
    }

    public function enableTeachersUpdate() {
        $teachers = Teacher::query()->get();
        return view('admins.settings.enable_teachers_update', compact('teachers'));
    }

    public function enableTeachersUpdateStore(Request $request)
    {
        Teacher::query()->update([
            'update_previous_month' => '0',
        ]);

        if ($request->teachers_id == '-1') {
            Teacher::query()->update([
                'update_previous_month' => '1',
            ]);
        }else{
            Teacher::query()->whereIn('id', $request->teachers_id)->update([
                'update_previous_month' => '1',
            ]);
        }

        session()->flash('success', 'تم التحديث بنجاح');

        return redirect()->back();
    }


}
