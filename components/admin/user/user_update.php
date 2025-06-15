<form id="edit-formEditUser" onsubmit="editUser(); return false;" class="w-full p-8 space-y-8 bg-white shadow rounded-xl" enctype="multipart/form-data">
  <h2 class="mb-2 text-3xl font-bold text-gray-800">Tambah user baru</h2>

  <div class="grid grid-cols-2 gap-6">
    <input type="hidden" name="user_id" id="edit-user_id" />

    <div>
      <label for="edit-username" class="block mb-1 font-semibold text-gray-700">Username</label>
      <input type="text" name="username" id="edit-username" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="edit-bio" class="block mb-1 font-semibold text-gray-700">Bio</label>
      <input type="text" name="bio" id="edit-bio" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="edit-profile_picture" class="block mb-1 font-semibold text-gray-700">Profile Picture</label>
      <input type="file" name="profile_picture" id="edit-profile_picture" class="w-full px-4 py-2 bg-white border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="edit-password" class="block mb-1 font-semibold text-gray-700">Password (Leave blank to keep current)</label>
      <input type="password" name="password" id="edit-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="edit-email" class="block mb-1 font-semibold text-gray-700">Email</label>
      <input type="email" name="email" id="edit-email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label class="block mb-1 font-semibold text-gray-700">Jenis Kelamin</label>
      <div class="flex items-center gap-6">
        <label class="inline-flex items-center">
          <input type="radio" name="jenis_kelamin" value="1" class="text-emerald-600 focus:ring-emerald-500" />
          <span class="ml-2">Laki-laki</span>
        </label>
        <label class="inline-flex items-center">
          <input type="radio" name="jenis_kelamin" value="0" class="text-emerald-600 focus:ring-emerald-500" />
          <span class="ml-2">Perempuan</span>
        </label>
      </div>
    </div>

    <div>
      <label for="edit-tanggal_lahir" class="block mb-1 font-semibold text-gray-700">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" id="edit-tanggal_lahir" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="edit-phone_number" class="block mb-1 font-semibold text-gray-700">Nomor Telepon</label>
      <input type="text" name="phone_number" id="edit-phone_number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>
  </div>

  <div class="pt-4">
    <button type="submit" class="px-6 py-2 font-semibold text-white transition-all duration-300 rounded-md bg-emerald-600 hover:bg-emerald-700">
      Update
    </button>
  </div>
</form>


<script>
  function getParam(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
  }

  async function getUser() {
    try {
      const user_id = getParam('user_id');
      if (!user_id) {
        showFlashMessage({
          type: 'error',
          messages: 'User ID is missing'
        });
        return;
      }

      const response = await fetch(`/nutritrack/api/get-user`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: user_id
        })
      });

      if (!response.ok) {
        showFlashMessage({
          type: 'error',
          messages: 'Failed to fetch user data'
        });
        return;
      }

      const result = await response.json();
      const user = result.data;


      if (result.status !== 'success' || !user) {
        const message = Array.isArray(result.message) ? result.message[0] : 'User not found';
        showFlashMessage({
          type: 'error',
          messages: message
        });
        return;
      }

      // Set value ke form
      document.getElementById('edit-user_id').value = user.user_id || '';
      document.getElementById('edit-username').value = user.username || '';
      document.getElementById('edit-bio').value = user.bio || '';
      document.getElementById('edit-email').value = user.email || '';
      document.getElementById('edit-tanggal_lahir').value = user.tanggal_lahir || '';
      document.getElementById('edit-phone_number').value = user.phone_number || '';
      document.getElementById('edit-password').value = ''; // Clear password field

      // Set radio button jenis kelamin
      if (user.jenis_kelamin === "1" || user.jenis_kelamin === 1) {
        document.querySelector('input[name="jenis_kelamin"][value="1"]').checked = true;
      } else if (user.jenis_kelamin === "0" || user.jenis_kelamin === 0) {
        document.querySelector('input[name="jenis_kelamin"][value="0"]').checked = true;
      }

    } catch (err) {
      console.error('getUser() error:', err);
      showFlashMessage({
        type: 'error',
        messages: 'An unexpected error occurred.'
      });
    }
  }

  async function editUser() {
    try {
      const form = document.getElementById('edit-formEditUser');
      const formData = new FormData(form);
      
      const response = await fetch('/nutritrack/api/user-edit', {
        method: 'POST',
        body: formData // Send FormData directly
      });
      const result = await response.json();
      // console.log(result); // Removed debugging log
      if (result.status === 'success') {
        showFlashMessage({
          type: 'success',
          messages: result.message
        });
        window.location.href = BASE_URL_JS + 'admin/users';
      } else {
        showFlashMessage({
          type: 'error',
          messages: result.message
        });
      }
    } catch (error) {
      console.error('Error:', error);
      showFlashMessage({
        type: 'error',
        messages: 'An unexpected error occurred.'
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    getUser();
  });
</script>
