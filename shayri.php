<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
$username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Shayri</title>
  <link rel="stylesheet" href="style.css">
  <style>
    
    body {
      margin: 0;
      font-family: 'Georgia', serif;
      color: #fff;
      overflow-x: hidden;
      position: relative;
      background-color: #1a1a1a; 
    }

    .container {
      text-align: center;
      padding: 50px 20px;
      position: relative;
      z-index: 1;
    }

    .header a {
      color: #fff;
      text-decoration: none;
      margin: 0 8px;
      transition: color 0.3s ease;
    }
    .header a:hover {
      color: #ff99cc;
    }

    
    .box {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(6px);
      padding: 20px;
      margin: 20px 0;
      border-radius: 15px;
      text-align: left;
      box-shadow: 0 0 15px rgba(0,0,0,0.4);
      font-style: italic;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }

    .box:hover {
      box-shadow: 0 0 25px rgba(255, 153, 204, 0.3);
    }

    .box.active {
      transform: scale(1.2); /* zoom in when clicked */
      box-shadow: 0 0 35px rgba(255, 153, 204, 0.5);
    }

    
    #dailyQuote {
      margin: 20px 0;
      padding: 18px;
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(6px);
      border-radius: 12px;
      font-size: 1.3rem;
      text-shadow: 0 1px 3px rgba(0,0,0,0.5);
      border-left: 4px solid #ff99cc;
      animation: fadeIn 1s ease;
    }
    @keyframes fadeIn { from {opacity:0;} to {opacity:1;} }


    #quoteHistory {
      margin-top: 30px;
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(6px);
      padding: 15px;
      border-radius: 12px;
      max-height: 200px;
      overflow-y: auto;
      text-align: left;
    }

  
    .petal {
      position: absolute;
      width: 15px;
      height: 15px;
      background: pink;
      border-radius: 50%;
      pointer-events: none;
      animation-timing-function: ease-in-out;
      opacity: 0.8;
    }
    @keyframes fall {
      0% {
        transform: translate(0, -20px) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translate(var(--drift), 100vh) rotate(var(--rotate)deg);
        opacity: 0;
      }
    }

    @media (max-width: 600px) {
      .box { padding: 15px; font-size: 1rem; }
      #dailyQuote { font-size: 1.1rem; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Shayri</h1>
      <div class="links">
        <span style="color:var(--muted); margin-right:10px;">Welcome, <?php echo htmlspecialchars($username); ?></span>
        <a href="profile.php">Profile</a>
        <?php if ($is_admin): ?>
          <a href="admin.php">Admin</a>
        <?php endif; ?>
        <a href="logout.php" style="color:var(--danger); margin-left:10px;">Logout</a>
      </div>
    </div>

    
    <div class="box">
      <p><strong>Lines</strong><br>
      "Tumhari saari jaruratein Puri karr jaayenge,<br>
      Harr khusi ko tmhare Saamne rakh jaayenge,<br>
      Tum kya ho ye bata Nahi sakte tumhe,<br>
      Tumse mohabbat krke bhi Hum sirf dost reh hi jaayenge"</p>
    </div>
    <div class="box">
      <h3>Lines</h3>
      <p>"Akela hu mujhe akele hii rehne do,<br>
      Ager ye dard hai mere To ye mujhe hi shene do,<br>
      Ab khamosh nhi rha Jata mujhse is kadar,<br>
      Sun sab ko rha hu ab Thode lafz mujhe bhi khene do"</p>
    </div>
    <div class="box">
      <h3>Lines</h3>
      <p>"Chodh gaye humko wo Akele hi raahon mein,<br>
      Chal diye rehne wo Auron ki panaho mein,<br>
      Shayad meri chahat Unhe raas nahi aayi,<br>
      Tabhi to simat gaye Wo gairon ki baahon mein"</p>
    </div>
    <div class="box">
      <h3>Reality</h3>
      <p>"“Karma Says” <br>
       If a man expects a women <br>
        to be an angel in his life.<br>
        He must first create heaven
        for her.<br>
        Angels don’t live in hell."</p>
    </div>
    <div class="box">
      <h3>Lines</h3>
      <p>"Zindagi ka har dard Uski meherbani hai,<br>
      Meri zindagi ek Adhuri kahani hai,<br>
      Mita dete hum Seene se har dard,<br>
      Lekin ye dard uski Aakhiri nishaani hai."</p>
    </div>
    <div class="box">
      <h3>Kitna Pyar Hai Tumse</h3>
      <p>Kitna pyar hai tumse yeh jaan lo,<br>
      Tum hi zindagi ho meri Is baat ko maan lo.<br>
      Tumhe dene ko mere paas Kuchh bhi nahi,<br>
      Bas ek jaan hai Jab ji chahe maang lo..</p>
    </div>

    
    <div id="dailyQuote"></div>

   
    <div id="quoteHistory">
      <h3>Quote History:</h3>
      <ul id="historyList"></ul>
    </div>
  </div>

  <script>
   
    const quotes = [
      "No matter how deep the night, it always turns to day. — Brook",
      "If you don't take risks, you can't create a future. — Monkey D. Luffy",
      "When you give up, that's when the game is over. — Hitori Gotou",
      "The world isn’t perfect, but it’s there for us. — Roy Mustang",
      "You don’t die for your friends… you live for them. — Erwin Smith",
      "Power comes in response to a need, not a desire. — Goku",
      "If you can’t find a reason to fight, then you shouldn’t be fighting. — Akame",
      "A real ninja never gives up. — Naruto Uzumaki",
      "To know sorrow is not terrifying. What is terrifying is to know you can't go back to happiness you could have. — Matsumoto Rangiku",
      "Humans are strong because they can change. — Natsu Dragneel"
    ];

    const day = new Date().getDate();
    const todaysQuote = quotes[day % quotes.length];
    document.getElementById("dailyQuote").innerHTML = todaysQuote;

    // Quote History
    const historyList = document.getElementById("historyList");
    let history = [todaysQuote];
    function renderHistory() {
      historyList.innerHTML = '';
      history.forEach(q => {
        const li = document.createElement('li');
        li.textContent = q;
        historyList.appendChild(li);
      });
    }
    renderHistory();


    const boxes = document.querySelectorAll('.box');
    boxes.forEach(box => {
      box.addEventListener('click', () => {
        box.classList.toggle('active');
      });
      box.addEventListener('mouseleave', () => {
        box.classList.remove('active');
      });
    });


    const maxPetals = 30;
    let currentPetals = 0;

    function createPetal() {
      if (currentPetals >= maxPetals) return;

      const petal = document.createElement('div');
      petal.classList.add('petal');
      const size = 10 + Math.random() * 15;
      petal.style.width = petal.style.height = size + 'px';
      petal.style.left = Math.random() * window.innerWidth + 'px';
      petal.style.background = Math.random() > 0.5 ? 'pink' : 'lightpink';
      petal.style.opacity = 0.5 + Math.random() * 0.5;

      const drift = (Math.random() - 0.5) * 120; 
      const rotate = (Math.random() - 0.5) * 720; 
      petal.style.setProperty('--drift', drift + 'px');
      petal.style.setProperty('--rotate', rotate + 'deg');
      petal.style.animationDuration = (6 + Math.random() * 6) + 's';

      document.body.appendChild(petal);
      currentPetals++;

      petal.addEventListener('animationend', () => {
        petal.remove();
        currentPetals--;
      });
    }

    setInterval(createPetal, 700);
  </script>
</body>
</html>
