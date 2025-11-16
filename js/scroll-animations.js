/**
 * Scroll Animations using Intersection Observer
 * Adds smooth animations when elements enter viewport
 */

document.addEventListener('DOMContentLoaded', function() {
  
  // ===== INTERSECTION OBSERVER SETUP =====
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-in');
        // Optional: Stop observing after animation
        // observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // ===== OBSERVE ELEMENTS =====
  
  // About page sections
  const visionMission = document.querySelector('.vision-mission');
  const whySection = document.querySelector('.why-section');
  const ctaSection = document.querySelector('.cta-section');
  
  if (visionMission) observer.observe(visionMission);
  if (whySection) observer.observe(whySection);
  if (ctaSection) observer.observe(ctaSection);

  // Team members (individual cards)
  const teamMembers = document.querySelectorAll('.team-member');
  teamMembers.forEach(member => observer.observe(member));

  // Card info sections
  const cardInfos = document.querySelectorAll('.card-info');
  cardInfos.forEach(card => observer.observe(card));

  // Dashboard elements
  const statsQuick = document.querySelector('.stats-quick');
  const reportsGrid = document.querySelector('.reports-grid');
  
  if (statsQuick) observer.observe(statsQuick);
  if (reportsGrid) observer.observe(reportsGrid);

  // Admin dashboard elements
  const dashboardStats = document.querySelector('.dashboard-stats');
  const adminActions = document.querySelector('.admin-actions');
  
  if (dashboardStats) observer.observe(dashboardStats);
  if (adminActions) observer.observe(adminActions);

  // Success page sections
  const successStories = document.querySelectorAll('.success-story');
  successStories.forEach(story => observer.observe(story));

  // Generic sections with class 'animate-on-scroll'
  const animateSections = document.querySelectorAll('.animate-on-scroll');
  animateSections.forEach(section => observer.observe(section));

});

/**
 * Button Hover Animations - Enhanced
 * Adds dynamic glow and scale effects to buttons
 */
document.addEventListener('DOMContentLoaded', function() {
  
  // All buttons
  const buttons = document.querySelectorAll('.btn, .action-btn, .cta-button, .hero-btn, .logout-btn');
  
  buttons.forEach(button => {
    button.addEventListener('mouseenter', function() {
      this.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
    });
    
    button.addEventListener('mouseleave', function() {
      this.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
    });
  });

});

/**
 * Smooth Dark/Light Mode Transition
 * Enhances theme toggle with smooth transitions
 */
document.addEventListener('DOMContentLoaded', function() {
  
  const themeToggle = document.getElementById('theme-toggle');
  
  if (themeToggle) {
    themeToggle.addEventListener('click', function() {
      // Add transition class to body
      document.body.style.transition = 'background 0.5s ease, color 0.5s ease';
      
      // Remove transition after animation completes
      setTimeout(() => {
        document.body.style.transition = '';
      }, 500);
    });
  }

});

/**
 * Interactive Card Effects
 * Enhanced hover effects for all card elements
 */
document.addEventListener('DOMContentLoaded', function() {
  
  // All cards
  const cards = document.querySelectorAll('.stat-box, .action-card, .team-member, .report-card, .card-info');
  
  cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
    });
  });

});

/**
 * Stat Number Animation
 * Animates numbers counting up when they enter viewport
 */
document.addEventListener('DOMContentLoaded', function() {
  
  const statNumbers = document.querySelectorAll('.stat-number, .stat-box h3');
  
  const animateNumber = (element) => {
    const target = parseInt(element.getAttribute('data-target') || element.textContent);
    const duration = 2000; // 2 seconds
    const increment = target / (duration / 16); // 60fps
    let current = 0;
    
    const updateNumber = () => {
      current += increment;
      if (current < target) {
        element.textContent = Math.floor(current);
        requestAnimationFrame(updateNumber);
      } else {
        element.textContent = target;
      }
    };
    
    updateNumber();
  };
  
  const numberObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
        animateNumber(entry.target);
        entry.target.classList.add('animated');
        numberObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  
  statNumbers.forEach(num => numberObserver.observe(num));

});
