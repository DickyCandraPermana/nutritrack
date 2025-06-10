<?php
$features = [
  ["icon" => "chart.svg", "text" => "Menampilkan statistik kepenuhan gizi harian pengguna"],
  ["icon" => "camera.svg", "text" => "Scan kandungan berbahaya di makanan"],
  ["icon" => "search.svg", "text" => "Mencari kandungan gizi dalam sebuah makanan"],
  ["icon" => "chart.svg", "text" => "Menampilkan statistik kepenuhan gizi harian pengguna"]
];
?>

<div class="flex flex-col items-center justify-center w-full h-full p-10 bg-green-500 keunggulan">
  <article class="flex flex-col items-center justify-center w-full gap-2 text-center">
    <h2 class="text-4xl font-bold text-white">
      Apa itu Nutri<span class="text-black">Track?</span>
    </h2>
    <p class="max-w-xl text-md text-slate-100">
      NutriTrack merupakan sebuah aplikasi berbasis web yang digunakan untuk melakukan scan makanan dan regulasi pola makan pengguna.
    </p>
  </article>

  <section class="flex flex-wrap items-center justify-center gap-6 mt-5">
    <?php foreach ($features as $feature) : ?>
      <div class="group flex flex-col items-center justify-center min-w-[250px] min-h-[200px] w-64 gap-2 p-4 mt-8 bg-white bg-opacity-80 border border-gray-300 rounded-md shadow-md transition-all duration-300 hover:scale-105 hover:translate-y-[-5px] hover:shadow-xl">
        <img src="public/assets/icons/<?= $feature['icon'] ?>"
          alt="Feature Icon"
          class="w-12 h-12 transition-all duration-300 group-hover:w-16 group-hover:h-16">
        <p class="text-center text-gray-800"><?= $feature['text'] ?></p>
      </div>

    <?php endforeach; ?>
  </section>

  <button class="px-12 py-3 mt-10 font-medium text-white uppercase transition-all duration-500 bg-green-800 rounded-lg shadow hover:scale-110 hover:drop-shadow-lg hover:bg-green-900" onclick="window.location.href = BASE_URL_JS + 'register'; ">
    Get Started
  </button>
</div>
