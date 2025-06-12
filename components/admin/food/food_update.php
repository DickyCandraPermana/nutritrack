<form id="edit-form-makanan" onsubmit="editMakanan(); return false;"
  class="w-full p-8 space-y-8 bg-white shadow rounded-xl">

  <h2 class="mb-2 text-3xl font-bold text-gray-800">Edit Makanan</h2>

  <input type="hidden" name="food_id" id="edit-food_id" value="<?= $_GET['food_id'] ?>">

  <div class="grid grid-cols-2 gap-6">
    <div>
      <label for="edit-nama_makanan" class="block mb-1 font-semibold text-gray-700">Nama Makanan</label>
      <input type="text" name="nama_makanan" id="edit-nama_makanan" required
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="edit-kategori" class="block mb-1 font-semibold text-gray-700">Kategori</label>
      <input type="text" name="kategori" id="edit-kategori"
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div class="col-span-2">
      <label for="edit-deskripsi" class="block mb-1 font-semibold text-gray-700">Deskripsi</label>
      <textarea name="deskripsi" id="edit-deskripsi" rows="4"
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
      <select id="edit-nutrition-select"
        class="w-1/2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <!-- Akan diisi dari JS -->
      </select>
      <button type="button" onclick="addNutritionField()"
        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Tambah Nutrisi</button>
    </div>
    <div id="edit-nutrition-fields" class="space-y-4"></div>
  </div>
</form>

<script>
  const currentFoodDetail = [];
  const currentFood = [];
  const nutritionList = []; // Declare nutritionList

  function getParam(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
  }

  async function getCurrentFood() {
    try {
      const food_id = getParam('food_id');
      const res = await fetch('/nutritrack/api/fetch-food-biasa', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          food_id: food_id
        })
      });
      const data = await res.json();
      // console.log(data); // Removed debugging log
      currentFood.push(...data.data);
    } catch (err) {
      console.error('Failed to fetch food:', err);
      showFlashMessage({
        type: 'error',
        messages: 'Failed to fetch food data.'
      });
    }
  }

  async function getFoodDetail() {
    try {
      const food_id = getParam('food_id');
      const res = await fetch('/nutritrack/api/fetch-food', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          food_id: food_id
        })
      });
      const data = await res.json();
      // console.log(data); // Removed debugging log
      currentFoodDetail.push(...data.data);
    } catch (err) {
      console.error('Failed to fetch food details:', err);
      showFlashMessage({
        type: 'error',
        messages: 'Failed to fetch food details.'
      });
    }
  }

  async function loadNutritions() {
    try {
      // Re-initialize nutritionList to ensure it's fresh
      nutritionList.length = 0; 
      const res = await fetch('/nutritrack/api/fetch-all-nutritions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        }
      });
      const data = await res.json();
      nutritionList.push(...data.data);

      const select = document.getElementById('edit-nutrition-select');
      select.innerHTML = ''; // Clear existing options
      data.data.forEach(nutri => {
        const option = document.createElement('option');
        option.value = nutri.nutrition_id;
        option.textContent = nutri.nama;
        select.appendChild(option);
      });
    } catch (err) {
      console.error('Failed to fetch nutritions:', err);
      showFlashMessage({
        type: 'error',
        messages: 'Failed to load nutritions.'
      });
    }
  }

  function addNutritionField() {
    const selectedId = document.getElementById('edit-nutrition-select').value;
    const selectedText = document.getElementById('edit-nutrition-select').selectedOptions[0].text;
    const fieldContainer = document.getElementById('edit-nutrition-fields');

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

  function renderNutritionFields() {
    const fieldContainer = document.getElementById('edit-nutrition-fields');
    // console.log(Object.values(currentFoodDetail)); // Removed debugging log
    Object.values(currentFoodDetail).forEach(nutrition => {
      const div = document.createElement('div');
      div.className = 'flex items-center gap-4';
      div.innerHTML = `
        <input type="hidden" name="nutritions[][nutrition_id]" value="${nutrition.nutrition_id}">
        <label class="w-1/4">${nutrition.nama}</label>
        <input type="number" name="nutritions[][jumlah]" step="0.01" placeholder="Jumlah"
          class="w-1/4 px-3 py-2 border rounded" required value="${nutrition.jumlah}">
        <input type="text" name="nutritions[][satuan]" placeholder="Satuan (mg, g, etc)"
          class="w-1/4 px-3 py-2 border rounded" required value="${nutrition.satuan}">
      `;
      fieldContainer.appendChild(div);
    });
  }

  async function editMakanan() {
    try {
      const form = document.getElementById('edit-form-makanan');
      const formData = new FormData(form);

      const nutritionData = [];
      const nutritionFields = document.querySelectorAll('#edit-nutrition-fields > div');
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

      // console.log(JSON.stringify(payload)); // Removed debugging log

      const res = await fetch('/nutritrack/api/food-edit', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      const result = await res.json();
      if (result.status === 'success') {
        showFlashMessage({
          type: 'success',
          messages: result.message
        });
        window.location.href = BASE_URL_JS + 'admin/foods';
      } else {
        showFlashMessage({
          type: 'error',
          messages: result.message
        });
      }
    } catch (err) {
      console.error('Submit error:', err);
      showFlashMessage({
        type: 'error',
        messages: 'An unexpected error occurred during food edit.'
      });
    }
  }

  loadNutritions();
  getFoodDetail().then(renderNutritionFields).then(getCurrentFood).then(() => {
    // console.log(currentFood[0].nama_makanan); // Removed debugging log
    document.getElementById('edit-nama_makanan').value = currentFood[0].nama_makanan;
    document.getElementById('edit-deskripsi').value = currentFood[0].deskripsi;
    document.getElementById('edit-kategori').value = currentFood[0].kategori;
  });
</script>
