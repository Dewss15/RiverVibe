<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Webby/login.php");
    exit;
}

$page_title = "Manage Reports - Admin";

// No additional external libraries needed

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
    
    /* Add New Report Button */
    .btn-add-new {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }
    
    .btn-add-new:hover {
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    }
    
    /* Modal Styles - Modern Glassmorphism Design */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 9999;
        animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .modal-overlay.active {
        display: flex;
        justify-content: center;
        align-items: center;
        overflow-y: auto;
        padding: 20px;
    }
    
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            backdrop-filter: blur(0px);
        }
        to { 
            opacity: 1; 
            backdrop-filter: blur(10px);
        }
    }
    
    .modal-container {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        box-shadow: 0 24px 80px rgba(0, 150, 199, 0.25),
                    0 0 0 1px rgba(0, 180, 216, 0.1) inset;
        max-width: 750px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 180, 216, 0.2);
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(60px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    body.dark-mode .modal-overlay {
        background: rgba(0, 0, 0, 0.85);
    }
    
    body.dark-mode .modal-container {
        background: rgba(26, 26, 26, 0.98);
        box-shadow: 0 24px 80px rgba(0, 150, 199, 0.4),
                    0 0 0 1px rgba(0, 180, 216, 0.2) inset;
        border: 1px solid rgba(0, 180, 216, 0.3);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #0096c7 0%, #00b4d8 50%, #48cae4 100%);
        color: white;
        padding: 28px 32px;
        border-radius: 24px 24px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0, 150, 199, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(128, 255, 219, 0.15) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .modal-header h2 {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 800;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 12px;
        color:#ffffff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
    }
    
    .modal-close {
        background: rgba(255, 255, 255, 0.25);
        border: none;
        color: white;
        font-size: 1.5rem;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }
    
    .modal-close:active {
        transform: rotate(90deg) scale(0.95);
    }
    
    .modal-body {
        padding: 32px 36px 36px 36px;
    }
    
    body.dark-mode .modal-body {
        background: rgba(26, 26, 26, 0.5);
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.98rem;
        letter-spacing: 0.3px;
        transition: color 0.3s ease;
    }
    
    body.dark-mode .form-group label {
        color: #1c1e22;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid rgba(0, 150, 199, 0.2);
        border-radius: 14px;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
        background: rgba(248, 249, 250, 0.8);
        color: #1a1a1a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #6c757d;
        opacity: 0.8;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #00b4d8;
        box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.15),
                    0 4px 12px rgba(0, 150, 199, 0.1);
        transform: translateY(-1px);
    }
    
    body.dark-mode .form-group input,
    body.dark-mode .form-group select,
    body.dark-mode .form-group textarea {
        background: rgba(42, 42, 42, 0.9);
        border-color: rgba(0, 180, 216, 0.3);
        color: #f0f0f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
    
    body.dark-mode .form-group input::placeholder,
    body.dark-mode .form-group textarea::placeholder {
        color: #9ca3af;
        opacity: 0.9;
    }
    
    body.dark-mode .form-group input:focus,
    body.dark-mode .form-group select:focus,
    body.dark-mode .form-group textarea:focus {
        border-color: #48cae4;
        box-shadow: 0 0 0 4px rgba(72, 202, 228, 0.2),
                    0 4px 12px rgba(0, 180, 216, 0.3);
        background: rgba(42, 42, 42, 1);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 110px;
        line-height: 1.6;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .modal-container {
            max-height: 95vh;
            border-radius: 20px;
        }
        
        .modal-body {
            padding: 24px 20px;
        }
    }
    
    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid rgba(0, 150, 199, 0.15);
    }
    
    body.dark-mode .form-actions {
        border-top-color: rgba(0, 180, 216, 0.2);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #0096c7 0%, #00b4d8 50%, #48cae4 100%);
        color: white;
        padding: 14px 36px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 16px rgba(0, 150, 199, 0.35);
        position: relative;
        overflow: hidden;
        letter-spacing: 0.5px;
    }
    
    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-submit:hover::before {
        left: 100%;
    }
    
    .btn-submit:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 24px rgba(0, 180, 216, 0.5);
    }
    
    .btn-submit:active {
        transform: translateY(-1px) scale(0.98);
    }
    
    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 4px 12px rgba(0, 150, 199, 0.2);
    }
    
    .btn-cancel {
        background: transparent;
        color: #6c757d;
        padding: 14px 36px;
        border: 2px solid #6c757d;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: 0.5px;
    }
    
    .btn-cancel:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(108, 117, 125, 0.3);
    }
    
    .btn-cancel:active {
        transform: translateY(0);
    }
    
    body.dark-mode .btn-cancel {
        color: #9ca3af;
        border-color: #6c757d;
    }
    
    body.dark-mode .btn-cancel:hover {
        background: #6c757d;
        color: white;
        border-color: #6c757d;
    }
    
    .alert {
        padding: 16px 22px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: none;
        font-weight: 600;
        font-size: 0.98rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .alert.show {
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-15px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border: 2px solid #28a745;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border: 2px solid #dc3545;
    }
    
    body.dark-mode .alert-success {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.2), rgba(40, 167, 69, 0.15));
        color: #80ffdb;
        border-color: #28a745;
    }
    
    body.dark-mode .alert-error {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.2), rgba(220, 53, 69, 0.15));
        color: #ff8fa3;
        border-color: #dc3545;
    }
    
    .file-upload-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }
    
    .file-upload-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }
    
    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 20px 24px;
        background: linear-gradient(135deg, rgba(0, 150, 199, 0.05), rgba(0, 180, 216, 0.08));
        border: 2px dashed rgba(0, 150, 199, 0.4);
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        font-weight: 600;
        color: #0096c7;
        position: relative;
        overflow: hidden;
    }
    
    .file-upload-label::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(0, 180, 216, 0.1);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }
    
    .file-upload-label:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .file-upload-label:hover {
        background: linear-gradient(135deg, rgba(0, 150, 199, 0.1), rgba(0, 180, 216, 0.15));
        border-color: #00b4d8;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 150, 199, 0.2);
    }
    
    .file-upload-label span {
        position: relative;
        z-index: 1;
    }
    
    .file-upload-label i {
        position: relative;
        z-index: 1;
        font-size: 1.3rem;
    }
    
    body.dark-mode .file-upload-label {
        background: linear-gradient(135deg, rgba(0, 150, 199, 0.1), rgba(0, 180, 216, 0.15));
        color: #48cae4;
        border-color: rgba(72, 202, 228, 0.5);
    }
    
    body.dark-mode .file-upload-label:hover {
        background: linear-gradient(135deg, rgba(0, 150, 199, 0.15), rgba(0, 180, 216, 0.2));
        border-color: #48cae4;
    }
    
    .file-name {
        margin-top: 10px;
        font-size: 0.92rem;
        color: #495057;
        font-weight: 600;
        padding: 8px 12px;
        background: rgba(0, 150, 199, 0.05);
        border-radius: 8px;
        display: inline-block;
    }
    
    body.dark-mode .file-name {
        color: #9ca3af;
        background: rgba(0, 180, 216, 0.1);
    }
    
    /* GPS Auto-Detect Button */
    .gps-detect-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, rgba(0, 150, 199, 0.1), rgba(0, 180, 216, 0.1));
        border: 2px solid rgba(0, 150, 199, 0.5);
        color: #0096c7;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin-top: 10px;
        box-shadow: 0 2px 8px rgba(0, 150, 199, 0.15);
        letter-spacing: 0.3px;
    }
    
    .gps-detect-btn:hover {
        background: linear-gradient(135deg, #0096c7, #00b4d8);
        color: white;
        border-color: #0096c7;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 150, 199, 0.35);
    }
    
    .gps-detect-btn:active {
        transform: translateY(0);
    }
    
    .gps-detect-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    body.dark-mode .gps-detect-btn {
        border-color: rgba(72, 202, 228, 0.6);
        color: #48cae4;
        background: linear-gradient(135deg, rgba(0, 150, 199, 0.15), rgba(0, 180, 216, 0.15));
    }
    
    body.dark-mode .gps-detect-btn:hover {
        background: linear-gradient(135deg, #00b4d8, #48cae4);
        color: #0a0a0a;
        border-color: #48cae4;
    }
    
    .coordinates-wrapper {
        position: relative;
    }
    
    .coordinates-wrapper input {
        margin-bottom: 4px;
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <a href="/Webby/admin/index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        <button onclick="openAddReportModal()" class="btn btn-add-new">
            <i class="fas fa-plus"></i> Add New Report
        </button>
    </div>
    
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

<!-- Add Report Modal -->
<div id="addReportModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2><i class="fas fa-plus-circle"></i> Add New Report</h2>
            <button class="modal-close" onclick="closeAddReportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="alertMessage" class="alert"></div>
            
            <form id="addReportForm" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label for="river_name">River Name *</label>
                        <input type="text" id="river_name" name="river_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location *</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="pollution_type">Pollution Type *</label>
                        <select id="pollution_type" name="pollution_type" required>
                            <option value="">Select Type</option>
                            <option value="chemical">Chemical</option>
                            <option value="sewage">Sewage</option>
                            <option value="plastic">Plastic</option>
                            <option value="industrial">Industrial</option>
                            <option value="domestic">Domestic</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="location_coordinates">Location Coordinates</label>
                        <div class="coordinates-wrapper">
                            <input type="text" id="location_coordinates" name="location_coordinates" 
                                   placeholder="e.g., 25.3176° N, 82.9739° E">
                            <button type="button" class="gps-detect-btn" onclick="detectGPS()" id="gpsBtn">
                                <i class="fas fa-location-crosshairs"></i> Auto-Detect
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_submitted">Date Submitted *</label>
                        <input type="date" id="date_submitted" name="date_submitted" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reported_by">Reported By</label>
                    <input type="text" id="reported_by" name="reported_by" 
                           placeholder="Name or ID of reporter">
                </div>
                
                <div class="form-group">
                    <label for="image">Upload Image</label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="image" name="image" accept="image/*" 
                               onchange="updateFileName(this)">
                        <label for="image" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to upload image</span>
                        </label>
                    </div>
                    <div id="fileName" class="file-name"></div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddReportModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-save"></i> Add Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set today's date as default
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date_submitted').value = today;
});

// Open modal
function openAddReportModal() {
    document.getElementById('addReportModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Close modal
function closeAddReportModal() {
    document.getElementById('addReportModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('addReportForm').reset();
    document.getElementById('fileName').textContent = '';
    hideAlert();
}

// Update file name display
function updateFileName(input) {
    const fileName = input.files[0]?.name || '';
    const fileNameDisplay = document.getElementById('fileName');
    if (fileName) {
        fileNameDisplay.textContent = `Selected: ${fileName}`;
        fileNameDisplay.style.color = '#28a745';
    } else {
        fileNameDisplay.textContent = '';
    }
}

// Show alert
function showAlert(message, type) {
    const alert = document.getElementById('alertMessage');
    alert.textContent = message;
    alert.className = `alert alert-${type} show`;
}

// Hide alert
function hideAlert() {
    const alert = document.getElementById('alertMessage');
    alert.classList.remove('show');
}

// Detect GPS coordinates
function detectGPS() {
    const gpsBtn = document.getElementById('gpsBtn');
    const coordsInput = document.getElementById('location_coordinates');
    
    // Check if geolocation is supported
    if (!navigator.geolocation) {
        showAlert('❌ Geolocation is not supported by your browser', 'error');
        return;
    }
    
    // Disable button and show loading
    gpsBtn.disabled = true;
    gpsBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detecting...';
    
    // Get current position
    navigator.geolocation.getCurrentPosition(
        // Success callback
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Format coordinates
            const latDir = lat >= 0 ? 'N' : 'S';
            const lngDir = lng >= 0 ? 'E' : 'W';
            const formattedCoords = `${Math.abs(lat).toFixed(4)}° ${latDir}, ${Math.abs(lng).toFixed(4)}° ${lngDir}`;
            
            // Set value
            coordsInput.value = formattedCoords;
            
            // Show success
            showAlert('✅ GPS coordinates detected successfully!', 'success');
            setTimeout(hideAlert, 3000);
            
            // Re-enable button
            gpsBtn.disabled = false;
            gpsBtn.innerHTML = '<i class="fas fa-location-crosshairs"></i> Auto-Detect';
        },
        // Error callback
        function(error) {
            let errorMessage = 'Unable to access location. ';
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Please allow GPS access.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Location information unavailable.';
                    break;
                case error.TIMEOUT:
                    errorMessage += 'Request timed out.';
                    break;
                default:
                    errorMessage += 'An unknown error occurred.';
            }
            
            showAlert('❌ ' + errorMessage, 'error');
            setTimeout(hideAlert, 4000);
            
            // Re-enable button
            gpsBtn.disabled = false;
            gpsBtn.innerHTML = '<i class="fas fa-location-crosshairs"></i> Auto-Detect';
        },
        // Options
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

// Handle form submission
document.getElementById('addReportForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    
    // Prepare form data
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/Webby/admin/api_add_report.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('✅ Report added successfully!', 'success');
            
            // Wait 1 second then reload page
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('❌ Error: ' + (data.error || 'Failed to add report'), 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('❌ Network error. Please try again.', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Close modal on backdrop click
document.getElementById('addReportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddReportModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddReportModal();
    }
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
