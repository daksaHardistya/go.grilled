<x-layoute>
    {{-- <x-navbar></x-navbar> --}}
        <div class="mainpage-home">
        <!-- <img class="bg-landingpage" src="img/bg.png" alt="">-->
            <div class="bodypage">
                <div class="logobody-atas">
                    <img class="logo-body" src="../img/logos/logo.png" alt="" height="100px">
                </div>
                <div class="teks-landingpage">
                    <h1>
                    Solusi Grill Tanpa Ribet !
                    </h1>
                </div>
                <a class="button-order" href='paket'>Order Now</a>
            </div>
        </div>
        <!-- Gelembung WhatsApp & Instagram -->
        <!-- Dropdown Bubble -->
        <div x-data="{ open: false }" class="bubble-icon fixed bottom-6 right-6 z-50">
            
            <!-- Dropdown item -->
            <div
                x-show="open"
                x-transition
                @click.outside="open = false"
                class="mt-3 flex flex-col items-end gap-2"
            >
                <a href="https://wa.me/6281938103934" target="_blank"
                    class="w-12 h-12 rounded-full bg-green-500 hover:bg-green-600 flex items-center justify-center shadow-lg">
                    <img src="../icon/whatsapp.png" alt="WhatsApp" class="icon w-6 h-6">
                </a><br>
                <a href="https://instagram.com/go.grilled" target="_blank"
                    class="w-12 h-12 rounded-full bg-pink-500 hover:bg-pink-600 flex items-center justify-center shadow-lg">
                    <img src="../icon/instagram.png" alt="Instagram" class="icon w-6 h-6">
                </a>
            </div>
            <!-- Tombol utama -->
            <button @click="open = !open"
                class="contact-icon">
                <img src="../icon/contact.png" alt="Hubungi Kami" class="icon w-7 h-7">
            </button>
        </div>
</x-layoute>