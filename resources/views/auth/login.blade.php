<x-guest-layout title="Masuk">
    <div class="mb-7">
        <p class="pn-eyebrow text-emerald-700">Area Administrator</p>
        <h1 class="pn-display mt-2 text-2xl text-slate-900 sm:text-[1.75rem]">Selamat datang kembali</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Masuk untuk mengelola booking, konten, dan promo Dante Pine Enrekang.
        </p>
    </div>

    @if (session('status'))
        <x-auth.alert variant="success" class="mb-5">{{ session('status') }}</x-auth.alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <x-auth.field name="email" label="Email" type="email" icon="mail" :value="old('email')" required autofocus
            autocomplete="username" placeholder="nama@email.com" />

        <x-auth.field name="password" label="Kata Sandi" type="password" icon="lock" required
            autocomplete="current-password" placeholder="Masukkan kata sandi" />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <button type="submit" class="pn-btn pn-btn-primary w-full">
            Masuk
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </form>

    <p class="mt-6 border-t border-slate-100 pt-5 text-center text-xs leading-relaxed text-slate-500">
        Halaman ini khusus administrator. Butuh bantuan akses? Hubungi
        <a href="mailto:info@dantepineenrekang.com"
            class="font-semibold text-emerald-700 transition hover:text-emerald-800">info@dantepineenrekang.com</a>.
    </p>
</x-guest-layout>
