<form onsubmit="kirimData(); return false;" method="post" class="flex flex-col w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-md">
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
      <div>
        <label for="phone_number" class="block font-medium text-gray-700">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Your phone number">
      </div>
      <div>
        <label for="bio" class="block font-medium text-gray-700">Bio</label>
        <textarea name="bio" id="bio" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Tell us about yourself"></textarea>
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

  async function kirimData() {
    // Client-side validation for confirmPassword
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (password !== confirmPassword) {
      showFlashMessage({
        type: 'error',
        messages: 'Password and Confirm Password do not match.'
      });
      return; // Stop execution if passwords don't match
    }

    try {
      const res = await fetch('/nutritrack/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email: document.getElementById('email').value,
          username: document.getElementById('username').value,
          password: document.getElementById('password').value,
          first_name: document.getElementById('first_name').value,
          last_name: document.getElementById('last_name').value,
          jenis_kelamin: document.querySelector('input[name="jenis_kelamin"]:checked').value,
          tanggal_lahir: document.getElementById('tanggal_lahir').value,
          phone_number: document.getElementById('phone_number').value, // Added
          bio: document.getElementById('bio').value // Added
        })
      });

      const data = await res.json();
      console.log(data);

      if (data.status === 'success') {
        showFlashMessage({
          type: 'success',
          messages: data.message
        });
        window.location.href = BASE_URL_JS + 'login';
      } else if (data.status === 'error') {
        showFlashMessage({
          type: 'error',
          messages: data.message
        });
      }
    } catch (err) {
      console.error(err);
    }
  }
</script>
