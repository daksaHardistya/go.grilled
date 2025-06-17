<!-- Navbar Bootstrap tanpa background putih -->
<nav class="navbar navbar-expand-lg bg-black text-white shadow-lg fixed-top">
  <div class="container text-white">
    <!-- Logo -->
    <a class="navbar-brand" href="/">
      <img src="../img/logos/logo-go.grill.png" alt="Go Grill Logo" style="height: 40px;" />
    </a>
    <!-- Tombol toggle menu hamburger untuk mobile -->
    <button
      class="navbar-toggler text-white"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu navigasi -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/">HOME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/paket">PAKET</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/produk">PRODUK</a>
        </li>
        <li class="nav-item">
          <a class="nav-link position-relative" href="/cart" aria-label="Cart">
            <img src="img/cart-icon.png" alt="Cart Icon" style="height: 24px;" />
            <span
              id="cart-count"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              style="font-size: 0.75rem;"
            >
              0
            </span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  function updateCartCount() {
    const paket = JSON.parse(localStorage.getItem('paket_dipilih') || '[]');
    const produk = JSON.parse(localStorage.getItem('produk_dipilih') || '[]');

    let totalCount = 0;

    paket.forEach((item) => {
      totalCount += item.jumlah_paket || 1;
    });

    produk.forEach((item) => {
      totalCount += item.jumlah_produk || 1;
    });

    const countElement = document.getElementById('cart-count');
    if (countElement) {
      countElement.textContent = totalCount;
      countElement.style.display = totalCount > 0 ? 'inline-block' : 'none';
    }
  }

  document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
