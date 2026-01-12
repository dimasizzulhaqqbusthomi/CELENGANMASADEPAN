<?php
require '../backend/function.php';

// SENTINEL: Proteksi Akses
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== 'contributor') {
    header("Location: ../login/index.php");
    exit;
}

$contributor_id = $_SESSION["id"];

// QUERY STRATEGIS: Mengambil data donatur dan mengurutkannya berdasarkan kampanye
// Kita melakukan JOIN ke tabel campaigns untuk mendapatkan nama kampanye langsung
$donors = query("SELECT v.*, c.name as campaign_name 
                 FROM v_donation_donor v
                 JOIN campaigns c ON v.campaign_id = c.id
                 WHERE c.contributor_id = $contributor_id
                 ORDER BY c.name ASC, v.donation_date DESC");

$total_dana = query("SELECT SUM(amount) as total FROM donations 
                     WHERE campaign_id IN (SELECT id FROM campaigns WHERE contributor_id = $contributor_id)")[0]['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Per Kampanye | CMD Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/e4ca1991ae.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8FAFC] text-slate-900">

    <div class="max-w-5xl mx-auto p-6 lg:p-12">
        <header class="mb-10 flex justify-between items-end">
            <div>
                <a href="dashboard.php" class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2 inline-block">← Dashboard</a>
                <h1 class="text-3xl font-black text-slate-900 italic">Laporan Donatur</h1>
                <p class="text-slate-500 text-sm">Data kontribusi dikelompokkan berdasarkan program aktif.</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase">Total Saldo Masuk</p>
                <p class="text-2xl font-black text-emerald-500 italic">Rp <?= number_format($total_dana ?? 0, 0, ',', '.') ?></p>
            </div>
        </header>

        <div class="space-y-8">
            <?php 
            $current_campaign = null;
            if (count($donors) > 0) : 
                foreach($donors as $row) : 
                    // LOGIKA PENGELOMPOKAN: Cek jika nama kampanye berubah
                    if ($current_campaign !== $row['campaign_name']) : 
                        $current_campaign = $row['campaign_name'];
                        // Jika bukan kampanye pertama, tutup tabel sebelumnya
                        if ($current_campaign !== null) echo "</tbody></table></div></div>";
            ?>
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="bg-slate-900 p-6 flex justify-between items-center">
                        <h3 class="text-white font-extrabold italic text-sm">
                            <i class="fa-solid fa-bullhorn mr-2 text-blue-400"></i> <?= $row['campaign_name'] ?>
                        </h3>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ID: #<?= $row['campaign_id'] ?></span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase">Donatur</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase text-right">Nominal</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase text-center">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
            <?php endif; ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[10px]">
                                            <?= strtoupper(substr($row['donor_name'], 0, 1)) ?>
                                        </div>
                                        <span class="font-bold text-slate-700 text-sm"><?= htmlspecialchars($row['donor_name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right font-black text-slate-800 italic text-sm">
                                    Rp <?= number_format($row['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="px-8 py-5 text-center text-[10px] font-medium text-slate-400 uppercase">
                                    <?= date('d M Y', strtotime($row['donation_date'])) ?>
                                </td>
                            </tr>
            <?php 
                endforeach; 
                echo "</tbody></table></div></div>"; // Tutup div terakhir
            else : 
            ?>
                <div class="text-center py-20 bg-white rounded-[3rem] border border-dashed border-slate-200">
                    <p class="text-slate-400 italic">Belum ada donasi yang masuk.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>