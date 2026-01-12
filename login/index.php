<?php
require '../backend/function.php';

// Jika sudah login, arahkan ke dashboard masing-masing secara otomatis
if (isset($_SESSION["login"])) {
    if ($_SESSION["role"] === 'contributor') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit;
}

if (isset($_POST['submit'])) {
    // Sanitasi input untuk mencegah SQL Injection
    $username = mysqli_real_escape_string($conn, strtolower($_POST['username']));
    $password = $_POST['password'];

    // STRATEGI: Cek tabel Contributors (Skema Database: contributors)
    $query_admin = "SELECT * FROM contributors WHERE username = '$username'";
    $res_admin = mysqli_query($conn, $query_admin);

    if (mysqli_num_rows($res_admin) === 1) {
        $row = mysqli_fetch_assoc($res_admin);
        // Verifikasi Hash Password (bcrypt)
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = 'contributor'; // Penentu hak akses Dashboard
            header("Location: ../admin/dashboard.php");
            exit;
        }
    }

    // STRATEGI: Jika bukan kontributor, cek tabel Donors (Skema Database: donors)
    $query_donor = "SELECT * FROM donors WHERE username = '$username'";
    $res_donor = mysqli_query($conn, $query_donor);

    if (mysqli_num_rows($res_donor) === 1) {
        $row = mysqli_fetch_assoc($res_donor);
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = 'donor';
            header("Location: ../index.php");
            exit;
        }
    }

    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk | Celengan Masa Depan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 leading-relaxed">
    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <div class="hidden lg:flex lg:w-1/2 bg-blue-600 p-12 flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500 rounded-full -mr-32 -mt-32 opacity-50"></div>
            <div class="relative z-10">
                <h1 class="text-white font-extrabold text-3xl tracking-tight italic">Celengan<span class="text-blue-200">MasaDepan</span></h1>
            </div>
            <div class="relative z-10">
                <img src="../img/Login.svg" alt="Illustration" class="w-full max-w-md mx-auto mb-10 drop-shadow-2xl" />
                <h2 class="text-white text-4xl font-bold leading-tight italic">Satu Gerbang <br/>Beribu Kebaikan.</h2>
                <p class="text-blue-100 mt-4 text-lg max-w-sm">Login untuk mulai menggalang dana atau berdonasi secara aman.</p>
            </div>
            <div class="relative z-10 text-blue-200 text-sm">© 2026 Celengan Masa Depan</div>
        </div>

        <div class="flex-1 flex flex-col justify-center px-6 py-12 lg:px-24 bg-white">
            <div class="max-w-sm mx-auto w-full">
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight italic">Selamat Datang</h2>
                    <p class="text-slate-500 mt-2 font-medium">Masukkan kredensial Anda untuk masuk.</p>
                </div>

                <?php if (isset($error)) : ?>
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-lg flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>Username atau Password salah!</span>
                    </div>
                <?php endif ?>

                <form action="" method="post" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Username</label>
                        <input type="text" name="username" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300" 
                            placeholder="aditmaulana / yayasan_kita" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300" 
                            placeholder="••••••••" />
                    </div>

                    <button type="submit" name="submit" 
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-extrabold py-4 rounded-2xl shadow-lg transition-all active:scale-[0.98]">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-slate-400 text-sm font-medium">
                        Butuh akun donatur? <a href="registration.php" class="text-blue-600 font-bold hover:underline">Daftar</a>
                    </p>
                    <div class="mt-8 border-t border-slate-100 pt-8">
                        <a href="../index.php" class="text-slate-400 text-xs font-bold uppercase tracking-widest hover:text-slate-900 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i> Beranda Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>