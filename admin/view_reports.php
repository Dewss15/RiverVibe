<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Webby/login.php");
    exit;
}

$page_title = "Manage Reports - Admin";

require_once __DIR__ . '/../components/db_connect.php';

// Get all reports with user information
$sql = "SELECT r.*, u.name, u.email 
        FROM river_reports r 
        LEFT JOIN users u ON r.user_id = u.id 
        ORDER BY r.date_submitted DESC";
$result = $conn->query($sql);
$reports = $result->fetch_all(MYSQLI_ASSOC);

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
        text-align: center;
        font-size: 1.15rem;
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
    
    .reports-table {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 150, 199, 0.1),
                    0 0 0 1px rgba(0, 180, 216, 0.1) inset;
        overflow-x: auto;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    thead {
        background: linear-gradient(135deg, #0096c7 0%, #00b4d8 100%);
        color: white;
    }
    
    th, td {
        padding: 18px;
        text-align: left;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    tbody tr {
        transition: all 0.3s ease;
    }
    
    tbody tr:hover {
        background: rgba(0, 180, 216, 0.03);
        transform: scale(1.005);
    }
    
    .badge {
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
    }
    
    .badge.pending { 
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .badge.in-progress { 
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 4px 15px rgba(0, 180, 216, 0.4); }
        50% { box-shadow: 0 4px 25px rgba(0, 180, 216, 0.6), 0 0 20px rgba(0, 180, 216, 0.3); }
    }
    
    .badge.resolved { 
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .action-btns {
        display: flex;
        gap: 10px;
    }
    
    .btn {
        padding: 10px 18px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 150, 199, 0.3);
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }
    
    .btn:hover {
        transform: translateY(-3px) scale(1.05);
    }
    
    .btn-edit:hover {
        box-shadow: 0 8px 20px rgba(0, 180, 216, 0.4);
    }
    
    .btn-delete:hover {
        box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
    }
    
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }
    
    .empty-state i {
        font-size: 5rem;
        color: rgba(0, 150, 199, 0.2);
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #666;
        font-size: 1.1rem;
    }
    
    /* Dark mode support */
    body.dark-mode {
        background: var(--bg-primary);
    }
    
    body.dark-mode .reports-table {
        background: rgba(26, 26, 26, 0.95);
        box-shadow: 0 8px 25px rgba(0, 150, 199, 0.15),
                    0 0 0 1px rgba(0, 180, 216, 0.15) inset;
    }
    
    body.dark-mode tbody tr {
        color: #e0e0e0;
    }
    
    body.dark-mode tbody tr:hover {
        background: rgba(0, 180, 216, 0.08);
    }
    
    body.dark-mode td {
        border-bottom-color: rgba(255, 255, 255, 0.05);
    }
    
    body.dark-mode .empty-state h3 {
        color: #e0e0e0;
    }
    
    body.dark-mode .empty-state p {
        color: #b0b0b0;
    }
</style>
CSS];

include __DIR__ . '/../components/header.php';
?>

<section class="admin-header">
    <h1>📋 Manage Reports</h1>
    <p>View and manage all pollution reports</p>
</section>

<div class="admin-container">
    <a href="/Webby/admin/index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    
    <div class="reports-table">
        <?php if (empty($reports)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list" style="font-size: 4rem; color: #ccc;"></i>
                <h3>No Reports Yet</h3>
                <p>No pollution reports have been submitted.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>River Name</th>
                        <th>Location</th>
                        <th>Pollution Type</th>
                        <th>Status</th>
                        <th>Reported By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td>#<?php echo $report['id']; ?></td>
                            <td><?php echo htmlspecialchars($report['river_name']); ?></td>
                            <td><?php echo htmlspecialchars($report['location']); ?></td>
                            <td><?php echo htmlspecialchars($report['pollution_type']); ?></td>
                            <td>
                                <span class="badge <?php echo strtolower(str_replace(' ', '-', $report['status'])); ?>">
                                    <?php echo $report['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $report['reported_by'] ? htmlspecialchars($report['reported_by']) : ($report['name'] ? htmlspecialchars($report['name']) : 'Anonymous'); ?>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($report['date_submitted'])); ?></td>
                            <td>
                                <div class="action-btns">
                                    <a href="/Webby/admin/edit_report.php?id=<?php echo $report['id']; ?>" 
                                       class="btn btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/Webby/admin/delete_report.php?id=<?php echo $report['id']; ?>" 
                                       class="btn btn-delete" 
                                       onclick="return confirm('Are you sure you want to delete this report?')" 
                                       title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
