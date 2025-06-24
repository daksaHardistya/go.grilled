<x-layoute>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card-status p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4">RINCIAN PEMBELANJAAN</h1>
        <!-- Customer Details -->
        <div class="customer-details mb-6">
            <h3 class="font-bold mb-2">Data Customer</h3>
            <div><strong>Nama:</strong> <span id="customer-name">-</span></div>
            <div><strong>Nomor WhatsApp:</strong> <span id="customer-phone">-</span></div>
            <div><strong>Alamat:</strong> <span id="customer-address">-</span></div>
            <div><strong>Email:</strong> <span id="customer-email">-</span></div>
            <div><strong>Nomor Transaksi:</strong> <span id="nomor-transaksi">-</span></div>
            <div><strong>Jenis Pembayaran:</strong> <span id="tipe-pembayaran">-</span></div>
        </div>
        <!-- Order Summary -->
        <div class="order-summary mb-6">
            <h3 class="font-bold mb-2">Rincian Pembelanjaan</h3>
            <table class="w-full text-left border border-gray-300 mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Nama Item</th>
                        <th class="p-2 border text-right">Harga</th>
                    </tr>
                </thead>
                <tbody id="order-details" class="bg-white"></tbody>
            </table>
            <h3 class="text-right text-xl font-bold">Total: Rp. <span id="total-amount">0</span></h3>
        </div>

        <!-- Metode Pembayaran -->
        <div class="payment-method mb-6">
        <h3 class="font-semibold mb-2">Pilih Metode Pembayaran:</h3>
        <div class="radio-group">
            <label>
            <input id="cash" class="radio" type="radio" name="payment-method" value="Cash">
            Cash <i class="fas fa-money-bill-wave"></i>
            </label>
            <label>
            <input id="transfer" class="radio" type="radio" name="payment-method" value="Transfer Bank">
            Transfer Bank <i class="fas fa-university"></i>
            </label>
        </div>
        </div><br>
        <!-- Upload Bukti Transfer -->
        <div id="upload-section" class="upload-section mb-6" style="display: none;">
            <h5>Rekening transfer :</h5>
            <h6>a.n. KadeK Yuda Wiryanatha</h6>
            <h6>Bank BRI: 1122334455 a.n. Go Grilled</h6>
            <p>*Pastikan untuk menyimpan dan mengunggah bukti transfer Anda.</p>
            <h5 class="text-black font-bold mb-2">Upload Bukti Transfer</h3>
            <input type="file" id="proof-upload" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" class="block mt-2" value="File belum diupload">
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-between mt-4">
            <x-backbutton/>
            <button id="confirm-button" class="button-confirm" style="display: none;">Place Order <i class="fas fa-check-circle mr-2"></i></button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const customerData = JSON.parse(localStorage.getItem('customerData')) || {};
            const orderPaket = JSON.parse(localStorage.getItem('paket_dipilih')) || [];
            const orderProduk = JSON.parse(localStorage.getItem('produk_dipilih')) || [];
            let tipePembayaran = localStorage.getItem('tipe_pembayaran') || '';
            let buktiPembayaran = localStorage.getItem('bukti_pembayaran') || '';

            function generateNomorPembayaran(nomorTlp) {
                const now = new Date();
                const tanggal = now.toISOString().slice(0, 10).replace(/-/g, '');
                const angkaHP = (nomorTlp || '').replace(/\D/g, '');
                const akhirHP = angkaHP.slice(-4).padStart(4, '0');
                const randomNum = Math.floor(100 + Math.random() * 900);
                return `${tanggal}${akhirHP}${randomNum}`;
            }

            const nomorPembayaran = generateNomorPembayaran(customerData.nomor_tlp || '');
            localStorage.setItem('paymentNumber', nomorPembayaran);

            // Tampilkan data customer
            document.getElementById('customer-name').innerText = customerData.nama || '-';
            document.getElementById('customer-address').innerText = customerData.alamat || '-';
            document.getElementById('customer-phone').innerText = customerData.nomor_tlp || '-';
            document.getElementById('customer-email').innerText = customerData.email || '-';
            document.getElementById('nomor-transaksi').innerText = nomorPembayaran;
            document.getElementById('tipe-pembayaran').innerText = tipePembayaran || '-';

            const orderTable = document.getElementById('order-details');
            let totalHarga = 0;

            orderPaket.forEach(item => {
                const subtotal = item.jumlah_paket * item.harga_paket;
                orderTable.innerHTML += `
                    <tr>
                        <td class="p-2 border">${item.nama_paket} (x${item.jumlah_paket})</td>
                        <td class="p-2 border text-right">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    </tr>`;
                totalHarga += subtotal;
            });

            orderProduk.forEach(item => {
                const subtotal = item.jumlah_produk * item.harga_produk;
                orderTable.innerHTML += `
                    <tr>
                        <td class="p-2 border">${item.nama_produk} (x${item.jumlah_produk})</td>
                        <td class="p-2 border text-right">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    </tr>`;
                totalHarga += subtotal;
            });

            document.getElementById('total-amount').innerText = totalHarga.toLocaleString('id-ID');

            const radios = document.querySelectorAll('input[name="payment-method"]');
            const uploadSection = document.getElementById('upload-section');
            const tipePembayaranSpan = document.getElementById('tipe-pembayaran');
            const confirmButton = document.getElementById('confirm-button');
            const proofInput = document.getElementById('proof-upload');


            radios.forEach(radio => {
                radio.addEventListener('change', function () {
                    tipePembayaran = this.value;
                    localStorage.setItem("tipe_pembayaran", tipePembayaran);
                    tipePembayaranSpan.innerText = tipePembayaran;

                    if (this.id === "transfer") {
                        uploadSection.style.display = "block";
                    } if (this.id === "cash") {
                        uploadSection.style.display = "none";
                        localStorage.setItem("bukti_pembayaran", "Cash");
                        confirmButton.style.display = "inline-block";
                    } else {
                        confirmButton.style.display = "none";
                    }
                });
            });

            proofInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) uploadBuktiTf(file);
            });

            function uploadBuktiTf(file) {
                const formData = new FormData();
                formData.append('bukti_pembayaran', file);

                fetch('/upload-bukti', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        localStorage.setItem("bukti_pembayaran", data.fileName);
                        alert("Bukti transfer berhasil diupload.");
                        confirmButton.style.display = "inline-block";
                    } else {
                        alert("Gagal mengupload bukti transfer.");
                    }
                })
                .catch(error => {
                    console.error("Upload Error:", error);
                    alert("Terjadi kesalahan saat upload bukti.");
                });
            }

            confirmButton.addEventListener('click', function () {
                buktiPembayaran = tipePembayaran === 'Transfer Bank'
                    ? (localStorage.getItem("bukti_pembayaran") || 'Belum diupload')
                    : 'Cash';

                const payload = {
                    pelanggan: {
                        nomor_tlp: customerData.nomor_tlp,
                        nama_pel: customerData.nama,
                        alamat_pel: customerData.alamat,
                        email_pel: customerData.email
                    },
                    order: {
                        nomor_transaksi: nomorPembayaran,
                        tipe_pembayaran: tipePembayaran,
                        total_belanjaan: totalHarga,
                        bukti_pembayaran: buktiPembayaran
                    },
                    orderPaket: orderPaket.map(item => ({
                        id_paket: item.id_paket,
                        jumlah_orderPaket: item.jumlah_paket
                    })),
                    orderProduk: orderProduk.map(item => ({
                        id_produk: item.id_produk,
                        jumlah_orderProduk: item.jumlah_produk
                    }))
                };

                fetch('/simpanTransaksi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    // Notifikasi WhatsApp
                    const adminPhone = '085777115304';
                    const fonnteToken = 'cFh96YKghJi8GQkN3LFN';

                    const pesanAdmin = `Order Baru Masuk!\n\n Nama: ${customerData.nama}\n Telepon: ${customerData.nomor_tlp}\n Alamat: ${customerData.alamat}\n Total: Rp ${totalHarga.toLocaleString('id-ID')}\n Pembayaran: ${tipePembayaran}\n Nomor Transaksi: ${nomorPembayaran} \n`;

                    fetch("https://api.fonnte.com/send", {
                        method: "POST",
                        headers: { "Authorization": fonnteToken },
                        body: new URLSearchParams({ target: adminPhone, message: pesanAdmin })
                    });

                    alert(" Pesanan berhasil dilakukan, tunggu konfirmasi dari admin kami");
                    window.location.href = "/invoice"; // Redirect ke invoice / success page
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Gagal menyimpan transaksi.");
                });
            });
        });
    </script>
</x-layoute>
