<x-guest-layout title="Buat Akun">
    <div class="mb-7">
        <p class="pn-eyebrow text-emerald-700">Pendaftaran</p>
        <h1 class="pn-display mt-2 text-2xl text-slate-900 sm:text-[1.75rem]">Buat akun baru</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Lengkapi data di bawah untuk membuat akun Dante Pine Enrekang.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <x-auth.field name="name" label="Nama Lengkap" icon="user" :value="old('name')" required autofocus
            autocomplete="name" placeholder="Nama Anda" />

        <x-auth.field name="email" label="Email" type="email" icon="mail" :value="old('email')" required
            autocomplete="username" placeholder="nama@email.com" />

        <x-auth.field name="password" label="Kata Sandi" type="password" icon="lock" required
            autocomplete="new-password" placeholder="Minimal 8 karakter"
            hint="Gunakan kombinasi huruf, angka, dan simbol." />

        <x-auth.field name="password_confirmation" label="Konfirmasi Kata Sandi" type="password" icon="lock" required
            autocomplete="new-password" placeholder="Ulangi kata sandi" />

        <button type="submit" class="pn-btn pn-btn-primary w-full">
            Buat Akun
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </form>

    <p class="mt-6 border-t border-slate-100 pt-5 text-center text-sm text-slate-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-800">
            Masuk di sini
        </a>
    </p>
</x-guest-layout>
