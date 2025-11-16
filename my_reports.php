<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Webby/login.php");
    exit;
}

// Redirect admins to admin panel instead
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: /Webby/admin/index.php");
    exit;
}

$page_title = "My Feedback - RiverVibe";

require_once 'components/db_connect.php';

// Get user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Get user's feedback submissions
// Match by email since feedback table doesn't have user_id by default
$user_email = $user['email'];
$stmt = $conn->prepare(
    "SELECT * FROM feedback 
     WHERE email = ? 
     ORDER BY 
        CASE status 
            WHEN 'Flagged' THEN 1 
            WHEN 'Pending' THEN 2 
            WHEN 'Reviewing' THEN 3 
            WHEN 'Reviewed' THEN 4 
        END, 
        created_at DESC"
);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$my_feedback = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$additional_css = [<<<CSS
<style>
    body {
        background: var(--bg-primary);
        min-height: 100vh;
    }
    
    .my-feedback-header {
        position: relative;
        background: linear-gradient(135deg, #0096c7 0%, #00b4d8 50%, #023e8a 100%);
        color: white;
        padding: 80px 20px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 150, 199, 0.3);
        overflow: hidden;
    }
    
    .my-feedback-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(128, 255, 219, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 50%, rgba(0, 180, 216, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .my-feedback-header h1 {
        font-size: 3.5rem;
        margin: 0 0 20px 0;
        font-weight: 700;
        background: linear-gradient(135deg, #ffffff 0%, #80ffdb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 20px rgba(128, 255, 219, 0.3);
        position: relative;
        z-index: 1;
    }
    
    .my-feedback-header p {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.9);
        margin: 15px auto 0 auto;
        text-align: center;
        font-weight: 400;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
    }
    
    .feedback-container {
        max-width: 1400px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 30px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 150, 199, 0.3);
    }
    
    .back-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 180, 216, 0.4);
        background: linear-gradient(135deg, #00b4d8, #0096c7);
    }
    
    .stats-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 150, 199, 0.1),
                    0 0 0 1px rgba(0, 180, 216, 0.1) inset;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 180, 216, 0.03) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }
    
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 150, 199, 0.2),
                    0 0 30px rgba(128, 255, 219, 0.15);
    }
    
    .stat-card h3 {
        font-size: 3rem;
        margin: 0 0 10px 0;
        font-weight: 800;
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-card p {
        font-size: 1rem;
        color: #666;
        margin: 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-card.pending h3 {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-card.reviewing h3 {
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-card.reviewed h3 {
        background: linear-gradient(135deg, #28a745, #20c997);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-card.flagged h3 {
        background: linear-gradient(135deg, #dc3545, #c82333);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .feedback-list {
        display: grid;
        gap: 30px;
    }
    
    .feedback-item {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 150, 199, 0.1),
                    0 0 0 1px rgba(0, 180, 216, 0.1) inset;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .feedback-item::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 180, 216, 0.02) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .feedback-item:hover::before {
        opacity: 1;
    }
    
    .feedback-item:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 15px 40px rgba(0, 150, 199, 0.2),
                    0 0 30px rgba(128, 255, 219, 0.1);
    }
    
    .feedback-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
        padding-bottom: 18px;
        border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 1;
    }
    
    .feedback-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .feedback-location {
        color: #666;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 5px 0;
    }
    
    .feedback-location i {
        color: #0096c7;
    }
    
    .feedback-date {
        color: #999;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 5px 0;
    }
    
    .feedback-date i {
        color: #0096c7;
    }
    
    /* Status Badges - Matching Admin Panel */
    .status-badge {
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 1;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .status-reviewing {
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 4px 15px rgba(0, 180, 216, 0.4); }
        50% { box-shadow: 0 4px 25px rgba(0, 180, 216, 0.6), 0 0 20px rgba(0, 180, 216, 0.3); }
    }
    
    .status-reviewed {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .status-flagged {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        animation: warning-flash 1.5s ease-in-out infinite;
    }
    
    @keyframes warning-flash {
        0%, 100% { box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4); }
        50% { box-shadow: 0 4px 30px rgba(220, 53, 69, 0.7), 0 0 25px rgba(220, 53, 69, 0.4); }
    }
    
    .feedback-content {
        margin: 20px 0;
        position: relative;
        z-index: 1;
    }
    
    .detail-row {
        display: grid;
        grid-template-columns: 180px 1fr;
        padding: 12px 0;
        font-size: 0.98rem;
        gap: 20px;
        align-items: start;
    }
    
    .detail-label {
        font-weight: 700;
        color: #555;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .detail-label i {
        color: #0096c7;
        font-size: 0.9rem;
    }
    
    .detail-value {
        color: #333;
        word-wrap: break-word;
        line-height: 1.6;
    }
    
    .pollution-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: linear-gradient(135deg, #ff9800, #ff6b6b);
        color: white;
        box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3);
    }
    
    .feedback-photo {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid rgba(0, 0, 0, 0.05);
    }
    
    .photo-preview {
        max-width: 400px;
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transition: transform 0.3s;
        cursor: pointer;
    }
    
    .photo-preview:hover {
        transform: scale(1.03);
    }
    
    .admin-notes-box {
        margin-top: 25px;
        padding: 20px 25px;
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.12), rgba(255, 152, 0, 0.08));
        border-left: 4px solid #ffc107;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.15);
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .admin-notes-box h4 {
        margin: 0 0 12px 0;
        color: #856404;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .admin-notes-box h4 i {
        color: #ff9800;
        font-size: 1.2rem;
    }
    
    .admin-notes-box p {
        margin: 0;
        color: #856404;
        line-height: 1.7;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.6);
        padding: 15px;
        border-radius: 10px;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }
    
    .updated-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: white;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 12px;
        box-shadow: 0 2px 8px rgba(255, 152, 0, 0.4);
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 2px 8px rgba(255, 152, 0, 0.4); }
        50% { box-shadow: 0 4px 16px rgba(255, 152, 0, 0.6); }
    }
    
    .updated-badge i {
        font-size: 0.9rem;
        animation: ring 2s ease-in-out infinite;
    }
    
    @keyframes ring {
        0%, 100% { transform: rotate(0deg); }
        10%, 30% { transform: rotate(-15deg); }
        20%, 40% { transform: rotate(15deg); }
    }
    
    .feedback-item.flagged {
        border: 3px solid #dc3545;
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3),
                    0 0 0 1px rgba(220, 53, 69, 0.2) inset;
    }
    
    .feedback-item.flagged:hover {
        box-shadow: 0 15px 40px rgba(220, 53, 69, 0.4),
                    0 0 30px rgba(220, 53, 69, 0.3);
    }
    
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 150, 199, 0.1);
    }
    
    .empty-state i {
        font-size: 5rem;
        color: #00b4d8;
        margin-bottom: 25px;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        color: #555;
        margin-bottom: 12px;
        font-size: 1.8rem;
        font-weight: 700;
    }
    
    .empty-state p {
        color: #777;
        font-size: 1.05rem;
        margin-bottom: 30px;
    }
    
    .cta-button {
        display: inline-block;
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        padding: 14px 35px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 150, 199, 0.3);
    }
    
    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 180, 216, 0.4);
        background: linear-gradient(135deg, #00b4d8, #0096c7);
    }
    
    /* Dark Mode */
    body.dark-mode {
        background: var(--bg-primary);
    }
    
    body.dark-mode .my-feedback-header {
        background: linear-gradient(135deg, #023e8a 0%, #0077b6 50%, #03045e 100%);
    }
    
    body.dark-mode .stat-card,
    body.dark-mode .feedback-item,
    body.dark-mode .empty-state {
        background: rgba(30, 41, 59, 0.95);
        color: #e0e0e0;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4),
                    0 0 0 1px rgba(0, 180, 216, 0.2) inset;
    }
    
    body.dark-mode .stat-card:hover,
    body.dark-mode .feedback-item:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6),
                    0 0 30px rgba(128, 255, 219, 0.2);
    }
    
    body.dark-mode .feedback-title {
        color: #fff;
    }
    
    body.dark-mode .feedback-location,
    body.dark-mode .feedback-date {
        color: #aaa;
    }
    
    body.dark-mode .detail-label {
        color: #aaa;
    }
    
    body.dark-mode .detail-value {
        color: #ccc;
    }
    
    body.dark-mode .stat-card p {
        color: #aaa;
    }
    
    body.dark-mode .empty-state i {
        color: #00b4d8;
        opacity: 0.3;
    }
    
    body.dark-mode .admin-notes-box {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.2), rgba(255, 152, 0, 0.15));
        border-left-color: #ffc107;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25);
    }
    
    body.dark-mode .admin-notes-box h4 {
        color: #ffca28;
    }
    
    body.dark-mode .admin-notes-box p {
        color: #ffca28;
        background: rgba(255, 193, 7, 0.1);
        border-color: rgba(255, 193, 7, 0.3);
    }
    
    body.dark-mode .feedback-item.flagged {
        border-color: #ff6b6b;
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3),
                    0 0 0 1px rgba(255, 107, 107, 0.2) inset;
    }
    
    body.dark-mode .feedback-item.flagged:hover {
        box-shadow: 0 15px 40px rgba(255, 107, 107, 0.4),
                    0 0 30px rgba(255, 107, 107, 0.3);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .my-feedback-header h1 {
            font-size: 2.5rem;
        }
        
        .my-feedback-header p {
            font-size: 1.1rem;
        }
        
        .stats-summary {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .detail-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .feedback-header {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>
CSS];

include 'components/header.php';
?>

<section class="my-feedback-header">
    <h1>💬 My Reports</h1>
    <p>Welcome back, <?php echo htmlspecialchars($user['name']); ?>! Track all your feedback submissions here.</p>
</section>

<div class="feedback-container">
    <a href="/Webby/dashboard.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    
    <?php
    // Calculate statistics
    $total_submissions = count($my_feedback);
    $pending = 0;
    $reviewing = 0;
    $reviewed = 0;
    $flagged = 0;
    
    foreach ($my_feedback as $feedback) {
        $status = $feedback['status'] ?? 'Pending';
        if ($status === 'Pending') $pending++;
        if ($status === 'Reviewing') $reviewing++;
        if ($status === 'Reviewed') $reviewed++;
        if ($status === 'Flagged') $flagged++;
    }
    ?>
    
    <div class="stats-summary">
        <div class="stat-card">
            <h3><?php echo $total_submissions; ?></h3>
            <p>Total Submissions</p>
        </div>
        <div class="stat-card pending">
            <h3><?php echo $pending; ?></h3>
            <p>Pending</p>
        </div>
        <div class="stat-card reviewing">
            <h3><?php echo $reviewing; ?></h3>
            <p>Reviewing</p>
        </div>
        <div class="stat-card reviewed">
            <h3><?php echo $reviewed; ?></h3>
            <p>Reviewed</p>
        </div>
        <div class="stat-card flagged">
            <h3><?php echo $flagged; ?></h3>
            <p>Flagged</p>
        </div>
    </div>
    
    <?php if (empty($my_feedback)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Feedback Submissions Yet</h3>
            <p>You haven't submitted any feedback about river pollution. Start making a difference today!</p>
            <a href="/Webby/feedback.php" class="cta-button">Submit Your First Feedback</a>
        </div>
    <?php else: ?>
        <div class="feedback-list">
            <?php foreach ($my_feedback as $feedback): ?>
                <?php
                $status = $feedback['status'] ?? 'Pending';
                $statusClass = 'status-' . strtolower($status);
                $flaggedClass = ($status === 'Flagged') ? ' flagged' : '';
                $hasAdminNotes = !empty($feedback['admin_notes']);
                ?>
                <div class="feedback-item<?php echo $flaggedClass; ?>">
                    <div class="feedback-header">
                        <div>
                            <div class="feedback-title">
                                <i class="fas fa-water"></i>
                                <?php echo htmlspecialchars($feedback['river_name']); ?>
                                <?php if ($hasAdminNotes): ?>
                                    <span class="updated-badge">
                                        <i class="fas fa-bell"></i>
                                        Updated by Admin
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="feedback-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($feedback['location']); ?>
                            </div>
                            <div class="feedback-date">
                                <i class="fas fa-clock"></i>
                                <?php echo date('F j, Y - g:i A', strtotime($feedback['created_at'])); ?>
                            </div>
                        </div>
                        <div>
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <?php if ($status === 'Pending'): ?>
                                    <i class="fas fa-hourglass-half"></i>
                                <?php elseif ($status === 'Reviewing'): ?>
                                    <i class="fas fa-eye"></i>
                                <?php elseif ($status === 'Reviewed'): ?>
                                    <i class="fas fa-check-double"></i>
                                <?php elseif ($status === 'Flagged'): ?>
                                    <i class="fas fa-flag"></i>
                                <?php endif; ?>
                                <?php echo $status; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="feedback-content">
                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="fas fa-flask"></i> Pollution Type:
                            </span>
                            <span class="detail-value">
                                <span class="pollution-badge"><?php echo htmlspecialchars($feedback['pollution_type']); ?></span>
                            </span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="fas fa-comment-dots"></i> Description:
                            </span>
                            <span class="detail-value">
                                <?php echo nl2br(htmlspecialchars($feedback['issue_description'])); ?>
                            </span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="fas fa-venus-mars"></i> Gender:
                            </span>
                            <span class="detail-value">
                                <?php echo htmlspecialchars($feedback['gender'] ?: 'Not specified'); ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($feedback['photo']) && file_exists(__DIR__ . '/' . $feedback['photo'])): ?>
                        <div class="feedback-photo">
                            <div class="detail-label" style="margin-bottom: 12px;">
                                <i class="fas fa-image"></i> Uploaded Photo:
                            </div>
                            <img src="/Webby/<?php echo htmlspecialchars($feedback['photo']); ?>" 
                                 alt="Pollution evidence" 
                                 class="photo-preview"
                                 onclick="window.open(this.src, '_blank')"
                                 title="Click to view full size">
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($hasAdminNotes): ?>
                        <div class="admin-notes-box">
                            <h4>
                                <i class="fas fa-sticky-note"></i>
                                Admin Notes
                            </h4>
                            <p><?php echo nl2br(htmlspecialchars($feedback['admin_notes'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'components/footer.php'; ?>
