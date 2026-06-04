<?php
require_once 'db.php';

// Fetch data
$teams = [];
$tournaments = [];
try {
    $teams = $pdo->query("SELECT * FROM teams ORDER BY id DESC")->fetchAll();
    $tournaments = $pdo->query("SELECT * FROM tournaments ORDER BY date ASC")->fetchAll();
} catch (PDOException $e) {
    // Graceful fallback
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - High-End E-Sports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/motion@10.16.2/dist/motion.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&display=swap');
        
        :root {
            --neon-blue: #00f3ff;
            --neon-purple: #bc13fe;
            --bg-dark: #07070a;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background-color: var(--bg-dark);
            color: #ffffff;
            overflow-x: hidden;
        }

        h1, h2, h3, .heading-font {
            font-family: 'Orbitron', sans-serif;
        }

        .glass-panel {
            background: rgba(13, 15, 23, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(0, 243, 255, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6);
        }
        
        .glass-panel:hover {
            border-color: rgba(0, 243, 255, 0.5);
            box-shadow: 0 0 25px rgba(0, 243, 255, 0.15);
            transition: all 0.4s ease;
        }

        .neon-text {
            color: #fff;
            text-shadow: 0 0 5px #fff, 0 0 10px var(--neon-blue), 0 0 20px var(--neon-blue), 0 0 40px var(--neon-blue);
        }
        
        .neon-purple-text {
            color: #fff;
            text-shadow: 0 0 5px #fff, 0 0 10px var(--neon-purple), 0 0 20px var(--neon-purple);
        }

        .grid-bg {
            background-image: 
                linear-gradient(rgba(0, 243, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 243, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            background-position: center center;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: rgba(0, 243, 255, 0.3); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 243, 255, 0.6); }
    </style>
</head>
<body class="grid-bg">
    
    <!-- Ambient glowing orbs -->
    <div class="fixed top-[20%] left-[10%] w-[500px] h-[500px] rounded-full bg-cyan-600/10 blur-[150px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[10%] right-[10%] w-[600px] h-[600px] rounded-full bg-purple-600/10 blur-[150px] pointer-events-none z-0"></div>

    <!-- Hero Section -->
    <header class="min-h-screen flex flex-col items-center justify-center relative overflow-hidden z-10">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-[#07070a] z-10 pointer-events-none"></div>
        <div class="z-20 text-center px-4">
            <div class="hero-badge inline-block mb-6 border border-cyan-500/50 bg-cyan-500/10 text-cyan-300 px-4 py-1 rounded-full text-sm font-bold tracking-widest backdrop-blur-sm">
                WELCOME TO THE NEXT LEVEL
            </div>
            <h1 class="hero-title text-6xl md:text-8xl lg:text-9xl font-black mb-2 tracking-wider">
                <span class="text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-400">NE</span><span class="text-transparent bg-clip-text bg-gradient-to-b from-cyan-300 to-blue-600">XUS</span>
            </h1>
            <h2 class="hero-title-2 text-2xl md:text-4xl font-bold text-slate-300 mb-8 tracking-[0.2em] uppercase">E-Sports Organization</h2>
            <p class="hero-subtitle text-lg md:text-xl text-slate-400 mb-10 max-w-2xl mx-auto font-medium leading-relaxed">
                Forging champions in the digital arena. Witness the synergy of skill, strategy, and cutting-edge performance.
            </p>
            <a href="#teams" class="hero-btn relative inline-flex items-center justify-center px-10 py-4 font-bold text-white transition-all duration-200 bg-transparent border-2 border-cyan-400 rounded-lg hover:bg-cyan-400/10 group overflow-hidden shadow-[0_0_20px_rgba(0,243,255,0.3)]">
                <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-cyan-400"></span>
                <span class="relative tracking-widest">DISCOVER ROSTER</span>
            </a>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-20 flex flex-col items-center opacity-70 animate-bounce">
            <span class="text-xs text-cyan-400 tracking-widest mb-2 font-bold">SCROLL</span>
            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </header>

    <!-- Teams Section -->
    <section id="teams" class="py-24 px-4 max-w-[90rem] mx-auto relative z-20">
        <div class="text-center mb-20">
            <h2 class="section-title text-5xl md:text-6xl font-black text-cyan-400 neon-text inline-block">ELITE SQUAD</h2>
            <div class="h-1 w-24 bg-cyan-400 mx-auto mt-6 shadow-[0_0_10px_#00f3ff]"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">
            <?php if (count($teams) > 0): ?>
                <?php foreach ($teams as $team): ?>
                    <div class="team-card glass-panel rounded-2xl overflow-hidden group flex flex-col h-full relative">
                        <!-- Top glow edge -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-500 opacity-70"></div>
                        
                        <!-- Image Container -->
                        <div class="h-72 overflow-hidden relative">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0d0f17] to-transparent z-10"></div>
                            <div class="absolute inset-0 bg-cyan-500/10 mix-blend-overlay z-10 group-hover:bg-transparent transition-all duration-700"></div>
                            
                            <!-- Game Info Badge -->
                            <?php if(!empty($team['game_info'])): ?>
                            <div class="absolute top-4 right-4 z-20 bg-black/60 backdrop-blur-md border border-cyan-500/50 text-cyan-300 text-xs font-bold px-3 py-1.5 rounded-full tracking-wider shadow-[0_0_10px_rgba(0,243,255,0.2)]">
                                <?= htmlspecialchars($team['game_info']) ?>
                            </div>
                            <?php endif; ?>

                            <img src="<?= htmlspecialchars($team['photo_path'] ?: 'https://via.placeholder.com/600x400/1e293b/00f3ff?text=NEXUS') ?>" 
                                 alt="<?= htmlspecialchars($team['name']) ?>" 
                                 class="w-full h-full object-cover object-top transform group-hover:scale-105 transition-transform duration-1000 ease-out">
                        </div>
                        
                        <!-- Content Container -->
                        <div class="p-8 flex-grow flex flex-col relative z-20 -mt-10">
                            <h3 class="text-3xl font-black mb-1 text-white heading-font tracking-wide drop-shadow-lg"><?= htmlspecialchars($team['name']) ?></h3>
                            <p class="text-slate-400 text-sm mb-6 leading-relaxed flex-grow"><?= htmlspecialchars($team['description']) ?></p>
                            
                            <div class="space-y-4 pt-5 border-t border-slate-700/50">
                                <!-- Achievements -->
                                <?php if(!empty($team['achievements'])): ?>
                                <div class="bg-slate-800/30 rounded-lg p-3 border border-slate-700/50 hover:border-cyan-500/30 transition-colors">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-xs text-slate-400 uppercase font-bold tracking-widest">Prestasi</span>
                                    </div>
                                    <p class="text-sm text-slate-200 font-medium"><?= nl2br(htmlspecialchars($team['achievements'])) ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Personal Data -->
                                <?php if(!empty($team['personal_data'])): ?>
                                <div class="bg-slate-800/30 rounded-lg p-3 border border-slate-700/50 hover:border-blue-500/30 transition-colors">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span class="text-xs text-slate-400 uppercase font-bold tracking-widest">Data Diri</span>
                                    </div>
                                    <p class="text-sm text-slate-200 font-medium"><?= nl2br(htmlspecialchars($team['personal_data'])) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-20 glass-panel rounded-2xl border-dashed border-2 border-slate-700">
                    <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="text-xl text-slate-400 font-bold">Awaiting Roster Deployment</p>
                    <p class="text-slate-500 mt-2">Initialize records via the command center.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Tournaments Section -->
    <section class="py-24 px-4 max-w-5xl mx-auto relative z-20">
        <div class="text-center mb-20">
            <h2 class="section-title text-5xl md:text-6xl font-black text-purple-400 neon-purple-text inline-block">BATTLE GROUNDS</h2>
            <div class="h-1 w-24 bg-purple-400 mx-auto mt-6 shadow-[0_0_10px_#bc13fe]"></div>
        </div>
        
        <div class="space-y-6">
            <?php if (count($tournaments) > 0): ?>
                <?php foreach ($tournaments as $tourney): ?>
                    <div class="tournament-row glass-panel rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between border-l-4 border-l-purple-500 hover:border-l-cyan-400 hover:bg-slate-800/50 transition-all duration-300 group">
                        <div class="mb-6 md:mb-0 md:pr-8 flex-1">
                            <h3 class="text-2xl font-black text-white mb-2 group-hover:text-cyan-300 transition-colors"><?= htmlspecialchars($tourney['title']) ?></h3>
                            <p class="text-slate-400 leading-relaxed font-medium"><?= nl2br(htmlspecialchars($tourney['details'])) ?></p>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="inline-flex flex-col items-center bg-black/40 rounded-xl px-6 py-4 border border-slate-700/50 group-hover:border-purple-500/50 transition-colors">
                                <span class="text-sm text-slate-400 font-bold uppercase tracking-widest mb-1">T-Minus</span>
                                <span class="text-2xl text-purple-400 font-black heading-font drop-shadow-[0_0_8px_rgba(188,19,254,0.5)]">
                                    <?= date('M d', strtotime($tourney['date'])) ?>
                                </span>
                                <span class="text-sm text-slate-500 font-bold"><?= date('Y', strtotime($tourney['date'])) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-16 glass-panel rounded-2xl border border-slate-800">
                    <p class="text-slate-500 font-bold text-lg">No active campaigns detected.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="py-12 text-center mt-10 relative z-20 border-t border-slate-800/80 bg-black/40 backdrop-blur-sm">
        <h2 class="text-2xl font-black text-slate-600 tracking-widest mb-4">NEXUS</h2>
        <p class="text-slate-500 font-medium tracking-wide">&copy; <?= date('Y') ?> NEXUS Esports. All systems online.</p>
    </footer>

    <!-- Animations Implementation -->
    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Advanced Hero Timeline
        const tl = gsap.timeline();
        tl.from(".hero-badge", { y: -20, opacity: 0, duration: 0.8, ease: "power3.out" })
          .from(".hero-title", { y: 40, opacity: 0, duration: 1, ease: "power3.out" }, "-=0.5")
          .from(".hero-title-2", { letterSpacing: "0.5em", opacity: 0, duration: 1, ease: "power2.out" }, "-=0.7")
          .from(".hero-subtitle", { y: 20, opacity: 0, duration: 0.8, ease: "power2.out" }, "-=0.6")
          .from(".hero-btn", { scale: 0.9, opacity: 0, duration: 0.5, ease: "back.out(1.5)" }, "-=0.4");

        // Section Titles
        gsap.utils.toArray('.section-title').forEach(title => {
            gsap.from(title, {
                scrollTrigger: { trigger: title, start: "top 85%" },
                y: 30, opacity: 0, duration: 0.8, ease: "power3.out"
            });
        });

        // Team Cards Stagger
        gsap.from(".team-card", {
            scrollTrigger: { trigger: "#teams", start: "top 75%" },
            y: 50, opacity: 0, duration: 0.8, stagger: 0.15, ease: "power2.out"
        });

        // Tournament Rows Stagger
        if(document.querySelector(".tournament-row")) {
            gsap.from(".tournament-row", {
                scrollTrigger: { trigger: ".tournament-row", start: "top 85%" },
                x: -30, opacity: 0, duration: 0.6, stagger: 0.1, ease: "power2.out"
            });
        }

        // Vanilla JS Motion Button Hover
        const btn = document.querySelector('.hero-btn');
        if (btn) {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                Motion.animate(btn, { x: x * 0.15, y: y * 0.15 }, { duration: 0.2, easing: "ease-out" });
            });
            btn.addEventListener('mouseleave', () => {
                Motion.animate(btn, { x: 0, y: 0 }, { type: "spring", stiffness: 400, damping: 25 });
            });
        }
    </script>
</body>
</html>
