<form action="<?= BASE_URL ?>profile/update" method="post" class="flex flex-col w-full gap-4 p-6 mx-auto bg-white rounded-lg shadow-md">
  <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

  <div class="space-y-2">
    <label for="username" class="font-semibold">Username</label>
    <input type="text" name="username" id="username" class="p-2 border rounded-md shadow-sm" placeholder="Username">
  </div>

  <div class="space-y-2">
    <label for="profile_picture" class="font-semibold">Upload Foto Profil:</label>
    <input type="file" id="profile_picture" name="profile_picture" class="p-2 border rounded-md">
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="space-y-2">
      <label for="first_name" class="font-semibold">First Name</label>
      <input type="text" name="first_name" id="first_name" class="p-2 border rounded-md shadow-sm" placeholder="First Name">
    </div>
    <div class="space-y-2">
      <label for="last_name" class="font-semibold">Last Name</label>
      <input type="text" name="last_name" id="last_name" class="p-2 border rounded-md shadow-sm" placeholder="Last Name">
    </div>
  </div>

  <div class="space-y-2">
    <label for="email" class="font-semibold">Email</label>
    <input type="email" name="email" id="email" class="p-2 border rounded-md shadow-sm" placeholder="Email">
  </div>

  <div class="space-y-2">
    <label for="password" class="font-semibold">Password</label>
    <input type="password" name="password" id="password" class="p-2 border rounded-md shadow-sm" placeholder="Password">
  </div>

  <div class="space-y-2">
    <label for="bio" class="font-semibold">Bio</label>
    <textarea name="bio" id="bio" class="p-2 border rounded-md shadow-sm" placeholder="Bio"></textarea>
  </div>

  <div class="space-y-2">
    <label class="font-semibold">Jenis Kelamin</label>
    <div class="flex items-center gap-4">
      <label class="flex items-center gap-2">
        <input type="radio" name="jenis_kelamin" value="1" class="accent-blue-600"> Laki-laki
      </label>
      <label class="flex items-center gap-2">
        <input type="radio" name="jenis_kelamin" value="0" class="accent-blue-600"> Perempuan
      </label>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="space-y-2">
      <label for="phone_number" class="font-semibold">Nomor Telepon</label>
      <input type="number" name="phone_number" id="phone_number" class="p-2 border rounded-md shadow-sm">
    </div>
    <div class="space-y-2">
      <label for="tanggal_lahir" class="font-semibold">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="p-2 border rounded-md shadow-sm">
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="space-y-2">
      <label for="berat_badan" class="font-semibold">Berat Badan (kg)</label>
      <input type="number" name="berat_badan" id="berat_badan" class="p-2 border rounded-md shadow-sm">
    </div>
    <div class="space-y-2">
      <label for="tinggi_badan" class="font-semibold">Tinggi Badan (cm)</label>
      <input type="number" name="tinggi_badan" id="tinggi_badan" class="p-2 border rounded-md shadow-sm">
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