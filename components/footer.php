<footer class="flex flex-col w-full px-12 py-10 text-md bg-green-950">
  <!-- Bagian atas: Logo & Subscribe -->
  <div class="flex flex-wrap items-center justify-between w-full pb-8 border-b-2 border-green-300">
    <a class="flex items-center gap-3" href="<?= BASE_URL ?>">
      <img src="public/assets/logo.png" alt="Nutritrack Logo" class="w-8 h-8">
      <h3 class="text-xl font-bold text-white translate-y-[2px]">Nutritrack</h3>
    </a>

    <form action="" class="flex items-center gap-2">
      <div class="relative">
        <i class="absolute text-gray-500 -translate-y-1/2 left-3 top-1/2 fa-solid fa-envelope"></i>
        <input type="email" name="footer-email" id="footer-email" class="p-2 pl-10 text-black rounded-md focus:outline-none" placeholder="Masukkan email Anda">
      </div>
      <button type="submit" class="flex items-center gap-2 px-4 py-2 text-white transition bg-blue-600 rounded-md hover:bg-blue-700">
        Subscribe <i class="fa-solid fa-paper-plane"></i>
      </button>
    </form>
  </div>

  <!-- Navigasi Footer -->
  <div class="flex flex-wrap justify-start gap-20 py-8 text-white border-b-2 border-green-300">
    <div>
      <h2 class="mb-4 text-xl font-bold">Tentang Kami</h2>
      <ul class="flex flex-col gap-2">
        <li><a href="#" class="hover:underline">Profil</a></li>
        <li><a href="#" class="hover:underline">Fitur</a></li>
        <li><a href="#" class="hover:underline">Blog</a></li>
        <li><a href="#" class="hover:underline">FAQ</a></li>
        <li><a href="#" class="hover:underline">Kontak</a></li>
      </ul>
    </div>

    <div>
      <h2 class="mb-4 text-xl font-bold">Bantuan</h2>
      <ul class="flex flex-col gap-2">
        <li><a href="#" class="hover:underline">Pusat Bantuan</a></li>
        <li><a href="#" class="hover:underline">Kebijakan Privasi</a></li>
        <li><a href="#" class="hover:underline">Ketentuan Layanan</a></li>
      </ul>
    </div>

    <div>
      <h2 class="mb-4 text-xl font-bold">Sosial Media</h2>
      <ul class="flex flex-col gap-2">
        <li><a href="#" class="flex items-center gap-2 hover:underline"><i class="fa-brands fa-facebook"></i> Facebook</a></li>
        <li><a href="#" class="flex items-center gap-2 hover:underline"><i class="fa-brands fa-instagram"></i> Instagram</a></li>
        <li><a href="#" class="flex items-center gap-2 hover:underline"><i class="fa-brands fa-twitter"></i> Twitter</a></li>
        <li><a href="#" class="flex items-center gap-2 hover:underline"><i class="fa-brands fa-tiktok"></i> TikTok</a></li>
      </ul>
    </div>
  </div>

  <!-- Copyright -->
  <div class="py-6 text-center text-white">
    <span>&copy; 2025 NutriTrack. All Rights Reserved</span>
  </div>
</footer>