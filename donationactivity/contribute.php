<?php
require '../backend/function.php';

if (isset($_POST["submit"])) {
    if (konfirmasi($_POST) > 0) {
      header("Location: index.php");
        exit;
    } else {
        echo mysqli_error($conn);
  } 
  
}

date_default_timezone_set('Asia/Jakarta');

$id_campaign = $_SESSION["id"];
$amount = (int) $_POST['amount'];
$name = $_POST['name'];
$no_telp = $_POST['no_telp'];
$email = $_POST['email'];
$massage = $_POST['massage'];

$createdAt = time();
$expiredAt = $createdAt + (15 * 60);
$_SESSION['payment_expired_at'] = $expiredAt;

if (time() > $_SESSION['payment_expired_at']) {
  die('Transaksi kadaluarsa. Silakan ulangi donasi.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pembayaran | Celengan Masa Depan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/e4ca1991ae.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .qr-frame { position: relative; border: 2px solid #e2e8f0; border-radius: 1.5rem; padding: 1rem; background: white; }
        .qr-frame::before, .qr-frame::after { content: ''; position: absolute; width: 40px; height: 40px; border: 4px solid #3b82f6; border-radius: 1rem; }
        .qr-frame::before { top: -2px; left: -2px; border-right: 0; border-bottom: 0; }
        .qr-frame::after { bottom: -2px; right: -2px; border-left: 0; border-top: 0; }
    </style>
</head>
<body class="text-slate-900 leading-relaxed">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 py-4">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="font-bold text-slate-800">Selesaikan Pembayaran</h1>
            <div class="w-10"></div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 py-8 space-y-6">
        
        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-xl shadow-blue-100 flex items-center justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-1">Selesaikan sebelum</p>
                <p class="font-bold"><?= date('H:i', $_SESSION['payment_expired_at']) ?> WIB</p>
            </div>
            <div class="flex flex-col items-end">
                <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-2xl backdrop-blur-md">
                    <i class="fa-regular fa-clock text-blue-100 animate-pulse"></i>
                    <span id="countdown" class="font-mono font-black text-xl tracking-tighter">15:00</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 text-center space-y-6">
            <div class="flex justify-between items-center px-2">
                <div class="text-left">
                    <h2 class="text-lg font-extrabold text-slate-800">Scan QRIS</h2>
                    <p class="text-xs text-slate-400 font-medium">Semua dompet digital & Bank</p>
                </div>
                <img src="../img/qris.png" class="h-8 object-contain" alt="QRIS" />
            </div>

            <div class="flex justify-center py-4">
                <div class="qr-frame">
                    <img src="../img/qrcode.png" alt="QR Code" class="w-64 h-64 grayscale-[0.2] hover:grayscale-0 transition-all duration-500" />
                </div>
            </div>

            <div class="pt-4 border-t border-slate-50 flex justify-between items-center">
                <span class="text-slate-400 font-bold text-sm uppercase tracking-widest">Total Donasi</span>
                <span class="text-2xl font-black text-blue-600 tracking-tight">Rp <?= number_format($amount, 0, ".", ",") ?></span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <form action="" method="POST">
                <input type="hidden" name="amount" value="<?= $amount ?>">
                <input type="hidden" name="name" value="<?= $name ?>">
                <input type="hidden" name="email" value="<?= $email ?>">
                <input type="hidden" name="no_telp" value="<?= $no_telp ?>">
                <input type="hidden" name="massage" value="<?= $massage ?>">
                <button name="submit" type="submit" 
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-emerald-100 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    Konfirmasi Pembayaran
                </button>
            </form>
            <p class="text-center text-[10px] text-slate-400 mt-4 px-4">
                Klik tombol di atas setelah Anda berhasil melakukan transfer melalui aplikasi dompet digital.
            </p>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <button onclick="document.getElementById('guide-content').classList.toggle('hidden')" class="w-full flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Panduan Pembayaran</h3>
                <i class="fa-solid fa-chevron-down text-slate-300"></i>
            </button>
            <div id="guide-content" class="mt-6 space-y-4">
                <div class="flex gap-4">
                    <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold flex items-center justify-center flex-shrink-0">1</span>
                    <p class="text-sm text-slate-600">Simpan atau <strong>screenshot</strong> gambar QR Code di atas.</p>
                </div>
                <div class="flex gap-4">
                    <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold flex items-center justify-center flex-shrink-0">2</span>
                    <p class="text-sm text-slate-600">Buka aplikasi dompet digital (Gojek, DANA, OVO) atau M-Banking Anda.</p>
                </div>
                <div class="flex gap-4">
                    <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold flex items-center justify-center flex-shrink-0">3</span>
                    <p class="text-sm text-slate-600">Pilih menu <strong>Pay/Bayar</strong> dan upload gambar QR yang sudah disimpan.</p>
                </div>
                <div class="flex gap-4">
                    <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold flex items-center justify-center flex-shrink-0">4</span>
                    <p class="text-sm text-slate-600">Periksa nominal, masukkan PIN, dan klik <strong>Konfirmasi Pembayaran</strong> pada halaman ini.</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="max-w-2xl mx-auto px-4 py-8 text-center text-slate-300 text-xs font-medium uppercase tracking-widest">
        Celengan Masa Depan Secure Checkout
    </footer>

    <script>
        const expiredAt = <?= $_SESSION['payment_expired_at'] * 1000 ?>;
        function updateCountdown() {
            const now = new Date().getTime();
            const diff = expiredAt - now;
            if (diff <= 0) {
                document.getElementById('countdown').innerText = '00:00';
                alert('Waktu pembayaran habis. Transaksi dibatalkan.');
                window.location.href = 'amount.php';
                return;
            }
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            document.getElementById('countdown').innerText = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
        }
        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
</body>
</html>