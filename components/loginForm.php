<div
  class="
  flex flex-col items-center justify-center 
  w-full m-auto h-svh 
  bg-gray-500 bg-opacity-35

  before:bg-[url(../assets/background.jpg)] 
  before:h-full before:w-full before:bg-cover before:bg-fixed before:bg-no-repeat 
  before:-z-10 before:blur-lg before:absolute before:mix-blend-darken">
  <h1 class="mb-20 text-4xl font-bold">Log in to use our website</h1>

  <div 
  class="
  flex flex-row 
  bg-blue-100 bg-opacity-85 
  border-blue-300 border-4 w-[600px] 
  shadow-md shadow-slate-500 rounded-sm">
    <div 
    class="
    w-full h-full 
    bg-[url(../assets/image-login.jpeg)] bg-cover bg-no-repeat bg-center"> </div>
    <form action="<?= BASE_URL ?>login" method="post" class="flex flex-col w-full gap-2 p-4">
      <h2 class="w-full mb-4 text-2xl font-bold text-center">Log In</h2>
      <div class="flex flex-col gap-2">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="p-1 shadow-sm" placeholder="username">
      </div>
      <div class="flex flex-col gap-2">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="p-1 shadow-sm" placeholder="********">
      </div>
      <div class="flex justify-between">
        <span class="flex gap-2 text-sm"><input type="checkbox" name="rememberMe" id="rememberMe"><label for="rememberMe">Remember Me</label></span>
        <a href="" class="text-sm text-blue-600">Forgot Password?</a>
      </div>
      <input type="submit" value="Log In" class="py-2 text-white bg-blue-600">
      <span class="w-full text-sm text-center">Belum memiliki akun? <a href="" class="text-blue-600">Daftar</a></span>
    </form>
  </div>
</div>