<div class="p-6 bg-gray-100 rounded-lg shadow-md">
  <h2 class="mb-4 text-xl font-semibold text-gray-800">Makanan Dikonsumsi</h2>

  <form id="tambahMakananForm" onsubmit="addFoodEntry(); return false;" class="space-y-4">
    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
    <!-- Dropdown Alpine -->
    <div x-data="dropdownData()" class="relative w-64">
      <!-- Button -->
      <button type="button" @click="open = !open" class="flex items-center justify-between w-full p-3 bg-white border border-gray-300 rounded-lg shadow">
        <span x-text="selected.nama_makanan ? selected.nama_makanan : 'Pilih Makanan'"></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Dropdown -->
      <div x-show="open" @click.away="open = false" class="absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg">
        <!-- Search Bar -->
        <input type="text" x-model="search" placeholder="Cari..." class="w-full p-2 border-b border-gray-300 focus:outline-none">

        <!-- Options -->
        <ul class="overflow-y-auto max-h-40">
          <template x-for="item in filteredItems" :key="item.food_id">
            <li @click="selected = item; open = false; search = ''; updateSatuanDisplay();" class="p-3 cursor-pointer hover:bg-gray-100">
              <span x-text="item.nama_makanan"></span>
            </li>
          </template>
        </ul>
      </div>

      <!-- Hidden Input to Send Value -->
      <input type="hidden" name="food_id" x-model="selected.food_id">
    </div>

    <!-- Catatan -->
    <div class="flex flex-col gap-2">
      <label for="catatan" class="font-medium text-gray-700">Catatan</label>
      <input type="text" name="catatan" id="catatan" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan catatan">
    </div>

    <!-- Jenis Makan -->
    <div class="flex flex-col gap-2">
      <label for="waktu_makan" class="font-medium text-gray-700">Jenis Makan</label>
      <select name="waktu_makan" id="waktu_makan" class="w-full p-3 bg-white border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-blue-400">
        <option value="sarapan">Sarapan</option>
        <option value="makan siang">Makan Siang</option>
        <option value="makan malam">Makan Malam</option>
        <option value="snack">Snack</option>
      </select>
    </div>

    <!-- Jumlah Porsi dan Satuan -->
    <div class="flex items-end gap-2">
      <div class="flex flex-col flex-1 gap-2">
        <label for="jumlah_porsi" class="font-medium text-gray-700">Jumlah Porsi</label>
        <input type="number" name="jumlah_porsi" id="jumlah_porsi" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan jumlah porsi" min="1" value="1">
      </div>
      <div class="flex-none">
        <span id="satuan_display" class="p-2 text-gray-700 bg-gray-200 border border-gray-300 rounded-md">Satuan</span>
      </div>
    </div>

    <!-- Submit -->
    <div class="flex flex-col gap-2">
      <button type="submit" class="w-full p-3 text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-400">Simpan</button>
    </div>
  </form>
</div>


<script>
  function dropdownData() {
    return {
      open: false,
      search: '',
      selected: {
        food_id: '',
        nama_makanan: 'Pilih Makanan', // Initialize with default text
        satuan: 'Satuan' // Initialize with default unit
      },
      items: <?php
              echo json_encode($foodData);
              ?>,
      get filteredItems() {
        return this.items.filter(i => i.nama_makanan.toLowerCase().includes(this.search.toLowerCase()));
      },
      updateSatuanDisplay() {
        document.getElementById('satuan_display').innerText = this.selected.satuan || 'Satuan';
      }
    }
  }

  async function addFoodEntry() {
    try {
      const form = document.getElementById('tambahMakananForm');
      const formData = new FormData(form);

      const data = {};
      for (let [key, value] of formData.entries()) {
        data[key] = value;
      }
      // Add satuan from selected food item
      data['satuan'] = document.getElementById('satuan_display').innerText;

      const res = await fetch(BASE_URL_JS + 'api/user-tambah-makanan', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });

      const result = await res.json();
      console.log(result);

      if (result.status === 'success') {
        showFlashMessage({
          type: 'success',
          messages: result.message
        });
        console.log('Attempting redirect to:', BASE_URL_JS + 'profile/tracking');
        window.location.href = BASE_URL_JS + 'profile/tracking';
      } else {
        showFlashMessage({
          type: 'error',
          messages: result.message
        });
      }
    } catch (err) {
      console.error('Error:', err);
      showFlashMessage({
        type: 'error',
        messages: 'An unexpected error occurred.'
      });
    }
  }
</script>
