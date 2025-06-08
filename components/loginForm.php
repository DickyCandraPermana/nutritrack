<div
  class="relative flex flex-col items-center justify-center w-full min-h-screen overflow-hidden bg-gray-500 bg-opacity-35">
  <div
    class="absolute inset-0 bg-[url(../assets/background.jpg)] bg-cover bg-fixed bg-no-repeat blur-lg mix-blend-darken -z-10"></div>

  <h1 class="mb-10 text-4xl font-bold text-white">Log in to use our website</h1>

  <div class="flex bg-blue-100 bg-opacity-85 border-blue-300 border-4 w-[600px] shadow-lg rounded-lg overflow-hidden">
    <div class="w-1/2 bg-[url(../assets/image-login.jpeg)] bg-cover bg-center hidden md:block"></div>

    <form onsubmit="kirimData(); return false;" class="flex flex-col w-full p-6 md:w-1/2">
      <h2 class="w-full mb-4 text-2xl font-bold text-center">Log In</h2>

      <div class="flex flex-col gap-2">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="p-2 border rounded shadow-sm" placeholder="Username">
      </div>

      <div class="flex flex-col gap-2">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="p-2 border rounded shadow-sm" placeholder="********">
      </div>

      <div class="flex justify-between mt-2 text-sm">
        <label class="flex items-center gap-2">
          <input type="checkbox" name="rememberMe" id="rememberMe" class="accent-blue-600">
          Remember Me
        </label>
        <a href="#" class="text-blue-600 hover:underline">Forgot Password?</a>
      </div>

      <button type="submit" class="w-full py-2 mt-4 text-white transition bg-blue-600 rounded hover:bg-blue-700">
        Log In
      </button>

      <p class="w-full mt-2 text-sm text-center">
        Belum memiliki akun? <a href="#" class="text-blue-600 hover:underline">Daftar</a>
      </p>
    </form>
  </div>
</div>

<script>
  async function kirimData() {
    try {
      const res = await fetch('api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          username: document.getElementById('username').value,
          password: document.getElementById('password').value
        })
      });

      console.log(res);
      const data = await res.json();


      if (data.status === 'success') {
        if (data.role === 'admin') {
          window.location.href = '/nutritrack/admin';
        } else {
          window.location.href = '/nutritrack/profile';
        }
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