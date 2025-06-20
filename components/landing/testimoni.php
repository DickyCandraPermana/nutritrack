<?php
$testimonials = [
  ["name" => "Nurul Firdaus", "role" => "Dosen Anapersis", "image" => "public/assets/testi1.png", "text" => "NutriTrack sangat membantu saya dalam memantau asupan gizi harian. UI-nya simpel dan mudah digunakan!"],
  ["name" => "Agus Purbayu", "role" => "Dosen Pemweb", "image" => "public/assets/testi2.png", "text" => "Aplikasi ini sangat membantu saya menjaga pola makan yang seimbang untuk performa terbaik saya."],
];
?>

<div class="relative w-full h-full features bg-gradient-to-t from-green-50 to-green-100" id="testimoni">
  <svg xmlns="http://www.w3.org/2000/svg" class="absolute -mt-4 drop-shadow-md" viewBox="0 0 1440 320">
    <path fill="#22c55e" fill-opacity="1" d="M0,32L48,48C96,64,192,96,288,133.3C384,171,480,213,576,213.3C672,213,768,171,864,154.7C960,139,1056,149,1152,133.3C1248,117,1344,75,1392,53.3L1440,32L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
  </svg>

  <div class="mt-[190px] flex flex-col gap-8 justify-around p-10">
    <h2 class="w-full text-3xl font-bold text-center text-green-500">Apa Kata Mereka</h2>
    <div class="flex flex-row justify-around">
      <?php foreach ($testimonials as $testi) : ?>
        <div class="flex flex-col items-center justify-center w-1/3 gap-3 px-4 py-8 text-center transition-all duration-300 bg-white rounded-tr-sm rounded-bl-sm shadow-md group bg-opacity-35 rounded-tl-3xl rounded-br-3xl shadow-slate-200 hover:-translate-y-2 hover:scale-105">
          <img src="<?= $testi['image'] ?>" class="w-24 h-24 transition-all duration-300 rounded-full drop-shadow-md group-hover:scale-110" alt="">
          <p class="text-sm text-slate-700"><?= $testi['text'] ?></p>
          <div>
            <h3 class="font-semibold"><?= $testi['name'] ?></h3>
            <p class="text-sm font-medium"><?= $testi['role'] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>