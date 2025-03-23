<form action="<?= BASE_URL ?>register" method="post" class="flex flex-col w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-md">
  <div id="step1" class="transition-all">
    <h2 class="mb-4 text-3xl font-bold text-center text-gray-800">Sign Up</h2>
    <div class="space-y-3">
      <div>
        <label for="email" class="block font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Enter your email">
      </div>
      <div>
        <label for="username" class="block font-medium text-gray-700">Username</label>
        <input type="text" name="username" id="username" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Choose a username">
      </div>
      <div>
        <label for="password" class="block font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="********">
      </div>
      <div>
        <label for="confirmPassword" class="block font-medium text-gray-700">Confirm Password</label>
        <input type="password" name="confirmPassword" id="confirmPassword" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="********">
      </div>
    </div>
    <button type="button" class="w-full py-2 mt-4 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700" onclick="nextStep()">Continue</button>
  </div>

  <div id="step2" class="hidden transition-all">
    <h2 class="mb-4 text-3xl font-bold text-center text-gray-800">Personal Details</h2>
    <div class="space-y-3">
      <div>
        <label for="first_name" class="block font-medium text-gray-700">First Name</label>
        <input type="text" name="first_name" id="first_name" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Your first name">
      </div>
      <div>
        <label for="last_name" class="block font-medium text-gray-700">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Your last name">
      </div>
      <div>
        <label class="block font-medium text-gray-700">Gender</label>
        <div class="flex items-center gap-4">
          <label class="flex items-center gap-2"><input type="radio" name="jenis_kelamin" value="1" class="accent-blue-600"> Male</label>
          <label class="flex items-center gap-2"><input type="radio" name="jenis_kelamin" value="0" class="accent-blue-600"> Female</label>
        </div>
      </div>
      <div>
        <label for="tanggal_lahir" class="block font-medium text-gray-700">Birth Date</label>
        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
      </div>
    </div>
    <div class="flex w-full gap-4 mt-4">
      <button type="button" onclick="backStep()" class="w-full py-2 text-white transition bg-gray-500 rounded-lg hover:bg-gray-600">Back</button>
      <button type="submit" class="w-full py-2 text-white transition bg-green-500 rounded-lg hover:bg-green-700">Sign Up</button>
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