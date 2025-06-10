<a href="<?= BASE_URL ?>admin/tambah-user"
  class="px-4 py-2 text-white rounded bg-emerald-600 hover:bg-emerald-700">Tambah User
</a>

<div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
  <h4 class="mb-4 text-lg font-semibold">Daftar User</h4>
  <table class="min-w-full border border-gray-300 table-auto">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 border">ID</th>
        <th class="px-4 py-2 border">Username</th>
        <th class="px-4 py-2 border">Email</th>
        <th class="px-4 py-2 border">Role</th>
        <th class="px-4 py-2 border">Aksi</th>
      </tr>
    </thead>
    <tbody id="userTableBody" class="text-center"></tbody>
  </table>
</div>

<script>
  async function fetchUsers() {
    try {
      const res = await fetch('/nutritrack/api/fetch-all-users');
      const tBody = document.getElementById('userTableBody');
      const data = await res.json();
      const users = data.data;
      tBody.innerHTML = '';

      users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="px-4 py-2 text-sm text-gray-700 border">${user.user_id}</td>
        <td class="px-4 py-2 text-sm text-gray-700 border">${user.username}</td>
        <td class="px-4 py-2 text-sm text-gray-700 border">${user.email}</td>
        <td class="px-4 py-2 text-sm text-gray-700 capitalize border">${user.role}</td>
        <td class="px-4 py-2 text-center border">
          <a href="update-user?user_id=${user.user_id}"
            class="mr-2 text-yellow-500 transition hover:text-yellow-700">
            <i class="fas fa-edit"></i>
          </a>
          <button onclick="unaliveUser(${user.user_id})"
            class="text-red-500 transition hover:text-red-700">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      `;
        tBody.appendChild(row);
      });

    } catch (error) {
      console.error('Error fetching users:', error);
      showFlashMessage({
        type: 'error',
        messages: 'Failed to fetch users.'
      });
    }
  }

  async function unaliveUser(user_id) {
    try {
      const res = await fetch('/nutritrack/api/user-delete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: user_id
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
      fetchUsers();
    } catch (error) {
      console.error('Error deleting user:', error);
      showFlashMessage({
        type: 'error',
        messages: 'Failed to delete user.'
      });
    }
  }

  fetchUsers();
</script>
