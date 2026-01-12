<?php
require '../backend/function.php';

// Proteksi Akses
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== 'contributor') {
    header("Location: ../login/index.php");
    exit;
}

if (isset($_POST["submit"])) {
    // Advisor Note: Fungsi tambah() harus menangani upload gambar dan pengaitan ID kontributor
    if (tambah_campaign($_POST) > 0) {
        echo "<script>alert('Kampanye berhasil diluncurkan!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal meluncurkan kampanye.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buat Kampanye Baru | CMD Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 p-6 lg:p-12">
    <div class="max-w-3xl mx-auto">
        <a href="dashboard.php" class="text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors mb-6 inline-block">
            ← Kembali ke Dashboard
        </a>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
            <header class="mb-10 text-center">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight italic">Luncurkan Kampanye Baru</h2>
                <p class="text-slate-500 mt-2">Pastikan informasi yang Anda berikan akurat dan transparan.</p>
            </header>

            <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="contributor_id" value="<?= $_SESSION['id']; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Program</label>
                        <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Bantuan Pangan Lansia">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Target Dana (Rp)</label>
                        <input type="number" name="target" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none" placeholder="10000000">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                        <select name="type" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="bencana">Bencana</option>
                            <option value="yayasan">Yayasan</option>
                            <option value="pendidikan">Pendidikan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tanggal Berakhir</label>
                        <input type="date" name="end_date" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Cover Kampanye</label>
                        <input type="file" name="image" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" rows="5" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none resize-none"></textarea>
                </div>

                <button type="submit" name="submit" class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl shadow-xl hover:bg-blue-600 transition-all active:scale-[0.98]">
                    Publikasikan Kampanye <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>