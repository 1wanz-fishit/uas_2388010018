<?php
session_start();
require_once '../db.php';

// Simple login logic
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && ($password === $admin['password'] || password_verify($password, $admin['password']))) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "Invalid credentials";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Fetch stats and data if logged in
if (isset($_SESSION['admin_logged_in'])) {
    $teams = $pdo->query("SELECT * FROM teams ORDER BY id DESC")->fetchAll();
    $tournaments = $pdo->query("SELECT * FROM tournaments ORDER BY date ASC")->fetchAll();
    $team_count = count($teams);
    $tourney_count = count($tournaments);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - NEXUS E-Sport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0b0f19; color: #f8fafc; font-family: 'Rajdhani', sans-serif; }
        h1, h2, h3, .heading-font { font-family: 'Orbitron', sans-serif; }
        .glass { background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border: 1px solid rgba(0,243,255,0.2); }
        .cyber-input { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(0, 243, 255, 0.3); color: #fff; transition: all 0.3s ease; }
        .cyber-input:focus { border-color: #00f3ff; box-shadow: 0 0 10px rgba(0,243,255,0.2); outline: none; }
        .cyber-button { background: linear-gradient(90deg, #00d2ff 0%, #3a7bd5 100%); transition: all 0.3s; }
        .cyber-button:hover { box-shadow: 0 0 20px rgba(0,210,255,0.5); transform: translateY(-2px); }
        .cyber-table th { background: rgba(0, 243, 255, 0.1); border-bottom: 2px solid #00f3ff; }
        .cyber-table td { border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .cyber-table tr:hover td { background: rgba(0, 243, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden pb-20">
    <!-- Background Decor -->
    <div class="fixed top-[-10%] left-[-10%] w-96 h-96 bg-cyan-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 z-0 pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 z-0 pointer-events-none"></div>

    <div class="relative z-10 px-4 md:px-8">
    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
        <!-- LOGIN SCREEN -->
        <div class="max-w-md mx-auto mt-32 glass p-10 rounded-2xl shadow-2xl shadow-cyan-500/20">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 tracking-wider heading-font">NEXUS ADMIN</h2>
                <p class="text-slate-400 mt-2">Secure access portal</p>
            </div>
            <?php if (isset($error)) echo "<div class='bg-red-500/20 border border-red-500/50 text-red-300 p-3 rounded mb-6 text-center font-bold'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-cyan-200">Username</label>
                    <input type="text" name="username" class="w-full cyber-input rounded-lg p-3" required>
                </div>
                <div class="mb-8">
                    <label class="block text-sm font-medium mb-2 text-cyan-200">Password</label>
                    <input type="password" name="password" class="w-full cyber-input rounded-lg p-3" required>
                </div>
                <button type="submit" name="login" class="w-full cyber-button text-white font-bold py-3 px-4 rounded-lg tracking-widest heading-font">AUTHENTICATE</button>
            </form>
        </div>

    <?php else: ?>
        <!-- DASHBOARD SCREEN -->
        <div class="max-w-7xl mx-auto mt-10">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 glass p-6 rounded-2xl border-l-4 border-l-cyan-400">
                <div class="mb-4 md:mb-0 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 tracking-wider heading-font">Irwan Mutholib-2388010018</h1>
                    <p class="text-slate-400 mt-1 font-bold">Manage your E-Sport Assets with full CRUD capability</p>
                </div>
                <a href="?logout=1" class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white px-6 py-2 rounded-lg font-bold transition-all shadow-[0_0_10px_rgba(239,68,68,0.2)]">TERMINATE SESSION</a>
            </div>
            
            <?php if(isset($_GET['success'])): ?>
                <div class="bg-green-500/20 border border-green-500/50 text-green-300 p-4 rounded-xl mb-8 font-bold flex items-center justify-between shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                    <?= htmlspecialchars($_GET['success']) ?>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-300 hover:text-white text-xl">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="glass p-6 rounded-2xl flex items-center gap-6 border-b-2 border-b-cyan-400">
                    <div class="bg-cyan-500/20 p-4 rounded-full border border-cyan-500/50">
                        <svg class="w-10 h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Total Members</p>
                        <h3 class="text-4xl font-black text-white heading-font"><?= $team_count ?></h3>
                    </div>
                </div>
                <div class="glass p-6 rounded-2xl flex items-center gap-6 border-b-2 border-b-purple-500">
                    <div class="bg-purple-500/20 p-4 rounded-full border border-purple-500/50">
                        <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Upcoming Tournaments</p>
                        <h3 class="text-4xl font-black text-white heading-font"><?= $tourney_count ?></h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
                
                <!-- TEAM MANAGEMENT -->
                <div class="glass p-6 md:p-8 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-cyan-400"></div>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-cyan-300 heading-font">Team Members</h3>
                    </div>
                    
                    <!-- Table Read/Edit/Delete -->
                    <div class="overflow-x-auto mb-8">
                        <table class="w-full text-left border-collapse cyber-table text-sm">
                            <thead>
                                <tr>
                                    <th class="p-3 text-cyan-200 font-bold tracking-wider">Name</th>
                                    <th class="p-3 text-cyan-200 font-bold tracking-wider">Role</th>
                                    <th class="p-3 text-cyan-200 font-bold tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($team_count > 0): foreach($teams as $t): ?>
                                <tr>
                                    <td class="p-3 font-bold text-white"><?= htmlspecialchars($t['name']) ?></td>
                                    <td class="p-3 text-slate-300"><?= htmlspecialchars($t['game_info']) ?></td>
                                    <td class="p-3 text-right">
                                        <a href="edit_team.php?id=<?= $t['id'] ?>" class="inline-block bg-blue-500/20 text-blue-300 border border-blue-500/50 hover:bg-blue-500 hover:text-white px-3 py-1 rounded text-xs font-bold transition-all mr-2">EDIT</a>
                                        <a href="admin_handler.php?action=delete_team&id=<?= $t['id'] ?>" onclick="return confirm('Hapus pemain ini?')" class="inline-block bg-red-500/20 text-red-300 border border-red-500/50 hover:bg-red-500 hover:text-white px-3 py-1 rounded text-xs font-bold transition-all">DEL</a>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td colspan="3" class="p-4 text-center text-slate-500">No members found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Form -->
                    <div class="border-t border-slate-700/50 pt-6">
                        <h4 class="text-lg font-bold text-cyan-400 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Deploy New Member
                        </h4>
                        <form action="admin_handler.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_team">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <input type="text" name="name" placeholder="Player Name" class="w-full cyber-input rounded p-2" required>
                                <input type="text" name="game_info" placeholder="Role (e.g. Midlaner)" class="w-full cyber-input rounded p-2">
                            </div>
                            <textarea name="description" placeholder="Short Description" class="w-full cyber-input rounded p-2 mb-4" rows="2" required></textarea>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <textarea name="achievements" placeholder="Achievements (Optional)" class="w-full cyber-input rounded p-2" rows="2"></textarea>
                                <textarea name="personal_data" placeholder="Personal Data (Optional)" class="w-full cyber-input rounded p-2" rows="2"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs mb-1 text-slate-400 uppercase tracking-widest font-bold">Photo Upload</label>
                                <input type="file" name="photo" class="w-full cyber-input rounded p-1 text-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-cyan-600 file:text-white hover:file:bg-cyan-500 cursor-pointer" accept="image/*" required>
                            </div>
                            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded tracking-widest transition-colors">ADD MEMBER</button>
                        </form>
                    </div>
                </div>
                
                <!-- TOURNAMENT MANAGEMENT -->
                <div class="glass p-6 md:p-8 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-purple-500"></div>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-purple-300 heading-font">Tournaments</h3>
                    </div>
                    
                    <!-- Table Read/Edit/Delete -->
                    <div class="overflow-x-auto mb-8">
                        <table class="w-full text-left border-collapse cyber-table text-sm">
                            <thead>
                                <tr>
                                    <th class="p-3 text-purple-200 font-bold tracking-wider">Title</th>
                                    <th class="p-3 text-purple-200 font-bold tracking-wider">Date</th>
                                    <th class="p-3 text-purple-200 font-bold tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($tourney_count > 0): foreach($tournaments as $t): ?>
                                <tr>
                                    <td class="p-3 font-bold text-white"><?= htmlspecialchars($t['title']) ?></td>
                                    <td class="p-3 text-slate-300 font-mono"><?= date('M d, Y', strtotime($t['date'])) ?></td>
                                    <td class="p-3 text-right">
                                        <a href="edit_tournament.php?id=<?= $t['id'] ?>" class="inline-block bg-blue-500/20 text-blue-300 border border-blue-500/50 hover:bg-blue-500 hover:text-white px-3 py-1 rounded text-xs font-bold transition-all mr-2">EDIT</a>
                                        <a href="admin_handler.php?action=delete_tournament&id=<?= $t['id'] ?>" onclick="return confirm('Hapus turnamen ini?')" class="inline-block bg-red-500/20 text-red-300 border border-red-500/50 hover:bg-red-500 hover:text-white px-3 py-1 rounded text-xs font-bold transition-all">DEL</a>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td colspan="3" class="p-4 text-center text-slate-500">No tournaments found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Form -->
                    <div class="border-t border-slate-700/50 pt-6">
                        <h4 class="text-lg font-bold text-purple-400 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Schedule Tournament
                        </h4>
                        <form action="admin_handler.php" method="POST">
                            <input type="hidden" name="action" value="add_tournament">
                            <div class="mb-4">
                                <input type="text" name="title" placeholder="Tournament Title" class="w-full cyber-input rounded p-2" required>
                            </div>
                            <div class="mb-4">
                                <input type="date" name="date" class="w-full cyber-input rounded p-2" style="color-scheme: dark;" required>
                            </div>
                            <div class="mb-4">
                                <textarea name="details" placeholder="Mission Details" class="w-full cyber-input rounded p-2" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 transition-all text-white font-bold py-2 px-4 rounded tracking-widest">ADD TOURNAMENT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>
</body>
</html>
