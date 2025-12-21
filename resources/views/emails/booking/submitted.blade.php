<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Permintaan Booking Diterima</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; background-color: #f8fafc; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0;">
        <h2 style="margin-top: 0; font-size: 20px; color: #0f172a;">Halo {{ $booking->name }},</h2>
        <p style="font-size: 15px; line-height: 1.6;">
            Terima kasih telah mengirim permintaan booking ke Dante Pine Enrekang. Kami sudah menerima detail reservasi Anda
            dan tim admin akan memverifikasi ketersediaan fasilitas dalam 1x24 jam.
        </p>

        <div style="margin: 24px 0; padding: 16px; background: #f1f5f9; border-radius: 12px;">
            <p style="margin: 0 0 8px; font-weight: 600;">Ringkasan Permintaan</p>
            <ul style="padding-left: 16px; margin: 0; font-size: 14px; line-height: 1.6;">
                <li>Kode Booking: <strong>{{ $booking->booking_code }}</strong></li>
                <li>Tanggal Kunjungan: {{ \Carbon\Carbon::parse($booking->visit_date)->translatedFormat('d M Y') }}</li>
                <li>Fasilitas: {{ optional($booking->facility)->name ?? '-' }}</li>
                <li>Jumlah Tamu: {{ $booking->guests }} orang</li>
            </ul>
        </div>

        <p style="font-size: 15px; line-height: 1.6;">
            Simpan kode booking di atas untuk keperluan konfirmasi pembayaran atau pengecekan status. Kami akan mengirimkan
            pembaruan melalui email/WhatsApp begitu permintaan Anda masuk tahap konfirmasi.
        </p>

        <p style="margin-top: 32px; font-size: 14px; color: #475569;">
            Salam hangat,<br>
            <strong>Tim Reservasi Dante Pine Enrekang</strong>
        </p>
    </div>
</body>

</html>
