<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Guest; 
use App\Models\AdminPst; // Pastikan nama model sesuai: AdminPst
use App\Models\Infographic; 
use App\Models\RunningText; // PENTING: Import model RunningText
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GuestsExport;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman Dashboard utama (yang menampung menu-menu).
     */
    public function index()
    {
        // Langsung arahkan ke menu pertama: Manajemen Buku Tamu
        return redirect()->route('admin.guests.index');
    }

    // ===============================================
    // --- MANAJEMEN BUKU TAMU (GUEST) ---
    // (Tidak ada perubahan di bagian ini)
    // ===============================================

    /**
     * Menampilkan Manajemen Buku Tamu (Daftar Tamu)
     */
    public function guestsIndex(Request $request)
    {
        $purposes = Guest::$purposes;

        $query = Guest::orderBy('created_at', 'desc');

        if ($request->filled('purpose') && $request->purpose !== 'Semua Keperluan') {
            $query->where('purpose', $request->purpose);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('institution', 'like', $search)
                  ->orWhere('phone', 'like', $search)
                  ->orWhere('objective', 'like', $search);
            });
        }
        
        $guests = $query->get();

        return view('admin.guests.index', [
            'guests' => $guests,
            'purposes' => $purposes,
            'selected_purpose' => $request->purpose,
            'selected_start_date' => $request->start_date,
            'selected_end_date' => $request->end_date,
            'selected_search' => $request->search,
        ]);
    }

    /**
     * Menyimpan data tamu baru.
     */
    public function guestsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'purpose' => 'required|in:' . implode(',', Guest::$purposes),
            'objective' => 'required|string',
        ]);

        Guest::create($validated);

        return redirect()->route('admin.guests.index')->with('success', 'Data tamu berhasil disimpan.');
    }

    /**
     * Memperbarui data tamu.
     */
    public function guestsUpdate(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'purpose' => 'required|in:' . implode(',', Guest::$purposes),
            'objective' => 'required|string',
        ]);

        $guest->update($validated);

        return redirect()->route('admin.guests.index')->with('success', 'Data tamu berhasil diubah.');
    }

    /**
     * Menghapus data tamu.
     */
    public function guestsDestroy(Guest $guest)
    {
        $guest->delete();

        return redirect()->route('admin.guests.index')->with('success', 'Data tamu berhasil dihapus.');
    }
    
    // ===============================================
    // --- MANAJEMEN ADMIN PST ---
    // ===============================================

    /**
     * Menampilkan Manajemen Admin PST.
     */
    public function adminPstIndex(Request $request)
    {
        $admins = AdminPst::orderBy('urutan', 'asc')->get(); // Menggunakan AdminPst

        return view('admin.admin_pst.index', [
            'admins' => $admins,
            'statusOptions' => AdminPst::$statusOptions,
        ]);
    }

    /**
     * Menyimpan data Admin PST baru (dengan upload file).
     */
    public function adminPstStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048', 
            // status_jaga dan urutan dihilangkan dari form
        ]);

        $validated['photo_path'] = null;

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('admin_photos', 'public');
        }

        // Tambahkan nilai default untuk status dan urutan
        $validated['status_jaga'] = 'Tidak Bertugas'; // Default status
        $validated['urutan'] = AdminPst::max('urutan') + 1; // Auto-increment urutan

        AdminPst::create($validated);

        return redirect()->route('admin.admin_pst.index')->with('success', 'Data Admin PST berhasil ditambahkan.');
    }

    /**
     * Memperbarui data Admin PST (hanya nama, jabatan, dan foto).
     */
    public function adminPstUpdate(Request $request, AdminPst $adminPst)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048', 
            // status_jaga dan urutan dihilangkan dari form
        ]);

        $dataToUpdate = [
            'name' => $validated['name'],
            'jabatan' => $validated['jabatan'],
        ];

        if ($request->hasFile('photo')) {
            if ($adminPst->photo_path) {
                Storage::disk('public')->delete($adminPst->photo_path);
            }
            $dataToUpdate['photo_path'] = $request->file('photo')->store('admin_photos', 'public');
        }

        $adminPst->update($dataToUpdate);

        return redirect()->route('admin.admin_pst.index')->with('success', 'Data Admin PST berhasil diperbarui.');
    }

    /**
     * Mengubah status jaga Admin PST (toggle).
     */
    public function adminPstToggleStatus(AdminPst $adminPst)
    {
        $newStatus = $adminPst->status_jaga === 'Sedang Bertugas' ? 'Tidak Bertugas' : 'Sedang Bertugas';
        
        $adminPst->update(['status_jaga' => $newStatus]);

        return redirect()->route('admin.admin_pst.index')->with('success', 'Status Admin PST berhasil diubah menjadi ' . $newStatus . '.');
    }

    /**
     * Menghapus data Admin PST.
     */
    public function adminPstDestroy(AdminPst $adminPst)
    {
        $adminPst->delete(); 

        return redirect()->route('admin.admin_pst.index')->with('success', 'Data Admin PST berhasil dihapus.');
    }

    // ===============================================
    // --- MANAJEMEN INFOGRAFIS ---
    // ===============================================

    /**
     * Menampilkan Manajemen Infografis.
     */
    public function infographicsIndex()
    {
        $infographics = Infographic::orderBy('urutan', 'asc')->get();

        return view('admin.infographics.index', [
            'infographics' => $infographics,
            'typeOptions' => Infographic::$typeOptions,
            'statusOptions' => Infographic::$statusOptions,
        ]);
    }

    /**
     * Menyimpan data Infografis baru.
     */
    public function infographicsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', Infographic::$typeOptions),
            // status dan urutan dihilangkan dari form
            'photo' => 'nullable|required_if:type,Foto (Upload)|image|max:2048', 
            'video_url' => 'nullable|required_if:type,Video (URL Embed)|url',
        ]);

        $contentUrl = null;

        if ($validated['type'] === 'Foto (Upload)' && $request->hasFile('photo')) {
            $contentUrl = $request->file('photo')->store('infographics', 'public');
        } elseif ($validated['type'] === 'Video (URL Embed)' && !empty($validated['video_url'])) {
            $contentUrl = Infographic::convertToEmbedUrl($validated['video_url']);
        }

        Infographic::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            // Tambahkan nilai default untuk status dan urutan
            'status' => 'Tidak Aktif', // Default status
            'urutan' => Infographic::max('urutan') + 1, // Auto-increment urutan
            'content_url' => $contentUrl,
        ]);

        return redirect()->route('admin.infographics.index')->with('success', 'Infografis berhasil ditambahkan.');
    }

    /**
     * Memperbarui data Infografis (hanya judul, tipe, dan konten).
     */
    public function infographicsUpdate(Request $request, Infographic $infographic)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', Infographic::$typeOptions),
            // status dan urutan dihilangkan dari form
            'photo' => 'nullable|image|max:2048', 
            'video_url' => 'nullable|url',
        ]);

        $contentUrl = $infographic->content_url;

        // Logika untuk menghapus file lama dan menyimpan file baru / memproses URL baru
        if ($validated['type'] === 'Foto (Upload)') {
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($infographic->isPhoto() && $infographic->content_url) {
                    Storage::disk('public')->delete($infographic->content_url);
                }
                $contentUrl = $request->file('photo')->store('infographics', 'public');
            }
            // Jika tipe berubah dari Video ke Foto, dan tidak ada file baru
            elseif (!$request->hasFile('photo') && !$infographic->isPhoto()) {
                $contentUrl = null; 
            }
        } 
        
        elseif ($validated['type'] === 'Video (URL Embed)') {
            // Hapus file foto lama jika tipe berubah menjadi video
            if ($infographic->isPhoto() && $infographic->content_url) {
                Storage::disk('public')->delete($infographic->content_url);
            }
            if (!empty($validated['video_url'])) {
                $contentUrl = Infographic::convertToEmbedUrl($validated['video_url']);
            }
            // Jika tipe berubah dari Foto ke Video, dan video_url kosong
            elseif (empty($validated['video_url'])) {
                $contentUrl = null;
            }
        }

        $infographic->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            // status dan urutan dipertahankan di nilai lama karena tidak diupdate di form
            'content_url' => $contentUrl,
        ]);

        return redirect()->route('admin.infographics.index')->with('success', 'Infografis berhasil diperbarui.');
    }

    /**
     * Mengubah status Infografis (toggle).
     */
    public function infographicsToggleStatus(Infographic $infographic)
    {
        $newStatus = $infographic->status === 'Aktif' ? 'Tidak Aktif' : 'Aktif';
        
        $infographic->update(['status' => $newStatus]);

        return redirect()->route('admin.infographics.index')->with('success', 'Status Infografis berhasil diubah menjadi ' . $newStatus . '.');
    }

    /**
     * Menghapus data Infografis.
     */
    public function infographicsDestroy(Infographic $infographic)
    {
        if ($infographic->isPhoto() && $infographic->content_url) {
            Storage::disk('public')->delete($infographic->content_url);
        }
        
        $infographic->delete();

        return redirect()->route('admin.infographics.index')->with('success', 'Infografis berhasil dihapus.');
    }

    // ===============================================
    // --- MANAJEMEN RUNNING TEXT ---
    // ===============================================

    /**
     * Menampilkan Manajemen Running Text.
     */
    public function runningTextIndex()
    {
        $runningTexts = RunningText::orderBy('urutan', 'asc')->get();

        return view('admin.running_texts.index', [
            'runningTexts' => $runningTexts,
            'statusOptions' => RunningText::$statusOptions,
        ]);
    }

    /**
     * Menyimpan data Running Text baru.
     */
    public function runningTextStore(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            // status dan urutan dihilangkan dari form
        ]);

        // Tambahkan nilai default untuk status dan urutan
        $validated['status'] = 'Tidak Aktif'; // Default status
        $validated['urutan'] = RunningText::max('urutan') + 1; // Auto-increment urutan

        RunningText::create($validated);

        return redirect()->route('admin.running_texts.index')->with('success', 'Running Text berhasil ditambahkan.');
    }

    /**
     * Memperbarui data Running Text (hanya konten).
     */
    public function runningTextUpdate(Request $request, RunningText $runningText)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            // status dan urutan dihilangkan dari form
        ]);

        $runningText->update(['content' => $validated['content']]);

        return redirect()->route('admin.running_texts.index')->with('success', 'Running Text berhasil diperbarui.');
    }

    /**
     * Mengubah status Running Text (toggle).
     */
    public function runningTextToggleStatus(RunningText $runningText)
    {
        $newStatus = $runningText->status === 'Aktif' ? 'Tidak Aktif' : 'Aktif';
        
        $runningText->update(['status' => $newStatus]);

        return redirect()->route('admin.running_texts.index')->with('success', 'Status Running Text berhasil diubah menjadi ' . $newStatus . '.');
    }

    /**
     * Menghapus data Running Text.
     */
    public function runningTextDestroy(RunningText $runningText)
    {
        $runningText->delete();

        return redirect()->route('admin.running_texts.index')->with('success', 'Data Running Text berhasil dihapus.');
    }

    public function guestsExport(Request $request)
    {
        // Menentukan nama file berdasarkan tanggal dan waktu export
        $filename = 'data_tamu_versi_' . now()->format('Ymd') . '.xlsx';

        // Memicu download dengan passing Request object ke GuestsExport
        return Excel::download(new GuestsExport($request), $filename);
    }
}