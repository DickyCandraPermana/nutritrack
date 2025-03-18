<form action="<?= BASE_URL ?>register" method="post" class="flex flex-col w-full gap-2 p-4">
  <div id="step1">
    <h2 class="w-full mb-4 text-2xl font-bold text-center">Sign Up</h2>
    <div class="flex flex-col gap-2">
      <label for="email">Email</label>
      <input type="text" name="email" id="email" class="p-1 shadow-sm" placeholder="email">
    </div>
    <div class="flex flex-col gap-2">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" class="p-1 shadow-sm" placeholder="username">
    </div>
    <div class="flex flex-col gap-2">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" class="p-1 shadow-sm" placeholder="********">
    </div>
    <div class="flex flex-col gap-2">
      <label for="confirmPassword">Confirm Password</label>
      <input type="password" name="confirmPassword" id="confirmPassword" class="p-1 shadow-sm" placeholder="********">
    </div>
    <button type="button" class="w-full py-2 text-white bg-blue-600" onclick="nextStep()">Continue</button>
  </div>
  <div id="step2" class="hidden">
    <div class="flex flex-col gap-2">
      <label for="first_name">First Name</label>
      <input type="text" name="first_name" id="first_name" class="p-1 shadow-sm" placeholder="First Name">
    </div>
    <div class="flex flex-col gap-2">
      <label for="last_name">Last Name</label>
      <input type="text" name="last_name" id="last_name" class="p-1 shadow-sm" placeholder="Last Name">
    </div>
    <div class="flex flex-col gap-2">
      <label for="jenis_kelamin">Jenis Kelamin</label>
      <div class="flex gap-4">
        <input type="radio" name="jenis_kelamin" value="1" id=""> <label for="">Laki-laki</label>
        <input type="radio" name="jenis_kelamin" value="0" id=""> <label for="">Perempuan</label>
      </div>
    </div>
    <div class="flex flex-col gap-2">
      <label for="tanggal_lahir">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="p-1 shadow-sm">
    </div>
    <div class="flex w-full gap-4">
      <button type="button" onclick="backStep()" class="w-full py-2 text-white bg-blue-600">Kembali</button>
      <button type="submit" class="w-full py-2 text-white bg-blue-600">Sign Up</button>
    </div>
  </div>
</form>

<script>
  function nextStep() {
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
  }

  function backStep() {
    document.getElementById('step1').classList.remove('hidden');
    document.getElementById('step2').classList.add('hidden');
  }
</script>