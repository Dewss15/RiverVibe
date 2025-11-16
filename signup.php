<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /Webby/dashboard.php');
    exit();
}

include 'components/db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email already registered';
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! Please login.';
                // Redirect after 2 seconds
                header('refresh:2;url=/Webby/login.php');
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $stmt->close();
    }
}

$page_title = 'Sign Up - RiverVibe';
include 'components/header.php';
?>

<style>
  /* Auth pages styling */
  .auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    background: linear-gradient(135deg, #e0f7fa 0%, #ffffff 50%, #f0f9ff 100%);
  }
  
  .auth-box {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 50px 40px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 150, 199, 0.2);
    max-width: 450px;
    width: 100%;
    border: 2px solid rgba(0, 188, 212, 0.1);
  }
  
  .auth-box h1 {
    font-size: 2rem;
    background: linear-gradient(135deg, #00bcd4, #0096c7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 30px;
    text-align: center;
    font-weight: 700;
  }
  
  .form-group {
    margin-bottom: 25px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 8px;
    color: #0096c7;
    font-weight: 600;
    font-size: 0.95rem;
  }
  
  .form-group input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid rgba(0, 188, 212, 0.2);
    border-radius: 12px;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
    background: white;
  }
  
  .form-group input:focus {
    outline: none;
    border-color: #00bcd4;
    box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1);
  }
  
  .btn-primary {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #0096c7, #00b4d8);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 150, 199, 0.3);
  }
  
  .alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 0.95rem;
  }
  
  .alert-error {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fca5a5;
  }
  
  .alert-success {
    background: #dcfce7;
    color: #16a34a;
    border: 1px solid #86efac;
  }
  
  .auth-footer {
    text-align: center;
    margin-top: 25px;
    color: #64748b;
    font-size: 0.95rem;
  }
  
  .auth-footer a {
    color: #0096c7;
    text-decoration: none;
    font-weight: 600;
  }
  
  .auth-footer a:hover {
    text-decoration: underline;
  }
  
  .back-home {
    text-align: center;
    margin-bottom: 20px;
  }
  
  .back-home a {
    color: #0096c7;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  
  .back-home a:hover {
    text-decoration: underline;
  }
</style>

<div class="auth-container">
  <div class="auth-box">
    <div class="back-home">
      <a href="/Webby/index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
    
    <h1>🌊 Sign Up</h1>
    
    <?php if ($error): ?>
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
      </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" action="">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required placeholder="Enter your name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
      </div>
      
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Minimum 6 characters">
      </div>
      
      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Re-enter password">
      </div>
      
      <button type="submit" class="btn-primary">Create Account</button>
    </form>
    
    <div class="auth-footer">
      Already have an account? <a href="/Webby/login.php">Login here</a>
    </div>
  </div>
</div>

<?php include 'components/footer.php'; ?>
