// 🌊 Three.js Hero Background Animation for index.php
// Creates an animated particle wave background for the hero section

(function() {
  'use strict';
  
  // Wait for Three.js to be available
  function initThreeBackground() {
    const container = document.getElementById('three-bg');
    
    if (!container || typeof THREE === 'undefined') {
      console.warn('Three.js background not initialized: container or THREE not found');
      return;
    }

    // Scene setup
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setClearColor(0x000000, 0);
    container.appendChild(renderer.domElement);
    
    // Create particle system
    const particlesGeometry = new THREE.BufferGeometry();
    const particlesCount = 5000;
    const posArray = new Float32Array(particlesCount * 3);
    
    for (let i = 0; i < particlesCount * 3; i++) {
      posArray[i] = (Math.random() - 0.5) * 100;
    }
    
    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    
    // Material for particles
    const particlesMaterial = new THREE.PointsMaterial({
      size: 0.15,
      color: 0x00bcd4,
      transparent: true,
      opacity: 0.8,
      blending: THREE.AdditiveBlending
    });
    
    const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particlesMesh);
    
    // Add ambient lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);
    
    // Camera position
    camera.position.z = 30;
    
    // Mouse movement effect
    let mouseX = 0;
    let mouseY = 0;
    
    document.addEventListener('mousemove', (event) => {
      mouseX = (event.clientX / window.innerWidth) * 2 - 1;
      mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
    });
    
    // Animation loop
    let animationId;
    function animate() {
      animationId = requestAnimationFrame(animate);
      
      // Rotate particles
      particlesMesh.rotation.y += 0.0005;
      particlesMesh.rotation.x += 0.0003;
      
      // Mouse interaction
      camera.position.x += (mouseX * 2 - camera.position.x) * 0.05;
      camera.position.y += (mouseY * 2 - camera.position.y) * 0.05;
      camera.lookAt(scene.position);
      
      // Wave effect
      const positions = particlesMesh.geometry.attributes.position.array;
      for (let i = 0; i < positions.length; i += 3) {
        const x = positions[i];
        const y = positions[i + 1];
        positions[i + 2] = Math.sin((x + Date.now() * 0.001) * 0.3) * 2 + 
                          Math.cos((y + Date.now() * 0.001) * 0.5) * 2;
      }
      particlesMesh.geometry.attributes.position.needsUpdate = true;
      
      renderer.render(scene, camera);
    }
    
    // Handle window resize
    function onWindowResize() {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    }
    
    window.addEventListener('resize', onWindowResize);
    
    // Start animation
    animate();
    
    // Cleanup function
    window.addEventListener('beforeunload', () => {
      if (animationId) {
        cancelAnimationFrame(animationId);
      }
      renderer.dispose();
      particlesGeometry.dispose();
      particlesMaterial.dispose();
    });
    
    console.log('✅ Three.js hero background initialized');
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThreeBackground);
  } else {
    initThreeBackground();
  }
})();

// 🫧 Floating Bubbles Animation (CSS-based, lightweight)
(function() {
  'use strict';
  
  function createFloatingBubbles() {
    const bubblesContainer = document.querySelector('.floating-bubbles');
    if (!bubblesContainer) return;
    
    const bubbleCount = 12;
    
    for (let i = 0; i < bubbleCount; i++) {
      const bubble = document.createElement('div');
      bubble.className = 'bubble';
      
      // Random size between 30px and 120px
      const size = Math.random() * 90 + 30;
      bubble.style.width = `${size}px`;
      bubble.style.height = `${size}px`;
      
      // Random horizontal position
      bubble.style.left = `${Math.random() * 100}%`;
      
      // Random animation duration (8s to 15s)
      const duration = Math.random() * 7 + 8;
      bubble.style.animationDuration = `${duration}s`;
      
      // Random delay (0s to 5s)
      const delay = Math.random() * 5;
      bubble.style.animationDelay = `${delay}s`;
      
      bubblesContainer.appendChild(bubble);
    }
    
    console.log('✅ Floating bubbles created');
  }
  
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', createFloatingBubbles);
  } else {
    createFloatingBubbles();
  }
})();
