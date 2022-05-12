<html>
<head></head>
<body>

@if(isOnlineStudent($details['student_info']->id))
    <img style='display:block;margin-left:auto;margin-right:auto'
         src='https://pbs.twimg.com/profile_images/1051962534604009473/XyW3M3qj.jpg' alt='Furqan Center' width='133'
         height='133' class='CToWUd'>
@else
    <img style='display:block;margin-left:auto;margin-right:auto'
         src='https://iksab.sa/wp-content/uploads/2021/11/300.png' alt='Furqan Center' width='133'
         height='133' class='CToWUd'>
@endif

<div style='text-align: center;'>
 <span style='font-family: tahoma, arial, helvetica, sans-serif;'>
  <strong>
   <span
       style='font-size: 14pt;'> Student Daily Report التقرير اليومي للطالب{{ getTitleName($details['student_info']->section) }} </span>
 <br>
   </strong>
  <span style='font-size: 12pt; color: #ff0000;'> {{ $details['assignment']->date }} </span>
</span>
</div>
<div id='chq_gmail_elements_commands' class='Lf a5s' data-chq-gmail-elements-table='chq_gmail_elements_commands'>
    <table class='Lf' border='0' cellspacing='0' cellpadding='0'
           data-chq-gmail-elements-table='chq_gmail_elements_commands'>
        <tbody>
        <tr valign='top'>
            <td class='Le' width='2' height='2'>
                <br>
                <br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<table align='center' cellpadding='1' cellspacing='1' style='height: 94px; width:100%'>
    <tbody>
    <tr valign='top'>
        <td>
            <h3 style='text-align: left;'>
          <span style='font-family: tahoma, arial, helvetica, sans-serif;'>
         <strong>Dear guardian of
            <br>
             <span style='color: #ff0000;'> {{ $details['student_info']->name }} </span>
          </strong>
</span>
            </h3>
        </td>
        <td>
            <h3 style='text-align: right;'>
         <span style='font-family: tahoma, arial, helvetica, sans-serif;'>
            <strong>  المكرم ولي أمر الطالب{{ getTitleName($details['student_info']->section) }}
              <br>&nbsp;
             <span style='color: #ff0000;'>  {{ $details['student_info']->name }} </span>
           </strong>
          </span>
            </h3>
        </td>
    </tr>
    </tbody>
</table>
<table style='height: 137px;' cellspacing='1' cellpadding='1' align='center' table-layout:
'fixed' ;width: '100%'>
<tbody>
<tr>
    <td style='width: 49%;' valign='top'>
        <div style=text-align='left';'>
            <strong>
            <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt;'>
              <span style='font-family: tahoma, arial, helvetica, sans-serif;'>Below is your child's performance report on
                <span style='color: #ff0000;'>{{ $details['assignment']->date }}</span>, with details in the attached file about grades, student's performance and progress to view, monitor, and follow up at home

            </span>
            </span>
            </strong>
        </div>
    </td>
    <td style='width: 2%;'>
        <div style='text-align: right;'>
         <span style='font-size: 12pt;'>
            <strong>
              <span style='font-family: tahoma, arial, helvetica, sans-serif;'>
              <span style='font-family: tahoma, arial, helvetica, sans-serif;'>
                <br>
                </span>
              </span>
        </strong>
         </span>
        </div>
    </td>
    <td style='width: 49%;' valign='top'>
        <div style='text-align: right;'>
         <span style='font-size: 12pt;'>
           <strong>
            <span style='font-family: tahoma, arial, helvetica, sans-serif;'>
                <span style='font-family: tahoma, arial, helvetica, sans-serif;'>نرجو الاطلاع على تقرير {{ $details['student_info']->section == 'male' ? 'ابنكم' : 'ابنتكم' }} ليوم
                 <span style='color: #ff0000;'>{{ substr($details['assignment']->date, -10) }} </span>ومتابعة الابن{{ getTitleName($details['student_info']->section) }} في المنزل، كما يمكنكم الاطلاع على تفاصيل الدرجات ومستوى الطالب{{ getTitleName($details['student_info']->section) }} وتقدمه{{ $details['student_info']->section == 'female' ? 'ا' : '' }} من خلال الملف المرفق
               </span>
             </span>
            </strong>
          </span>
        </div>
    </td>
</tr>
</tbody>
</table>
<br>
<br>
<div style='text-align: center;'>
  <span style='font-size: 14pt; font-family: tahoma, arial, helvetica, sans-serif;'>
    <strong>Today's Report&nbsp;&nbsp;</strong>
    <strong>نتيجة اليوم<strong>
  </span>
</div>
<p>
    <br>
</p>
<table style='border-collapse: collapse; width: 100%;' border='1' style='height: 77px; width:100%' align='center'>
    <tbody>
    <tr>
        <td style='text-align: center; height: 26px;'>
            <h3>
          <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong> المجموع
              <br>
            </strong>

            <strong>Total</strong>
          </span>
            </h3>
        </td>
        <td style='text-align: center; height: 26px;'>
            <h3>
          <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong>السلوك
              <br>
            </strong>

            <strong>Behavior</strong>
          </span>
            </h3>
        </td>
        <td style='text-align: center; height: 26px;'>
            <h3>
          <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong>المراجعة اليومية
              <br>
            </strong>
            <strong>Daily Revision</strong>
          </span>
            </h3>
        </td>
        <td style='text-align: center; height: 26px;'>
            <h3>
          <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong> آخر 5 صفحات
              <br>
            </strong>

            <strong>Last 5 Pages</strong>
          </span>
            </h3>
        </td>
        <td style='text-align: center; height: 26px;'>
            <h3>
          <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong>الدرس الجديد</strong>&nbsp;          </span>

                <br>
                <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong>New Lesson</strong>
          </span>
            </h3>
    </tr>
    <tr style='height: 51px;'>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'>0</span>
            </h3>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'>0</span>
            </h3>
        </td>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'>0</span>
            </h3>
        </td>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'>0</span>
            </h3>
        </td>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'>0</span>
            </h3>
        </td>
    </tr>
    </tbody>
</table>
<br>
<br>
<div style='text-align: center;'>
  <span style='font-family: tahoma, arial, helvetica, sans-serif;'>
    <span style='font-size: 18.6667px;'>
     <strong>Tomorrow's Requirements المطلوب يوم غد</strong>
    </span>
  </span>
</div>
<p>
    <br>
</p>
<table style='border-collapse: collapse; width: 100%;' border='1' style='height: 61px; width:100%' align='center'>
    <tr style='height: 26px;'>
        <td style='text-align: center; height: 10px; '>
            <h3>
          <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong> إلى
              <br>
            </strong>

            <strong>To</strong>
         </span>
            </h3>
        </td>
        <td style='text-align: center; height: 10px; '>
            <h3>
          <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong>من
              <br>
            </strong>
            <strong>From</strong>
          </span>
            </h3>
        </td>
        <td style='text-align: center; height: 10px; '>
            <h3>
          <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #ff0000;'>
            <strong>سورة
              <br>Surat

            </strong>
          </span>
            </h3>
        </td>
        <td style='text-align: center; height: 10px; '>
            <h3>
          <span style='color: #ff0000; font-family: tahoma, arial, helvetica, sans-serif;'>
            <span style='font-size: 16px;'>م</span>
          </span>
            </h3>
        </td>
    </tr>
    <tr style='height: 51px;'>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'> {{ $details['assignment']->new_lesson_to }} </span>
            </h3>
        </td>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'> {{ $details['assignment']->new_lesson_from }} </span>
            </h3>
        </td>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'> {{ $details['assignment']->new_lesson }} </span>
            </h3>
        </td>
        <td style='text-align: center; height: 51px; '>
            <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'><em>New Lesson الدرس الجديد</em></span>
            </h3>
        </td>
    <tr style='height: 51px;'>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'>{{ $details['assignment']->daily_revision_to }}</span>
            </h3>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'>{{ $details['assignment']->daily_revision_from }}</span>
            </h3>
        </td>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span
                    style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'> {{ $details['assignment']->daily_revision }} </span>
            </h3>
        </td>
        <td style='text-align: center; height: 51px; '>
            <h3>
                <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 12pt; color: #000000;'><em>Daily Revision المراجعة اليومية</em></span>
            </h3>
        </td>
    </tr>
</table>
<br>
<br>
<br>
<br>
<div style='text-align: center; border:1px solid black; text-align: center;'>
<span style='font-family: tahoma, arial, helvetica, sans-serif;'>
   <span style='font-size: 18.6667px;'>
      <strong>Teacher Notes ملاحظات المعلم</strong>
    </span>
  </span>
    <div style='text-align: center;'>
        <br>
        <span style='font-family: tahoma, arial, helvetica, sans-serif; font-size: 14pt; color: #ff0000;'>
    <strong> أهلاً وسهلاً بكم </strong>
  </span>
        <br></div>
</div>
<br>
</body>
</html>
