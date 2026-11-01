<x-guest-layout title="Lupa Kata Sandi">
    <div class="mb-7">
        <p class="pn-eyebrow text-emerald-700">Pemulihan Akun</p>
        <h1 class="pn-display mt-2 text-2xl text-slate-900 sm:text-[1.75rem]">Lupa kata sandi?</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Masukkan alamat email akun Anda. Kami akan mengirim tautan untuk membuat kata sandi baru.
        </p>
    </div>

    @if (session('status'))
        <x-auth.alert variant="success" class="mb-5">{{ session('status') }}</x-auth.alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <x-auth.field name="email" label="Email" type="email" icon="mail" :value="old('email')" required autofocus
            autocomplete="username" placeholder="nama@email.com" />

        <button type="submit" class="pn-btn pn-btn-primary w-full">
            Kirim Tautan Reset
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
            </svg>
        </button>
    </form>

    <p class="mt-6 border-t border-slate-100 pt-5 text-center text-sm text-slate-500">
        Ingat kata sandi Anda?
        <a href="{{ route('login') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-800">
            Kembali ke halaman masuk
        </a>
    </p>
</x-guest-layout>
