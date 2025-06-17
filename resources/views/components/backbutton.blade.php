<button type="button" onclick="goBackWithRefresh()" class="btn-secondary">
    Kembali
</button>

<script>
    function goBackWithRefresh() {
        const previousUrl = document.referrer;
        if (previousUrl) {
            // Arahkan ke halaman sebelumnya, dan tambahkan parameter dummy agar halaman reload
            window.location.href = previousUrl + (previousUrl.includes('?') ? '&' : '?') + 'refresh=' + new Date().getTime();
        } else {
            // Kalau tidak ada referrer (misalnya user langsung ke halaman ini), fallback ke halaman tertentu
            window.location.href = '/'; // Ganti dengan URL fallback jika perlu
        }
    }
</script>

