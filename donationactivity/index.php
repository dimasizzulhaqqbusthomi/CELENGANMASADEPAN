<?php
require '../backend/function.php';

$id = $_GET["id"];

if (!isset($id)) {
    header("Location: ../index.php");
    exit;
}

$result = query("SELECT * FROM campaigns WHERE id = $id")[0];
$dnt = query("
  SELECT * FROM v_donation_donor 
  WHERE campaign_id = $id AND donor_name IS NOT NULL
  ORDER BY donation_date DESC
");

$pesan = query("
  SELECT * FROM v_donation_donor 
  WHERE campaign_id = $id AND message IS NOT NULL
  ORDER BY donation_date ASC
");

$contributorId = $result['contributor_id'];
$penggalang = mysqli_query(
    $conn,
    "SELECT * FROM contributors WHERE id = $contributorId"
);

$penggalang = mysqli_fetch_assoc($penggalang);

$today = new DateTime();
$end   = new DateTime($result['end_date']);
$diff  = $today->diff($end);

$sisaHari = $diff->invert ? 0 : $diff->days;

$donations = query("SELECT * FROM donations WHERE campaign_id = $id");

$summary = query("
  SELECT 
    COUNT(*) AS total_donasi,
    COALESCE(SUM(amount),0) AS total_terkumpul
  FROM donations
  WHERE campaign_id = $id
")[0];

$progress = 0;

if ($result['target'] > 0) {
    $progress = ($summary['total_terkumpul'] / $result['target']) * 100;
    $progress = min(100, round($progress));
}

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $result["name"] ?> | Celengan Masa Depan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/e4ca1991ae.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: "Plus Jakarta Sans", sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="text-slate-900 leading-relaxed">

    <nav class="sticky top-0 z-50 glass-nav border-b border-slate-100">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="../index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <span class="font-bold text-slate-800 tracking-tight">Detail Kampanye</span>
            <div class="w-10"></div> </div>
    </nav>

    <main class="max-w-2xl mx-auto pb-32">
        <div class="relative w-full h-80 overflow-hidden shadow-lg">
            <img src="../img/<?= $result["image"] ?>" alt="<?= $result["name"] ?>" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" />
            <div class="absolute bottom-4 left-4">
                <span class="bg-blue-600 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Kemanusiaan</span>
            </div>
        </div>

        <div class="bg-white p-6 shadow-sm border-b border-slate-100">
            <h1 class="text-2xl font-extrabold text-slate-900 leading-tight mb-4"><?= $result["name"] ?></h1>
            
            <div class="mb-2">
                <span class="text-3xl font-extrabold text-blue-600">Rp <?= number_format($summary['total_terkumpul'], 0, ',', '.') ?></span>
                <span class="text-slate-400 text-sm font-medium ml-1 italic">terkumpul</span>
            </div>

            <div class="w-full bg-slate-100 rounded-full h-3 mb-4 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-400 h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(37,99,235,0.3)]" style="width: <?= $progress ?>%"></div>
            </div>

            <div class="flex justify-between items-center text-sm mb-6">
                <div class="flex flex-col">
                    <span class="text-slate-400 font-medium">Target Dana</span>
                    <span class="font-bold text-slate-700 text-base">Rp <?= number_format($result['target'], 0, ',', '.') ?></span>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-slate-400 font-medium italic">Sisa Waktu</span>
                    <span class="font-bold text-slate-700 text-base"><?= $sisaHari > 0 ? "$sisaHari Hari" : "Berakhir" ?></span>
                </div>
            </div>

            <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-2xl border border-blue-100">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <p class="text-sm font-bold text-blue-900"><span class="text-lg"><?= count($donations) ?></span> Orang Baik telah berdonasi</p>
            </div>
        </div>

        <section class="mt-4 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-4">Penggalang Dana</h3>
            <div class="flex items-center gap-4 group cursor-pointer">
                <div class="relative">
                    <img src="../img/profile.jfif" class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-md ring-1 ring-slate-100" alt="Profile" />
                    <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-5 h-5 rounded-full flex items-center justify-center border-2 border-white text-[8px]">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors"><?= $penggalang['name']; ?></h4>
                    <p class="text-xs text-slate-500 font-medium">Organisasi Terverifikasi ✓</p>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </section>

        <section class="mt-4 bg-white p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-900">Donasi Terakhir <span class="text-blue-500 ml-1">(<?= count($donations) ?>)</span></h3>
                <a href="feature/donation.php?id=<?= $id ?>" class="text-blue-600 font-bold text-xs uppercase tracking-widest hover:text-blue-700">Lihat Semua</a>
            </div>

            <div class="space-y-4">
                <?php foreach(array_slice($dnt, 0, 3) as $dn) : ?>
                <div class="flex gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition-all">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                        <?= strtoupper(substr($dn["donor_name"], 0, 1)) ?>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm"><?= $dn["donor_name"] ?></h4>
                        <p class="text-xs text-slate-500 mt-0.5">Berdonasi <span class="font-bold text-slate-800 tracking-tight">Rp <?= number_format($dn['amount'], 0, ',', '.') ?></span></p>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase"><?= date('d M Y', strtotime($dn["donation_date"])) ?></p>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </section>

        <section class="mt-4 bg-white p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Doa Para Donatur</h3>
                <a href="feature/comment.php?id=<?= $id ?>" class="text-blue-600 font-bold text-xs uppercase tracking-widest hover:text-blue-700">Lihat Semua</a>
            </div>

            <div class="space-y-6">
                <?php foreach(array_slice($pesan, 0, 3) as $ps) : ?>
                <div class="relative pl-6 border-l-2 border-blue-100 group">
                    <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-blue-400 ring-4 ring-white group-hover:scale-125 transition-transform"></div>
                    <div class="flex justify-between items-start mb-2">
                        <h5 class="font-bold text-sm text-slate-800"><?= $ps["donor_name"] ?></h5>
                        <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter"><?= date('d M Y', strtotime($ps["donation_date"])) ?></span>
                    </div>
                    <p class="text-sm text-slate-600 italic leading-relaxed">"<?= $ps["message"] ?>"</p>
                </div>
                <?php endforeach ?>
            </div>
        </section>
    </main>

    <div class="fixed bottom-0 inset-x-0 glass-nav p-4 border-t border-slate-200 shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
        <div class="max-w-2xl mx-auto flex gap-3">
            <a href="../index.php" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all active:scale-95">
                <i class="fa-solid fa-share-nodes"></i>
            </a>
            <a href="amount.php?id=<?= $id ?>" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-extrabold text-lg flex items-center justify-center rounded-2xl shadow-lg shadow-red-200 transition-all active:scale-[0.98]"> 
                Donasi Sekarang 
            </a>
        </div>
    </div>

</body>
</html>