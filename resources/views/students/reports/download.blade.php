<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ auth()->user()->name }} - التقرير الشهري</title>
    <link href="{{ asset('dashboard/assets/css/pdf.css') }}" rel="stylesheet" media="all" type="text/css" />

</head>
<body>
 <div id="top-header">
                <div class="first-imgs">
                    <img src="{{ asset('/dashboard/assets/img/logo2.png') }}" alt="">
                    <img src="{{ asset('/dashboard/assets/img/logo3.png') }}" alt="">
                </div>
                <div class="text-center">
                    <h2 >الدليل الشهري لسير ومتابعة {{ auth()->user()->name }} في حلقات مركز الفرقان لتعليم القران الكريم</h2>
                </div>
                <div class="last-imgs">
                    <img src="{{ asset('/dashboard/assets/img/logo1.png') }}" alt="">
                </div>
        </div>

    <div class="container">
        <div class="content-body">

            <!-- edit marks and new assignment of yesterday -->
            <section class="horizontal-grid" id="horizontal-grid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a class="heading-elements-toggle"><i class="ft-align-justify font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>التاريخ</th>
                                                <th>اليوم</th>
                                                <th>الدرس الجديد</th>
                                                <th>من</th>
                                                <th>إلى</th>
                                                <th>أخر 5 صفحات</th>
                                                <th>المراجعة اليومية</th>
                                                <th>من</th>
                                                <th>إلى</th>

                                                <th>خطأ</th>
                                                <th>تنبيه</th>
                                                <th>عدد الصفحات</th>
                                                <th>درجات الدرس</th>
                                                <th>درجات أخر 5 صفحات</th>
                                                <th>درجات المراجعة اليومية</th>
                                                <th>درجات السلوك</th>
                                                <th>المجموع</th>
                                                <th>ملاحظات الى ولي الأمر</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($monthReports as $dayReport)
                                                <tr class="bg-blue bg-lighten-5">
                                                    <td>{{ substr($dayReport->date, -10) }}</td>
                                                    <td>{{ substr($dayReport->date, 0, -10) }}</td>
                                                    <td>{{ $dayReport->new_lesson }}</td>
                                                    <td>{{ $dayReport->new_lesson_from }}</td>
                                                    <td>{{ $dayReport->new_lesson_to }}</td>
                                                    <td>{{ $dayReport->last_5_pages }}</td>
                                                    <td>{{ $dayReport->daily_revision }}</td>
                                                    <td>{{ $dayReport->daily_revision_from }}</td>
                                                    <td>{{ $dayReport->daily_revision_to }}</td>
                                                    <td>{{ $dayReport->mistake }}</td>
                                                    <td>{{ $dayReport->alert }}</td>
                                                    <td>{{ $dayReport->number_pages }}</td>
                                                    <td>{{ $dayReport->lesson_grade }}</td>
                                                    <td>{{ $dayReport->last_5_pages_grade }}</td>
                                                    <td>{{ $dayReport->daily_revision_grade }}</td>
                                                    <td>{{ $dayReport->behavior_grade }}</td>
                                                    <td>{{ $dayReport->total }}</td>
                                                    <td>{{ $dayReport->notes_to_parent }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- edit marks and new assignment of yesterday -->


        </div>
    </div>
</body>
</html>
