<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die("Unauthorized access.");
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM teams WHERE id = ?");
$stmt->execute([$id]);
$team = $stmt->fetch();

if (!$team) {
    die("Team member not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Member - NEXUS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0b0f19; color: #fff; font-family: 'Rajdhani', sans-serif; }
        .heading-font { font-family: 'Orbitron', sans-serif; }
        .cyber-input { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(0, 243, 255, 0.3); color: #fff; transition: all 0.3s ease; }
        .cyber-input:focus { border-color: #00f3ff; box-shadow: 0 0 10px rgba(0,243,255,0.2); outline: none; }
    </style>
</head>
<body class="p-8">
    <div class="max-w-2xl mx-auto bg-slate-900/50 backdrop-blur border border-cyan-500/30 p-8 rounded-2xl shadow-lg shadow-cyan-500/10">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-cyan-400 heading-font">EDIT MEMBER</h1>
            <a href="index.php" class="text-slate-400 hover:text-white underline">Back to Dashboard</a>
        </div>
        
        <form action="admin_handler.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit_team">
            <input type="hidden" name="id" value="<?= htmlspecialchars($team['id']) ?>">
            
            <div class="mb-4">
                <label class="block mb-2 text-cyan-200">Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($team['name']) ?>" class="w-full cyber-input p-3 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-cyan-200">Role / Game Info</label>
                <input type="text" name="game_info" value="<?= htmlspecialchars($team['game_info']) ?>" class="w-full cyber-input p-3 rounded">
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-cyan-200">Description</label>
                <textarea name="description" class="w-full cyber-input p-3 rounded" rows="3" required><?= htmlspecialchars($team['description']) ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-cyan-200">Achievements</label>
                <textarea name="achievements" class="w-full cyber-input p-3 rounded" rows="3"><?= htmlspecialchars($team['achievements']) ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-cyan-200">Personal Data</label>
                <textarea name="personal_data" class="w-full cyber-input p-3 rounded" rows="3"><?= htmlspecialchars($team['personal_data']) ?></textarea>
            </div>
            <div class="mb-8">
                <label class="block mb-2 text-cyan-200">Update Photo (Leave blank to keep current photo)</label>
                <input type="file" name="photo" class="w-full cyber-input p-2 rounded" accept="image/*">
                <?php if($team['photo_path']): ?>
                    <p class="text-xs text-slate-400 mt-2">Current photo: <?= htmlspecialchars(basename($team['photo_path'])) ?></p>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 px-4 rounded heading-font tracking-widest">SAVE CHANGES</button>
        </form>
    </div>
</body>
</html>
