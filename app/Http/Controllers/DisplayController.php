<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use App\Models\QueueStatus;
use App\Models\AdminPst;
use App\Models\Infographic; 
use App\Models\RunningText;
use Illuminate\Support\Collection;

class DisplayController extends Controller
{
    public function showDisplay()
    {
        // *** Implementasi Nyata: Ambil Nomor Antrian dari Database ***
        // Karena kita sudah mengimpornya, Class QueueStatus sekarang bisa ditemukan
        $queueStatus = QueueStatus::getCurrent(); 
        $currentQueueNumber = $queueStatus->current_number;

        // 2. Ambil Admin yang Sedang Bertugas dari Database
        // Ambil admin pertama yang statusnya 'Sedang Bertugas' dan urutan terkecil
        $adminPST = AdminPST::where('status_jaga', 'Sedang Bertugas')
                            ->orderBy('urutan', 'asc')
                            ->first();

        // Siapkan data admin untuk dikirim ke view
        if ($adminPST) {
            $adminData = (object)[
                'name' => $adminPST->name, 
                'jabatan' => $adminPST->jabatan, 
                // Menggunakan accessor getPhotoUrlAttribute() yang sudah kita buat
                'photo_url' => $adminPST->photo_url, 
            ];
        } else {
            // Data default jika tidak ada Admin PST yang sedang bertugas
            $adminData = (object)[
                'name' => 'ADMIN TIDAK BERTUGAS', 
                'jabatan' => 'SILAKAN HUBUNGI PETUGAS', 
                'photo_url' => asset('images/infographic_placeholder.png'), // Placeholder default
            ];
        }
        

        // 3. Ambil Infografis Aktif dari Database
        $infographics = Infographic::where('status', 'Aktif')
                                ->orderBy('urutan', 'asc')
                                ->get();
                                
        // Jika tidak ada Infografis aktif, gunakan placeholder
        $infographicList = $infographics->map(function ($item) {
            return [
                'type' => $item->type,
                // Jika foto, gunakan asset path. Jika video, gunakan URL embed.
                'content' => $item->isPhoto() ? asset('storage/' . $item->content_url) : $item->content_url,
                'title' => $item->title
            ];
        })->toArray();
        
        // Jika list kosong, masukkan placeholder
        if (empty($infographicList)) {
            $infographicList[] = [
                'type' => 'Foto (Upload)',
                'content' => asset('images/infographic_placeholder.png'),
                'title' => 'Konten Placeholder'
            ];
        }

        // 4. Ambil Running Text Aktif dari Database (BARU)
        $runningTextItems = RunningText::where('status', 'Aktif')
                                    ->orderBy('urutan', 'asc')
                                    ->pluck('content')
                                    ->toArray();
                                    
        // Jika tidak ada running text aktif, gunakan default
        if (empty($runningTextItems)) {
             $runningText = 'Selamat Datang di Badan Pusat Statistik Kota Metro';
        } else {
            // Gabungkan semua teks dengan pemisah
            $runningText = collect($runningTextItems)->implode('........... ');
        }
        
        // Kirim list infografis, bukan hanya satu URL
        return view('queue.display', [
            'admin' => $adminData, 
            'queue_number' => $currentQueueNumber, 
            'running_text' => $runningText, // Menggunakan running text dari DB
            'infographic_list' => $infographicList,
        ]);
    }

    public function updateQueue(Request $request)
    {
        // Logika untuk BACK dan NEXT
        $request->validate(['action' => 'required|in:next,back']);

        $queueStatus = QueueStatus::getCurrent();
        $currentNumber = $queueStatus->current_number;

        if ($request->action === 'next') {
            $newNumber = $currentNumber + 1;
        } elseif ($request->action === 'back' && $currentNumber > 1) {
            $newNumber = $currentNumber - 1;
        } else {
            // Jika action 'back' dan nomor sudah 1, jangan diubah
            $newNumber = $currentNumber; 
        }

        // Simpan ke Database
        $queueStatus->current_number = $newNumber;
        $queueStatus->save();

        return response()->json([
            'success' => true, 
            'new_number' => $newNumber
        ]);

    }
}