<form onsubmit="updateProfileData(); return false;" class="flex flex-col w-full gap-4 p-6 mx-auto bg-white rounded-lg shadow-md" enctype="multipart/form-data" id="update-profile-form">
  <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

  <div class="space-y-2">
    <label for="username" class="font-semibold">Username</label>
    <input type="text" name="username" id="username" class="p-2 border rounded-md shadow-sm" placeholder="Username" value="<?= $user['username'] ?>">
  </div>

  <div class="space-y-2">
    <label for="profile_picture" class="font-semibold">Upload Foto Profil:</label>
    <input type="file" id="profile_picture" name="profile_picture" class="p-2 border rounded-md">
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="space-y-2">
      <label for="first_name" class="font-semibold">First Name</label>
      <input type="text" name="first_name" id="first_name" class="p-2 border rounded-md shadow-sm" placeholder="First Name" value="<?= $user['first_name'] ?>">
    </div>
    <div class="space-y-2">
      <label for="last_name" class="font-semibold">Last Name</label>
      <input type="text" name="last_name" id="last_name" class="p-2 border rounded-md shadow-sm" placeholder="Last Name" value="<?= $user['last_name'] ?>">
    </div>
  </div>

  <div class="space-y-2">
    <label for="email" class="font-semibold">Email</label>
    <input type="email" name="email" id="email" class="p-2 border rounded-md shadow-sm" placeholder="Email" value="<?= $user['email'] ?>">
  </div>

  <div class="space-y-2">
    <label for="bio" class="font-semibold">Bio</label>
    <textarea name="bio" id="bio" class="p-2 border rounded-md shadow-sm" placeholder="Bio"><?= $user['bio'] ?></textarea>
  </div>

  <div class="space-y-2">
    <label class="font-semibold">Jenis Kelamin</label>
    <div class="flex items-center gap-4">
      <label class="flex items-center gap-2">
        <input type="radio" name="jenis_kelamin" value="1" class="accent-blue-600" <?= $user['jenis_kelamin'] == 1 ? 'checked' : '' ?>> Laki-laki
      </label>
      <label class="flex items-center gap-2">
        <input type="radio" name="jenis_kelamin" value="0" class="accent-blue-600" <?= $user['jenis_kelamin'] == 0 ? 'checked' : '' ?>> Perempuan
      </label>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="space-y-2">
      <label for="phone_number" class="font-semibold">Nomor Telepon</label>
      <input type="number" name="phone_number" id="phone_number" class="p-2 border rounded-md shadow-sm" value="<?= $user['phone_number'] ?>">
    </div>
    <div class="space-y-2">
      <label for="tanggal_lahir" class="font-semibold">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="p-2 border rounded-md shadow-sm" value="<?= $user['tanggal_lahir'] ?>">
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="space-y-2">
      <label for="berat_badan" class="font-semibold">Berat Badan (kg)</label>
      <input type="number" name="berat_badan" id="berat_badan" class="p-2 border rounded-md shadow-sm" value="<?= $user['berat_badan'] ?>" min="0">
    </div>
    <div class="space-y-2">
      <label for="tinggi_badan" class="font-semibold">Tinggi Badan (cm)</label>
      <input type="number" name="tinggi_badan" id="tinggi_badan" class="p-2 border rounded-md shadow-sm" value="<?= $user['tinggi_badan'] ?>" min="0">
    </div>
  </div>

  <div class="space-y-2">
    <label for="aktivitas" class="font-semibold">Aktivitas</label>
    <select name="aktivitas" id="aktivitas" class="p-2 border rounded-md shadow-sm">
      <option value="sangat ringan">Sangat Ringan</option>
      <option value="ringan">Ringan</option>
      <option value="sedang">Sedang</option>
      <option value="aktif">Aktif</option>
      <option value="sangat aktif">Sangat Aktif</option>
    </select>
  </div>

  <button type="submit" class="w-full p-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
</form>

<script>
  async function updateProfileData() {
    console.log('updateProfileData function called.');
    try {
      const form = document.querySelector('#update-profile-form');
      const formData = new FormData(form); // Use FormData to include file input

      const res = await fetch('/nutritrack/api/update-user-profile', {
        method: 'POST',
        body: formData // Send FormData directly, no Content-Type header needed
      });

      const result = await res.json();
      console.log('API Response (result):', result);
      console.log('Result Status:', result.status);
      console.log('Result Message:', result.message);

      if (result.status === 'success') {
        showFlashMessage({
          type: 'success',
          messages: result.message
        });
        // Optionally redirect or refresh part of the page
        window.location.href = BASE_URL_JS + 'profile/personal'; // Redirect to personal profile to see changes
      } else {
        showFlashMessage({
          type: 'error',
          messages: result.message
        });
      }
    } catch (err) {
      console.error(err);
      showFlashMessage({
        type: 'error',
        messages: 'An unexpected error occurred.'
      });
    }
  }
</script>
