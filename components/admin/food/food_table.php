<a href="<?= BASE_URL ?>admin/tambah-food"
  class="px-4 py-2 text-white rounded bg-emerald-600 hover:bg-emerald-700">Tambah Makanan
</a>

<div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
  <h4 class="mb-4 text-lg font-semibold">Daftar Makanan</h4>
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
    try {
      const res = await fetch('/api/fetch-all-foods');
      const tBody = document.getElementById('foodTableBody');
      const data = await res.json();
      const foods = data.data;
      tBody.innerHTML = '';

      foods.forEach(food => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="px-4 py-2 text-sm text-gray-700 border">${food.user_id}</td>
        <td class="px-4 py-2 text-sm text-gray-700 border">${food.name}</td>
        <td class="px-4 py-2 text-center border">
          <a href="update-user?user_id=${food.user_id}"
            class="mr-2 text-yellow-500 transition hover:text-yellow-700">
            <i class="fas fa-edit"></i>
          </a>
          <button onclick="unaliveFood(${food.user_id})"
            class="text-red-500 transition hover:text-red-700">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      `;
        tBody.appendChild(row);
      });

    } catch (error) {
      console.error('Error fetching users:', error);
    }
  }

  async function unaliveFood(food_id) {
    try {
      const res = await fetch('/api/food-delete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          food_id: food_id
        })
      });
      const data = await res.json();
      console.log(data);
      fetchFoods();
    } catch (error) {
      console.error('Error fetching users:', error);
    }
  }

  fetchUsers();
</script>