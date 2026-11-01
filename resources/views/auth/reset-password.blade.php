<x-guest-layout title="Atur Ulang Kata Sandi">
    <div class="mb-7">
        <p class="pn-eyebrow text-emerald-700">Pemulihan Akun</p>
        <h1 class="pn-display mt-2 text-2xl text-slate-900 sm:text-[1.75rem]">Atur kata sandi baru</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Buat kata sandi baru untuk akun Anda, lalu masuk kembali menggunakan kata sandi tersebut.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-auth.field name="email" label="Email" type="email" icon="mail" :value="old('email', $request->email)" required
            autofocus autocomplete="username" placeholder="nama@email.com" />

        <x-auth.field name="password" label="Kata Sandi Baru" type="password" icon="lock" required
            autocomplete="new-password" placeholder="Minimal 8 karakter"
            hint="Gunakan kombinasi huruf, angka, dan simbol." />

        <x-auth.field name="password_confirmation" label="Konfirmasi Kata Sandi Baru" type="password" icon="lock" required
            autocomplete="new-password" placeholder="Ulangi kata sandi baru" />

        <button type="submit" class="pn-btn pn-btn-primary w-full">
            Simpan Kata Sandi
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </button>
    </form>

    <p class="mt-6 border-t border-slate-100 pt-5 text-center text-sm text-slate-500">
        <a href="{{ route('login') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-800">
            Kembali ke halaman masuk
        </a>
    </p>
</x-guest-layout>
