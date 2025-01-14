<?php

namespace App\Exports;

use App\Models\MemberReport;
use App\Models\NonMemberReport;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AbsenExport implements FromCollection, WithHeadings, WithColumnFormatting, WithColumnWidths
{
    protected $startDate;
    protected $endDate;

    // Constructor untuk menerima parameter tanggal
    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate)->startOfDay();
        $this->endDate = Carbon::parse($endDate)->endOfDay();
    }

    public function collection()
    {
        $nonMemberReports = NonMemberReport::whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        $memberReports = MemberReport::with('category')->whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        $nonMemberReportsData = $nonMemberReports->map(function ($report) {
            return [
                'jam_absen' => Carbon::parse($report->created_at)->format('d-m-Y H:i'),
                'nama' => $report->nama,
                'kategori' => 'Non Member',
                'id_member' => '',
                'payment' => $report->payment,
                'harga' => $report->harga,
            ];
        });

        // Mapping data MemberReport
        $memberReportsData = $memberReports->map(function ($report) {
            $typeMemberCategory = $report->category;

            return [
                'jam_absen' => Carbon::parse($report->created_at)->format('d-m-Y H:i'),
                'nama' => $report->nama,
                'kategori' => $typeMemberCategory ? $typeMemberCategory->name : 'Unknown',
                'id_member' => $report->id_member,
                'payment' => $report->payment,
                'harga' => $typeMemberCategory ? $typeMemberCategory->biaya : 0,
            ];
        });

        // Gabungkan data NonMemberReport dan MemberReport
        $allReports = $nonMemberReportsData->merge($memberReportsData);

        // Return data sebagai collection
        return collect($allReports);
    }

    // Menambahkan headings pada file Excel
    public function headings(): array
    {
        return [
            'Jam Absen',
            'Nama',
            'ID Member',
            'Kategori',
            'Payment',
            'Harga'
        ];
    }

    // Format kolom
    public function columnFormats(): array
    {
        return [
            // Memformat kolom harga dan payment agar menggunakan format angka dengan koma sebagai pemisah
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    // Menambahkan lebar kolom
    public function columnWidths(): array
    {
        return [
            'A' => 30, // Jam Absen
            'B' => 30, // Nama
            'C' => 30, // ID Member
            'D' => 30, // Kategori
            'E' => 20, // Payment
            'F' => 20, // Harga
        ];
    }
}
