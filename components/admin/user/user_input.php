<form id="formInputUser" onsubmit="inputUser(); return false;"
  class="w-full p-8 space-y-8 bg-white shadow rounded-xl">

  <h2 class="mb-2 text-3xl font-bold text-gray-800">Tambah user baru</h2>

  <div class="grid grid-cols-2 gap-6">
    <div>
      <label for="username" class="block mb-1 font-semibold text-gray-700">Username</label>
      <input type="text" name="username" id="username" required
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="bio" class="block mb-1 font-semibold text-gray-700">Bio</label>
      <input type="text" name="bio" id="bio"
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="password" class="block mb-1 font-semibold text-gray-700">Password</label>
      <input type="password" name="password" id="password" required
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="email" class="block mb-1 font-semibold text-gray-700">Email</label>
      <input type="email" name="email" id="email" required
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label class="block mb-1 font-semibold text-gray-700">Jenis Kelamin</label>
      <div class="flex items-center gap-6">
        <label class="inline-flex items-center">
          <input type="radio" name="jenis_kelamin" value="1"
            class="text-emerald-600 focus:ring-emerald-500" />
          <span class="ml-2">Laki-laki</span>
        </label>
        <label class="inline-flex items-center">
          <input type="radio" name="jenis_kelamin" value="0"
            class="text-emerald-600 focus:ring-emerald-500" />
          <span class="ml-2">Perempuan</span>
        </label>
      </div>
    </div>

    <div>
      <label for="tanggal_lahir" class="block mb-1 font-semibold text-gray-700">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" id="tanggal_lahir"
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>

    <div>
      <label for="phone_number" class="block mb-1 font-semibold text-gray-700">Nomor Telepon</label>
      <input type="text" name="phone_number" id="phone_number"
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
    </div>
  </div>

  <div class="pt-4">
    <button type="submit"
      class="px-6 py-2 font-semibold text-white transition-all duration-300 rounded-md bg-emerald-600 hover:bg-emerald-700">
      Input User
    </button>
  </div>
</form>

<script>
  async function inputUser() {
    try {
      const form = document.getElementById('formInputUser');
      const formData = new FormData(form);
      
      // Convert FormData to a plain object for JSON.stringify
      const data = {};
      for (let [key, value] of formData.entries()) {
        data[key] = value;
      }

      const response = await fetch('/nutritrack/api/user-input', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();
      // console.log(result); // Removed debugging log
      showFlashMessage({
        type: result.status,
        messages: result.message
      });
    } catch (error) {
      console.error('Error:', error);
      showFlashMessage({
        type: 'error',
        messages: 'An unexpected error occurred.'
      });
    }
  }
</script>
