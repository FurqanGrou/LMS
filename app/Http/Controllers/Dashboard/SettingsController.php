<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(){

        $settings = Settings::orderBy('id', 'desc')->first();

        return view('dashboard.settings.edit', compact('settings'));
    }

    public function update(Request $request){

        $settings = Settings::orderBy('id', 'desc')->first();

        $rules = [
            'website_title' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'administration_phone' => 'required|numeric ',
            'support_phone' => 'required|numeric ',
            'about_us' => 'required|string',
            'terms_conditions' => 'required|string',
            'payment_api_url' => 'required|url',
            'payment_api_token_key' => 'required|string',
            'sms_account_sid' => 'required|string',
            'sms_auth_token' => 'required|string',
            'sms_verify_sid' => 'required|string',
        ];

        $messages = [
            'website_title.required'  => 'يجب التأكد من إدخال الأسم',
            'website_title.string'    => 'يجب التأكد من إدخال الأسم بشكل صحيح',
            'address.required' => 'يجب التأكد من إدخال العنوان',
            'address.string' => 'يجب التأكد من إدخال العنوان بشكل صحيح',
            'email.required'    => 'يجب التأكد من إدخال البريد الإلكتروني',
            'email.email'    => 'يجب التأكد من إدخال البريد الإلكتروني بشكل صحيح',
            'administration_phone.required' => 'يجب التأكد من إدخال رقم هاتف الإدارة',
            'administration_phone.numeric' => 'يجب التأكد من إدخال رقم هاتف الإدارة بشكل صحيح',
            'support_phone.required' => 'يجب التأكد من إدخال رقم هاتف الدعم الفني',
            'support_phone.numeric' => 'يجب التأكد من إدخال رقم هاتف الدعم الفني بشكل صحيح',
            'about_us.required' => 'يجب التأكد من إدخال رسالة من نحن',
            'about_us.string' => 'يجب التأكد من إدخال رسالة من نحن بشكل صحيح',
            'terms_conditions.required' => 'يجب التأكد من إدخال نص الشروط والأحكام',
            'terms_conditions.string' => 'يجب التأكد من إدخال نص الشروط والأحكام بشكل صحيح',
        ];

        $data = $this->validate($request, $rules, $messages);

        $settings->update($data);

        session()->flash('success', 'تم التحديث بنجاح');

        return redirect(dRoute('settings.edit'));
    }
}
