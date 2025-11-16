<?php
session_start();
$page_title = "Feedback - RiversVibe";

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'components/db_connect.php';
    
    // Get form data with validation
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : 'Other';
    $river_name = isset($_POST['river_name']) ? trim($_POST['river_name']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $pollution_type = isset($_POST['pollution_type']) ? $_POST['pollution_type'] : '';
    $issue_description = isset($_POST['issue_description']) ? trim($_POST['issue_description']) : '';
    
    // Validate required fields
    if (empty($river_name) || empty($location) || empty($pollution_type) || empty($issue_description)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Handle file upload
        $photo_path = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            $max_size = 2 * 1024 * 1024; // 2MB
            
            if (in_array($_FILES['photo']['type'], $allowed_types) && $_FILES['photo']['size'] <= $max_size) {
                $upload_dir = 'uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $new_filename = 'report_' . time() . '_' . uniqid() . '.' . $file_extension;
                $target_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
                    $photo_path = $target_path;
                }
            }
        }
        
        // Insert into feedback table
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, gender, river_name, location, pollution_type, issue_description, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $name, $email, $gender, $river_name, $location, $pollution_type, $issue_description, $photo_path);
        
        if ($stmt->execute()) {
            $success_message = "Thank you! Your feedback has been submitted successfully. We appreciate your contribution to keeping our rivers clean! 🌊";
            // Clear the form by redirecting
            // header("Location: feedback.php?success=1");
            // exit;
        } else {
            $error_message = "Sorry, there was an error submitting your feedback. Please try again.";
        }
        
        $stmt->close();
    }
}

// Check for success parameter in URL
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success_message = "Thank you! Your feedback has been submitted successfully. 🌊";
}

$additional_css = ['
<style>
body { font-family:\'Inter\', sans-serif; margin:0; }

.feedback-header { 
    text-align:center; 
    color:white; 
    padding:80px 20px; 
    position:relative; 
    overflow:hidden;
    background: linear-gradient(145deg, #0096c7, #00b4d8, #4dd0e1);
    box-shadow: 0 10px 40px rgba(0, 150, 199, 0.3);
}
.feedback-header h1 { 
    font-size:3rem; 
    margin-bottom:15px; 
    font-weight: 800;
    letter-spacing: 0.5px;
    text-shadow: 0 3px 15px rgba(0,0,0,0.3),
                 0 0 20px rgba(255, 255, 255, 0.2);
}
.feedback-header p { 
    font-size:1.2rem; 
    max-width:700px; 
    margin:auto; 
    font-weight: 400;
    text-shadow: 0 2px 8px rgba(0,0,0,0.25);
}

section:nth-of-type(2) {
    min-height: 100vh;
    padding: 60px 20px;
    background: url(\'https://static.vecteezy.com/system/resources/previews/028/237/379/non_2x/contaminated-water-concept-dirty-water-flows-from-the-pipe-into-the-river-sea-water-pollution-environment-contamination-ai-generative-photo.jpg\');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    position: relative;
}

section:nth-of-type(2)::before {
    content: \'\';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 188, 212, 0.1) 0%, rgba(77, 208, 225, 0.15) 100%);
    backdrop-filter: blur(3px);
    z-index: 0;
}

form {
  max-width:700px; 
  margin:40px auto; 
  display:flex; 
  flex-direction:column; 
  gap:25px;
  background: linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.95)); 
  padding:45px 40px; 
  border-radius:25px; 
  box-shadow: 0 15px 50px rgba(0,0,0,0.35),
              0 0 0 1px rgba(0, 188, 212, 0.1) inset;
  backdrop-filter: blur(15px);
  position: relative;
  z-index: 1;
  animation: formFadeIn 0.6s ease-out;
}

@keyframes formFadeIn {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

form .form-group {
  position: relative;
  display: flex;
  flex-direction: column;
}

form label { 
  font-weight: 600; 
  font-size: 0.95rem;
  color: #2c3e50;
  margin-bottom: 8px;
  letter-spacing: 0.3px;
  transition: all 0.3s ease;
}

form input, form select, form textarea { 
  padding: 14px 18px; 
  border: 2px solid rgba(0, 188, 212, 0.2); 
  border-radius: 12px; 
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
  font-size: 1rem;
  font-family: inherit;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

form input:focus, form select:focus, form textarea:focus { 
  border-color: #00bcd4;
  box-shadow: 0 0 20px rgba(0, 188, 212, 0.4),
              0 0 0 3px rgba(0, 188, 212, 0.1);
  outline: none;
  transform: translateY(-2px);
  background: white;
}

/* Typing micro-interaction */
form input:not(:placeholder-shown),
form textarea:not(:placeholder-shown) {
  border-color: #4dd0e1;
}

form input::placeholder,
form textarea::placeholder {
  color: #999;
  font-weight: 300;
}

form .gender-options { 
  display: flex; 
  gap: 20px; 
  margin-top: 8px;
}

form .gender-options label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-weight: 500;
  margin-bottom: 0;
}

form .gender-options input { 
  width: auto; 
  transform: scale(1.3); 
  cursor: pointer;
  accent-color: #00bcd4;
}

.btn { 
  padding: 16px 35px; 
  border: none; 
  border-radius: 50px; 
  background: linear-gradient(135deg, #ffd60a, #ffc300); 
  color: #222; 
  font-weight: 700; 
  font-size: 1.1rem;
  letter-spacing: 0.5px;
  text-decoration: none; 
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
  cursor: pointer; 
  margin-top: 15px;
  box-shadow: 0 6px 20px rgba(255, 214, 10, 0.4);
  position: relative;
  overflow: hidden;
}

.btn::before {
  content: \'\';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.btn:hover::before {
  width: 300px;
  height: 300px;
}

.btn:hover, .btn:focus { 
  background: linear-gradient(135deg, #ffc300, #ffaa00); 
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 10px 30px rgba(255, 214, 10, 0.6);
}

/* Success Animation */
.success-message {
  background: linear-gradient(135deg, #4caf50, #81c784);
  color: white;
  padding: 20px 30px;
  border-radius: 15px;
  text-align: center;
  font-weight: 600;
  box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
  animation: successPop 0.5s ease-out;
}

@keyframes successPop {
  0% {
    opacity: 0;
    transform: scale(0.8);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

/* Dark Mode Enhanced */
body.dark-mode form {
  background: linear-gradient(145deg, rgba(30, 30, 30, 0.98), rgba(20, 20, 20, 0.95));
  border: 2px solid rgba(77, 208, 225, 0.3);
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.7),
              0 0 0 1px rgba(77, 208, 225, 0.2) inset;
}

body.dark-mode form label {
  color: #e0e0e0;
  font-weight: 600;
}

body.dark-mode form input,
body.dark-mode form select,
body.dark-mode form textarea {
  background: rgba(20, 20, 20, 0.9);
  color: #e0e0e0;
  border-color: rgba(77, 208, 225, 0.3);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

body.dark-mode form input:focus,
body.dark-mode form select:focus,
body.dark-mode form textarea:focus {
  border-color: #4dd0e1;
  box-shadow: 0 0 20px rgba(77, 208, 225, 0.5),
              0 0 0 3px rgba(77, 208, 225, 0.15);
  background: rgba(30, 30, 30, 0.95);
}

body.dark-mode form input:not(:placeholder-shown),
body.dark-mode form textarea:not(:placeholder-shown) {
  border-color: #4dd0e1;
  box-shadow: 0 0 15px rgba(77, 208, 225, 0.3);
}

body.dark-mode form input::placeholder,
body.dark-mode form textarea::placeholder {
  color: #666;
}

body.dark-mode .gender-options label {
  color: #e0e0e0;
}

body.dark-mode .feedback-header {
  background: linear-gradient(145deg, #0a4d5c, #006d80, #0096c7);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}

body.dark-mode section:nth-of-type(2)::before {
  background: linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 50, 70, 0.5) 100%);
}
body.dark-mode .btn {
  background: linear-gradient(135deg, #ffd60a, #ffc300);
  color: #000;
}

body.dark-mode .btn:hover {
  background: linear-gradient(135deg, #ffc300, #ffb700);
  box-shadow: 0 4px 15px rgba(255, 214, 10, 0.4);
}

.success-message {
    max-width: 650px;
    margin: 20px auto;
    padding: 15px 20px;
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    color: #155724;
    text-align: center;
    font-weight: 500;
}

.error-message {
    max-width: 650px;
    margin: 20px auto;
    padding: 15px 20px;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    color: #721c24;
    text-align: center;
    font-weight: 500;
}

form, .feedback-header { animation: floatUp 2s ease-out; }
@keyframes floatUp { 0% { transform: translateY(30px); opacity:0; } 100% { transform: translateY(0); opacity:1; } }
</style>
'];

include 'components/header.php';
?>

<section class="feedback-header">
  <h1>We Value Your Feedback 💬</h1>
  <p>Share your experience, report pollution, or give suggestions to help us keep our rivers clean and healthy for everyone.</p>
</section>

<section>
  <?php if ($success_message): ?>
    <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
  <?php endif; ?>
  
  <?php if ($error_message): ?>
    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
  <?php endif; ?>

  <form action="/Webby/feedback.php" method="post" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" placeholder="Your Name">

    <label>Email:</label>
    <input type="email" name="email" placeholder="Your Email">

    <label>Gender:</label>
    <div class="gender-options">
      <label><input type="radio" name="gender" value="male"> Male</label>
      <label><input type="radio" name="gender" value="female"> Female</label>
      <label><input type="radio" name="gender" value="other"> Other</label>
    </div>

    <label>River Name:</label>
    <input type="text" name="river_name" placeholder="Name of River" required>

    <label>Location (City/Area):</label>
    <input type="text" name="location" placeholder="e.g., Haridwar, Uttarakhand" required>

    <label>Type of Pollution:</label>
    <select name="pollution_type" required>
      <option value="Plastic">Plastic</option>
      <option value="Chemical">Chemical</option>
      <option value="Sewage">Sewage</option>
      <option value="Industrial">Industrial</option>
      <option value="Agricultural">Agricultural</option>
      <option value="Other">Other</option>
    </select>

    <label>Describe Issue:</label>
    <textarea rows="5" name="issue_description" placeholder="Describe the pollution or issue" required></textarea>

    <label>Upload Photo (JPG/PNG up to 2MB):</label>
    <input type="file" name="photo" accept="image/png, image/jpeg">

    <input type="hidden" name="latitude" id="lat">
    <input type="hidden" name="longitude" id="lng">

    <button type="submit" class="btn">Submit Feedback</button>
  </form>
</section>

<script>
  // Try to auto-capture geolocation
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos){
      document.getElementById('lat').value = pos.coords.latitude.toFixed(7);
      document.getElementById('lng').value = pos.coords.longitude.toFixed(7);
    }, function(err){ /* silent */ }, { enableHighAccuracy:true, timeout:5000 });
  }
</script>

<?php include 'components/footer.php'; ?>
