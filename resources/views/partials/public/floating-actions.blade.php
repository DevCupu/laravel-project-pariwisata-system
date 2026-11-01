@php
    $waLink = $waLink ?? 'https://wa.me/6282145671234';
    $phone = $phone ?? null;
@endphp

<!-- Floating actions -->
<div class="pn-fab-wrap">
    @if ($phone)
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="pn-totop" style="opacity:1;visibility:visible;transform:none" aria-label="Telepon kami">
            <i class="fas fa-phone"></i>
        </a>
    @endif
    <button type="button" id="pn-totop" class="pn-totop" aria-label="Kembali ke atas">
        <i class="fas fa-arrow-up"></i>
    </button>
    <a href="{{ $waLink }}" target="_blank" rel="noopener" class="pn-wa" aria-label="Chat WhatsApp">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>
</div>

<!-- Sticky mobile CTA -->
<div class="pn-sticky-cta">
    <a href="{{ $waLink }}" target="_blank" rel="noopener" class="flex items-center gap-3">
        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            <i class="fab fa-whatsapp text-lg"></i>
        </span>
        <span class="leading-tight">
            <span class="block text-xs font-medium text-slate-500">Butuh bantuan?</span>
            <span class="block text-sm font-bold text-slate-900">Chat WhatsApp</span>
        </span>
    </a>
    <a href="{{ route('booking.form') }}" class="pn-btn pn-btn-primary !px-5 !py-3 text-sm">
        <i class="fas fa-calendar-check"></i> Booking
    </a>
</div>
