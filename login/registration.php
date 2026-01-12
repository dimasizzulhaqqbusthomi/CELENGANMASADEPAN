<?php
require '../backend/function.php';

if (isset($_POST["submit"])) {
  if (registrasi($_POST) > 0) {
        $_SESSION['success'] = "Berhasil Registrasi Akun";
        header("Location: index.php");
        exit;
    } else {
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Akun | Celengan Masa Depan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full flex flex-col lg:flex-row bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
            
            <div class="lg:w-2/5 bg-blue-600 p-10 flex flex-col justify-between text-white relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 rounded-full -mr-16 -mt-16 opacity-40"></div>
                
                <div class="relative z-10">
                    <h1 class="font-extrabold text-2xl tracking-tight">Celengan<span class="text-blue-200">MasaDepan</span></h1>
                    <div class="mt-12">
                        <h2 class="text-3xl font-bold leading-tight">Bergabunglah dalam Misi Kebaikan</h2>
                        <p class="text-blue-100 mt-4 text-sm leading-relaxed">Satu akun untuk mengelola seluruh donasi Anda, melacak dampak bantuan, dan membantu sesama dengan lebih transparan.</p>
                    </div>
                </div>

                <div class="relative z-10 hidden lg:block">
                    <img src="../img/Login.svg" alt="Register Illustration" class="w-full opacity-90 scale-110" />
                </div>

                <div class="relative z-10 text-xs text-blue-200 font-medium tracking-wide">
                    DIPERCAYA OLEH RIBUAN DONATUR
                </div>
            </div>

            <div class="flex-1 p-8 lg:p-12">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Akun Baru</h2>
                        <p class="text-slate-500 text-sm mt-1">Lengkapi data di bawah untuk memulai.</p>
                    </div>
                    <a href="index.php" class="text-slate-400 hover:text-blue-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>

                <?php if (isset($_SESSION['terdaftar'])) : ?>
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-lg flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span><?= $_SESSION['terdaftar'] ?></span>
                    </div>
                <?php unset($_SESSION['terdaftar']); endif ?>

                <form action="" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                            placeholder="Adit Maulana" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Username</label>
                        <input type="text" name="username" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                            placeholder="adit_maulana" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                            placeholder="adit@example.com" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon</label>
                        <input type="number" name="no_telp" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                            placeholder="08123456789" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                            placeholder="••••••••" />
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <button type="submit" name="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-8 border-t border-slate-100 text-center">
                    <p class="text-slate-500 text-sm">
                        Sudah punya akun? 
                        <a href="index.php" class="text-blue-600 font-bold hover:underline ml-1">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>