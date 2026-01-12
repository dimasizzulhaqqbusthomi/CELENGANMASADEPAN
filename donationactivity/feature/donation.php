<?php
require '../../backend/function.php';

$id = $_GET["id"];

// Proteksi ID
if (!isset($id)) {
    header("Location: ../index.php");
    exit;
}

$dnt = query("
  SELECT * FROM v_donation_donor 
  WHERE campaign_id = $id AND donor_name IS NOT NULL
  ORDER BY donation_date DESC
");

$total_donatur = count($dnt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Donatur | Celengan Masa Depan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/e4ca1991ae.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="text-slate-900 leading-relaxed">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center gap-4">
            <a href="../index.php?id=<?= $id ?>" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="font-bold text-slate-800">Riwayat Donasi</h1>
                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest"><?= $total_donatur ?> Kontribusi Terkumpul</p>
            </div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto p-4 space-y-3">
        
        <?php if ($total_donatur > 0) : ?>
            <?php foreach($dnt as $row) : ?>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 transition-all hover:border-blue-200">
                    
                    <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-blue-50 to-indigo-50 flex items-center justify-center text-blue-600 font-extrabold text-lg border border-blue-100 shadow-sm flex-shrink-0">
                        <?= strtoupper(substr($row["donor_name"], 0, 1)) ?>
                    </div>

                    <div class="flex-1 min-w-0">
                        <h2 class="font-bold text-slate-800 text-sm truncate"><?= htmlspecialchars($row["donor_name"]) ?></h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Berdonasi sebesar <span class="font-bold text-slate-900 tracking-tight">Rp <?= number_format($row['amount'], 0, ',', '.') ?></span>
                        </p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter bg-slate-100 px-2 py-0.5 rounded-md">
                                <?= date('d M Y', strtotime($row["donation_date"])) ?>
                            </span>
                            <span class="text-[9px] font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-md">
                                <i class="fa-solid fa-check-double mr-0.5"></i> Berhasil
                            </span>
                        </div>
                    </div>

                    <div class="hidden sm:block">
                        <i class="fa-solid fa-heart text-slate-100 text-2xl"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <i class="fa-solid fa-box-open text-4xl text-slate-200 mb-4"></i>
                <p class="text-slate-400 font-medium">Belum ada riwayat donasi.</p>
            </div>
        <?php endif; ?>

    </main>

    <footer class="text-center py-10 opacity-30">
        <i class="fa-solid fa-shield-heart text-2xl"></i>
    </footer>

</body>
</html>