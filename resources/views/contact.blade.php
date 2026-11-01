@extends('layouts.public')

@section('title', 'Hubungi Kami')
@section('meta_description', 'Hubungi tim Dante Pine Enrekang untuk pertanyaan, kerjasama, atau bantuan reservasi melalui formulir, telepon, atau WhatsApp.')

@section('content')
    @include('partials.public.page-hero', [
        'title' => 'Hubungi Kami',
        'subtitle' => 'Punya pertanyaan, rencana kerjasama, atau butuh bantuan reservasi? Tim kami siap membantu.',
        'eyebrow' => 'Kontak & Bantuan',
        'breadcrumb' => [['label' => 'Hubungi Kami']],
    ])

    <section class="pn-section pn-section--tint">
        <div class="pn-container">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="pn-card p-6 sm:p-8">
                        <h2 class="pn-heading text-2xl">Kirim Pesan</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">Isi formulir berikut atau hubungi kami melalui telepon/WhatsApp.</p>

                        @if (session('success'))
                            <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="mt-6 space-y-5">
                            @csrf

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="pn-field-label" for="name">Nama <span class="text-rose-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="pn-field">
                                    @error('name')
                                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="pn-field-label" for="email">Email <span class="text-rose-500">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="pn-field">
                                    @error('email')
                                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="pn-field-label" for="subject">Subjek</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="pn-field">
                                @error('subject')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="pn-field-label" for="message">Pesan <span class="text-rose-500">*</span></label>
                                <textarea id="message" name="message" rows="6" required class="pn-field">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <a href="{{ route('home') }}" class="text-sm font-medium text-slate-500 transition hover:text-emerald-700">Kembali</a>
                                <button type="submit" class="pn-btn pn-btn-primary">
                                    <i class="fas fa-paper-plane" aria-hidden="true"></i> Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <aside class="space-y-5">
                    <div class="pn-card p-6">
                        <h3 class="text-lg font-bold text-slate-900">Informasi Kontak</h3>
                        <p class="mt-1 text-sm text-slate-500">Kanal berikut memberikan respons tercepat.</p>
                        <ul class="mt-5 space-y-4 text-sm">
                            <li class="flex items-start gap-3">
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"><i class="fas fa-phone" aria-hidden="true"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">Telepon / WhatsApp</p>
                                    <a href="tel:+6282145671234" class="text-slate-600 hover:text-emerald-700">0821-4567-1234</a>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">Email</p>
                                    <a href="mailto:info@dantepineenrekang.com" class="text-slate-600 hover:text-emerald-700">info@dantepineenrekang.com</a>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">Alamat</p>
                                    <p class="text-slate-600">Jalur Poros Enrekang - Tana Toraja, Sulawesi Selatan</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"><i class="fas fa-clock" aria-hidden="true"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">Jam Operasional</p>
                                    <p class="text-slate-600">Senin - Minggu: 08.00 - 22.00 WITA</p>
                                </div>
                            </li>
                        </ul>
                        <a href="https://wa.me/6282145671234" target="_blank" rel="noopener" class="pn-btn pn-btn-primary mt-6 w-full">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> Hubungi via WhatsApp
                        </a>
                    </div>

                    <div class="pn-card overflow-hidden">
                        <iframe src="https://www.google.com/maps?q=Enrekang&output=embed" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" title="Lokasi Dante Pine Enrekang"></iframe>
                    </div>

                    <div class="pn-card p-6">
                        <h4 class="text-base font-bold text-slate-900">Pertanyaan Umum</h4>
                        <div class="mt-4 space-y-3">
                            <details class="pn-faq">
                                <summary class="flex items-center justify-between gap-3 text-sm font-semibold text-slate-900">
                                    Bagaimana cara reservasi?
                                    <i class="pn-faq-icon fas fa-plus text-xs text-emerald-600" aria-hidden="true"></i>
                                </summary>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Klik tombol "Booking Sekarang" atau isi formulir kontak dengan subjek "Reservasi".</p>
                            </details>
                            <details class="pn-faq">
                                <summary class="flex items-center justify-between gap-3 text-sm font-semibold text-slate-900">
                                    Apakah tersedia tenda sewaan?
                                    <i class="pn-faq-icon fas fa-plus text-xs text-emerald-600" aria-hidden="true"></i>
                                </summary>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Ya, tersedia paket camping yang dapat dipilih. Lihat bagian Paket Camping di beranda.</p>
                            </details>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
