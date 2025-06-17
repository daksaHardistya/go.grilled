<x-layoute>
    <x-navbar></x-navbar><br><br><br><br><br>
    <div class="produkSatuan">
        <h1 class="heading-produk">PRODUK SATUAN</h1>
        <form id="produkForm" action="cart" method="GET">
            <div class="menu-items">
                @foreach ($produkSatuan as $row)
                    <div class="produkSatuan-item" data-id="{{ $row->id_produk }}">
                        <img class="img-produk" src="{{ asset('../storage/'.$row->image_produk) }}" alt="{{ $row->nama_produk }}">
                        <h2>Rp. {{ number_format($row->harga_produk, 0, ',', '.') }}</h2>
                        <p hidden>{{ $row->id_produk }}</p>
                        @if ($row->stock_produk > 0)
                            <h3>
                                <input 
                                    class="checklis" 
                                    type="checkbox" 
                                    data-id="{{ $row->id_produk }}"
                                    data-name="{{ $row->nama_produk }}" 
                                    data-price="{{ $row->harga_produk }}" 
                                    style="width:20px;height:20px;"> 
                                {{ $row->nama_produk }}
                            </h3>
                            <label class="label-jumlah" style="display: none;" data-id="{{ $row->id_produk }}">Jumlah:</label>
                            <input 
                                type="number" 
                                class="jumlah-pesanan" 
                                data-id="{{ $row->id_produk }}"
                                min="1" 
                                value="1" 
                                max="{{ $row->stock_produk }}" 
                                data-stok="{{ $row->stock_produk }}"
                                style="display: none; width:60px;">
                        @else
                            <h3 style="color: red;">{{ $row->nama_produk }} - <span style="font-weight: bold;">Stok Habis</span></h3>
                        @endif
                    </div>
                @endforeach
            </div><br>

            @csrf
            <div class="button-container">
                <x-backbutton/>
                <input type="hidden" name="produk_terpilih" id="produkTerpilih" value="">
                <button type="submit" class="tombolnext" id="buttonnext">Skip</button>
            </div>            
        </form>
    </div>
</x-layoute>

<!-- === SCRIPT === -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll(".checklis");
        const buttonnext = document.getElementById("buttonnext");

        buttonnext.innerText = "Skip";

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                const id_produk = this.dataset.id;
                const jumlahInput = document.querySelector(`.jumlah-pesanan[data-id="${id_produk}"]`);
                const jumlahLabel = document.querySelector(`.label-jumlah[data-id="${id_produk}"]`);

                if (this.checked) {
                    jumlahInput.style.display = "inline-block";
                    jumlahLabel.style.display = "inline-block";
                } else {
                    jumlahInput.style.display = "none";
                    jumlahLabel.style.display = "none";
                }

                const adaYangDipilih = Array.from(checkboxes).some(cb => cb.checked);
                buttonnext.innerText = adaYangDipilih ? "Next" : "Skip";
            });
        });

        document.getElementById("produkForm").addEventListener("submit", function (e) {
            const produkDipilih = [];
            const checkboxes = document.querySelectorAll(".checklis");
            
            for (let checkbox of checkboxes) {
                if (checkbox.checked) {
                    const id = checkbox.dataset.id;
                    const nama = checkbox.dataset.name;
                    const harga = checkbox.dataset.price;
                    const jumlahInput = document.querySelector(`.jumlah-pesanan[data-id="${id}"]`);
                    const jumlah = parseInt(jumlahInput.value);
                    const stok = parseInt(jumlahInput.getAttribute("max"));
                    const card = document.querySelector(`.produkSatuan-item[data-id="${id}"]`);
                    const img = card.querySelector(".img-produk")?.src || '';

                    if (jumlah > stok) {
                        alert(`Jumlah produk "${nama}" melebihi stok tersedia (${stok}).`);
                        e.preventDefault();
                        return;
                    }

                    produkDipilih.push({
                        id_produk: id,
                        nama_produk: nama,
                        harga_produk: parseInt(harga),
                        jumlah_produk: jumlah,
                        image_produk: img
                    });
                }
            }

            // Ambil data sebelumnya dari localStorage
            let existingData = JSON.parse(localStorage.getItem("produk_dipilih")) || [];

            // Gabungkan tanpa duplikat (update jumlah jika produk sudah ada)
            for (let baru of produkDipilih) {
                let index = existingData.findIndex(p => p.id_produk === baru.id_produk);
                if (index !== -1) {
                    existingData[index].jumlah_produk += baru.jumlah_produk;
                } else {
                    existingData.push(baru);
                }
            }

            // Simpan kembali ke localStorage
            if (produkDipilih.length > 0) {
                localStorage.setItem("produk_dipilih", JSON.stringify(existingData));
                alert("Produk berhasil ditambahkan ke keranjang.");
            }
        });
    });
</script>
