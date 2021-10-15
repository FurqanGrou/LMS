@extends('dashboard.layouts.master')

@section('content')

    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')

    <form class="form" method="POST" action="{{ dRoute('settings.update', $settings->id) }}">

        @csrf
        @method('PUT')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-settings"></i> إعدادات التطبيق</h4>

            {{--Public info--}}
            <div class="row">
                <div class="col-12">
                    <div class="card" data-height="">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-form">معلومات عامة</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show" style="">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="full-name">اسم التطبيق</label>
                                            <input type="text" id="full-name" class="form-control" name="website_title" value="{{ old('website_title', $settings->website_title) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">العنوان</label>
                                            <input type="text" id="address" class="form-control" name="address" value="{{ old('address', $settings->address) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="email">البريد الإلكتروني</label>
                                            <input type="email" id="email" class="form-control" name="email" value="{{ old('email', $settings->email) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="administration_phone">رقم هاتف الإدارة</label>
                                            <input type="text" id="administration_phone" class="form-control" name="administration_phone" value="{{ old('administration_phone', $settings->administration_phone) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="support_phone">رقم هاتف الدعم الفني</label>
                                            <input type="text" id="support_phone" class="form-control" name="support_phone" value="{{ old('support_phone', $settings->support_phone) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="support_phone">رقم هاتف الدعم الفني</label>
                                            <input type="text" id="support_phone" class="form-control" name="support_phone" value="{{ old('support_phone', $settings->support_phone) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <fieldset class="form-group">
                                            <label for="about_us">نص من نحن</label>
                                            <textarea name="about_us" id="about_us" style="width: 100%" rows="7" required>{{ old('about_us', $settings->about_us) }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="form-group">
                                            <label for="terms_conditions">نص الشروط والأحكام</label>
                                            <textarea name="terms_conditions" id="terms_conditions" style="width: 100%" rows="7" required>{{ old('terms_conditions', $settings->terms_conditions) }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--Payment--}}
            <div class="row">
                <div class="col-12">
                    <div class="card" data-height="">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-form">معلومات بوابة الدفع</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show" style="">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="api_url">API URL</label>
                                            <input type="text" id="api_url" class="form-control" name="payment_api_url" value="{{ old('payment_api_url', $settings->payment_api_url) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="api_token_key">API Token Key</label>
                                            <input type="password" id="api_token_key" class="form-control" name="payment_api_token_key" value="{{ old('payment_api_token_key', $settings->payment_api_token_key) }}" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--SMS Messages--}}
            <div class="row">
                <div class="col-12">
                    <div class="card" data-height="">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-form">معلومات خدمة الرسائل SMS</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="account_sid">TWILIO_ACCOUNT_SID</label>
                                            <input type="password" id="account_sid" class="form-control" name="sms_account_sid" value="{{ old('sms_account_sid', $settings->sms_account_sid) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="auth_token">TWILIO_AUTH_TOKEN</label>
                                            <input type="password" id="auth_token" class="form-control" name="sms_auth_token" value="{{ old('sms_auth_token', $settings->sms_auth_token) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="verify_sid">TWILIO_VERIFY_SID</label>
                                            <input type="password" id="verify_sid" class="form-control" name="sms_verify_sid" value="{{ old('sms_verify_sid', $settings->sms_verify_sid) }}" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> تحديث
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection
