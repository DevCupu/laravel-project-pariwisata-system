@component('mail::message')
# Pesan Kontak Baru

**Nama:** {{ $data['name'] }}

**Email:** {{ $data['email'] }}

**Subjek:** {{ $data['subject'] ?? '-' }}

**Pesan:**

{{ $data['message'] }}

---

Pesan ini dikirim dari form Kontak di situs.

@endcomponent
