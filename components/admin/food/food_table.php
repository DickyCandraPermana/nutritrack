<a href="<?= BASE_URL ?>admin/tambah-food"
  class="px-4 py-2 text-white rounded bg-emerald-600 hover:bg-emerald-700">Tambah Makanan
</a>

<div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
  <h4 class="mb-4 text-lg font-semibold">Daftar Makanan</h4>
  <div class="mb-4">
    <input type="text" id="foodSearchInput" placeholder="Cari makanan..."
      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
  </div>
  <table class="min-w-full border border-gray-300 table-auto">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 border">ID</th>
        <th class="px-4 py-2 border">Nama Makanan</th>
        <th class="px-4 py-2 border">Aksi</th>
      </tr>
    </thead>
    <tbody id="foodTableBody" class="text-center"></tbody>
  </table>
</div>

<script>
  async function fetchFoods() {
    const searchInput = document.getElementById('foodSearchInput');
    const searchValue = searchInput ? searchInput.value : '';

    try {
      const res = await fetch('/nutritrack/api/fetch-all-foods', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          search: searchValue,
          perPage: 500,
          page: 1
        })
      });
      const tBody = document.getElementById('foodTableBody');
      const data = await res.json();
      const foods = data.data[0];
      // console.log(foods); // Removed debugging log
      tBody.innerHTML = '';

      foods.forEach(food => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="px-4 py-2 text-sm text-gray-700 border">${food.id}</td>
        <td class="px-4 py-2 text-sm text-left text-gray-700 border">${food.nama}</td>
        <td class="px-4 py-2 text-center border">
          <a href="update-food?food_id=${food.id}"
            class="mr-2 text-yellow-500 transition hover:text-yellow-700">
            <i class="fas fa-edit"></i>
          </a>
          <button onclick="unaliveFood(${food.id})"
            class="text-red-500 transition hover:text-red-700">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      `;
        tBody.appendChild(row);
      });

    } catch (error) {
      console.error('Error fetching foods:', error);
      showFlashMessage({
        type: 'error',
        messages: 'Failed to fetch food list.'
      });
    }
  }

  async function unaliveFood(food_id) {
    try {
      const res = await fetch('/nutritrack/api/food-delete', {
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
      if (data.status === 'success') {
        showFlashMessage({
          type: 'success',
          messages: data.message
        });
      } else {
        showFlashMessage({
          type: 'error',
          messages: data.message
        });
      }
      fetchFoods();
    } catch (error) {
      console.error('Error deleting food:', error);
      showFlashMessage({
        type: 'error',
        messages: 'Failed to delete food.'
      });
    }
  }

  fetchFoods();

  document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('foodSearchInput');
    if (searchInput) {
      searchInput.addEventListener('input', fetchFoods);
    }
  });
</script>
