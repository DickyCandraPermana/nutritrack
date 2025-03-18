<form action="<?= BASE_URL ?>profile/update" method="post" class="flex flex-col w-full gap-2 p-4 overscroll-scroll h-svh">
  <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
  <div class="flex flex-col gap-2">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" class="p-1 shadow-sm" placeholder="username">
  </div>
  <div class="flex flex-col">
    <label for="profile_picture">Upload Foto Profil:</label>
    <input type="file" id="profile_picture" name="profile_picture">
  </div>
  <div class="flex flex-col gap-2">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name" class="p-1 shadow-sm" placeholder="First Name">
  </div>
  <div class="flex flex-col gap-2">
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" class="p-1 shadow-sm" placeholder="Last Name">
  </div>
  <div class="flex flex-col gap-2">
    <label for="email">Email</label>
    <input type="text" name="email" id="email" class="p-1 shadow-sm" placeholder="email">
  </div>
  <div class="flex flex-col gap-2">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" class="p-1 shadow-sm" placeholder="password">
  </div>
  <div class="flex flex-col gap-2">
    <label for="bio">Bio</label>
    <textarea name="bio" id="bio" class="p-1 shadow-sm" placeholder="bio"></textarea>
  </div>
  <div class="flex flex-col gap-2">
    <label for="jenis_kelamin">Jenis Kelamin</label>
    <div class="flex gap-4">
      <input type="radio" name="jenis_kelamin" value="1" id=""> <label for="">Laki-laki</label>
      <input type="radio" name="jenis_kelamin" value="0" id=""> <label for="">Perempuan</label>
    </div>
  </div>
  <div class="flex flex-col gap-2">
    <label for="phone_number">Nomer Telepon</label>
    <input type="number" name="phone_number" id="phone_number" class="p-1 shadow-sm">
  </div>
  <div class="flex flex-col gap-2">
    <label for="tanggal_lahir">Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="p-1 shadow-sm">
  </div>
  <div class="flex flex-col gap-2">
    <label for="berat_badan">Berat Badan</label>
    <input type="number" name="berat_badan" id="berat_badan" class="p-1 shadow-sm">
  </div>
  <div class="flex flex-col gap-2">
    <label for="tinggi_badan">Tinggi Badan</label>
    <input type="number" name="tinggi_badan" id="tinggi_badan" class="p-1 shadow-sm">
  </div>
  <div class="flex flex-col gap-2">
    <label for="aktivitas">Aktivitas</label>
    <select name="aktivitas" id="aktivitas">
      <option value="sangat ringan">Sangat Ringan</option>
      <option value="ringan">Ringan</option>
      <option value="sedang">Sedang</option>
      <option value="aktif">Aktif</option>
      <option value="sangat aktif">Sangat Aktif</option>
    </select>
  </div>

  <button type="submit">Simpan</button>
</form>