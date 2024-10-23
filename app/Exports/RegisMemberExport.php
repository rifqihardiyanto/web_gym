<?php

namespace App\Exports;

use App\Models\DaftarMember;
use App\Models\Member;
use App\Models\MemberReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Carbon\Carbon;

class RegisMemberExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Ambil data berdasarkan rentang tanggal
     */
    public function collection()
    {
        // Validasi format tanggal
        $startDate = Carbon::parse($this->startDate)->format('Y-m-d');
        $endDate = Carbon::parse($this->endDate)->format('Y-m-d');

        return MemberReport::with('category')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();
    }

    /**
     * Definisikan header kolom
     */
    public function headings(): array
    {
        return [
            'Tanggal Daftar',
            'Nama',
            'ID Member',
            'Tipe Member',
            'Harga',
        ];
    }

    /**
     * Mapping data sesuai dengan kolom yang akan diekspor
     */
    public function map($member): array
    {
        return [
            $member->created_at->format('Y-m-d'),
            $member->name,
            $member->id_member,
            $member->category->name,
            $member->category->biaya,
        ];
    }

    /**
     * Terapkan gaya pada worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Terapkan gaya untuk header di baris pertama
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
            'C' => 30,
            'D' => 30,
            'E' => 30,
        ];
    }
}
