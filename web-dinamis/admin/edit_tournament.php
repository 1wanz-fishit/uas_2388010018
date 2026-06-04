<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die("Unauthorized access.");
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM tournaments WHERE id = ?");
$stmt->execute([$id]);
$tourney = $stmt->fetch();

if (!$tourney) {
    die("Tournament not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Tournament - NEXUS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0b0f19; color: #fff; font-family: 'Rajdhani', sans-serif; }
        .heading-font { font-family: 'Orbitron', sans-serif; }
        .cyber-input { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(188, 19, 254, 0.3); color: #fff; transition: all 0.3s ease; }
        .cyber-input:focus { border-color: #bc13fe; box-shadow: 0 0 10px rgba(188,19,254,0.2); outline: none; }
    </style>
</head>
<body class="p-8">
    <div class="max-w-2xl mx-auto bg-slate-900/50 backdrop-blur border border-purple-500/30 p-8 rounded-2xl shadow-lg shadow-purple-500/10">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-purple-400 heading-font">EDIT TOURNAMENT</h1>
            <a href="index.php" class="text-slate-400 hover:text-white underline">Back to Dashboard</a>
        </div>
        
        <form action="admin_handler.php" method="POST">
            <input type="hidden" name="action" value="edit_tournament">
            <input type="hidden" name="id" value="<?= htmlspecialchars($tourney['id']) ?>">
            
            <div class="mb-4">
                <label class="block mb-2 text-purple-200">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($tourney['title']) ?>" class="w-full cyber-input p-3 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-purple-200">Date</label>
                <input type="date" name="date" value="<?= htmlspecialchars($tourney['date']) ?>" class="w-full cyber-input p-3 rounded" style="color-scheme: dark;" required>
            </div>
            <div class="mb-8">
                <label class="block mb-2 text-purple-200">Details</label>
                <textarea name="details" class="w-full cyber-input p-3 rounded" rows="5" required><?= htmlspecialchars($tourney['details']) ?></textarea>
            </div>
            
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 px-4 rounded heading-font tracking-widest">SAVE CHANGES</button>
        </form>
    </div>
</body>
</html>
