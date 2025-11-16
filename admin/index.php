<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Webby/login.php");
    exit;
}

$page_title = "Admin Dashboard - RiverVibe";

require_once __DIR__ . '/../components/db_connect.php';

// Get statistics
$total_users = 0;
$total_reports = 0;
$pending_reports = 0;
$total_feedback = 0;

$result = $conn->query("SELECT COUNT(*) as total FROM users");
if ($row = $result->fetch_assoc()) $total_users = $row['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM river_reports");
if ($row = $result->fetch_assoc()) $total_reports = $row['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM river_reports WHERE status = 'Pending'");
if ($row = $result->fetch_assoc()) $pending_reports = $row['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM feedback");
if ($row = $result->fetch_assoc()) $total_feedback = $row['total'];

$additional_css = [<<<CSS
<style>
    body {
        background: var(--bg-primary);
        animation: pageLoad 0.5s ease-out;
    }
    
    @keyframes pageLoad {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .admin-header {
        position: relative;
        background: linear-gradient(135deg, #0096c7 0%, #00b4d8 50%, #023e8a 100%);
        color: white;
        padding: 80px 20px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 150, 199, 0.4);
        overflow: hidden;
    }
    
    .admin-header::before {
        content: \'\';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(-20px, 20px); }
    }
    
    .admin-header h1 {
        font-size: 2.8rem;
        margin-bottom: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-shadow: 0 3px 15px rgba(0, 0, 0, 0.3),
                     0 0 20px rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 1;
    }
    
    .admin-container {
        max-width: 1400px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
        opacity: 0;
        animation: fadeSlideUp 0.6s ease-out 0.2s forwards;
    }
    
    @keyframes fadeSlideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .stat-box {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06),
                    0 0 0 1px rgba(102, 126, 234, 0.08) inset;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-box::before {
        content: \'\';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .stat-box:hover::before {
        opacity: 1;
    }
    
    .stat-box:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12),
                    0 0 15px rgba(102, 126, 234, 0.12);
    }
    
    .stat-box i {
        font-size: 3rem;
        margin-bottom: 20px;
        background: linear-gradient(135deg, var(--ocean-teal), var(--ocean-aqua));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        z-index: 1;
    }
    
    .stat-box.users i { 
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .stat-box.reports i { 
        background: linear-gradient(135deg, #00b4d8, #4dd0e1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .stat-box.pending i { 
        background: linear-gradient(135deg, #ff9800, #ffa726);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .stat-box.feedback i { 
        background: linear-gradient(135deg, #80ffdb, #4dd0e1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-box h3 {
        color: var(--text-primary);
        margin-bottom: 15px;
        font-size: 2.5rem;
        font-weight: 600;
        transition: var(--transition-theme);
        position: relative;
        z-index: 1;
    }
    
    .stat-box p {
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 1.05rem;
        font-weight: 500;
        transition: var(--transition-theme);
        position: relative;
        z-index: 1;
    }
    
    .admin-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        opacity: 0;
        animation: fadeSlideUp 0.6s ease-out 0.4s forwards;
    }
    
    .action-card {
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 40px 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-md);
        transition: var(--transition-smooth);
        border: 2px solid var(--border-color);
        position: relative;
        overflow: hidden;
    }
    
    .action-card::before {
        content: \'\';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--ocean-teal), var(--ocean-aqua));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .action-card:hover::before {
        transform: scaleX(1);
    }
    
    .action-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
        border-color: var(--ocean-aqua);
    }
    
    .action-card h3 {
        color: var(--text-primary);
        margin-bottom: 15px;
        font-size: 1.5rem;
        font-weight: 600;
        transition: var(--transition-theme);
        position: relative;
        z-index: 1;
    }
    
    .action-card p {
        color: var(--text-secondary);
        margin-bottom: 20px;
        line-height: 1.8;
        font-size: 1rem;
        transition: var(--transition-theme);
        position: relative;
        z-index: 1;
    }
    
    .action-btn {
        display: inline-block;
        background: linear-gradient(135deg, #0096c7 0%, #00b4d8 50%, #023e8a 100%);
        color: white;
        padding: 14px 32px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.05rem;
        letter-spacing: 0.3px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 6px 20px rgba(0, 150, 199, 0.4);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: \'\';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
        z-index: -1;
    }
    
    .action-btn:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .action-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 35px rgba(0, 180, 216, 0.6),
                    0 0 30px rgba(128, 255, 219, 0.3);
    }
    
    .logout-btn {
        background: linear-gradient(135deg, #ff4444, #cc0000);
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-block;
        margin-top: 30px;
        box-shadow: 0 6px 20px rgba(255, 68, 68, 0.3);
    }
    
    .logout-btn:hover {
        background: linear-gradient(135deg, #cc0000, #990000);
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(255, 68, 68, 0.5);
    }
    
    /* Dark mode enhanced */
    body.dark-mode {
        background: var(--bg-primary);
    }
    
    body.dark-mode .admin-header {
        background: linear-gradient(135deg, #023e8a 0%, #0077b6 50%, #03045e 100%);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
    }
    
    body.dark-mode .stat-box,
    body.dark-mode .action-card {
        background: rgba(30, 41, 59, 0.95);
        color: #e0e0e0;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4),
                    0 0 0 1px rgba(0, 180, 216, 0.25) inset;
    }
    
    body.dark-mode .stat-box:hover,
    body.dark-mode .action-card:hover {
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7),
                    0 0 40px rgba(128, 255, 219, 0.25);
    }
    
    body.dark-mode .stat-box h3,
    body.dark-mode .action-card h3 {
        color: #fff;
    }
    
    body.dark-mode .stat-box p,
    body.dark-mode .action-card p {
        color: #ccc;
    }
</style>
CSS];

include __DIR__ . '/../components/header.php';
?>

<section class="admin-header">
    <h1>🛡️ Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>! Manage the RiverVibe platform.</p>
</section>

<div class="admin-container">
    <div class="dashboard-stats">
        <div class="stat-box users">
            <i class="fas fa-users"></i>
            <h3><?php echo $total_users; ?></h3>
            <p>Total Users</p>
        </div>
        
        <div class="stat-box reports">
            <i class="fas fa-file-alt"></i>
            <h3><?php echo $total_reports; ?></h3>
            <p>Total Reports</p>
        </div>
        
        <div class="stat-box pending">
            <i class="fas fa-clock"></i>
            <h3><?php echo $pending_reports; ?></h3>
            <p>Pending Review</p>
        </div>
        
        <div class="stat-box feedback">
            <i class="fas fa-comments"></i>
            <h3><?php echo $total_feedback; ?></h3>
            <p>Feedback Received</p>
        </div>
    </div>
    
    <div class="admin-actions">
        <div class="action-card">
            <h3>📋 Manage Reports</h3>
            <p>View, edit, verify, and delete pollution reports submitted by users.</p>
            <a href="/Webby/admin/view_reports.php" class="action-btn">View Reports</a>
        </div>
        
        <div class="action-card">
            <h3>💬 View Feedback</h3>
            <p>Review feedback and pollution reports submitted through the feedback form.</p>
            <a href="/Webby/admin/view_feedback.php" class="action-btn">View Feedback</a>
        </div>
        
        <div class="action-card">
            <h3>🌊Public Dashboard</h3>
            <p>View the public dashboard and monitor all river pollution data.</p>
            <a href="/Webby/dashboard.php" class="action-btn">Go to Dashboard</a>
        </div>
    </div>
    
    <div style="text-align: center;">
        <a href="/Webby/logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
