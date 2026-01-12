<?php
require 'backend/function.php';

$bencana = query("SELECT * FROM v_campaigns_per_contributor WHERE type = 'bencana'");
$yayasan = query("SELECT * FROM v_campaigns_per_contributor WHERE type = 'yayasan'");
$pendidikan = query("SELECT * FROM v_campaigns_per_contributor WHERE type = 'pendidikan'");

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Celengan Masa Depan | Platform Donasi Modern</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/e4ca1991ae.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
      body {
        font-family: "Plus Jakarta Sans", sans-serif;
        background-color: #f8fafc;
      }
      .card-zoom:hover img {
        transform: scale(1.05);
      }
    </style>
  </head>
  <body class="text-slate-800">
    <header class="hidden lg:block bg-blue-600 text-white/90 py-2">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center text-xs font-medium tracking-wide uppercase">
          <div class="flex gap-6 items-center">
            <span><i class="fa-solid fa-location-dot mr-2 text-blue-200"></i> Gresik, East Java</span>
            <span><i class="fa-solid fa-envelope mr-2 text-blue-200"></i> info@smam1gresik.sch.id</span>
          </div>
          <div class="flex gap-4 items-center text-base">
            <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-youtube"></i></a>
          </div>
        </div>
    </header>

    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-slate-100">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-20">
          <h1 class="font-extrabold text-2xl tracking-tight bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">
            Celengan<span class="text-slate-900">MasaDepan</span>
          </h1>
          
          <ul class="hidden md:flex gap-8 items-center font-semibold text-slate-600 text-sm">
            <li><a href="#" class="hover:text-blue-600 transition-colors">Home</a></li>
            <li><a href="#bencana" class="hover:text-blue-600 transition-colors">Bencana</a></li>
            <li><a href="#yayasan" class="hover:text-blue-600 transition-colors">Yayasan</a></li>
            <li><a href="#pendidikan" class="hover:text-blue-600 transition-colors">Pendidikan</a></li>
            
            <?php if (!isset($_SESSION["login"])) : ?>
              <li><a href="login/index.php" class="bg-blue-600 text-white px-6 py-2.5 rounded-full hover:bg-blue-700 shadow-md shadow-blue-200 transition-all active:scale-95">Login</a></li>
            <?php else : ?>
              <li><a href="login/logout.php" class="border-2 border-red-100 text-red-500 px-6 py-2 rounded-full hover:bg-red-50 transition-all">Logout</a></li>
            <?php endif ?>
          </ul>
          
          <div class="md:hidden text-2xl"><i class="fa-solid fa-bars"></i></div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-12">
      
      <?php 
      $sections = [
        ['id' => 'bencana', 'title' => 'Tanggap Bencana', 'desc' => 'Bantu saudara kita pulih dari musibah.', 'data' => $bencana],
        ['id' => 'yayasan', 'title' => 'Dukungan Yayasan', 'desc' => 'Mendukung program keberlanjutan sosial.', 'data' => $yayasan],
        ['id' => 'pendidikan', 'title' => 'Donasi Pendidikan', 'desc' => 'Wujudkan mimpi anak bangsa.', 'data' => $pendidikan]
      ];

      foreach ($sections as $sec) : ?>
      <section id="<?= $sec['id'] ?>" class="mb-20 scroll-mt-24">
        <div class="mb-8 border-l-4 border-blue-600 pl-4">
          <h2 class="text-3xl font-extrabold text-slate-900"><?= $sec['title'] ?></h2>
          <p class="text-slate-500 mt-1"><?= $sec['desc'] ?></p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <?php foreach($sec['data'] as $row) : 
              $total = (int) $row['total_amount'];
              $target = (int) $row['target'];
              $progress = $target > 0 ? min(100, ($total / $target) * 100) : 0;
          ?>
          <a href="donationactivity/index.php?id=<?= $row["campaign_id"] ?>" class="group">
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 flex flex-col h-full">
              <div class="relative overflow-hidden h-48 card-zoom">
                <img src="img/<?= $row["image"] ?>" class="w-full h-full object-cover transition-transform duration-500" alt="<?= $row["campaign_name"] ?>" />
                <div class="absolute top-3 left-3">
                    <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest text-blue-600 shadow-sm">
                        <?= $sec['id'] ?>
                    </span>
                </div>
              </div>

              <div class="p-5 flex flex-col flex-1">
                <span class="text-blue-600 text-xs font-bold uppercase tracking-tighter mb-2 block"><?= $row["contributor_name"] ?></span>
                <h3 class="font-bold text-lg text-slate-800 leading-snug group-hover:text-blue-600 transition-colors line-clamp-2 mb-4">
                  <?= $row["campaign_name"] ?>
                </h3>
                
                <div class="mt-auto">
                    <div class="flex justify-between items-end mb-2">
                        <div class="text-xs text-slate-400 font-medium">Terkumpul</div>
                        <div class="text-sm font-bold text-slate-900">Rp <?= number_format($total, 0, ',', '.') ?></div>
                    </div>
                    
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                      <div class="bg-gradient-to-r from-blue-600 to-cyan-400 h-full rounded-full transition-all duration-1000" style="width: <?= round($progress) ?>%"></div>
                    </div>
                    <div class="mt-2 text-[11px] font-bold text-blue-600 text-right"><?= round($progress) ?>% Tercapai</div>
                </div>
              </div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endforeach; ?>

    </main>

    <footer id="contact" class="bg-slate-900 text-slate-300 pt-20">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 pb-16 border-b border-white/10">
          <div>
            <h1 class="font-extrabold text-2xl text-white mb-6">Celengan<span class="text-blue-400">MasaDepan</span></h1>
            <p class="text-sm leading-relaxed mb-6">Platform aman dan terpercaya untuk menyalurkan kepedulian Anda kepada mereka yang membutuhkan.</p>
            <div class="flex gap-4">
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all"><i class="fa-brands fa-instagram"></i></a>
            </div>
          </div>
          
          <div>
            <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-xs">Navigasi Cepat</h4>
            <ul class="space-y-4 text-sm font-medium">
              <li><a href="#bencana" class="hover:text-blue-400 transition-colors">Donasi Bencana</a></li>
              <li><a href="#yayasan" class="hover:text-blue-400 transition-colors">Donasi Yayasan</a></li>
              <li><a href="#pendidikan" class="hover:text-blue-400 transition-colors">Donasi Pendidikan</a></li>
            </ul>
          </div>

          <div>
            <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-xs">Kontak Kantor</h4>
            <div class="flex gap-4 mb-4 text-sm">
                <i class="fa-solid fa-location-dot text-blue-400 mt-1"></i>
                <p>Jl. KH. Kholil 90 Gresik<br>61116, East Java</p>
            </div>
            <div class="flex gap-4 text-sm">
                <i class="fa-solid fa-phone text-blue-400"></i>
                <p>+62 878-7217-6733</p>
            </div>
          </div>

          <div>
            <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-xs">Jam Operasional</h4>
            <div class="bg-white/5 p-4 rounded-2xl">
                <p class="text-sm">Setiap Hari</p>
                <p class="text-xl font-bold text-white">06.00 — 21.00</p>
                <p class="text-[10px] uppercase tracking-widest text-slate-500 mt-1">Waktu Indonesia Barat</p>
            </div>
          </div>
        </div>

        <div class="py-8 text-center text-xs font-medium tracking-wide text-slate-500 uppercase">
          © 2026 Celengan Masa Depan. Crafted with integrity in Bangkalan.
        </div>
      </div>
    </footer>
  </body>
</html>