@extends('layouts.public')

@section('title', 'Hubungi Kami')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h1 class="text-2xl font-bold">Hubungi Kami</h1>
                    <p class="text-gray-600 mt-1">Punya pertanyaan, kerjasama, atau butuh bantuan? Isi formulir berikut atau hubungi kami melalui telepon/WhatsApp.</p>

                    @if(session('success'))
                        <div class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Nama *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
                                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
                                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Subjek</label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
                            @error('subject') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Pesan *</label>
                            <textarea name="message" rows="6" required
                                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">{{ old('message') }}</textarea>
                            @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-800">Kembali</a>
                            <button type="submit" class="rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700">Kirim Pesan</button>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-semibold">Informasi Kontak</h3>
                    <p class="text-sm text-gray-500 mt-1">Hubungi tim kami melalui kanal berikut untuk respons cepat.</p>

                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="text-green-600 text-xl"><i class="fas fa-phone"></i></span>
                            <div>
                                <div class="font-semibold">Telepon / WhatsApp</div>
                                <a href="tel:+6282145671234" class="text-sm text-gray-700">0821-4567-1234</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-600 text-xl"><i class="fas fa-envelope"></i></span>
                            <div>
                                <div class="font-semibold">Email</div>
                                <a href="mailto:info@dantepineenrekang.com" class="text-sm text-gray-700">info@dantepineenrekang.com</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-600 text-xl"><i class="fas fa-map-marker-alt"></i></span>
                            <div>
                                <div class="font-semibold">Alamat</div>
                                <p class="text-sm text-gray-700">Jalur Poros Enrekang - Tana Toraja, Sulawesi Selatan</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-600 text-xl"><i class="fas fa-clock"></i></span>
                            <div>
                                <div class="font-semibold">Jam Operasional</div>
                                <p class="text-sm text-gray-700">Senin - Minggu: 08.00 - 22.00 WITA</p>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-4">
                        <a href="https://wa.me/6282145671234" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:bg-green-700">Hubungi via WhatsApp</a>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                    <h4 class="font-semibold">Lokasi</h4>
                    <p class="text-xs text-gray-500 mt-1">Peta lokasi Dante Pine (klik untuk buka di Google Maps).</p>
                    <div class="mt-3 overflow-hidden rounded-lg">
                        <!-- Placeholder iframe - replace src dengan Google Maps embed URL -->
                        <iframe src="https://www.google.com/maps?q=Enrekang&output=embed" width="100%" height="160" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                    <h4 class="font-semibold">Pertanyaan Umum</h4>
                    <div class="mt-3 text-sm text-gray-700 space-y-2">
                        <details class="group">
                            <summary class="cursor-pointer font-medium">Bagaimana cara reservasi?</summary>
                            <div class="mt-1 text-sm text-gray-600">Anda dapat melakukan reservasi melalui tombol "Booking Sekarang" di menu atau mengisi formulir kontak dengan subjek "Reservasi".</div>
                        </details>
                        <details class="group">
                            <summary class="cursor-pointer font-medium">Apakah tersedia tenda sewaan?</summary>
                            <div class="mt-1 text-sm text-gray-600">Ya, tersedia paket camping yang dapat dipilih. Cek menu Paket Camping di halaman beranda.</div>
                        </details>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
