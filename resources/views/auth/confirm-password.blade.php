<x-guest-layout title="Konfirmasi Kata Sandi">
    <div class="mb-7">
        <p class="pn-eyebrow text-emerald-700">Area Aman</p>
        <h1 class="pn-display mt-2 text-2xl text-slate-900 sm:text-[1.75rem]">Konfirmasi kata sandi</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Ini adalah area aman aplikasi. Silakan masukkan kata sandi Anda kembali sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <x-auth.field name="password" label="Kata Sandi" type="password" icon="lock" required
            autofocus autocomplete="current-password" placeholder="Masukkan kata sandi" />

        <button type="submit" class="pn-btn pn-btn-primary w-full">
            Konfirmasi
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </button>
    </form>
</x-guest-layout>
