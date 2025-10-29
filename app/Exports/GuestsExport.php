<?php

namespace App\Exports;

use App\Models\Guest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuestsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Replikasi logika filter dari AdminController::guestsIndex
        $query = Guest::query()->orderBy('created_at', 'desc');

        if ($this->request->filled('purpose') && $this->request->purpose !== 'Semua Keperluan') {
            $query->where('purpose', $this->request->purpose);
        }

        if ($this->request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $this->request->start_date);
        }

        if ($this->request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $this->request->end_date);
        }
        
        if ($this->request->filled('search')) {
            $search = '%' . $this->request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('institution', 'like', $search)
                  ->orWhere('phone', 'like', $search)
                  ->orWhere('objective', 'like', $search);
            });
        }

        return $query;
    }

    /**
     * Tentukan header kolom
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Asal Instansi',
            'No. Telepon',
            'Keperluan',
            'Tujuan',
            'Tanggal Kunjungan',
        ];
    }

    /**
     * Map data dari database ke baris Excel
     * @param mixed $guest
     * @return array
     */
    public function map($guest): array
    {
        return [
            $guest->id,
            $guest->name,
            $guest->institution,
            $guest->phone,
            $guest->purpose,
            $guest->objective,
            // Perhatikan: Menghilangkan jam saat format di sini (seperti permintaan 2)
            \Carbon\Carbon::parse($guest->created_at)->format('d/m/Y'), 
        ];
    }
}