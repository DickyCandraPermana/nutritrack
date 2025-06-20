<div class="flex items-center justify-center p-6 bg-gray-100">
  <div class="w-full max-w-2xl p-6 bg-white shadow-md rounded-xl">
    <h2 class="mb-6 text-3xl font-bold text-center text-green-600">Frequently Asked Questions</h2>

    <div class="space-y-4">
      <!-- FAQ Item 1 -->
      <div class="border rounded-lg">
        <button class="flex items-center justify-between w-full px-4 py-3 text-lg font-medium text-left toggle-btn">
          Apa itu NutriTrack?
          <svg class="w-5 h-5 transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div class="hidden px-4 py-3">
          Lorem ipsum (/ˌlɔː.rəm ˈɪp.səm/ LOR-əm IP-səm) is a dummy or placeholder text commonly used in graphic design, publishing, and web development. Its purpose is to permit a page layout to be designed, independently of the copy that will subsequently populate it, or to demonstrate various fonts of a typeface without meaningful text that could be distracting.
        </div>
      </div>

      <!-- FAQ Item 2 -->
      <div class="border rounded-lg">
        <button class="flex items-center justify-between w-full px-4 py-3 text-lg font-medium text-left toggle-btn">
          Apakah saja fitur dalam web NutriTrack?
          <svg class="w-5 h-5 transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div class="hidden px-4 py-3">
          Lorem ipsum (/ˌlɔː.rəm ˈɪp.səm/ LOR-əm IP-səm) is a dummy or placeholder text commonly used in graphic design, publishing, and web development. Its purpose is to permit a page layout to be designed, independently of the copy that will subsequently populate it, or to demonstrate various fonts of a typeface without meaningful text that could be distracting.
        </div>
      </div>

      <!-- FAQ Item 3 -->
      <div class="border rounded-lg">
        <button class="flex items-center justify-between w-full px-4 py-3 text-lg font-medium text-left toggle-btn">
          Bagaimana cara menggunakan NutriTrack?
          <svg class="w-5 h-5 transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div class="hidden px-4 py-3">
          Lorem ipsum (/ˌlɔː.rəm ˈɪp.səm/ LOR-əm IP-səm) is a dummy or placeholder text commonly used in graphic design, publishing, and web development. Its purpose is to permit a page layout to be designed, independently of the copy that will subsequently populate it, or to demonstrate various fonts of a typeface without meaningful text that could be distracting.
        </div>
      </div>
    </div>
  </div>

  <script>
    // JavaScript Accordion Toggle
    document.querySelectorAll('.toggle-btn').forEach((btn) => {
      btn.addEventListener('click', () => {
        const content = btn.nextElementSibling;
        const icon = btn.querySelector('svg');
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
      });
    });
  </script>
</div>