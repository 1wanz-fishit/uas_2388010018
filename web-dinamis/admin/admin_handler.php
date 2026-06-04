<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die("Unauthorized access.");
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add_team') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $game_info = $_POST['game_info'] ?? '';
    $achievements = $_POST['achievements'] ?? '';
    $personal_data = $_POST['personal_data'] ?? '';
    
    $photo_path = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = time() . '_' . basename($_FILES['photo']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_path = 'uploads/' . $filename;
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO teams (name, description, photo_path, game_info, achievements, personal_data) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $photo_path, $game_info, $achievements, $personal_data]);
    
    header("Location: index.php?success=Team member added successfully");
    exit;

} elseif ($action === 'edit_team') {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $game_info = $_POST['game_info'] ?? '';
    $achievements = $_POST['achievements'] ?? '';
    $personal_data = $_POST['personal_data'] ?? '';
    
    // Check if new photo is uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = time() . '_' . basename($_FILES['photo']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_path = 'uploads/' . $filename;
            $stmt = $pdo->prepare("UPDATE teams SET name=?, description=?, photo_path=?, game_info=?, achievements=?, personal_data=? WHERE id=?");
            $stmt->execute([$name, $description, $photo_path, $game_info, $achievements, $personal_data, $id]);
        }
    } else {
        $stmt = $pdo->prepare("UPDATE teams SET name=?, description=?, game_info=?, achievements=?, personal_data=? WHERE id=?");
        $stmt->execute([$name, $description, $game_info, $achievements, $personal_data, $id]);
    }
    
    header("Location: index.php?success=Team member updated successfully");
    exit;

} elseif ($action === 'delete_team') {
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM teams WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?success=Team member deleted");
    exit;

} elseif ($action === 'add_tournament') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $details = $_POST['details'] ?? '';
    
    $stmt = $pdo->prepare("INSERT INTO tournaments (title, date, details) VALUES (?, ?, ?)");
    $stmt->execute([$title, $date, $details]);
    
    header("Location: index.php?success=Tournament added successfully");
    exit;

} elseif ($action === 'edit_tournament') {
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $details = $_POST['details'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE tournaments SET title=?, date=?, details=? WHERE id=?");
    $stmt->execute([$title, $date, $details, $id]);
    
    header("Location: index.php?success=Tournament updated successfully");
    exit;

} elseif ($action === 'delete_tournament') {
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM tournaments WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?success=Tournament deleted");
    exit;
}
?>
