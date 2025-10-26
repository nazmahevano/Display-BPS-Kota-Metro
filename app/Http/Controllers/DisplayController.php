<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use App\Models\QueueStatus;

class DisplayController extends Controller
{
    public function showDisplay()
    {
        // *** Implementasi Nyata: Ambil Nomor Antrian dari Database ***
        // Karena kita sudah mengimpornya, Class QueueStatus sekarang bisa ditemukan
        $queueStatus = QueueStatus::getCurrent(); 
        $currentQueueNumber = $queueStatus->current_number;

        // *** Simulasi Data Sisanya (Admin, Running Text, Infografis) ***
        // ... (data simulasi lainnya) ...
        $data = [
            'admin' => (object)['name' => 'SHOLEH PATI ADITYO S.', 'jabatan' => 'AHLI STATISTIK MUDA', 'photo_url' => asset('images/admin_photo.jpg')],
            'running_text_items' => ['Teks Running Text Pertama', 'Pemberitahuan: Pelayanan ditutup pukul 16:00 WIB', 'Selamat Datang di Badan Pusat Statistik Kota Metro'],
            'infographic_url' => asset('images/infographic_placeholder.png'),
        ];
        $runningText = collect($data['running_text_items'])->implode('........... ');
        
        // Periksa juga apakah view sudah benar: queue.display
        return view('queue.display', [
            'admin' => $data['admin'],
            'queue_number' => $currentQueueNumber, 
            'running_text' => $runningText,
            'infographic_url' => $data['infographic_url'],
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