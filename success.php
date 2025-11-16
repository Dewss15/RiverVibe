<?php
session_start();
$page_title = "RiverVibe - Success Stories";

// Additional CSS - all the unique styles for this page
$additional_css = [<<<CSS
<style>

    /* Success Stories Page Styles - Navbar inherited from style.css */
    
    /* Page-specific CSS variables (complementing base theme) */
    .success-page-wrapper {
      --gradient-bg: linear-gradient(135deg, #1CB5E0, #000851);
      --glass: rgba(255, 255, 255, 0.1);
      --shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
      --radius: 22px;
      --success-primary: #3AE7AB;
      --success-accent: #ffb347;
      --text-main: #ffffff;
      --text-sec: #d9e6f2;
      --border-color: rgba(255, 255, 255, 0.18);
    }

    /* Success page body adjustments (preserve navbar) */
    body.success-page {
      background: transparent;
      position: relative;
      overflow-x: hidden;
      min-height: 100vh;
    }

    #riverCanvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1;
      pointer-events: none;
      display: block;
    }
    
    #progressBar {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        background: #3AE7AB;
        width: 0%;
        z-index: 1000;
        transition: width 0.1s linear;
    }

    .eco-icons {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }
    .eco-icon {
        position: absolute;
        font-size: 1.5rem;
        opacity: 0;
        animation: floatUp 15s infinite linear;
    }
    .eco-icon:nth-child(1) { left: 10%; animation-duration: 13s; animation-delay: 0s; }
    .eco-icon:nth-child(2) { left: 20%; animation-duration: 17s; animation-delay: 2s; }
    .eco-icon:nth-child(3) { left: 45%; animation-duration: 20s; animation-delay: 5s; }
    .eco-icon:nth-child(4) { left: 70%; animation-duration: 12s; animation-delay: 1s; }
    .eco-icon:nth-child(5) { left: 85%; animation-duration: 18s; animation-delay: 8s; }

    @keyframes floatUp {
        0% { transform: translateY(100vh) rotate(0deg); opacity: 0.6; }
        100% { transform: translateY(-10vh) rotate(360deg); opacity: 0; }
    }

    .success-header {
      text-align: center;
      padding: 80px 20px 60px;
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 1400px;
      margin: 0 auto;
    }

    .success-header h1 {
      font-family: "Lobster Two", cursive;
      font-size: 3.5em;
      margin-bottom: 25px;
      color: #3AE7AB;
      text-shadow: 0 4px 15px rgba(0,0,0,0.5);
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInDown 1s forwards 0.2s;
    }

    .success-header p {
      font-size: 1.25em;
      max-width: 750px;
      margin: auto;
      color: #d9e6f2;
      background: rgba(0, 0, 0, 0.45);
      padding: 18px 25px;
      border-radius: 22px;
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
      line-height: 1.7;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInDown 1s forwards 0.5s;
    }
    
    .highlight {
        color: #3AE7AB;
        font-weight: 700;
    }

    @keyframes fadeInDown {
        to { opacity: 1; transform: translateY(0); }
    }

    .cta-container {
        margin-top: 30px;
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInDown 1s forwards 0.8s;
    }

    .cta-button {
      display: inline-block;
      background-image: linear-gradient(45deg, #3AE7AB, #2dcf96);
      color: #000;
      padding: 14px 28px;
      border-radius: 50px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(58, 231, 171, 0.3);
      border: none;
      margin: 10px;
    }

    .cta-button:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 8px 25px rgba(58, 231, 171, 0.5);
    }
    
    .cta-button.secondary {
        background-image: none;
        background-color: transparent;
        border: 2px solid #3AE7AB;
        color: #3AE7AB;
        box-shadow: none;
    }
    
    .cta-button.secondary:hover {
        background-color: #3AE7AB;
        color: #000;
    }

    .card-section {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      gap: 35px;
      padding: 0 25px 80px;
      max-width: 1250px;
      width: 100%;
      margin: 0 auto;
      z-index: 1;
    }

    .card {
      width: 350px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-radius: 22px;
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.18);
      opacity: 0;
      transform: translateY(50px);
      position: relative;
      animation: subtleFloat 5s ease-in-out infinite;
    }
    
    .card:nth-of-type(1) { animation-delay: 0s; }
    .card:nth-of-type(2) { animation-delay: -1.7s; }
    .card:nth-of-type(3) { animation-delay: -3.4s; }

    @keyframes subtleFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .card.is-visible {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }
    .card.is-visible:nth-of-type(1) { transition-delay: 0.2s; }
    .card.is-visible:nth-of-type(2) { transition-delay: 0.4s; }
    .card.is-visible:nth-of-type(3) { transition-delay: 0.6s; }

    .card:hover {
      transform: translateY(-15px) scale(1.03) !important;
      box-shadow: 0 0 40px 8px rgba(58, 231, 171, 0.35);
      border-color: #3AE7AB;
      animation-play-state: paused;
    }
    
    .card-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #ffb347;
        color: #000;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8em;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        z-index: 1;
    }

    .card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      display: block;
      border-bottom: 1px solid rgba(255, 255, 255, 0.18);
      transition: transform 0.4s ease;
    }

    .card:hover img {
        transform: scale(1.1);
    }
    
    .card-content {
      padding: 25px;
    }

    .card-title {
      font-size: 1.5em;
      font-weight: 700;
      color: #ffb347;
      margin-bottom: 12px;
      font-family: "Lobster Two", cursive;
    }

    .card-meta {
      font-size: 0.9em;
      color: #d9e6f2;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .card-meta i {
        color: #3AE7AB;
        margin-right: -8px;
    }

    .card-desc {
      font-size: 1em;
      line-height: 1.7;
      color: #ffffff;
    }

    .join-badge {
      position: fixed;
      top: 80px;
      right: 20px;
      background: #ffb347;
      color: #000;
      padding: 12px 22px;
      font-weight: bold;
      font-size: 1em;
      border-radius: 50px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      cursor: pointer;
      z-index: 999;
      transition: all 0.3s ease;
      animation: pulse 2.5s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 179, 71, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 179, 71, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 179, 71, 0); }
    }

    .join-badge:hover {
      transform: scale(1.1) rotate(2deg);
      animation-play-state: paused;
    }

    .testimonial-section {
        width: 100%;
        max-width: 100vw;
        padding: 50px 0 100px;
        z-index: 1;
        overflow: hidden;
        display: flex;
        justify-content: center;
        margin: 0 auto;
    }
    .testimonial-wrapper {
        display: flex;
        width: 200%;
        animation: scroll 40s linear infinite;
    }
    .testimonial-card {
        min-width: 400px;
        margin: 0 20px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 22px;
        padding: 30px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        color: #d9e6f2;
    }
    .testimonial-quote {
        font-size: 1.1em;
        font-style: italic;
        line-height: 1.6;
        margin-bottom: 20px;
        position: relative;
        padding-left: 35px;
    }
    .testimonial-quote::before {
        content: "\\f10d";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: #3AE7AB;
        font-size: 2em;
        position: absolute;
        top: -10px;
        left: 0;
        opacity: 0.5;
    }
    .testimonial-author {
        text-align: right;
        font-weight: 700;
        color: #ffffff;
    }
    .testimonial-author span {
        display: block;
        font-weight: 400;
        font-size: 0.9em;
        color: #ffb347;
    }
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .footer {
        width: 100%;
        padding: 40px 20px 20px;
        background: rgba(0, 8, 81, 0.5);
        backdrop-filter: blur(10px);
        text-align: center;
        border-top: 1px solid rgba(255, 255, 255, 0.18);
        z-index: 1;
        margin: 0 auto;
    }
    .footer .social-links a {
        color: #d9e6f2;
        font-size: 1.8em;
        margin: 0 15px;
        transition: color 0.3s, transform 0.3s;
    }
    .footer .social-links a:hover {
        color: #3AE7AB;
        transform: translateY(-5px);
    }
    .footer-slogan {
        font-family: "Lobster Two", cursive;
        font-size: 1.5em;
        color: #ffb347;
        margin: 25px 0;
    }
    .footer-credit {
        font-size: 0.9em;
        color: #d9e6f2;
        opacity: 0.7;
    }

    @media (max-width: 1200px) {
        .testimonial-card { min-width: 350px; }
    }
    @media (max-width: 768px) {
      .success-header {
        padding: 60px 15px 50px;
      }
      .success-header h1 {
        font-size: 2.8em;
      }
      .success-header p { 
        font-size: 1.1em;
        padding: 15px 20px;
      }
      .card {
        width: 85vw;
        max-width: 400px;
        margin: 0 auto;
      }
      .card-section {
        padding: 0 15px 60px;
        gap: 25px;
      }
      .testimonial-card { min-width: 300px; }
    }
    @media (max-width: 480px) {
        .success-header {
          padding: 50px 10px 40px;
        }
        .success-header h1 { font-size: 2.4em; }
        .cta-button { 
          padding: 12px 22px; 
          width: 80%; 
          margin-bottom: 10px;
          display: block;
          margin-left: auto;
          margin-right: auto;
        }
        .join-badge { 
          font-size: 0.9em; 
          padding: 10px 18px;
          top: 15px;
          right: 15px;
        }
        .card-section {
          padding: 0 10px 50px;
          gap: 20px;
        }
        .testimonial-card { min-width: 280px; }
    }
  </style>
CSS];

include 'components/header.php';

?>

<script>
  // Add success-page class to body for scoped styling
  document.body.classList.add('success-page');
</script>

  <div id="progressBar"></div>

  <div class="eco-icons">
      <div class="eco-icon">🌿</div>
      <div class="eco-icon">💧</div>
      <div class="eco-icon">♻️</div>
      <div class="eco-icon">🍃</div>
      <div class="eco-icon">✨</div>
  </div>

  <canvas id="riverCanvas"></canvas>

  <section class="success-header">
    <h1>🌊 Our River Heroes</h1>
    <p>
      Real stories from real people making a real <span class="highlight">difference</span>. See the <span class="highlight">impact</span> our amazing <span class="highlight">community</span> is having on rivers, all powered by this platform.
    </p>
    <div class="cta-container">
        <a href="/Webby/feedback.php" class="cta-button">💬 Share Your Story!</a>
        <a href="/Webby/index.php" class="cta-button secondary">🏠 Back to Home</a>
    </div>
  </section>

  <main class="card-section">
    
    <div class="card">
      <div class="card-badge">🌟 Community Choice</div>
      <img src="https://image.pollinations.ai/prompt/A%20young%20volunteer%20smiling%20proudly%2C%20holding%20bags%20filled%20with%20collected%20river%20trash%2C%20standing%20beside%20a%20much%20cleaner%20riverbank.%20Small%20%E2%80%98before%20cleanup%E2%80%99%20photo%20pasted%20on%20a%20cardboard%20sign%20that%20she%20is%20holding.%20Morning%20sunlight%2C%20realistic%2C%20inspiring%2C%20community%20volunteer%20vibe" alt="Volunteer holding sign and trash bags"/>
      <div class="card-content">
        <div class="card-title">One Person, Big Impact</div>
        <div class="card-meta">
          <span><i class="fa-solid fa-location-dot"></i> Kham River</span>
          <span><i class="fa-solid fa-calendar-days"></i> August 2025</span>
        </div>
        <div class="card-desc">
          Sarah used our site to report a dump spot. She then organized a solo cleanup, inspiring her neighbors to join in. Her small act started a local movement!
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-badge">🏆 Top Cleanup</div>
      <img src="https://image.pollinations.ai/prompt/A%20group%20of%208-12%20happy%20volunteers%2C%20wearing%20casual%20t-shirts%20(some%20with%20'River%20Vibe%20Cleanup')%2C%20posing%20together%20after%20cleaning%20a%20polluted%20riverbank.%20Some%20holding%20garbage%20bags%2C%20some%20giving%20thumbs%20up%2C%20one%20holding%20a%20cardboard%20sign%20saying%20%E2%80%9CCleaned%20with%20River%20Vibe%20%F0%9F%8C%8A%E2%80%9D.%20Real%20community%20emotion" alt="Community team after river cleanup"/>
      <div class="card-content">
        <div class="card-title">The "River Vibe" Crew</div>
        <div class="card-meta">
            <span><i class="fa-solid fa-location-dot"></i> Yamuna Ghat</span>
            <span><i class="fa-solid fa-calendar-days"></i> July 2025</span>
        </div>
        <div class="card-desc">
          The 'Yamuna Guardians' team formed on our platform. They've now hosted 5 successful cleanups, removing over two tons of waste. Teamwork at its finest!
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-badge">💡 Tech For Good</div>
      <img src="https://image.pollinations.ai/prompt/A%20student%20showing%20a%20phone%20screen%20displaying%20a%20river%20cleanup%20website%2C%20while%20standing%20near%20a%20cleaned%20river%20area%20with%20friends%20behind%20him%20holding%20trash%20bags.%20Digital%20impact%20meets%20ground%20action%2C%20real%20inspirational%20mood" alt="Student showing River Vibe website on phone at cleanup site"/>
      <div class="card-content">
        <div class="card-title">From App to Action</div>
        <div class="card-meta">
            <span><i class="fa-solid fa-location-dot"></i> Coimbatore</span>
            <span><i class="fa-solid fa-calendar-days"></i> June 2025</span>
        </div>
        <div class="card-desc">
          Local students used our app to map pollution hotspots. This data helped them organize a targeted and highly efficient cleanup drive with the city council.
        </div>
      </div>
    </div>
  </main>
  
  <section class="testimonial-section">
      <div class="testimonial-wrapper">
          <div class="testimonial-card">
              <p class="testimonial-quote">Seeing the river behind my house transform from a dumping ground to a clean stream... it's a feeling I can't describe. This platform made it possible.</p>
              <p class="testimonial-author">&mdash; Anjali P. <span>Community Organizer</span></p>
          </div>
          <div class="testimonial-card">
              <p class="testimonial-quote">I felt helpless about the pollution. River Vibe gave me the tools and the community to actually do something. It's empowering.</p>
              <p class="testimonial-author">&mdash; David L. <span>Volunteer</span></p>
          </div>
          <div class="testimonial-card">
              <p class="testimonial-quote">This isn't just about cleaning rivers; it's about reconnecting with our neighbors and our environment. It has changed our whole town's mindset.</p>
              <p class="testimonial-author">&mdash; Priya Singh <span>Local Teacher</span></p>
          </div>
          <div class="testimonial-card">
              <p class="testimonial-quote">The mapping feature is brilliant. It helped us pinpoint the exact sources of pollution and present concrete data to local authorities. A game-changer.</p>
              <p class="testimonial-author">&mdash; Rohan Desai <span>Student Activist</span></p>
          </div>
          <div class="testimonial-card">
              <p class="testimonial-quote">Seeing the river behind my house transform from a dumping ground to a clean stream... it's a feeling I can't describe. This platform made it possible.</p>
              <p class="testimonial-author">&mdash; Anjali P. <span>Community Organizer</span></p>
          </div>
          <div class="testimonial-card">
              <p class="testimonial-quote">I felt helpless about the pollution. River Vibe gave me the tools and the community to actually do something. It's empowering.</p>
              <p class="testimonial-author">&mdash; David L. <span>Volunteer</span></p>
          </div>
          <div class="testimonial-card">
              <p class="testimonial-quote">This isn't just about cleaning rivers; it's about reconnecting with our neighbors and our environment. It has changed our whole town's mindset.</p>
              <p class="testimonial-author">&mdash; Priya Singh <span>Local Teacher</span></p>
          </div>
           <div class="testimonial-card">
              <p class="testimonial-quote">The mapping feature is brilliant. It helped us pinpoint the exact sources of pollution and present concrete data to local authorities. A game-changer.</p>
              <p class="testimonial-author">&mdash; Rohan Desai <span>Student Activist</span></p>
          </div>
      </div>
  </section>

  <script>
    // River Canvas Animation
    const canvas = document.getElementById('riverCanvas');
    const ctx = canvas.getContext('2d');
    let width, height;

    function resizeCanvas() {
      width = window.innerWidth;
      height = window.innerHeight;
      canvas.width = width;
      canvas.height = height;
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    let waveOffset = 0;

    function drawRiver() {
      ctx.clearRect(0, 0, width, height);
      const gradient = ctx.createLinearGradient(0, 0, 0, height);
      gradient.addColorStop(0, '#1cb5e0');
      gradient.addColorStop(1, '#000851');
      ctx.fillStyle = gradient;
      ctx.fillRect(0, 0, width, height);
      ctx.strokeStyle = 'rgba(58, 231, 171, 0.4)';
      ctx.lineWidth = 2;

      for (let y = 0; y < height; y += 20) {
        ctx.beginPath();
        for (let x = 0; x <= width; x += 10) {
          const waveHeight = 7;
          const yOffset = waveHeight * Math.sin((x + waveOffset) * 0.02 + y * 0.1);
          ctx.lineTo(x, y + yOffset);
        }
        ctx.stroke();
      }
      waveOffset += 2;
      requestAnimationFrame(drawRiver);
    }
    drawRiver();
    
    // Enhanced JavaScript Features
    document.addEventListener('DOMContentLoaded', () => {
        const progressBar = document.getElementById('progressBar');
        const header = document.querySelector('.success-header');
        
        // Scroll Progress Bar
        const updateProgressBar = () => {
            const { scrollTop, scrollHeight } = document.documentElement;
            const scrollPercent = (scrollTop / (scrollHeight - window.innerHeight)) * 100 + '%';
            progressBar.style.width = scrollPercent;
        };
        window.addEventListener('scroll', updateProgressBar);

        // Parallax Effect for Header
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            if (scrollY < window.innerHeight) {
                 header.style.transform = `translateY(${scrollY * 0.4}px)`;
            }
        });
        
        // Animate Elements on Scroll-in (Intersection Observer)
        const cards = document.querySelectorAll('.card');
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.2
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        cards.forEach(card => {
            observer.observe(card);
        });
    });
  </script>

<?php include 'components/footer.php'; ?>
