<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /Webby/dashboard.php');
    exit();
}

include __DIR__ . '/components/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'All fields are required';
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header('Location: /Webby/admin/index.php');
                } else {
                    header('Location: /Webby/dashboard.php');
                }
                exit();
            } else {
                $error = 'Invalid email or password';
            }
        } else {
            $error = 'Invalid email or password';
        }
        $stmt->close();
    }
}

$page_title = 'Login - RiverVibe';
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
  
  .demo-credentials {
    background: #e0f7fa;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 0.9rem;
    color: #0096c7;
  }
  
  .demo-credentials strong {
    display: block;
    margin-bottom: 8px;
  }
</style>

<div class="auth-container">
  <div class="auth-box">
    <div class="back-home">
      <a href="/Webby/index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
    
    <h1>🌊 Login</h1>
    
    <div class="demo-credentials">
      <strong>Demo Admin Credentials:</strong>
      Email: admin@rivervibe.com<br>
      Password: admin123
    </div>
    
    <?php if ($error): ?>
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" action="/Webby/login.php">
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Enter your password">
      </div>
      
      <button type="submit" class="btn-primary">Login</button>
    </form>
    
    <div class="auth-footer">
      Don't have an account? <a href="/Webby/signup.php">Sign up here</a>
    </div>
  </div>
</div>

<?php include 'components/footer.php'; ?>
