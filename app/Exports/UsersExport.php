<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromQuery, WithHeadings, WithChunkReading, ShouldAutoSize
{
    protected $from;
    protected $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function query()
    {
        return User::query()
            ->when($this->from && $this->to, function ($q) {
                $q->whereBetween('created_at', [
                    $this->from . ' 00:00:00',
                    $this->to   . ' 23:59:59'
                ]);
            })
            ->select('id', 'name', 'email', 'created_at');
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Created At'];
    }

    public function chunkSize(): int
    {
        return 1000; // 🔥 MEMORY SAFE
    }
}
