<x-layoute>
    <div class="container my-5">
        <div class="card-invoice shadow-lg rounded-lg">
            <div class="card-body">
                <div class="tittle-invoice text-center">
                    <h2 class="text-danger">INVOICE PEMESANAN</h1>
                        <h1>GO.GRILLED SINGARAJA</h1>
                        <p class="text-muted">Terima kasih atas pesanan Anda</p>
                </div>

                <!-- Informasi Pelanggan -->
                <div class="data-pelanggan mb-4">
                    <h2 class="h5 text-dark border-bottom pb-2 mb-4">Informasi Pelanggan</h2>
                    <div><strong>Nama:</strong> <span id="customer-name">-</span></div>
                    <div><strong>Alamat:</strong> <span id="customer-address">-</span></div>
                    <div><strong>No. Telepon:</strong> <span id="customer-phone">-</span></div>
                    <div><strong>Email:</strong> <span id="customer-email">-</span></div>
                </div>

                <!-- Detail Transaksi -->
                <div class="detail-transaksi mb-4">
                    <h2 class="h5 text-dark border-bottom pb-2 mb-4">Detail Pembayaran</h2>
                    <div><strong>Nomor Transaksi:</strong> <span id="nomor-pembayaran">-</span></div>
                    <div><strong>Metode Pembayaran:</strong> <span id="tipe-pembayaran">-</span></div>
                </div>

                <!-- Daftar Pesanan -->
                <div class="daftar-pesanan mb-4">
                    <h2 class="h5 text-dark border-bottom pb-2 mb-4">Daftar Pesanan</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table-light">
                                <th class="text-left">Nama Item</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="order-details"></tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td class="text-right">Total:</td>
                                <td class="text-right">Rp. <span style="font-size: medium" id="total-amount">0</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Tombol Selesai dan Download PDF -->
                <div class="text-center mt-4">
                    <button id="download-pdf" class="btn btn-danger mx-2"><i class="fas fa-file-pdf"></i> Download
                        PDF</button>
                    <button id="finish-button"
                        class="btn-sukses text-white px-4 py-2 rounded transition duration-200"><i
                            class="fas fa-check-circle"></i> Selesai</button>
                </div>
                <x-contact></x-contact>
            </div>
        </div>
    </div>

    <!-- CDN html2pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const customerData = JSON.parse(localStorage.getItem('customerData')) || {};
            const orderPaket = JSON.parse(localStorage.getItem('paket_dipilih')) || [];
            const orderProduk = JSON.parse(localStorage.getItem('produk_dipilih')) || [];
            const tipePembayaran = localStorage.getItem('tipe_pembayaran') || '-';
            const nomorPembayaran = localStorage.getItem('paymentNumber') || '-';

            document.getElementById('customer-name').textContent = customerData.nama || '-';
            document.getElementById('customer-address').textContent = customerData.alamat || '-';
            document.getElementById('customer-phone').textContent = customerData.nomor_tlp || '-';
            document.getElementById('customer-email').textContent = customerData.email || '-';
            document.getElementById('nomor-pembayaran').textContent = nomorPembayaran;
            document.getElementById('tipe-pembayaran').textContent = tipePembayaran;

            const orderTable = document.getElementById('order-details');
            orderTable.innerHTML = ''; // Bersihkan dulu isinya

            let totalHarga = 0;

            // Fungsi buat row agar lebih rapi
            function buatRow(namaItem, subtotal) {
                const tr = document.createElement('tr');
                const tdNama = document.createElement('td');
                tdNama.textContent = namaItem;
                const tdSubtotal = document.createElement('td');
                tdSubtotal.classList.add('text-right');
                tdSubtotal.textContent = `Rp. ${subtotal.toLocaleString('id-ID')}`;
                tr.appendChild(tdNama);
                tr.appendChild(tdSubtotal);
                return tr;
            }

            orderPaket.forEach(item => {
                const subtotal = item.jumlah_paket * item.harga_paket;
                totalHarga += subtotal;
                orderTable.appendChild(buatRow(`${item.nama_paket} x${item.jumlah_paket}`, subtotal));
            });

            orderProduk.forEach(item => {
                const subtotal = item.jumlah_produk * item.harga_produk;
                totalHarga += subtotal;
                orderTable.appendChild(buatRow(`${item.nama_produk} x${item.jumlah_produk}`, subtotal));
            });

            document.getElementById('total-amount').textContent = totalHarga.toLocaleString('id-ID');

            // Download PDF
            document.getElementById("download-pdf").addEventListener("click", function() {
                // const element = document.querySelector(".card-body");
                const element1 = document.querySelector(".tittle-invoice");
                const element2 = document.querySelector(".data-pelanggan");
                const element3 = document.querySelector(".detail-transaksi");
                const element4 = document.querySelector(".daftar-pesanan");
                const element5 = document.querySelector(".table");
                // const element6 = document.querySelector("thead");
                // const element7 = document.querySelector("tfoot");

                //menggabungkan semua elemen yang ingin diunduh
                const combinedElement = document.createElement("div");
                combinedElement.appendChild(element1.cloneNode(true));
                combinedElement.appendChild(element2.cloneNode(true));
                combinedElement.appendChild(element3.cloneNode(true));
                combinedElement.appendChild(element4.cloneNode(true));
                combinedElement.appendChild(element5.cloneNode(true));
                // combinedElement.appendChild(element6.cloneNode(true));
                // combinedElement.appendChild(element7.cloneNode(true));



                // Menggunakan html2pdf untuk mengunduh PDF
                const element = combinedElement;
                const opt = {
                    margin: [-1, 0.5, -1, 0.5], // [top, right, bottom, left] dalam satuan inci
                    filename: 'invoice_gogrilled.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                html2pdf().set(opt).from(element).save();
            });

            // Tombol selesai
            document.getElementById("finish-button").addEventListener("click", function() {
                localStorage.removeItem('customerData');
                localStorage.removeItem('paket_dipilih');
                localStorage.removeItem('produk_dipilih');
                localStorage.removeItem('tipe_pembayaran');
                localStorage.removeItem('paymentNumber');
                window.location.href = '/';
            });
        });
    </script>
</x-layoute>
