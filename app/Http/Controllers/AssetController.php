<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;

class AssetController extends Controller
{
    // Menampilkan halaman utama dashboard aset
    public function index()
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('dashboard', compact('categories', 'locations'));
    }

    // [AJAX GET] Ambil semua data + fitur Live Search
    public function fetch(Request $request)
    {
        $query = Asset::with(['category', 'location']);

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_aset', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->latest()->get());
    }

    // [AJAX POST] Simpan data baru (Hanya Admin)
    public function store(Request $request)
    {
        $request->validate([
            'nama_aset' => 'required',
            'serial_number' => 'required|unique:assets,serial_number',
            'category_id' => 'required',
            'location_id' => 'required',
            'status' => 'required'
        ]);

        $asset = Asset::create($request->all());
        return response()->json(['success' => true, 'message' => 'Aset berhasil ditambahkan!']);
    }

    // [AJAX GET] Ambil 1 data untuk di-load ke form Edit (Hanya Admin)
    public function edit($id)
    {
        return response()->json(Asset::find($id));
    }

    // [AJAX PUT] Update data (Hanya Admin)
    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);
        $asset->update($request->all());
        return response()->json(['success' => true, 'message' => 'Aset berhasil diperbarui!']);
    }

    // [AJAX DELETE] Hapus data (Hanya Admin)
    public function destroy($id)
    {
        Asset::find($id)->delete();
        return response()->json(['success' => true, 'message' => 'Aset berhasil dihapus!']);
    }
}