<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if ($id && in_array($action, ['approve','reject'])) {
    $status = $action === 'approve' ? 'approved' : 'rejected';
    $sql = "UPDATE event_requests SET status='$status' WHERE id=$id LIMIT 1";
    mysqli_query($conn, $sql);
}

header('Location: requests.php');
exit;
