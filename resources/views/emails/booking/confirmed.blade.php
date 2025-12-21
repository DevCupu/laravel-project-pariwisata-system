<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Booking Dikonfirmasi</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; background-color: #f8fafc; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0;">
        <h2 style="margin-top: 0; font-size: 20px; color: #0f172a;">Booking Anda Sudah Dikonfirmasi 🎉</h2>
        <p style="font-size: 15px; line-height: 1.6;">
            Terima kasih {{ $booking->name }}, permintaan kunjungan Anda ke Dante Pine Enrekang telah kami konfirmasi.
            Simpan kode booking berikut sebagai referensi utama saat pembayaran maupun check-in di lokasi.
        </p>

        <div style="margin: 24px 0; padding: 16px; background: #ecfccb; border-radius: 12px; border: 1px solid #bbf7d0;">
            <p style="margin: 0 0 12px; font-weight: 600;">Detail Konfirmasi</p>
            <table style="width: 100%; font-size: 14px; line-height: 1.6; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="padding: 4px 0; width: 40%; color: #475569;">Kode Booking</td>
                        <td style="padding: 4px 0; font-weight: 600;">{{ $booking->booking_code }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #475569;">Tanggal Kunjungan</td>
                        <td style="padding: 4px 0;">{{ \Carbon\Carbon::parse($booking->visit_date)->translatedFormat('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #475569;">Fasilitas</td>
                        <td style="padding: 4px 0;">{{ optional($booking->facility)->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #475569;">Jumlah Tamu</td>
                        <td style="padding: 4px 0;">{{ $booking->guests }} orang</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-bottom: 24px; padding: 16px; background: #f1f5f9; border-radius: 12px;">
            <p style="margin: 0 0 8px; font-weight: 600;">Instruksi Pembayaran</p>
            <p style="font-size: 14px; line-height: 1.6; margin: 0;">
                • Transfer pembayaran DP sesuai informasi yang diberikan admin.<br>
                • Cantumkan <strong>{{ $booking->booking_code }}</strong> pada berita transfer / bukti pembayaran.<br>
                • Kirim bukti pembayaran ke admin agar slot Anda resmi terjadwal.
            </p>
        </div>

        @if ($booking->notes)
            <p style="font-size: 13px; line-height: 1.6; color: #475569;">
                <strong>Catatan Anda:</strong> {{ $booking->notes }}
            </p>
        @endif

        <p style="font-size: 14px; line-height: 1.6;">
            Sampai jumpa di Dante Pine Enrekang! Bila ada pertanyaan tambahan, langsung balas email ini atau hubungi admin kami melalui WhatsApp resmi.
        </p>

        <p style="margin-top: 32px; font-size: 14px; color: #475569;">
            Salam hangat,<br>
            <strong>Tim Reservasi Dante Pine Enrekang</strong>
        </p>
    </div>
</body>

</html>
