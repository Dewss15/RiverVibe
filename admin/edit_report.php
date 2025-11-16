<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Webby/login.php");
    exit;
}

require_once __DIR__ . '/../components/db_connect.php';

$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $pollution_type = $_POST['pollution_type'];
    $description = $_POST['description'];
    
    $stmt = $conn->prepare("UPDATE river_reports SET status = ?, pollution_type = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sssi", $status, $pollution_type, $description, $report_id);
    
    if ($stmt->execute()) {
        $success_message = "Report updated successfully!";
    } else {
        $error_message = "Error updating report.";
    }
    $stmt->close();
}

// Fetch report data
$stmt = $conn->prepare("SELECT * FROM river_reports WHERE id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();
$stmt->close();

if (!$report) {
    header("Location: /Webby/admin/view_reports.php");
    exit;
}

$page_title = "Edit Report #" . $report_id;

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
        max-width: 1200px;
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
    
    .edit-form {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 8px 25px rgba(0, 150, 199, 0.1),
                    0 0 0 1px rgba(0, 180, 216, 0.1) inset;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .edit-form::before {
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
    
    .edit-form:hover::before {
        opacity: 1;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 10px;
        color: #1a1a2e;
        font-size: 1.05rem;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid rgba(0, 150, 199, 0.15);
        border-radius: 12px;
        font-size: 1rem;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.7);
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0096c7;
        box-shadow: 0 0 0 4px rgba(0, 150, 199, 0.1);
        background: white;
        transform: translateY(-2px);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        padding: 16px 40px;
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0, 150, 199, 0.3);
        font-family: 'Poppins', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 180, 216, 0.4);
        background: linear-gradient(135deg, #00b4d8, #0096c7);
    }
    
    .submit-btn:active {
        transform: translateY(-1px);
    }
    
    .success-message {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: 2px solid #28a745;
        color: #155724;
        padding: 18px 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.4s ease-out;
    }
    
    .error-message {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        border: 2px solid #dc3545;
        color: #721c24;
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
    
    .info-box {
        background: linear-gradient(135deg, rgba(0, 180, 216, 0.08), rgba(0, 150, 199, 0.05));
        border: 2px solid rgba(0, 150, 199, 0.2);
        padding: 20px 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0, 150, 199, 0.1);
    }
    
    .info-box p {
        margin: 8px 0;
        color: #004085;
        font-size: 1rem;
        font-weight: 500;
    }
    
    .info-box p strong {
        color: #0096c7;
        font-weight: 600;
    }
    
    /* Dark mode support */
    body.dark-mode {
        background: #0d1117;
    }
    
    body.dark-mode .admin-header {
        background: linear-gradient(135deg, #0f4c75 0%, #1b262c 100%);
    }
    
    body.dark-mode .edit-form {
        background: rgba(26, 30, 44, 0.95);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4),
                    0 0 0 1px rgba(0, 180, 216, 0.15) inset;
    }
    
    body.dark-mode .info-box {
        background: linear-gradient(135deg, rgba(0, 150, 199, 0.15), rgba(0, 180, 216, 0.1));
        border-color: rgba(0, 180, 216, 0.3);
    }
    
    body.dark-mode .info-box p {
        color: #b3d9ff;
    }
    
    body.dark-mode .info-box p strong {
        color: #00b4d8;
    }
    
    body.dark-mode .form-group label {
        color: #171616ff;
    }
    
    body.dark-mode .form-group input,
    body.dark-mode .form-group select,
    body.dark-mode .form-group textarea {
        background: rgba(13, 17, 23, 0.6);
        color: #e0e0e0;
        border-color: rgba(0, 150, 199, 0.25);
    }
    
    body.dark-mode .form-group input:focus,
    body.dark-mode .form-group select:focus,
    body.dark-mode .form-group textarea:focus {
        background: rgba(13, 17, 23, 0.8);
        border-color: #00b4d8;
    }
    
    body.dark-mode .back-link {
        background: linear-gradient(135deg, #0f4c75, #1b7fa8);
    }
    
    body.dark-mode .back-link:hover {
        background: linear-gradient(135deg, #1b7fa8, #0f4c75);
    }
</style>
CSS];

include __DIR__ . '/../components/header.php';
?>

<section class="admin-header">
    <h1><i class="fas fa-edit"></i> Edit Report</h1>
    <p>Update pollution report details and status</p>
</section>

<div class="admin-container">
    <a href="/Webby/admin/view_reports.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
    
    <?php if ($success_message): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <div class="info-box">
        <p><strong>Report ID:</strong> #<?php echo $report['id']; ?></p>
        <p><strong>River Name:</strong> <?php echo htmlspecialchars($report['river_name']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
        <p><strong>Submitted Date:</strong> <?php echo date('F j, Y', strtotime($report['date_submitted'])); ?></p>
    </div>
    
    <div class="edit-form">
        <form method="POST">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="Pending" <?php echo $report['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Verified" <?php echo $report['status'] === 'Verified' ? 'selected' : ''; ?>>Verified</option>
                    <option value="Resolved" <?php echo $report['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pollution_type">Pollution Type</label>
                <select name="pollution_type" id="pollution_type" required>
                    <option value="Plastic" <?php echo $report['pollution_type'] === 'Plastic' ? 'selected' : ''; ?>>Plastic</option>
                    <option value="Chemical" <?php echo $report['pollution_type'] === 'Chemical' ? 'selected' : ''; ?>>Chemical</option>
                    <option value="Sewage" <?php echo $report['pollution_type'] === 'Sewage' ? 'selected' : ''; ?>>Sewage</option>
                    <option value="Industrial" <?php echo $report['pollution_type'] === 'Industrial' ? 'selected' : ''; ?>>Industrial</option>
                    <option value="Agricultural" <?php echo $report['pollution_type'] === 'Agricultural' ? 'selected' : ''; ?>>Agricultural</option>
                    <option value="Other" <?php echo $report['pollution_type'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required><?php echo htmlspecialchars($report['description']); ?></textarea>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Update Report
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
