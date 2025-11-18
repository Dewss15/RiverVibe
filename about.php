<?php
session_start();
$page_title = 'About - RiverVibe';
include 'components/header.php';
?>

<style>
  /* ===============================
     GLOBAL STYLES & TYPOGRAPHY
     =============================== */
  * {
    font-family: 'Poppins', sans-serif;
  }

  body {
    background: linear-gradient(135deg, #e0f7fa 0%, #ffffff 50%, #f0f9ff 100%);
    overflow-x: hidden;
    position: relative;
  }
  
  /* Animated background particles for Gen Z vibe */
  body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
      radial-gradient(circle at 20% 50%, rgba(0, 188, 212, 0.05) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(77, 208, 225, 0.05) 0%, transparent 50%),
      radial-gradient(circle at 40% 80%, rgba(0, 150, 199, 0.05) 0%, transparent 50%);
    animation: breathe 15s ease-in-out infinite;
    pointer-events: none;
    z-index: -1;
  }
  
  @keyframes breathe {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.1); }
  }
  
  /* Dark mode gradient background */
  body.dark-mode {
    background: linear-gradient(135deg, #0a192f 0%, #1a1a2e 50%, #16213e 100%);
  }
  
  body.dark-mode::before {
    background: 
      radial-gradient(circle at 20% 50%, rgba(77, 208, 225, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(0, 188, 212, 0.08) 0%, transparent 50%),
      radial-gradient(circle at 40% 80%, rgba(77, 208, 225, 0.06) 0%, transparent 50%);
  }

  /* Section styling */
  section {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 30px;
  }

  section h1 {
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    background: linear-gradient(135deg, #00bcd4, #0096c7, #4dd0e1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 40px;
    font-weight: 700;
    position: relative;
    animation: slideInDown 0.8s ease-out;
    letter-spacing: -1px;
  }
  
  @keyframes slideInDown {
    from {
      opacity: 0;
      transform: translateY(-30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Dark mode title */
  body.dark-mode section h1 {
    background: linear-gradient(135deg, #4dd0e1, #00e5ff, #80ffdb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Card container */
  .card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
  }

  /* Hide radio buttons */
  .card-input {
    display: none;
  }

  /* Modern Cards */
  .card-label {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 188, 212, 0.15);
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid rgba(0, 188, 212, 0.1);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
  }
  
  .card-label:nth-child(1) { animation-delay: 0.1s; }
  .card-label:nth-child(2) { animation-delay: 0.2s; }
  .card-label:nth-child(3) { animation-delay: 0.3s; }
  
  /* Dark mode cards */
  body.dark-mode .card-label {
    background: rgba(30, 30, 30, 0.9);
    border-color: rgba(77, 208, 225, 0.2);
  }

  .card-label::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #00bcd4, #4dd0e1);
    transform: scaleX(0);
    transition: transform 0.3s ease;
  }

  .card-label:hover::before {
    transform: scaleX(1);
  }

  .card-label:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 12px 40px rgba(0, 188, 212, 0.35), 0 0 20px rgba(77, 208, 225, 0.2);
    border-color: #4dd0e1;
  }
  
  body.dark-mode .card-label:hover {
    box-shadow: 0 12px 40px rgba(77, 208, 225, 0.5), 0 0 30px rgba(77, 208, 225, 0.3);
    border-color: #00e5ff;
  }

  .card-label h2 {
    color: #0096c7;
    margin-bottom: 15px;
    font-size: 1.5rem;
    transition: color 0.3s ease;
  }
  
  .card-label:hover h2 {
    color: #00bcd4;
  }
  
  body.dark-mode .card-label h2 {
    color: #4dd0e1;
  }
  
  body.dark-mode .card-label:hover h2 {
    color: #00e5ff;
  }
  
  body.dark-mode .card-label p {
    color: #b0b0b0;
  }

  /* Info sections */
  .card-info {
    display: none;
    margin-top: 40px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 188, 212, 0.15);
    border: 2px solid rgba(0, 188, 212, 0.1);
    animation: fadeInUp 0.6s ease, slideIn 0.5s ease;
  }
  
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  body.dark-mode .card-info {
    background: rgba(30, 30, 30, 0.9);
    border-color: rgba(77, 208, 225, 0.2);
  }
  
  body.dark-mode .card-info h3 {
    color: #4dd0e1;
  }
  
  body.dark-mode .card-info p {
    color: #b0b0b0;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .numbered-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 20px;
  }

  .info-item {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .number {
    background: linear-gradient(135deg, #00bcd4, #4dd0e1);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(0, 188, 212, 0.3);
  }

  .info-item p {
    margin: 0;
    flex-grow: 1;
  }

  /* Show info when radio checked */
  #card1:checked ~ .card-info-container #info1,
  #card2:checked ~ .card-info-container #info2,
  #card3:checked ~ .card-info-container #info3 {
    display: block;
  }

  /* Images in cards */
  .card-label img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 15px;
    transition: transform 0.3s;
  }

  .card-label img:hover {
    transform: scale(1.05);
  }

  /* Headings */
  .card-info h3 {
    margin-bottom: 25px;
    color: #0096c7;
    font-size: 1.5rem;
    font-weight: 600;
  }

  .card-info p {
    margin-bottom: 8px;
    text-align: left;
    color: #333;
    line-height: 1.6;
  }

  /* Team Section */
  .team-section {
    background: linear-gradient(135deg, rgba(0, 188, 212, 0.05) 0%, rgba(77, 208, 225, 0.05) 100%);
    padding: 80px 20px;
    border-radius: 20px;
    margin: 40px auto;
  }

  .team-section h2 {
    color: #0096c7;
    margin-bottom: 50px;
    text-align: center;
  }

  .team-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    max-width: 800px;
    margin: 0 auto;
  }

  .team-member {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 188, 212, 0.15);
    text-align: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid rgba(0, 188, 212, 0.1);
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
  }
  
  .team-member:nth-child(1) { animation-delay: 0.2s; }
  .team-member:nth-child(2) { animation-delay: 0.3s; }
  
  body.dark-mode .team-member {
    background: rgba(30, 30, 30, 0.9);
    border-color: rgba(77, 208, 225, 0.2);
  }
  
  body.dark-mode .team-member h3 {
    color: #4dd0e1;
  }
  
  body.dark-mode .team-member p {
    color: #b0b0b0;
  }

  .team-member:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 12px 40px rgba(0, 188, 212, 0.3), 0 0 20px rgba(77, 208, 225, 0.2);
  }
  
  body.dark-mode .team-member:hover {
    box-shadow: 0 12px 40px rgba(77, 208, 225, 0.4), 0 0 30px rgba(77, 208, 225, 0.25);
  }

  .member-img {
    width: 150px;
    height: 150px;
    margin: 0 auto 25px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #00bcd4;
    box-shadow: 0 4px 15px rgba(0, 188, 212, 0.3);
  }

  .member-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
  }

  .team-member:hover .member-img img {
    transform: scale(1.1);
  }

  .team-member h3 {
    color: #0096c7;
    margin-bottom: 15px;
    font-weight: 600;
  }

  .social-links {
    margin-top: 20px;
    display: flex;
    gap: 15px;
    justify-content: center;
  }

  .social-icon {
    color: #00bcd4;
    font-size: 24px;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: rgba(0, 188, 212, 0.1);
  }

  .social-icon:hover {
    color: white;
    background: linear-gradient(135deg, #00bcd4, #4dd0e1);
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 188, 212, 0.4);
  }

  /* Vision & Mission Section - Neumorphic 3D Enhanced */
  .vision-mission {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 45px;
    padding: 100px 40px;
    max-width: 1300px;
    background: linear-gradient(145deg, #0096c7, #00b4d8, #4dd0e1);
    color: white;
    border-radius: 30px;
    margin: 80px auto;
    box-shadow: 0 20px 60px rgba(0, 150, 199, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    opacity: 1;
    transform: translateY(50);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .vision-mission::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: breathe 8s ease-in-out infinite;
    pointer-events: none;
  }

  @keyframes breathe {
    0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.3; }
    50% { transform: translate(10px, 10px) scale(1.05); opacity: 0.5; }
  }

  .vision-mission.animate-in {
    opacity: 1;
    transform: translateY(0);
  }

  .vision-box, .mission-box {
    padding: 55px 50px;
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.08));
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 28px;
    border: 2px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    justify-content: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    overflow: hidden;
  }

  .vision-box::before, .mission-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.5s ease;
    pointer-events: none;
  }

  .vision-box:hover::before, .mission-box:hover::before {
    opacity: 1;
  }

  .vision-box:hover, .mission-box:hover {
    transform: translateY(-12px) scale(1.03) perspective(1000px) rotateX(2deg);
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0.15));
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3),
                0 0 30px rgba(255, 255, 255, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.4) inset;
  }

  .vision-box h2, .mission-box h2 {
    margin-bottom: 28px;
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: 1px;
    text_shadow: 0 3px 15px rgba(0, 0, 0, 0.25),
                 0 0 20px rgba(255, 255, 255, 0.2);
    text-shadow: none;
    position: relative;
    z-index: 1;
  }

  .vision-box p, .mission-box p {
    line-height: 1.9;
    opacity: 0.98;
    font-size: 1.12rem;
    max-width: 500px;
    font-weight: 400;
    text-align: center;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
    position: relative;
    z-index: 1;
  }
  
  /* Dark Mode Enhancement for Vision/Mission */
  body.dark-mode .vision-mission {
    background: linear-gradient(145deg, #0a4d5c, #006d80, #0096c7);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(77, 208, 225, 0.2) inset;
  }
  
  body.dark-mode .vision-box,
  body.dark-mode .mission-box {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.05));
    border-color: rgba(77, 208, 225, 0.5);
  }
  
  body.dark-mode .vision-box:hover,
  body.dark-mode .mission-box:hover {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0.08));
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5),
                0 0 40px rgba(77, 208, 225, 0.4),
                0 0 0 1px rgba(77, 208, 225, 0.6) inset;
  }

  /* Why We Started Section */
  .why-section {
    padding: 80px 30px;
    max-width: 1000px;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 24px;
    margin: 60px auto;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 6px 25px rgba(0, 188, 212, 0.15);
    border: 2px solid rgba(0, 188, 212, 0.1);
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.2s;
  }

  .why-section.animate-in {
    opacity: 1;
    transform: translateY(0);
  }

  .why-section h2 {
    background: linear-gradient(90deg, #00b4d8, #0096c7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 30px;
    font-size: 2.2rem;
    font-weight: 700;
    letter-spacing: 0.5px;
  }

  .why-section p {
    max-width: 900px;
    margin: 0 auto;
    line-height: 1.8;
    color: #222;
    font-size: 1.1rem;
    font-weight: 400;
  }

  /* Contact Section */
  .contact-section {
    padding: 80px 20px;
    background: linear-gradient(135deg, rgba(0, 188, 212, 0.05) 0%, rgba(77, 208, 225, 0.05) 100%);
    border-radius: 20px;
    margin: 40px auto;
  }

  .contact-section h2 {
    color: #0096c7;
    margin-bottom: 40px;
    text-align: center;
  }

  .contact-links {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
  }

  .contact-link {
    color: #0096c7;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 28px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 50px;
    transition: all 0.3s;
    border: 2px solid rgba(0, 188, 212, 0.2);
    font-weight: 500;
  }

  .contact-link:hover {
    background: linear-gradient(135deg, #00bcd4, #4dd0e1);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 188, 212, 0.3);
    border-color: transparent;
  }

  .contact-link i {
    font-size: 20px;
  }

  /* CTA Section - Enhanced Gen Z */
  .cta-section {
    padding: 100px 40px;
    max-width: 1300px;
    background: linear-gradient(145deg, #0096c7, #00b4d8, #4dd0e1);
    color: white;
    text-align: center;
    border-radius: 30px;
    margin: 80px auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 20px 60px rgba(0, 150, 199, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transform: translateY(50px);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.3s;
  }

  .cta-section.animate-in {
    opacity: 1;
    transform: translateY(0);
  }

  .cta-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 50%;
    animation: float 10s ease-in-out infinite;
  }

  .cta-section::after {
    content: '';
    position: absolute;
    bottom: -60%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite reverse;
  }

  @keyframes float {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(-30px, 30px); }
  }

  .cta-section h2 {
    font-size: 2.8rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-shadow: 0 3px 15px rgba(0, 0, 0, 0.25),
                 0 0 20px rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 1;
    margin-bottom: 25px;
  }

  .cta-section p {
    font-size: 1.2rem;
    opacity: 0.98;
    margin-bottom: 45px;
    max-width: 900px;
    line-height: 1.9;
    font-weight: 400;
    white-space: pre-line;
    position: relative;
    z-index: 1;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
  }

  .cta-button {
    display: inline-block;
    padding: 18px 48px;
    background: white;
    color: #0096c7;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 800;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset;
    position: relative;
    z-index: 1;
    overflow: hidden;
  }

  .cta-button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: linear-gradient(135deg, #0096c7, #00b4d8);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: -1;
  }

  .cta-button:hover::before {
    width: 350px;
    height: 350px;
  }

  .cta-button:hover {
    color: white;
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 15px 45px rgba(0, 150, 199, 0.7),
                0 0 30px rgba(255, 255, 255, 0.4);
  }
  
  /* Dark Mode CTA */
  body.dark-mode .cta-section {
    background: linear-gradient(145deg, #0a4d5c, #006d80, #0096c7);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(77, 208, 225, 0.2) inset;
  }
  
  body.dark-mode .cta-button {
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(77, 208, 225, 0.3) inset;
  }
  
  body.dark-mode .cta-button:hover {
    box-shadow: 0 15px 45px rgba(77, 208, 225, 0.6),
                0 0 40px rgba(77, 208, 225, 0.5);
  }

  .ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-animation 0.6s ease-out;
    pointer-events: none;
  }

  @keyframes ripple-animation {
    to {
      transform: scale(4);
      opacity: 0;
    }
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .vision-mission {
      grid-template-columns: 1fr;
      padding: 60px 20px;
    }

    .vision-box h2, .mission-box h2 {
      font-size: 1.8rem;
    }

    .vision-box p, .mission-box p {
      font-size: 0.95rem;
    }

    .why-section {
      padding: 60px 20px;
    }

    .why-section h2 {
      font-size: 1.8rem;
    }

    .why-section p {
      font-size: 0.95rem;
      padding: 0 15px;
    }

    .cta-section {
      padding: 80px 20px;
    }

    .cta-section h2 {
      font-size: 2rem;
    }

    .cta-section p {
      font-size: 1rem;
      padding: 0 15px;
    }

    .cta-button {
      padding: 14px 35px;
      font-size: 16px;
    }

    .team-container {
      grid-template-columns: 1fr;
    }

    .contact-links {
      flex-direction: column;
      align-items: center;
    }

    .contact-link {
      width: 100%;
      max-width: 300px;
      justify-content: center;
    }
  }

  /* Dark Mode Support */
  body.dark-mode .why-section {
    background: rgba(30, 30, 30, 0.8);
    border-color: rgba(0, 188, 212, 0.3);
  }

  body.dark-mode .why-section p {
    color: #e0e0e0;
  }

  body.dark-mode .vision-mission,
  body.dark-mode .cta-section {
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
  }
</style>

<section>
  <h1>About Our Project</h1>

  <!-- Radio buttons for interaction -->
  <input type="radio" name="cards" id="card1" class="card-input" checked>
  <input type="radio" name="cards" id="card2" class="card-input">
  <input type="radio" name="cards" id="card3" class="card-input">

  <div class="card-container">
    <label for="card1" class="card-label">
      <h2>🎯 Goal</h2>
      <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Goal">
      <p>Click to see details</p>
    </label>
    <label for="card2" class="card-label">
      <h2>🌍 Impact</h2>
      <img src="https://img.freepik.com/premium-photo/ai-generated-ai-generative-plastic-ecology-ocean-sea-water-underwater-pollution-trash_95211-11220.jpg?w=740" alt="Impact">
      <p>Click to see details</p>
    </label>
    <label for="card3" class="card-label">
      <h2>👨‍💻 Users</h2>
      <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80" alt="Users">
      <p>Click to see details</p>
    </label>
  </div>

  <!-- Info Sections -->
  <div class="card-info-container">
    <div class="card-info" id="info1">
      <h3>🎯 Goal Details</h3>
      <div class="numbered-info">
        <div class="info-item">
          <span class="number">1</span>
          <p>Enable locals to report river pollution easily</p>
        </div>
        <div class="info-item">
          <span class="number">2</span>
          <p>Help authorities track issues efficiently</p>
        </div>
        <div class="info-item">
          <span class="number">3</span>
          <p>Encourage community awareness & action</p>
        </div>
      </div>
    </div>
    <div class="card-info" id="info2">
      <h3>🌍 Impact Details</h3>
      <div class="numbered-info">
        <div class="info-item">
          <span class="number">1</span>
          <p>Raise awareness about pollution</p>
        </div>
        <div class="info-item">
          <span class="number">2</span>
          <p>Promote clean-up drives</p>
        </div>
        <div class="info-item">
          <span class="number">3</span>
          <p>Protect aquatic life and environment</p>
        </div>
      </div>
    </div>
    <div class="card-info" id="info3">
      <h3>👨‍💻 Users Details</h3>
      <div class="numbered-info">
        <div class="info-item">
          <span class="number">1</span>
          <p>Local residents reporting pollution</p>
        </div>
        <div class="info-item">
          <span class="number">2</span>
          <p>NGOs monitoring environmental health</p>
        </div>
        <div class="info-item">
          <span class="number">3</span>
          <p>Students, teachers, researchers tracking rivers</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Team Section -->
<section class="team-section">
  <h2>Meet the minds behind the RiverVibe Team</h2>
  <div class="team-container">
    <div class="team-member">
      <div class="member-img">
        <img src="/Webby/DSC_6694.JPG" alt="Dewpearl Gonsalves">
      </div>
      <h3>Dewpearl Gonsalves</h3>
      <div class="social-links">
        <a href="https://github.com" target="_blank" class="social-icon">
          <i class="fab fa-github"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" class="social-icon">
          <i class="fab fa-linkedin"></i>
        </a>
      </div>
    </div>
    <div class="team-member">
      <div class="member-img">
        <img src="/Webby/WhatsApp Image 2025-10-19 at 11.25.59 PM.jpeg" alt="Anchal">
      </div>
      <h3>Anchal Shukla</h3>
      <div class="social-links">
        <a href="https://github.com" target="_blank" class="social-icon">
          <i class="fab fa-github"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" class="social-icon">
          <i class="fab fa-linkedin"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Vision & Mission Section -->
<section class="vision-mission">
  <div class="vision-box">
    <h2>🌍 Vision</h2>
    <p>To create a world where every river flows clean, free, and alive — protected by the voices of those who care enough to speak up.
RiverVibe envisions communities that don't wait for change — they start it, one report, one voice, one ripple at a time.</p>
  </div>
  <div class="mission-box">
    <h2>💫 Mission</h2>
    <p>Our mission is to give people the power to make their environment better.
      RiverVibe connects citizens, students, and environmental groups through a simple, open platform where anyone can:
      Report pollution spotted in local rivers
      Raise awareness within the community
      Inspire collective clean-up actions and responsibility
      Because every drop matters — and every voice can create a wave.</p>
  </div>
</section>

<!-- Why We Started Section -->
<section class="why-section">
  <h2>💧Why We Started</h2>
  <p>We noticed something simple but powerful:
  People see pollution every day — but have nowhere to say it.
  RiverVibe was born from that gap — an idea sparked by two students (Dewpearl & Anchal) who wanted to make reporting river pollution easy, visible, and impactful.
  What started as a college project grew into a cause — one that transforms awareness into action, and frustration into empowerment.</p>
</section>

<!-- Contact Section -->
<section class="contact-section">
  <h2>Get in Touch</h2>
  <div class="contact-links">
    <a href="mailto:contact@rivervibe.com" class="contact-link">
      <i class="fas fa-envelope"></i>
      contact@rivervibe.com
    </a>
    <a href="https://instagram.com/rivervibe" target="_blank" class="contact-link">
      <i class="fab fa-instagram"></i>
      @rivervibe
    </a>
  </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
  <h2>✊Join the Movement</h2>
  <p>Don't just scroll — speak up, share, and start the ripple.
  🌊 Report pollution in your area
  💬 Spread awareness with your community
  🤝 Connect with people who care
  Together, we're building a cleaner, louder, and more hopeful future for our rivers.
👉 Join RiverVibe — Let your voice flow where it matters most.</p>
  <a href="/Webby/feedback.php" class="cta-button">Report Pollution</a>
</section>

<!-- Scroll Animation Script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const animatedSections = document.querySelectorAll('.vision-mission, .why-section, .cta-section');
    
    const observerOptions = {
      threshold: 0.15,
      rootMargin: '0px 0px -50px 0px'
    };
    
    const onIntersection = (entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-in');
          observer.unobserve(entry.target);
        }
      });
    };
    
    const observer = new IntersectionObserver(onIntersection, observerOptions);
    animatedSections.forEach(section => observer.observe(section));
    
    // CTA Button Ripple Effect
    const ctaButton = document.querySelector('.cta-button');
    if (ctaButton) {
      ctaButton.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');
        
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
      });
    }
    
    console.log('✅ About page scroll animations initialized');
  });
</script>

<?php include 'components/footer.php'; ?>
