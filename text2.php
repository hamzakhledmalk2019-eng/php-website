﻿<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>الاختبار التحصيلي لمهارات البرمجة بلغة PHP - اختبار إلكتروني</title>
  <style>
    body{font-family: 'Segoe UI', Tahoma, Arial; background:#f7f9fc; color:#111; padding:20px}
    .container{max-width:980px;margin:0 auto;background:#fff;padding:22px;border-radius:10px;box-shadow:0 6px 18px rgba(20,30,50,0.08)}
    h1{font-size:22px;margin-bottom:6px;text-align:center}
    p.lead{text-align:center;margin-top:0;color:#333}
    .question{margin:14px 0;padding:12px;border-radius:8px;border:1px solid #e6eef8;background:#fbfdff}
    .qtext{font-weight:600}
    .choices{margin-top:8px}
    label{display:block;margin:6px 0;cursor:pointer}
    .btns{display:flex;gap:10px;justify-content:center;margin-top:16px;flex-wrap:wrap}
    button{padding:10px 16px;border-radius:8px;border:0;background:#2563eb;color:#fff;cursor:pointer}
    button.secondary{background:#6b7280}
    .result{margin-top:18px;padding:14px;border-radius:10px;background:#eef6ff}
    .wrong{color:#b91c1c}
    .correct{color:#064e3b}
    .small{font-size:13px;color:#374151}
    .download{background:#059669}
    .student-info{margin-bottom:20px;padding:12px;border-radius:8px;border:1px solid #ddd;background:#fdfdfd}
    .student-info label{font-weight:600}
    .student-info input{width:100%;padding:8px;margin-top:4px;margin-bottom:10px;border-radius:6px;border:1px solid #ccc}
    @media (min-width:700px){ .student-info input{width:50%} }
    .timer{font-size:18px;font-weight:bold;text-align:center;margin:10px;padding:10px;border-radius:8px;background:#fef9c3;color:#92400e}
  </style>
</head>
<body>
  <div class="container">
    <h1>الاختبار التحصيلي لمهارات البرمجة بلغة PHP (اختبار إلكتروني)</h1>
    <p class="lead">أدخل بياناتك أولاً ثم أجب على جميع الأسئلة.</p>

    <div id="timer" class="timer">الوقت المتبقي: 15:00</div>

    <form id="quizForm">
      <div class="student-info">
        <label>اسم الطالب:</label>
        <input type="text" name="studentName" required>
        <label>الصف:</label>
        <input type="text" name="studentClass" required>
        <label>المدرسة:</label>
        <input type="text" name="studentSchool" required>
      </div>

      <div id="questions"></div>

      <div class="btns">
        <button type="button" id="submitBtn">تقديم</button>
        <button type="button" id="resetBtn" class="secondary">إعادة الاختبار</button>
        <button type="button" id="downloadBtn" class="download">تحميل النتائج (CSV)</button>
      </div>
    </form>

    <div id="resultArea"></div>
  </div>

<script>
// --------------------- المؤقت ---------------------
let duration = 15 * 60; // 15 دقيقة
let timerDisplay = document.getElementById('timer');
let countdown = setInterval(()=>{
  let minutes = Math.floor(duration / 60);
  let seconds = duration % 60;
  timerDisplay.textContent = `الوقت المتبقي: ${minutes}:${seconds.toString().padStart(2,'0')}`;
  if(duration <= 0){
    clearInterval(countdown);
    alert('انتهى الوقت! سيتم تسليم الاختبار تلقائياً.');
    document.getElementById('submitBtn').click();
  }
  duration--;
},1000);

// --------------------- الأسئلة (مثال مختصر) ---------------------
const quiz = [
  {id:1,type:'tf',text:'تُعرف البرامج المجانية بأنها البرامج التي يسمح مالكها للجميع بإستخدامها',answer:true},
  {id:2,type:'tf',text:'البرامج مفتوحة المصدر هي برامج لا يسمح بنشرها للمستخدمين',answer:false},
  {id:3,type:'tf',text:'صفحة الويب الساكنة Static Web Page هي صفحة تعرض فقط المعلومات ويظهر بها عنوان يتحرك من اليمين إلي اليسار ومؤثرات صوتية',answer:true},
  {id:4,type:'tf',text:'صفحة الويب الديناميكية لا يمكن تعديل بياناتها  ',answer:false},
  {id:5,type:'tf',text:' البرمجة الضعيفة هي أحد أسباب إختراق الموقع',answer:true},
  {id:6,type:'tf',text:' من الممكن استغلال ثغرات أمنية لإختراق الموقع ',answer:true},
  {id:7,type:'tf',text:' لا يمكن للمخترق حذف أو تعديل موقع ثم إختراقه ',answer:false},
  {id:8,type:'tf',text:' كلمات المرور password يجب أن تكون معقدة نوعا ما ',answer:true},
  {id:9,type:'tf',text:'الكود المكتوب بلغة HTML يتم تنفيذه علي مستعرض الإنترنت ',answer:true},
  {id:10,type:'tf',text:'صفحات ويب إمتدادها PHP تشير إلي تنفيذ كود PHP علي جهاز العميل ',answer:false},
  {id:11,type:'tf',text:' برنامج Apashe هو أحد تطبيقات نظم إدارة قواعد البيانات',answer:false},
  {id:12,type:'tf',text:'حزمة تطبيقات WAMP تعمل علي نظام التشغيل WINDOWS  ',answer:true},
  {id:13,type:'tf',text:' يمكن إنشاء ثلاثة جداول داخل قاعدة البيانات',answer:true},
  {id:14,type:'tf',text:' يعد برنامجExPression Web  من أشهر برامج انشاء صفحات الويب بلغة PHP',answer:true},
  {id:15,type:'tf',text:'اسم المتغير في لغة PHP  يبدأ بعلامة(<)   ',answer:false},
  {id:16,type:'tf',text:'لتعريف ثابت قي لغة PHP  يجب أن يسبق الثابت الكلمة(Define)  ',answer:true},
  {id:17,type:'tf',text:'نستخدم الدالة SHA لحفظ كلمة المرور مشفرة في PHP  ',answer:true},
  {id:18,type:'tf',text:' يتم إغلاق كود PHP  بالأمر >؟',answer:false},
  {id:19,type:'tf',text:'تستخدم جملة do..while في تنفيذ عدد غير محدد من التكرارات ولا يتم تنفيذ هذه التكرارات الا اذا عند تحقق الشرط  ',answer:false},
  {id:20,type:'tf',text:' لابد من إدراج آداة Image لوضع عناصر التحكم علي صفحة البحث',answer:false},
  {id:21,type:'tf',text:' تستخدم جملة (for) في طباعة عنوان موقع الوزارة 10 مرات',answer:true},
  {id:22,type:'tf',text:'الدالة ARAY تحتوي علي معامل واحد  ',answer:true},
  {id:23,type:'tf',text:'الدالة Explod تحتوي علي معاملين ',answer:true},
  {id:24,type:'tf',text:' يجب توصية المستخدم نحو إستخدام كلمات مرور ذات مواصفات محددة    ',answer:true},
  {id:25,type:'tf',text:'عند تحميل الموقع تظهر أمام المستخدم صفحةHeader.php ',answer:false},
  // ... باقي الأسئلة (نفس ما في الكود الأصلي)
  {
  id:26, 
  type: 'mc', 
  text: 'يٌكتب كود Script بلغات كثيرة من أشهرها', 
  options: ['C#','HTML','PHP','لا شيء مما سبق'], 
  answer: 2
},

{
  id:27, 
  type: 'mc', 
  text: 'أحد طرق نقل البيانات من صفحة ويب إلي أخري ويتيح فيها مطور الموقع للمستخدم زيارة بعض صفحات الموقع أو كلها يطلق عليها', 
  options: ['Password','UserName','Server','Session'], 
  answer: 3
},
{
  id:28, 
  type: 'mc', 
  text: 'اللغة المستخدمة في إدارة كافة قواعد البيانات هي بدءاً من إنشاء قاعدة البيانات والتعامل مع البيانات المخزنة داخل الجدول هي', 
  options: ['Javascript','HTML','SQL','Databases'], 
  answer: 3
},
{
  id:29, 
  type: 'mc', 
  text: 'يمكن تشغيل برنامج Apache وبرنامجMySQL بالضغط علي............  من لوحة التحكم XAMPP', 
  options: ['OPEN','START','STOP','CONFIG'], 
  answer: 1
},
{
  id:30, 
  type: 'mc', 
  text: 'لإنشاء قاعدة بيانات يتم إختيار تبويب ', 
  options: ['IMPORT','EXPORT','SQL','Databases'], 
  answer: 3
},
{
  id:31, 
  type: 'mc', 
  text: 'برنامج ..............  يقوم بتحويل جهازك الشخصي إلي جهاز خادم سيرفربحيث يستطيع ترجمة الكود المطلوب بلغةPHP.', 
  options: ['MYSQL','APACHE','Expression','Access'], 
  answer: 1
},
{
  id:32, 
  type: 'mc', 
  text: 'لدعم اللغة العربية في صفحات الموقع لابد من استخدام التكويد', 
  options: ['UTF16','UTF64','UTF8 ','UTF32'], 
  answer: 3
},
{
  id:33, 
  type: 'mc', 
  text: 'لعرض الصفحة والكود معاً في برنامجExpression web   يتم النقر علي زر', 
  options: ['Design','Split','code','panels'], 
  answer: 1
},
{
  id:34, 
  type: 'mc', 
  text: 'لإنشاء موقع بإستخدام Expression Web يتم إختيارNew Site من قائمة', 
  options: ['FILE','INSERT','SITE','Panels'], 
  answer: 0
},
{
  id:35, 
  type: 'mc', 
  text: 'HTML  هي لغة تكويد تستخدم في إنشاء صفحة ويب ثابتة تحفظ بإمتداد ', 
  options: ['HTM','HTML','PHP','gpg'], 
  answer: 0
},
{
  id:36, 
  type: 'mc', 
  text: 'نهاية كود PHP', 
  options: ['>?? ','??','?>','لا شيء مما سبق'], 
  answer: 2
},
{
  id:37, 
  type: 'mc', 
  text: 'للإعلان عن صف نستخدم الأمر', 
  options: ['<Tr>','Td','Br','HTML'], 
  answer: 3
},
{
  id:38, 
  type: 'mc', 
  text: 'للإعلان عن عمود نستخدم الأمر', 
  options: ['<Tr>','Td','Br','HTML'], 
  answer: 1
},
{
  id:39, 
  type: 'mc', 
  text: 'المقصود من كتابة الكود التالي Include("connection.php")', 
  options: ['تضمين كود php  لصفحة Connection','تضمين كود الإتصال بقاعدة البيانات Connection ','الإعلان عن متغير','الإعلان عن ثابت '], 
  answer: 0
},
{
  id:40, 
  type: 'mc', 
  text: 'يكتب كود php  في', 
  options: ['Databases','Visual Basic','Html','كل ما سبق'], 
  answer: 1
},
{
  id:41, 
  type: 'mc', 
  text: 'أحد تطبيقات تأمين مواقع الويب', 
  options: ['For','And','post','Netsparker'], 
  answer: 2
},
{
  id:42, 
  type: 'mc', 
  text: 'يستخدم التطبيق ......... لتصميم صفحة تسجيل مستخدم جديد', 
  options: ['PH','Word','ACCESS','Expression'], 
  answer: 3
},
{
  id:43, 
  type: 'mc', 
  text: 'الدالة ......... تقوم بإنشاء مجموعة جديدة تحتوي علي مجموعة من العناصر', 
  options: ['Form','Explode','END','ARRAY'], 
  answer: 3
},
{
  id:44, 
  type: 'mc', 
  text: ' المقصود من كتابة الكود التالي Include("header.php")', 
  options: ['تضمين كود php  لصفحة Header','تضمين كود الإتصال بقاعدة البيانات Connection ','الإعلان عن متغير','الإعلان عن ثابت '], 
  answer: 3
},
{
  id:45, 
  type: 'mc', 
  text: 'تستخدم الدالة .........   لتحويل متغير إلي مصفوفة علي عدة عناصر', 
  options: ['EXPLOD','ARRAY','END','FORM'], 
  answer: 1
},
{
  id:46, 
  type: 'mc', 
  text: 'من خصائص النموذج ', 
  options: ['Method','Post','HTML','Cet'], 
  answer: 0
},
{
  id:47, 
  type: 'mc', 
  text: 'يمكن حماية الموقع علي مستوي', 
  options: [' مسئول الموقع','الخادم','مطوري المواقع','كل ما سبق'], 
  answer: 3
},
{
  id:48, 
  type: 'mc', 
  text: 'للخروج من جملة IF  نكتب الأمر', 
  options: ['SQL','AND','ECHO','EXIT'], 
  answer: 3
},
{
  id:49, 
  type: 'mc', 
  text: 'تستخدم PHP  في توفير العديد من أساليب البرمجة للتأكد من هوية الملف المرفوع', 
  options: ['ECHO ','SQL','PHP','HTML'], 
  answer: 3
},
{
  id:50, 
  type: 'mc', 
  text: 'لتعيين بيان نصي في جدول قاعدة البيانات بإستخدام MYSQL نختار البيان من نوع', 
  options: ['Integer','Primary','VarChar','لا شيء مما سبق'], 
  answer: 3
},
];

// بناء الواجهة
const qDiv = document.getElementById('questions');
quiz.forEach(q=>{
  const div = document.createElement('div');
  div.className='question';
  const qText = document.createElement('div'); qText.className='qtext'; qText.textContent = q.id + ' - ' + q.text;
  div.appendChild(qText);
  const choices = document.createElement('div'); choices.className='choices';
  if(q.type==='tf'){
    ['صح','خطأ'].forEach((labelText,idx)=>{
      const lbl = document.createElement('label');
      lbl.innerHTML = `<input type="radio" name="q${q.id}" value="${idx}" required> ${labelText}`;
      choices.appendChild(lbl);
    });
  } else {
    q.options.forEach((opt,i)=>{
      const lbl = document.createElement('label');
      lbl.innerHTML = `<input type="radio" name="q${q.id}" value="${i}" required> ${opt}`;
      choices.appendChild(lbl);
    });
  }
  div.appendChild(choices);
  qDiv.appendChild(div);
});

// --------------------- التقديم ---------------------
document.getElementById('submitBtn').addEventListener('click',()=>{
  clearInterval(countdown);
  const form = document.getElementById('quizForm');
  const formData = new FormData(form);
  const studentName = formData.get('studentName');
  const studentClass = formData.get('studentClass');
  const studentSchool = formData.get('studentSchool');

  let score=0; const details=[];
  for(const q of quiz){
    const user = formData.get('q'+q.id);
    let userAns = null;
    if(user===null){ userAns = null; } else { userAns = Number(user); }
    let correct=false;
    if(q.type==='tf'){
      correct = ((q.answer && userAns===0) || (!q.answer && userAns===1));
      details.push({id:q.id, type:'tf', user:userAns===null? null : (userAns===0? 'صح':'خطأ'), correct:q.answer? 'صح':'خطأ', isCorrect: correct});
    } else {
      correct = (userAns===q.answer);
      details.push({id:q.id, type:'mc', user: userAns===null? null : q.options[userAns], correct: q.options[q.answer], isCorrect: correct});
    }
    if(correct) score += 1;
  }

  const resultArea = document.getElementById('resultArea');
  resultArea.innerHTML = '';
  const percent = Math.round((score/quiz.length)*100);
  let level = '';
  if(percent >= 85){ level = 'ممتاز'; }
  else if(percent >= 65){ level = 'جيد'; }
  else { level = 'إعادة الاختبار'; }

  const summary = document.createElement('div'); summary.className='result';
  summary.innerHTML = `<strong>الطالب: ${studentName} | الصف: ${studentClass} | المدرسة: ${studentSchool}</strong><br><br>
    <strong>الدرجة: ${score} / ${quiz.length} (${percent}%)</strong><br>
    <strong>التقدير: ${level}</strong>`;
  resultArea.appendChild(summary);
});
</script>
</body>
</html>
