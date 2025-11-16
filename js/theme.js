// 🌗 RiverVibe Theme Toggle Script
// Handles Light/Dark mode with localStorage persistence

(function() {
  'use strict';
  
  // Initialize theme on page load (before DOM ready to prevent flicker)
  const initTheme = () => {
    const isDarkMode = localStorage.getItem('rivervibeTheme') === 'dark';
    if (isDarkMode) {
      document.body.classList.add('dark-mode');
    }
  };
  
  // Call immediately to prevent flicker
  initTheme();
  
  // Wait for DOM to be ready
  document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    
    if (!themeToggle) {
      console.warn('Theme toggle button not found');
      return;
    }
    
    // Update icon based on current theme
    const updateIcon = (isDark) => {
      const icon = themeToggle.querySelector('i');
      if (icon) {
        icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
      }
    };
    
    // Set initial icon
    const isDarkMode = document.body.classList.contains('dark-mode');
    updateIcon(isDarkMode);
    
    // Toggle theme on button click
    themeToggle.addEventListener('click', function() {
      const isDark = document.body.classList.toggle('dark-mode');
      
      // Save to localStorage
      localStorage.setItem('rivervibeTheme', isDark ? 'dark' : 'light');
      
      // Update icon
      updateIcon(isDark);
      
      // Add rotation animation
      themeToggle.style.transform = 'rotate(360deg) scale(1.1)';
      setTimeout(() => {
        themeToggle.style.transform = '';
      }, 300);
    });
    
    // Keyboard accessibility
    themeToggle.addEventListener('keypress', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        themeToggle.click();
      }
    });
  });
})();
