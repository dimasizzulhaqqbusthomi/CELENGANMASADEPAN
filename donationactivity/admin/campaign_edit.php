<?php
require '../backend/function.php';

// Proteksi Akses
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== 'contributor') {
    header("Location: ../../login/index.php");
    exit;
}

// Ambil ID dari URL
$id = $_GET["id"];
$contributor_id = $_SESSION["id"];

// Ambil data kampanye dan pastikan milik kontributor yang sedang login (Anti-IDOR)
$camp = query("SELECT * FROM campaigns WHERE id = $id AND contributor_id = $contributor_id");

if (!$camp) {
    echo "<script>alert('Akses Ilegal: Kampanye tidak ditemukan atau bukan milik Anda.'); window.location.href='dashboard.php';</script>";
    exit;
}

$camp = $camp[0];

if (isset($_POST["submit"])) {
    if (ubah_campaign($_POST) > 0) {
        echo "<script>alert('Data kampanye berhasil diperbarui!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Tidak ada perubahan data atau gagal diperbarui.'); window.location.href='dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kampanye | CMD Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8FAFC] p-6 lg:p-12">
    <div class="max-w-3xl mx-auto">
        <a href="dashboard.php" class="text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors mb-6 inline-block">
            ← Kembali ke Dashboard
        </a>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
            <header class="mb-10">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight italic text-center">Optimasi Kampanye</h2>
                <p class="text-slate-500 mt-2 text-center">Perbarui informasi untuk meningkatkan kepercayaan donatur.</p>
            </header>

            <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="id" value="<?= $camp['id']; ?>">
                <input type="hidden" name="gambarLama" value="<?= $camp['image']; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Nama Program</label>
                        <input type="text" name="name" required value="<?= $camp['name']; ?>"
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Target Dana (Rp)</label>
                        <input type="number" name="target" required value="<?= $camp['target']; ?>"
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Kategori</label>
                        <select name="type" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="bencana" <?= $camp['type'] == 'bencana' ? 'selected' : ''; ?>>Bencana</option>
                            <option value="yayasan" <?= $camp['type'] == 'yayasan' ? 'selected' : ''; ?>>Yayasan</option>
                            <option value="pendidikan" <?= $camp['type'] == 'pendidikan' ? 'selected' : ''; ?>>Pendidikan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Tanggal Berakhir</label>
                        <input type="date" name="end_date" required value="<?= $camp['end_date']; ?>"
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Gambar Sampul</label>
                        <div class="flex items-center gap-4">
                            <img src="../../img/<?= $camp['image']; ?>" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                            <input type="file" name="image" class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-bold">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Deskripsi Kampanye</label>
                    <textarea name="description" rows="5" required 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none resize-none"><?= $camp['description']; ?></textarea>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" name="submit" 
                        class="flex-1 bg-slate-900 text-white font-black py-5 rounded-2xl shadow-xl hover:bg-blue-600 transition-all active:scale-[0.98]">
                        Simpan Perubahan <i class="fa-solid fa-check-double ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>