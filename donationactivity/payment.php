<?php
require '../backend/function.php';

if (!isset($_GET["amount"])) {
    header("Location: index.php");
    exit;
}

if ($_GET["amount"] < 0) {
    header("Location: amount.php");
    exit;
}

$_SESSION["amount"] = $_GET["amount"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Konfirmasi Donasi | Celengan Masa Depan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/e4ca1991ae.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 leading-relaxed">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 py-4">
        <div class="max-w-2xl mx-auto flex items-center gap-4">
            <a href="amount.php?id=<?= $_SESSION["id"]?>" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="font-bold text-slate-800">Lengkapi Data Donasi</h1>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 py-6">
        <form id="donationForm" action="contribute.php" method="POST" class="space-y-6">
            
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-3xl p-6 text-white shadow-xl shadow-blue-100">
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-2">Nominal Donasi</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl font-medium text-blue-200">Rp</span>
                    <span class="text-4xl font-extrabold tracking-tight"><?= number_format($_SESSION["amount"], 0, ',', '.') ?></span>
                    <input type="hidden" name="amount" value="<?= (int)$_SESSION["amount"] ?>">
                </div>
            </div>

            <section class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Metode Pembayaran</h3>
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border-2 border-blue-500 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="bg-white p-2 rounded-xl shadow-sm">
                            <img src="../img/qris.png" alt="QRIS" class="w-12 h-6 object-contain" />
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800">QRIS</p>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tight">Otomatis Terverifikasi</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-circle-check text-blue-500 text-xl"></i>
                    <input type="hidden" name="payment" value="qris" />
                </div>
            </section>

            <section class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Identitas Donatur</h3>
                    <a href="../login/" class="text-xs font-bold text-blue-600 hover:underline tracking-tight">Sudah punya akun? Masuk</a>
                </div>

                <div class="space-y-4">
                    <div class="group">
                        <input type="text" name="name" 
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                            placeholder="Nama Lengkap" required />
                        <p class="text-[10px] text-slate-400 mt-2 px-1 font-medium italic">*Bisa diisi "Hamba Allah" jika ingin anonim</p>
                    </div>

                    <input type="number" name="no_telp" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                        placeholder="Nomor WhatsApp (Aktif)" required />

                    <input type="email" name="email" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                        placeholder="Alamat Email" required />
                    
                    <div class="flex gap-3 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                        <i class="fa-solid fa-shield-check text-blue-500 mt-1"></i>
                        <p class="text-[11px] text-blue-700 leading-snug">
                            Data Anda aman dan hanya akan digunakan untuk pengiriman kuitansi donasi serta laporan dampak sosial.
                        </p>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Pesan Dukungan (Opsional)</h3>
                <textarea
                    name="massage"
                    class="w-full p-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all h-32 resize-none text-sm"
                    placeholder="Tulis doa atau kata-kata penyemangat agar penggalang dana dan relawan semakin semangat..."
                ></textarea>
            </section>

            <div class="h-10"></div>
        </form>
    </main>

    <div class="fixed bottom-0 inset-x-0 bg-white/90 backdrop-blur-lg border-t border-slate-200 p-4 z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
        <div class="max-w-2xl mx-auto flex items-center justify-between gap-6">
            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Bayar</span>
                <span class="text-xl font-extrabold text-blue-600 tracking-tight">Rp <?= number_format($_SESSION["amount"], 0, ",", ".") ?></span>
            </div>
            <button type="button" name="submit" onclick="document.getElementById('donationForm').requestSubmit()"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-red-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                Bayar Sekarang
                <i class="fa-solid fa-arrow-right-long text-sm"></i>
            </button>
        </div>
    </div>

</body>
</html>