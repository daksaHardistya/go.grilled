<!-- Navbar -->
<nav class="bg-black text-white shadow-lg">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <!-- Logo -->
      <div class="w-24">
        <img src="../../../img/logos/logo-go.grill.png" alt="Go Grill Logo" class="h-auto">
      </div>
  
      <!-- Desktop Menu -->
      <ul class="hidden md:flex space-x-6 text-white font-medium">
        <li><a href="/admin/dashboard" class="hover:text-yellow-400 transition">Dashboard</a></li>
        <li><a href="/admin/order" class="hover:text-yellow-400 transition">Order</a></li>
        <li><a href="/admin/paket" class="hover:text-yellow-400 transition">Paket</a></li>
        <li><a href="/admin/produk" class="hover:text-yellow-400 transition">Produk</a></li>
        <li><a href="/admin/pembukuan" class="hover:text-yellow-400 transition">Pembukuan</a></li>
        <li>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-red-500 hover:text-red-400 font-semibold transition">Logout</a>
          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
      </ul>
  
      <!-- Mobile Hamburger -->
      <div class="md:hidden">
        <button id="mobileMenuBtn" class="text-yellow-400 text-2xl focus:outline-none">
          ☰
        </button>
      </div>
    </div>
  
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-black text-white px-4 pb-4 space-y-2">
      <a href="/admin/dashboard" class="block hover:text-yellow-400">Dashboard</a>
      <a href="/admin/order" class="block hover:text-yellow-400">Order</a>
      <a href="/admin/produk" class="block hover:text-yellow-400">Produk</a>
      <a href="/admin/paket" class="block hover:text-yellow-400">Paket</a>
      <a href="/admin/pembukuan" class="block hover:text-yellow-400">Pembukuan</a>
      <a href="/logout" class="block text-red-500 hover:text-red-400">Logout</a>
    </div>
  </nav>
  
  <!-- Script Toggle Menu -->
  <script>
    document.getElementById('mobileMenuBtn').addEventListener('click', () => {
      const menu = document.getElementById('mobileMenu');
      menu.classList.toggle('hidden');
    });
  </script>
  