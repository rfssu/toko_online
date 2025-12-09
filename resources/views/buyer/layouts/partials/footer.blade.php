<footer class="bg-gray-900 text-gray-300 border-t border-gray-800">
    <div class="container mx-auto px-4 md:px-8 py-10 lg:py-16">
        <div class="footer grid-cols-1 md:grid-cols-3 gap-10 text-base-content">
            
            {{-- KOLOM 1: Brand & Deskripsi --}}
            <aside class="flex flex-col gap-4 text-gray-400">
                <div class="font-bold text-3xl text-white">
                    <i class="fa-solid fa-store text-amber-500 mr-1"></i> Khas<span class="text-amber-500">Jogja.</span>
                </div>
                <p class="leading-relaxed text-sm max-w-xs">
                    Pusat oleh-oleh terpercaya yang bekerja sama langsung dengan UMKM lokal. Rasa otentik, harga terbaik, dan pengiriman aman ke seluruh Indonesia.
                </p>
                <div class="flex gap-4 mt-2">
                    <a class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-amber-600 hover:text-white transition duration-300 cursor-pointer">
                        <i class="fa-brands fa-instagram text-xl"></i>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-green-600 hover:text-white transition duration-300 cursor-pointer">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white transition duration-300 cursor-pointer">
                        <i class="fa-brands fa-tiktok text-xl"></i>
                    </a>
                </div>
            </aside> 
            
            {{-- KOLOM 2: Kontak & Jam Buka --}}
            <nav class="text-gray-400">
                <header class="footer-title text-white opacity-100 text-lg mb-4">Hubungi Kami</header> 
                <div class="flex flex-col gap-4">
                    <div class="flex gap-3 items-start">
                        <i class="fa-solid fa-location-dot mt-1 text-amber-500 w-5"></i>
                        <span class="text-sm">Jl. Malioboro No. 123, Sosromenduran, Gedong Tengen, Kota Yogyakarta, DIY 55271</span>
                    </div>
                    <div class="flex gap-3 items-center">
                        <i class="fa-solid fa-phone text-amber-500 w-5"></i>
                        <span class="text-sm">+62 812-3456-7890 (Admin)</span>
                    </div>
                    <div class="flex gap-3 items-center">
                        <i class="fa-solid fa-clock text-amber-500 w-5"></i>
                        <span class="text-sm">Buka Setiap Hari: 07.00 - 21.00 WIB</span>
                    </div>
                </div>
            </nav>
            
            {{-- KOLOM 3: Maps --}}
            <div class="w-full">
                <header class="footer-title text-white opacity-100 text-lg mb-4">Lokasi Toko</header> 
                <div class="w-full h-48 bg-gray-800 rounded-xl overflow-hidden relative group border border-gray-700">
                    {{-- Ganti src dengan embed map asli nanti --}}
                    <img src="https://mt1.google.com/vt/lyrs=m&x=1325&y=3143&z=13" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition duration-500">
                    
                    <a href="https://maps.google.com" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-transparent transition cursor-pointer">
                        <button class="btn btn-sm bg-white text-gray-900 border-none shadow-lg gap-2 hover:bg-gray-100 font-bold">
                            <i class="fa-solid fa-map-location-dot text-amber-600"></i> Buka di Google Maps
                        </button>
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Copyright Bar --}}
        <div class="border-t border-gray-800 mt-10 pt-6 text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} <span class="text-gray-300 font-bold">KhasJogja.</span> All rights reserved.
            </p>
        </div>
    </div>
</footer>