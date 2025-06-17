<x-layoute>
    <x-navbar/><br><br>
    <div class="container my-5">
        <h1 class="text-center fw-bold mb-5 text-white">PAKET MENU</h1>
        <form id="orderPaketForm">
            <input type="hidden" name="tambahpaket" id="orderPaket">

            <!-- === LOOP KATEGORI PAKET === -->
            @php
                $paketKategori = [
                    'PAKET BASIC' => $paketmenubasic,
                    'PAKET SPECIAL' => $paketmenuspecial,
                    'PAKET FAMILY' => $paketmenufamily,
                ];
            @endphp

            @foreach($paketKategori as $kategori => $paketList)
                <div class="card mb-4 shadow-sm bg-transparan text-white">
                    <div class="card-header bg-dark text-white">
                        <h2 class="mb-0">{{ $kategori }}</h2>
                    </div>
                    <div class="card-body bg-grey text-white">
                        <div class="row g-4">
                            @foreach ($paketList as $row)
                                <div class="col-md-4">
                                    <div class="card padding-card shadow-sm">
                                        <img src="{{ asset('../storage/' . $row->image_paket) }}" class="card-img-top img-fluid" alt="{{ $row->nama_paket }}">
                                        <div class="card-body d-flex flex-column">
                                            <input type="hidden" class="id_paket" value="{{ $row->id_paket }}">
                                            <h5 class="card-title nama_paket">{{ $row->nama_paket }}</h5>
                                            <p class="card-text">{{ $row->detail_paket }}</p>
                                            <h6 class="harga_paket text-danger">Rp. {{ number_format($row->harga_paket, 0, ',', '.') }}</h6>

                                            <div class="mt-auto">
                                                @if ($row->stock_paket > 0)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input paket-checkbox" type="checkbox" id="checkbox{{ $row->id_paket }}">
                                                        <label class="form-check-label" for="checkbox{{ $row->id_paket }}">
                                                            Pilih
                                                        </label>
                                                    </div>
                                                    <input type="number"
                                                        class="form-control jumlah-input"
                                                        min="1"
                                                        value="1"
                                                        data-stok="{{ $row->stock_paket }}"
                                                        max="{{ $row->stock_paket }}"
                                                        style="display: none;">
                                                @else
                                                    <span class="text-danger fw-bold">Stok Habis</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between mt-4">
                <x-backbutton />
                <button type="button" class="btn btn-danger tambahpaket" id="tambahpaket" style="display:none;">Next</button>
            </div>
        </form>
    </div>
</x-layoute>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll(".paket-checkbox");
        const orderButton = document.getElementById("tambahpaket");

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", () => {
                const cardBody = checkbox.closest(".card-body");
                const jumlahInput = cardBody.querySelector(".jumlah-input");

                jumlahInput.style.display = checkbox.checked ? "block" : "none";

                const adaYangDipilih = Array.from(checkboxes).some(cb => cb.checked);
                orderButton.style.display = adaYangDipilih ? "inline-block" : "none";
            });
        });

        orderButton.addEventListener("click", () => {
            const paketBaru = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const cardBody = checkbox.closest(".card-body");
                    const id = cardBody.querySelector(".id_paket").value;
                    const nama = cardBody.querySelector(".nama_paket").innerText;
                    const hargaText = cardBody.querySelector(".harga_paket").innerText;
                    const jumlahInput = cardBody.querySelector(".jumlah-input");
                    const img = cardBody.closest(".card").querySelector("img").src;
                    const jumlah = parseInt(jumlahInput.value);
                    const maxStok = parseInt(jumlahInput.getAttribute("max"));
                    const harga = parseInt(hargaText.replace(/[^\d]/g, ''));

                    if (jumlah > maxStok) {
                        alert(`Jumlah paket "${nama}" melebihi stok tersedia (${maxStok}).`);
                        return;
                    }

                    paketBaru.push({
                        id_paket: id,
                        nama_paket: nama,
                        harga_paket: harga,
                        jumlah_paket: jumlah,
                        image_paket: img
                    });
                }
            });

            if (paketBaru.length === 0) {
                alert("Anda belum memilih paket apapun.");
                return;
            }

            const paketLama = JSON.parse(localStorage.getItem("paket_dipilih") || "[]");

            paketBaru.forEach(paket => {
                const existing = paketLama.find(p => p.id_paket === paket.id_paket);
                if (existing) {
                    existing.jumlah_paket += paket.jumlah_paket;
                } else {
                    paketLama.push(paket);
                }
            });

            localStorage.setItem("paket_dipilih", JSON.stringify(paketLama));
            alert("Paket telah ditambahkan ke keranjang!");
            window.location.href = "/produk";
        });
    });
</script>

