<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Webby/login.php");
    exit;
}

$page_title = "View Feedback - Admin";

// IMPORTANT: Set $additional_css BEFORE including header.php
$additional_css = [<<<CSS
<style>
    body {
        background: var(--bg-primary);
    }
    
    .admin-header {
        position: relative;
        background: linear-gradient(135deg, #0096c7 0%, #00b4d8 50%, #023e8a 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        margin-bottom: 0;
        box-shadow: 0 10px 40px rgba(0, 150, 199, 0.3);
        overflow: hidden;
    }
    
    .admin-header::before {
        content: \"\";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(128, 255, 219, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 50%, rgba(0, 180, 216, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .admin-header h1 {
        margin: 0;
        font-size: 2.8rem;
        font-weight: 700;
        background: linear-gradient(135deg, #ffffff 0%, #80ffdb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 20px rgba(128, 255, 219, 0.3);
        position: relative;
        z-index: 1;
    }
    
    .admin-header p {
        margin: 15px auto 0 auto;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.15rem;
        text-align: center;
        font-weight: 400;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
    }
    
    .admin-container {
        max-width: 1400px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .back-link {
        display: inline-block;
        margin-bottom: 25px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        font-size: 1.05rem;
        box-shadow: 0 4px 12px rgba(0, 150, 199, 0.3);
    }
    
    .back-link:hover {
        background: linear-gradient(135deg, #00b4d8, #0096c7);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 180, 216, 0.4);
    }
    
    .back-link i {
        margin-right: 8px;
    }
    
    /* Success/Error Messages */
    .alert-message {
        padding: 18px 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.4s ease-out;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: 2px solid #28a745;
        color: #155724;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        border: 2px solid #dc3545;
        color: #721c24;
    }
    
    .feedback-grid {
        display: grid;
        gap: 30px;
    }
    
    .feedback-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 150, 199, 0.1),
                    0 0 0 1px rgba(0, 180, 216, 0.1) inset;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .feedback-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 180, 216, 0.02) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .feedback-card:hover::before {
        opacity: 1;
    }
    
    .feedback-card:hover {
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
        font-size: 1.5rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 8px;
    }
    
    .feedback-meta {
        color: #666;
        font-size: 0.95rem;
        margin: 5px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .feedback-meta i {
        color: #0096c7;
        font-size: 0.9rem;
    }
    
    /* Status Badge */
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
        grid-template-columns: 160px 1fr;
        padding: 10px 0;
        font-size: 0.98rem;
        gap: 15px;
    }
    
    .detail-label {
        font-weight: 700;
        color: #555;
    }
    
    .detail-value {
        color: #333;
        word-wrap: break-word;
        line-height: 1.6;
    }
    
    .photo-preview {
        max-width: 350px;
        width: 100%;
        border-radius: 15px;
        margin-top: 10px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        transition: transform 0.3s;
    }
    
    .photo-preview:hover {
        transform: scale(1.05);
    }
    
    /* Admin Action Buttons */
    .admin-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 1;
    }
    
    .action-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
        z-index: 0;
    }
    
    .action-btn:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .action-btn i {
        position: relative;
        z-index: 1;
    }
    
    .action-btn span {
        position: relative;
        z-index: 1;
    }
    
    .action-btn:hover {
        transform: translateY(-3px) scale(1.05);
    }
    
    .btn-reviewing {
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
    }
    
    .btn-reviewing:hover {
        box-shadow: 0 8px 25px rgba(0, 180, 216, 0.4);
    }
    
    .btn-reviewed {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .btn-reviewed:hover {
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    }
    
    .btn-flagged {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    .btn-flagged:hover {
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
    }
    
    .btn-notes {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: #222;
    }
    
    .btn-notes:hover {
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
    }
    
    .btn-pending {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .btn-pending:hover {
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
    }
    
    /* Admin Notes Section */
    .admin-notes-section {
        margin-top: 20px;
        padding: 18px;
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1));
        border-left: 4px solid #ffc107;
        border-radius: 12px;
    }
    
    .admin-notes-section h4 {
        margin: 0 0 10px 0;
        color: #856404;
        font-size: 1rem;
        font-weight: 700;
    }
    
    .admin-notes-section p {
        margin: 0;
        color: #856404;
        line-height: 1.6;
    }
    
    /* Modal for Admin Notes */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }
    
    .modal-container {
        background: white;
        border-radius: 24px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    
    .modal-overlay.active .modal-container {
        transform: scale(1);
    }
    
    .modal-header {
        padding: 25px 30px;
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        border-radius: 24px 24px 0 0;
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 1.3rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 30px;
    }
    
    .modal-body textarea {
        width: 100%;
        min-height: 150px;
        padding: 15px;
        border: 2px solid rgba(0, 150, 199, 0.2);
        border-radius: 12px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: all 0.3s;
    }
    
    .modal-body textarea:focus {
        outline: none;
        border-color: #0096c7;
        box-shadow: 0 0 0 3px rgba(0, 150, 199, 0.1);
    }
    
    .modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }
    
    .modal-btn {
        flex: 1;
        padding: 14px 24px;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .modal-btn-save {
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
    }
    
    .modal-btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 150, 199, 0.4);
    }
    
    .modal-btn-cancel {
        background: #e9ecef;
        color: #333;
    }
    
    .modal-btn-cancel:hover {
        background: #dee2e6;
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
        font-size: 1.5rem;
    }
    
    .empty-state p {
        color: #777;
        font-size: 1.05rem;
    }
    
    /* Dark mode support */
    body.dark-mode {
        background: var(--bg-primary);
    }
    
    body.dark-mode .admin-header {
        background: linear-gradient(135deg, #023e8a 0%, #0077b6 50%, #03045e 100%);
    }
    
    body.dark-mode .feedback-card {
        background: rgba(30, 41, 59, 0.95);
        color: #e0e0e0;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4),
                    0 0 0 1px rgba(0, 180, 216, 0.2) inset;
    }
    
    body.dark-mode .feedback-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6),
                    0 0 30px rgba(128, 255, 219, 0.2);
    }
    
    body.dark-mode .feedback-title {
        color: #fff;
    }
    
    body.dark-mode .detail-label {
        color: #aaa;
    }
    
    body.dark-mode .detail-value {
        color: #ccc;
    }
    
    body.dark-mode .empty-state {
        background: rgba(30, 41, 59, 0.95);
    }
    
    body.dark-mode .empty-state i {
        color: #00b4d8;
        opacity: 0.3;
    }
    
    body.dark-mode .empty-state h3 {
        color: #ccc;
    }
    
    body.dark-mode .empty-state p {
        color: #999;
    }
    
    body.dark-mode .modal-container {
        background: rgba(30, 41, 59, 0.98);
        color: #e0e0e0;
    }
    
    body.dark-mode .modal-body textarea {
        background: rgba(15, 23, 42, 0.8);
        color: #e0e0e0;
        border-color: rgba(0, 180, 216, 0.3);
    }
    
    body.dark-mode .modal-btn-cancel {
        background: #2a3f5f;
        color: #e0e0e0;
    }
    
    body.dark-mode .admin-notes-section {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.15), rgba(255, 152, 0, 0.15));
        border-left-color: #ffc107;
    }
    
    body.dark-mode .admin-notes-section h4,
    body.dark-mode .admin-notes-section p {
        color: #ffca28;
    }
    
    body.dark-mode .alert-success {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.3), rgba(32, 201, 151, 0.3));
        color: #81c784;
        border-color: #28a745;
    }
    
    body.dark-mode .alert-error {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.3), rgba(200, 35, 51, 0.3));
        color: #e57373;
        border-color: #dc3545;
    }
</style>
CSS];

require_once __DIR__ . '/../components/db_connect.php';

// Get all feedback with status column
$sql = "SELECT * FROM feedback ORDER BY CASE status WHEN 'Pending' THEN 1 WHEN 'Reviewing' THEN 2 WHEN 'Flagged' THEN 3 WHEN 'Reviewed' THEN 4 END, created_at DESC";
$result = $conn->query($sql);
$feedback_list = $result->fetch_all(MYSQLI_ASSOC);

include __DIR__ . '/../components/header.php';
?>

<section class="admin-header">
    <h1>💬 Feedback Review System</h1>
    <p>Manage and review all feedback submissions</p>
</section>

<div class="admin-container">
    <a href="/Webby/admin/index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert-message alert-success">
            <i class="fas fa-check-circle"></i>
            <?php 
            echo $_SESSION['success_message']; 
            unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert-message alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <?php 
            echo $_SESSION['error_message']; 
            unset($_SESSION['error_message']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($feedback_list)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Feedback Yet</h3>
            <p>No feedback has been submitted. Check back later!</p>
        </div>
    <?php else: ?>
        <div class="feedback-grid">
            <?php foreach ($feedback_list as $feedback): ?>
                <?php
                $status = $feedback['status'] ?? 'Pending';
                $statusClass = 'status-' . strtolower($status);
                ?>
                <div class="feedback-card">
                    <div class="feedback-header">
                        <div>
                            <div class="feedback-title">
                                <i class="fas fa-water"></i> 
                                <?php echo htmlspecialchars($feedback['river_name']); ?>
                            </div>
                            <div class="feedback-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($feedback['location']); ?>
                            </div>
                            <div class="feedback-meta">
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
                            <span class="detail-label"><i class="fas fa-user"></i> Name:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($feedback['name'] ?: 'Anonymous'); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-envelope"></i> Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($feedback['email'] ?: 'Not provided'); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-venus-mars"></i> Gender:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($feedback['gender'] ?: 'Not specified'); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-flask"></i> Pollution Type:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($feedback['pollution_type']); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-comment-dots"></i> Description:</span>
                            <span class="detail-value"><?php echo nl2br(htmlspecialchars($feedback['issue_description'])); ?></span>
                        </div>
                        
                        <?php if (!empty($feedback['photo']) && file_exists(__DIR__ . '/../' . $feedback['photo'])): ?>
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-image"></i> Photo:</span>
                            <span class="detail-value">
                                <img src="/Webby/<?php echo htmlspecialchars($feedback['photo']); ?>" 
                                     alt="Pollution photo" 
                                     class="photo-preview"
                                     onclick="window.open(this.src, '_blank')">
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($feedback['admin_notes'])): ?>
                        <div class="admin-notes-section">
                            <h4><i class="fas fa-sticky-note"></i> Admin Notes:</h4>
                            <p><?php echo nl2br(htmlspecialchars($feedback['admin_notes'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Admin Action Buttons -->
                    <div class="admin-actions">
                        <?php if ($status !== 'Reviewing'): ?>
                        <a href="/Webby/admin/update_feedback_status.php?id=<?php echo $feedback['id']; ?>&status=Reviewing" 
                           class="action-btn btn-reviewing"
                           onclick="return confirm('Mark this feedback as Reviewing?')">
                            <i class="fas fa-eye"></i>
                            <span>Mark Reviewing</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($status !== 'Reviewed'): ?>
                        <a href="/Webby/admin/update_feedback_status.php?id=<?php echo $feedback['id']; ?>&status=Reviewed" 
                           class="action-btn btn-reviewed"
                           onclick="return confirm('Mark this feedback as Reviewed?')">
                            <i class="fas fa-check-double"></i>
                            <span>Mark Reviewed</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($status !== 'Flagged'): ?>
                        <a href="/Webby/admin/update_feedback_status.php?id=<?php echo $feedback['id']; ?>&status=Flagged" 
                           class="action-btn btn-flagged"
                           onclick="return confirm('Flag this feedback? This will notify the user.')">
                            <i class="fas fa-flag"></i>
                            <span>Flag Feedback</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($status !== 'Pending'): ?>
                        <a href="/Webby/admin/update_feedback_status.php?id=<?php echo $feedback['id']; ?>&status=Pending" 
                           class="action-btn btn-pending"
                           onclick="return confirm('Reset to Pending status?')">
                            <i class="fas fa-undo"></i>
                            <span>Reset to Pending</span>
                        </a>
                        <?php endif; ?>
                        
                        <button class="action-btn btn-notes" 
                                onclick="openNotesModal(<?php echo $feedback['id']; ?>, '<?php echo htmlspecialchars($feedback['river_name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($feedback['admin_notes'] ?? '', ENT_QUOTES); ?>', '<?php echo htmlspecialchars($status, ENT_QUOTES); ?>')">
                            <i class="fas fa-sticky-note"></i>
                            <span><?php echo empty($feedback['admin_notes']) ? 'Add Notes' : 'Edit Notes'; ?></span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Admin Notes Modal -->
<div id="notesModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-sticky-note"></i> Admin Notes</h3>
            <button class="modal-close" onclick="closeNotesModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="notesForm" method="POST" action="/Webby/admin/update_feedback_status.php">
                <input type="hidden" name="id" id="feedbackId">
                <input type="hidden" name="status" id="feedbackStatus">
                <p style="margin-bottom: 15px; color: #666; font-size: 0.95rem;">
                    <strong>Feedback:</strong> <span id="feedbackRiverName"></span>
                </p>
                <textarea name="admin_notes" 
                          id="adminNotesTextarea" 
                          placeholder="Enter admin notes here... (e.g., action taken, follow-up required, reason for flagging)"
                          required></textarea>
                <div class="modal-actions">
                    <button type="submit" class="modal-btn modal-btn-save">
                        <i class="fas fa-save"></i> Save Notes
                    </button>
                    <button type="button" class="modal-btn modal-btn-cancel" onclick="closeNotesModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openNotesModal(feedbackId, riverName, existingNotes, currentStatus) {
    document.getElementById('feedbackId').value = feedbackId;
    document.getElementById('feedbackStatus').value = currentStatus || 'Pending';
    document.getElementById('feedbackRiverName').textContent = riverName;
    document.getElementById('adminNotesTextarea').value = existingNotes;
    
    const modal = document.getElementById('notesModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeNotesModal() {
    const modal = document.getElementById('notesModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal on overlay click
document.getElementById('notesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNotesModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNotesModal();
    }
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
