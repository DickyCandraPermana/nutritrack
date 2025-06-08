<footer class="relative overflow-hidden gradient-bg">
  <!-- Background Decorations -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute w-20 h-20 rounded-full floating top-10 left-10 bg-white/10"></div>
    <div class="absolute w-16 h-16 rounded-full floating top-20 right-20 bg-white/10"
      style="animation-delay: 1s;"></div>
    <div class="absolute w-12 h-12 rounded-full floating bottom-10 left-1/4 bg-white/10"
      style="animation-delay: 2s;"></div>
    <div
      class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-green-600/20 to-emerald-600/20">
    </div>
  </div>

  <div class="relative z-10 px-2 py-1 mx-auto max-w-7xl">
    <!-- Admin Status Bar -->
    <div class="p-6 mb-8 glass-effect rounded-2xl hover-lift">
      <div class="flex flex-col items-center justify-between lg:flex-row">
        <div class="flex items-center mb-4 space-x-4 lg:mb-0">
          <div class="px-4 py-2 text-sm font-semibold text-white rounded-full admin-badge">
            <i class="mr-2 fas fa-crown"></i>Admin Dashboard
          </div>
          <div class="flex items-center space-x-2 text-white">
            <div class="w-3 h-3 bg-green-400 rounded-full pulse-dot"></div>
            <span class="text-sm font-medium">System Online</span>
          </div>
        </div>
        <div class="flex items-center space-x-6 text-white/90">
          <div class="text-center">
            <div class="text-2xl font-bold" id="userCount"><?= $data['total_users'] ?></div>
            <div class="text-xs">Total Users</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold" id="foodCount"><?= $data['total_food'] ?></div>
            <div class="text-xs">Food Items</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-yellow-300" id="premiumCount"><?= $data['total_premium_users'] ?></div>
            <div class="text-xs">Premium Users</div>
          </div>
        </div>
      </div>
    </div>

  </div>
</footer>