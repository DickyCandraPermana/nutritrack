<form id="formInputMakanan" onsubmit="inputMakanan(); return false;"
  class="w-full p-8 space-y-8 bg-white shadow rounded-xl">

  <h2 class="mb-2 text-3xl font-bold text-gray-800">Tambah Makanan</h2>

  <div class="grid grid-cols-2 gap-6">
    <div>
      <label for="nama_makanan" class="block mb-1 font-semibold text-gray-700">Nama Makanan</label>
      <input type="text" name="nama_makanan" id="nama_makanan" required
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="kategori" class="block mb-1 font-semibold text-gray-700">Kategori</label>
      <input type="text" name="kategori" id="kategori"
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div class="col-span-2">
      <label for="deskripsi" class="block mb-1 font-semibold text-gray-700">Deskripsi</label>
      <textarea name="deskripsi" id="deskripsi" rows="4"
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
    </div>
  </div>

  <div class="pt-4">
    <button type="submit"
      class="px-6 py-2 font-semibold text-white transition-all duration-300 rounded-md bg-emerald-600 hover:bg-emerald-700">
      Input Makanan
    </button>
  </div>

  <!-- Dropdown + Button Tambah Nutrisi -->
  <div class="mt-8">
    <label class="block mb-1 font-semibold text-gray-700">Tambah Nutrisi</label>
    <div class="flex gap-4 mb-4">
      <select id="nutritionSelect"
        class="w-1/2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <!-- Akan diisi dari JS -->
      </select>
      <button type="button" onclick="addNutritionField()"
        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Tambah Nutrisi</button>
    </div>
    <div id="nutritionFields" class="space-y-4"></div>
  </div>
</form>




<script>
  const nutritionList = [];

  async function loadNutritions() {
    try {
      const res = await fetch('/nutritrack/api/fetch-all-nutritions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        }
      }); // Ganti sesuai endpoint lo
      const data = await res.json();
      nutritionList.push(...data.data);

      const select = document.getElementById('nutritionSelect');
      data.data.forEach(nutri => {
        const option = document.createElement('option');
        option.value = nutri.nutrition_id;
        option.textContent = nutri.nama;
        select.appendChild(option);
      });
    } catch (err) {
      console.error('Failed to fetch nutritions:', err);
    }
  }

  function addNutritionField() {
    const selectedId = document.getElementById('nutritionSelect').value;
    const selectedText = document.getElementById('nutritionSelect').selectedOptions[0].text;
    const fieldContainer = document.getElementById('nutritionFields');

    const div = document.createElement('div');
    div.className = 'flex items-center gap-4';
    div.innerHTML = `
      <input type="hidden" name="nutritions[][nutrition_id]" value="${selectedId}">
      <label class="w-1/4">${selectedText}</label>
      <input type="number" name="nutritions[][jumlah]" step="0.01" placeholder="Jumlah"
        class="w-1/4 px-3 py-2 border rounded" required>
      <input type="text" name="nutritions[][satuan]" placeholder="Satuan (mg, g, etc)"
        class="w-1/4 px-3 py-2 border rounded" required>
    `;
    fieldContainer.appendChild(div);
  }

  async function inputMakanan() {
    try {
      const form = document.getElementById('formInputMakanan');
      const formData = new FormData(form);

      // Nutrisi
      const nutritionData = [];
      const nutritionFields = document.querySelectorAll('#nutritionFields > div');
      nutritionFields.forEach(field => {
        const inputs = field.querySelectorAll('input');
        nutritionData.push({
          nutrition_id: inputs[0].value,
          jumlah: inputs[1].value,
          satuan: inputs[2].value
        });
      });

      const payload = Object.fromEntries(formData);
      payload.nutrisis = nutritionData;

      console.log(JSON.stringify(payload));
      const res = await fetch('/nutritrack/api/food-input', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      const result = await res.json();
      console.log(result);

      if (result.status === 'success') {
        showFlashMessage("success", result.message[0]);
        window.location.href = '<?= BASE_URL ?>admin/foods';
      } else {
        showFlashMessage("error", result.message[0]);
      }
    } catch (err) {
      console.error('Submit error:', err);
    }
  }

  loadNutritions();
</script>