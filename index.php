<?php
session_start();
$page_title = "RiverVibe - Home";
include 'components/header.php';
?>

<section class="hero">
  <div id="three-bg"></div>
  <div class="floating-bubbles"></div>
  <div class="hero-content">
    <h1 class="hero-title-animate">Speak Up for Our Rivers 🌊</h1>
    <p class="hero-text-animate">Report pollution in your local rivers and help protect our environment.</p>
    <div class="hero-buttons-animate">
      <a href="/Webby/login.php" class="btn hero-btn">Login</a>
      <a href="/Webby/signup.php" class="btn hero-btn">Sign Up</a>
    </div>
  </div>
</section>

<!-- Info Cards Section -->
<section class="info-cards-section">
  <h2 class="text-gradient">How RiverVibe Works</h2>
  <p>Join thousands of environmental champions making a difference</p>
  
  <div class="info-cards-container">
    <div class="info-card fade-in">
      <div class="info-card-icon">
        <i class="fas fa-bullhorn"></i>
      </div>
      <h3>Report Pollution</h3>
      <p>Spotted pollution in your local river? Report it instantly with photos and location data. Your voice matters!</p>
      <a href="/Webby/feedback.php" class="btn">Report Now</a>
    </div>

    <div class="info-card fade-in">
      <div class="info-card-icon">
        <i class="fas fa-chart-line"></i>
      </div>
      <h3>Track Progress</h3>
      <p>View real-time dashboards showing pollution levels, cleanup efforts, and environmental impact across all rivers.</p>
      <a href="/Webby/dashboard.php" class="btn">View Dashboard</a>
    </div>

    <div class="info-card fade-in">
      <div class="info-card-icon">
        <i class="fas fa-heart"></i>
      </div>
      <h3>Success Stories</h3>
      <p>Read inspiring stories from our community and see the positive change we're creating together.</p>
      <a href="/Webby/success.php" class="btn">Get Inspired</a>
    </div>
  </div>
</section>

<?php include 'components/footer.php'; ?>
