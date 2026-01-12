<?php
require '../../backend/function.php';
$id = $_GET["id"];

// Validasi ID untuk keamanan dasar
if (!isset($id)) {
    header("Location: ../index.php");
    exit;
}

$pesan = query("
  SELECT * FROM v_donation_donor 
  WHERE campaign_id = $id 
    AND message IS NOT NULL
  ORDER BY donation_date DESC
");

// Menghitung jumlah pesan untuk header
$total_pesan = count($pesan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Doa & Dukungan | Celengan Masa Depan</title>
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
                <h1 class="font-bold text-slate-800">Doa & Dukungan</h1>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest"><?= $total_pesan ?> Orang Baik Telah Mendoakan</p>
            </div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto p-4 space-y-4">
        
        <?php if ($total_pesan > 0) : ?>
            <?php foreach($pesan as $row) : ?>
                <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 transition-all hover:shadow-md">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100 shadow-sm">
                            <?= strtoupper(substr($row["donor_name"], 0, 1)) ?>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800 text-sm tracking-tight"><?= htmlspecialchars($row["donor_name"]) ?></h2>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">
                                <?= date('d F Y', strtotime($row["donation_date"])) ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <i class="fa-solid fa-quote-left absolute -top-2 -left-2 text-slate-50 text-3xl -z-10"></i>
                        <p class="text-sm text-slate-600 italic leading-relaxed pl-2 border-l-2 border-blue-100">
                            "<?= htmlspecialchars($row["message"]) ?>"
                        </p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">
                            <i class="fa-solid fa-heart mr-1"></i> Donasi Terkonfirmasi
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <i class="fa-solid fa-comment-slash text-4xl text-slate-200 mb-4"></i>
                <p class="text-slate-400 font-medium">Belum ada doa yang dituliskan.</p>
            </div>
        <?php endif; ?>

    </main>

    <footer class="text-center py-10">
        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em]">Celengan Masa Depan</p>
    </footer>

</body>
</html>