<x-guest-layout title="Verifikasi Email">
    <div class="mb-7">
        <p class="pn-eyebrow text-emerald-700">Verifikasi Akun</p>
        <h1 class="pn-display mt-2 text-2xl text-slate-900 sm:text-[1.75rem]">Verifikasi email Anda</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Terima kasih sudah mendaftar. Sebelum memulai, verifikasi alamat email Anda melalui tautan yang baru saja
            kami kirimkan. Jika belum menerima email, kami bisa mengirimkannya kembali.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <x-auth.alert variant="success" class="mb-5">
            Tautan verifikasi baru sudah dikirim ke alamat email yang Anda gunakan saat mendaftar.
        </x-auth.alert>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="pn-btn pn-btn-primary w-full">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="w-full rounded-xl px-4 py-2 text-sm font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                Keluar dari akun
            </button>
        </form>
    </div>
</x-guest-layout>
