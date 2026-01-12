<?php
require '../backend/function.php';

// SENTINEL: Proteksi Akses Tingkat Tinggi
// Hanya user dengan role 'contributor' yang bisa masuk
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== 'contributor') {
    header("Location: ../login/index.php");
    exit;
}

$contributor_id = $_SESSION["id"];
$username = $_SESSION["username"];

// 1. Ambil data ringkasan kampanye milik kontributor ini
$my_campaigns = query("SELECT * FROM v_campaigns_per_contributor WHERE contributor_id = $contributor_id");

// 2. Hitung statistik total untuk Widget
$stats = query("SELECT 
    COUNT(campaign_id) as total_program, 
    SUM(total_amount) as dana_terkumpul,
    SUM(target) as total_target
    FROM v_campaigns_per_contributor 
    WHERE contributor_id = $contributor_id")[0];

// Logika persentase total (menghindari pembagian nol)
$total_target = $stats['total_target'] > 0 ? $stats['total_target'] : 1;
$overall_progress = round(($stats['dana_terkumpul'] / $total_target) * 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contributor Panel | Celengan Masa Depan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/e4ca1991ae.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8FAFC] text-slate-900">

    <div class="flex min-h-screen">
        <aside class="w-72 bg-slate-900 text-white hidden lg:flex flex-col p-8 sticky top-0 h-screen">
            <div class="mb-12">
                <h1 class="text-2xl font-black tracking-tighter italic">CMD.<span class="text-blue-500">ADMIN</span></h1>
            </div>
            
            <nav class="flex-1 space-y-4">
                <a href="dashboard.php" class="flex items-center gap-4 bg-blue-600 p-4 rounded-2xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all">
                    <i class="fa-solid fa-house-chimney w-5"></i> Ringkasan
                </a>
                <a href="campaign_add.php" class="flex items-center gap-4 text-slate-400 hover:text-white p-4 rounded-2xl text-sm font-bold transition-all">
                    <i class="fa-solid fa-circle-plus w-5"></i> Buat Kampanye
                </a>
                <a href="donors.php" class="flex items-center gap-4 text-slate-400 hover:text-white p-4 rounded-2xl text-sm font-bold transition-all">
                    <i class="fa-solid fa-hand-holding-heart w-5"></i> Data Donatur
                </a>
            </nav>

            <div class="mt-auto pt-8 border-t border-slate-800">
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold text-white uppercase">
                        <?= substr($username, 0, 1) ?>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold truncate"><?= $username ?></p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Kontributor</p>
                    </div>
                </div>
                <a href="../login/logout.php" class="flex items-center gap-4 text-red-400 hover:bg-red-500/10 p-4 rounded-2xl text-sm font-bold transition-all">
                    <i class="fa-solid fa-right-from-bracket w-5"></i> Keluar
                </a>
            </div>
        </aside>

        <main class="flex-1 p-6 lg:p-12">
            <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-12">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight italic">Dashboard Strategis</h2>
                    <p class="text-slate-500 font-medium">Pantau efektivitas kampanye sosial Anda secara real-time.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">Sistem Aktif</span>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-4">Total Program</p>
                        <h3 class="text-4xl font-black text-slate-900"><?= $stats['total_program'] ?></h3>
                        <p class="text-blue-600 text-[10px] font-bold mt-2 uppercase">Kampanye Terdaftar</p>
                    </div>
                    <i class="fa-solid fa-folder text-slate-50 text-7xl absolute -bottom-2 -right-2 transition-transform group-hover:scale-110"></i>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-4">Dana Terkumpul</p>
                        <h3 class="text-4xl font-black text-slate-900 italic">Rp <?= number_format($stats['dana_terkumpul'] ?? 0, 0, ',', '.') ?></h3>
                        <p class="text-emerald-500 text-[10px] font-bold mt-2 uppercase">Total Donasi Masuk</p>
                    </div>
                    <i class="fa-solid fa-coins text-slate-50 text-7xl absolute -bottom-2 -right-2 transition-transform group-hover:scale-110"></i>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-4">Efektivitas</p>
                        <h3 class="text-4xl font-black text-slate-900"><?= $overall_progress ?>%</h3>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full mt-4 overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: <?= $overall_progress ?>%"></div>
                        </div>
                    </div>
                    <i class="fa-solid fa-chart-pie text-slate-50 text-7xl absolute -bottom-2 -right-2 transition-transform group-hover:scale-110"></i>
                </div>
            </div>

            <section class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-10 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="text-xl font-black text-slate-900 italic">Performa Kampanye Aktif</h3>
                    <a href="campaign_add.php" class="bg-slate-900 hover:bg-blue-600 text-white px-8 py-3 rounded-2xl text-xs font-bold transition-all shadow-lg shadow-slate-200">
                        Buat Kampanye Baru <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Program</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Progress</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Target Dana</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach($my_campaigns as $row) : ?>
                            <tr class="group hover:bg-slate-50/50 transition-all">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                                            <img src="../img/<?= $row['image'] ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-extrabold text-slate-800 text-sm italic group-hover:text-blue-600 transition-colors"><?= $row['campaign_name'] ?></p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">ID Program: #<?= $row['campaign_id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <?php 
                                        $percent = round(($row['total_amount'] / $row['target']) * 100);
                                        $color = $percent >= 100 ? 'bg-emerald-500' : 'bg-blue-500';
                                    ?>
                                    <div class="flex flex-col items-center">
                                        <div class="w-32 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="<?= $color ?> h-full rounded-full transition-all duration-1000" style="width: <?= min(100, $percent) ?>%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-800 mt-2"><?= $percent ?>%</span>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <p class="text-sm font-black text-slate-800 tracking-tight italic">Rp <?= number_format($row['target'], 0, ',', '.') ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Terkumpul: Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></p>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex justify-center gap-2">
                                        <a href="campaign_edit.php?id=<?= $row['campaign_id'] ?>" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $row['campaign_id'] ?>)" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        function confirmDelete(id) {
            if(confirm('Strategi Advisor: Menghapus kampanye akan menghilangkan potensi donasi masuk. Anda yakin?')) {
                window.location.href = 'campaign_delete.php?id=' + id;
            }
        }
    </script>
</body>
</html>