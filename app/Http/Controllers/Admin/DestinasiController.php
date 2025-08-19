<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\KategoriWisata;
use Illuminate\Http\Request;

class DestinasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Destinasi::with('kategori');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->where('status', true);
            } elseif ($request->status == 'draft') {
                $query->where('status', false);
            }
        }

        $destinasis = $query->latest()->get();
        $kategoris = KategoriWisata::all();
        $lokasiUnik = Destinasi::select('lokasi')->distinct()->pluck('lokasi');

        $totalDestinasi = Destinasi::count();
        $destinasiAktif = Destinasi::where('status', 1)->count();
        $bulanIni = Destinasi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.destinasi.index', compact('destinasis', 'kategoris', 'lokasiUnik', 'totalDestinasi', 'bulanIni', 'destinasiAktif'));
    }


    public function create()
    {
        $kategoris = KategoriWisata::all();

        return view('admin.destinasi.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        // Debug apakah data yang di kirim berhasil!
        // dd($request->all());

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'harga_tiket' => 'nullable|integer|min:0',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'status' => 'nullable|boolean',
            'kategori_id' => 'required|exists:kategori_wisatas,id',
            'gambar' => 'nullable|image',
            'maps_embed' => 'nullable',

        ]);

        $data['status'] = $request->has('status');

        // Cek apakah gratis dicentang
        if ($request->has('gratis')) {
            $data['harga_tiket'] = 0;
        } else {
            // Jika user isi manual, pastikan tetap format integer
            $data['harga_tiket'] = (int) str_replace('.', '', $request->input('harga_tiket'));
        }

        // Simpan gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('gambar-destinasi', 'public');
        }

        Destinasi::create($data);
        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi berhasil ditambahkan!');
    }

    public function edit(Destinasi $destinasi)
    {
        $kategoris = KategoriWisata::all();
        return view('admin.destinasi.edit', compact('destinasi', 'kategoris'));
    }

    public function update(Request $request, Destinasi $destinasi)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'harga_tiket' => 'nullable|integer|min:0',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'status' => 'nullable|boolean',
            'kategori_id' => 'required|exists:kategori_wisatas,id',
            'gambar' => 'nullable|image',
            'maps_embed' => 'nullable',
        ]);

        $data['status'] = $request->boolean('status');

        // Cek apakah gratis dicentang
        if ($request->has('gratis')) {
            $data['harga_tiket'] = 0;
        } else {
            // Jika user isi manual, pastikan tetap format integer
            $data['harga_tiket'] = (int) str_replace('.', '', $request->input('harga_tiket'));
        }

        // Simpan gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('gambar-destinasi', 'public');
        }

        $destinasi->update($data);
        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi berhasil diperbarui!');
    }

    public function show(Destinasi $destinasi)
    {
        return view('admin.destinasi.show', compact('destinasi'));
    }


    public function destroy(Destinasi $destinasi)
    {
        $destinasi->delete();
        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi berhasil dihapus!');
    }
}
