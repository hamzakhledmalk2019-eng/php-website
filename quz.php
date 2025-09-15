﻿<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>استطلاع رأى</title>
  <style>
    :root{--bg:#f7f9fc;--card:#ffffff;--accent:#2563eb;--muted:#6b7280}
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,"Noto Sans",Arial; background:var(--bg); color:#111; padding:24px}
    .container{max-width:780px;margin:0 auto}
    header{display:flex;align-items:center;gap:12px}
    h1{font-size:1.25rem;margin:0}
    .card{background:var(--card);border-radius:12px;padding:18px;margin-top:16px;box-shadow:0 6px 18px rgba(16,24,40,0.06)}
    .q{padding:12px;border-radius:8px;margin-bottom:12px;border:1px solid #efefef}
    .question{font-weight:600;margin-bottom:8px}
    label{display:block;cursor:pointer;padding:6px;border-radius:6px}
    input[type=radio]{margin-inline-start:6px}
    .controls{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
    button{background:var(--accent);color:white;border:none;padding:8px 12px;border-radius:8px;cursor:pointer}
    button.secondary{background:#e5e7eb;color:#111}
    .result{padding:12px;margin-top:12px;border-radius:8px;background:#f1f5f9}
    small{color:var(--muted)}
    #timer{font-weight:bold;color:var(--accent)}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{border:1px solid #ddd;padding:6px;text-align:center}
    th{background:#f3f4f6}
    @media (prefers-color-scheme:dark){:root{--bg:#0b1220;--card:#071122;--accent:#60a5fa} body{color:#e6eef8}}
  </style>
</head>
<body>
  <div class="container">
    <header>
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden>
        <circle cx="12" cy="12" r="10" stroke="#2563eb" stroke-width="1.5" fill="rgba(37,99,235,0.08)"/>
        <path d="M8 12.5l2.5 2L16 9" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <h1>استطلاع رأي حول مدي صلاحية أساليب التعلم النفسية (كلي/ تتابعي) لدي طلاب المرحلة الثانوية.</h1>
    </header>

    <section class="card" id="quiz-card">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div><small id="progress">0 / 0</small></div>
        <div>⏳ <span id="timer">03:00</span></div>
      </div>
      <form id="quiz-form">
        <!-- الأسئلة تُحمّل تلقائياً بواسطة JavaScript -->
        <div id="questions"></div>

        <div class="controls">
          <button type="button" id="submit-btn">تصحيح</button>
          <button type="button" id="reset-btn" class="secondary">إعادة ضبط</button>
        </div>
      </form>

      <div id="result" class="result" hidden aria-live="polite"></div>
    </section>

    <details class="card" style="margin-top:14px">
      <summary>📊 سجل النتائج السابقة</summary>
      <div id="history"></div>
    </details>

    <details class="card" style="margin-top:14px">
      <summary>ملاحظات</summary>
      <p>نتمنى لك التوفيق والنجاح <code>questions</code> الباحث/تامر ابراهيم عبدالفتاح البلتاجى.</p>
    </details>
  </div>

  <script>
    // === بيانات الأسئلة ===
    const questions = [
      {id:1,text:'أميل إلي',choices:['فهم تفاصيل الموضوع لأن البناء العام ربما يكون غامضاً.','فهم البناء العام لأن التفاصيل ربما تكون غامضة'],answer:1},
      {id:2,text:'أبدأ بفهم:',choices:['جميع الأجزاء حتى أفهم الشيء بالكامل.','الشيء بالكامل حتى أفهم الأجزاء.'],answer:1},
      {id:3,text:'عندما أحل المشكلات الرياضية:',choices:['عادة ما اعمل علي الوصول إلي الحل خطوة خطوة في الوقت المحدد.','عادة ما أتوصل إلي الحلول لكن بعد الصراع مع الخطوات المؤدية للحل.'],answer:1},
      {id:4,text:'عندما أقوم بتحليل قصة أو رواية:',choices:['أفكر في الأحداث وأحاول تجميعها لإكتشاف الموضوع.','أعرف فقط الموضوعات وبعد أن أنهي قرائتها أعود للأحداث التي تفسرها'],answer:1},
      {id:5,text:'الأكثر أهمية عندي بالنسبة للمحاضر أن: ',choices:['يعرض المادة في خطوات متسلسلة واضحة.','يعطيني صورة عامة، ويربط بين المادة والموضوعات الأخرى.'],answer:1},
      {id:6,text:'أتعلم',choices:['بسرعة مناسبة، إذا كانت الدراسة صعبة.','بداية بسرعة، ثم أرتبك فجأة ثم أفهم.'],answer:1},
      {id:7,text:'لفهم كمية من المعلومات أميل إلي:',choices:['التركيز علي التفاصيل وأهمل الصورة العامة.','فهم الصورة العامة قبل الدخول في التفاصيل.'],answer:1},
      {id:8,text:'عند كتابة ورقة أميل إلي:',choices:['العمل علي (التفكير أو الكتابة) في بداية الورقة ثم التقدم للإمام.','العمل علي (التفكير أو الكتابة) في أجزاء مختلفة من الورقة، ثم أرتبها'],answer:1},
      {id:9,text:'عندما أتعلم موضوعاً جديداً، أفضل:',choices:['التركيز علي الموضوع، والتعلم أكثر حوله قدر الإمكان.','محاولة عمل إرتباطات بين الموضوع والموضوعات ذات الصلة.'],answer:1},
      {id:10,text:'بعض الأساتذة يقدمون لمحاضراتهم بملخص لما سيعطيه وهذه الملخصات تكون:',choices:['مفيدة إلي حد ما.','مفيدة جدا لي.'],answer:1},
      {id:11,text:'عندما أحل المشكلات في جماعة، أميل إلي:',choices:['التفكير في خطوات عملية الحل.','التفكير في النتائج المحتملة أو تطبيقات الحل في مدي أوسع.'],answer:1},   
    ];

    const qContainer=document.getElementById('questions');
    const progress=document.getElementById('progress');
    const timerEl=document.getElementById('timer');
    let timerInterval;

    function renderQuestions(){
      qContainer.innerHTML='';
      questions.forEach((q,qi)=>{
        const qDiv=document.createElement('div');
        qDiv.className='q';
        qDiv.innerHTML=`
          <div class="question">${qi+1}. ${escapeHtml(q.text)}</div>
          <div role="radiogroup" aria-labelledby="q${q.id}">
            ${q.choices.map((c,idx)=>`
              <label>
                <input type="radio" name="q${q.id}" value="${idx}" /> ${escapeHtml(c)}
              </label>
            `).join('')}
          </div>`;
        qContainer.appendChild(qDiv);
      });
      updateProgress();
    }

    function escapeHtml(unsafe){
      return unsafe.replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#039;'}[m]));
    }

    function updateProgress(){
      const total=questions.length;
      const selected=questions.reduce((acc,q)=>{
        const val=document.querySelector(`input[name="q${q.id}"]:checked`);
        return acc+(val?1:0);
      },0);
      progress.textContent=`${selected} / ${total}`;
    }

    document.getElementById('quiz-form').addEventListener('change',updateProgress);

    function grade(){
      let score=0;
      const feedback=[];
      questions.forEach((q,i)=>{
        const sel=document.querySelector(`input[name="q${q.id}"]:checked`);
        const user=sel?Number(sel.value):null;
        const correct=q.answer;
        const ok=user===correct;
        if(ok) score++;
        feedback.push({index:i+1,ok,user,correct});
      });
      showResult(score,questions.length,feedback);
      saveResult(score,questions.length);
    }

    function showResult(score,total,feedback){
      const resultBox=document.getElementById('result');
      resultBox.hidden=false;
      const percent=Math.round((score/total)*100);
       let text=`<strong>النتيجة:   </strong>`; 
        /* ${score} من ${total} — ${percent}% */
      text+='<ul>';
      feedback.forEach(f=>{
    /*    text+=`<li>السؤال ${f.index}: ${f.ok?'صحيح':`خاطئ (الإجابة الصحيحة: ${escapeHtml(questions[f.index-1].choices[f.correct])})`}`;
        text+='</li>'; */
      });
      text+='</ul>';
      if(percent>50) text+='<p><a href="vedio.php">عزيزى الطالب الأسلوب الكلى هو المناسب لك</a></p>';
      else if(percent===50) text+='<p>يمكنك المحاولة مرة أخرى لتحسين النتيجة.</p>';
      else text+='<p><a href="map.php"> عزيزى الطالب الأسلوب التتابعى هو المناسب لك </a></p>';
      resultBox.innerHTML=text;
      
      
    }

    function resetQuiz(){
      document.getElementById('quiz-form').reset();
      document.getElementById('result').hidden=true;
      updateProgress();
      resetTimer();
      startTimer();
    }

    document.getElementById('submit-btn').addEventListener('click',grade);
    document.getElementById('reset-btn').addEventListener('click',resetQuiz);

    function startTimer(){
      let time=180;
      updateTimerDisplay(time);
      timerInterval=setInterval(()=>{
        time--;
        updateTimerDisplay(time);
        if(time<=0){
          clearInterval(timerInterval);
          grade();
        }
      },1000);
    }

    function resetTimer(){
      clearInterval(timerInterval);
      timerEl.textContent='03:00';
    }

    function updateTimerDisplay(sec){
      const m=String(Math.floor(sec/60)).padStart(2,'0');
      const s=String(sec%60).padStart(2,'0');
      timerEl.textContent=`${m}:${s}`;
    }

    function saveResult(score,total){
      const percent=Math.round((score/total)*100);
      const history=JSON.parse(localStorage.getItem('quizHistory')||'[]');
      history.push({date:new Date().toLocaleString(),score:`${score}/${total} (${percent}%)`});
      localStorage.setItem('quizHistory',JSON.stringify(history));
      renderHistory();
    }

    function renderHistory(){
      const history=JSON.parse(localStorage.getItem('quizHistory')||'[]');
      if(history.length===0){
        document.getElementById('history').innerHTML='<p>لا يوجد محاولات سابقة.</p>';
        return;
      }
      let html='<table><tr><th>التاريخ</th><th>الدرجة</th></tr>';
      history.forEach(h=>{
        html+=`<tr><td>${h.date}</td><td>${h.score}</td></tr>`;
      });
      html+='</table>';
      document.getElementById('history').innerHTML=html;
    }

    renderQuestions();
    renderHistory();
    startTimer();
  </script>
</body>
</html>
