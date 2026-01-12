<?php
require '../backend/function.php';

$_SESSION["id"] = $_GET["id"];

if (!isset($_SESSION["id"])) {
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pilih Nominal Donasi | Celengan Masa Depan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/e4ca1991ae.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center gap-4">
            <a href="index.php?id=<?= $_SESSION["id"]?>" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="font-bold text-slate-800">Tentukan Nominal Donasi</h1>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-blue-600 rounded-3xl p-6 mb-8 text-white shadow-xl shadow-blue-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-1">Anda akan berdonasi untuk:</p>
            <h2 class="text-lg font-bold leading-snug">Membantu Sesama Melalui Solidaritas Bersama</h2>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Pilih Nominal Cepat</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-8">
                <?php 
                $presets = [10000, 20000, 50000, 100000];
                foreach($presets as $amt) : 
                ?>
                <a href="payment.php?amount=<?= $amt ?>" 
                   class="group flex flex-col p-4 rounded-2xl border-2 border-slate-100 hover:border-blue-500 hover:bg-blue-50 transition-all duration-200">
                    <img src="../img/ic_emoji_01.svg" class="w-8 mb-3 group-hover:scale-110 transition-transform" alt="emoji" />
                    <span class="text-xs font-bold text-slate-400 group-hover:text-blue-400 transition-colors uppercase">Nominal</span>
                    <span class="text-lg font-extrabold text-slate-700 group-hover:text-blue-600 transition-colors">Rp <?= number_format($amt, 0, ',', '.') ?></span>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="border-t border-slate-100 pt-8">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Atau Masukkan Nominal Lain</h3>
                <form action="payment.php" method="get">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <span class="text-2xl font-extrabold text-slate-300 group-focus-within:text-blue-500 transition-colors">Rp</span>
                        </div>
                        <input type="number" name="amount" 
                               class="block w-full pl-16 pr-5 py-5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-blue-500 focus:bg-white transition-all outline-none" 
                               placeholder="0" required />
                    </div>
                    
                    <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100 flex gap-3">
                        <i class="fa-solid fa-circle-info text-amber-500 mt-1"></i>
                        <p class="text-xs text-amber-700 leading-relaxed">
                            Minimal donasi adalah <strong>Rp 1.000</strong>. Pastikan nominal yang Anda masukkan sudah sesuai sebelum lanjut ke pembayaran.
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full mt-8 bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        Lanjut Pembayaran
                        <i class="fa-solid fa-arrow-right text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-slate-400 text-xs mt-8 font-medium italic">
            "Sedekah tidak akan mengurangi harta."
        </p>
    </main>

</body>
</html>