<?php

namespace App\Http\Controllers;

use AbsenExport as GlobalAbsenExport;
use App\Models\MemberReport;
use App\Models\NonMemberReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\AbsenExport;
use Maatwebsite\Excel\Facades\Excel;

class AbsenController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan jika tanggal tidak ada, maka menggunakan tanggal hari ini
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::today()->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::today()->endOfDay();

        // Ambil data berdasarkan tanggal yang sudah difilter
        $nonMemberReports = NonMemberReport::with('category')->whereBetween('created_at', [$startDate, $endDate])->get();
        $memberReports = MemberReport::with('category')->whereBetween('created_at', [$startDate, $endDate])->get();

        // Mapping data non member
        $nonMemberReportsData = collect($nonMemberReports->map(function ($report) {
            $typeMemberCategory = $report->category;
            return [
                'jam_absen' => Carbon::parse($report->created_at)->format('H:i'),
                'nama' => $report->nama,
                'kategori' => $typeMemberCategory ? $typeMemberCategory->name : 'Unknown',
                'id_member' => '',
                'payment' => $report->payment,
                'harga' => $report->harga,
            ];
        }));

        // Mapping data member
        $memberReportsData = collect($memberReports->map(function ($report) {
            $typeMemberCategory = $report->category;

            return [
                'jam_absen' => Carbon::parse($report->created_at)->format('H:i'),
                'nama' => $report->nama,
                'kategori' => $typeMemberCategory ? $typeMemberCategory->name : 'Unknown',
                'id_member' => $report->id_member,
                'payment' => '',
                'harga' => $typeMemberCategory ? $typeMemberCategory->biaya : 0,
            ];
        }));

        // Gabungkan data Non Member dan Member
        $allReports = $nonMemberReportsData->merge($memberReportsData);

        // Hitung total harga
        $totalHarga = $allReports->sum('harga');

        return view('dashboard.laporan.absen', compact('allReports', 'totalHarga'));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::today()->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::today()->endOfDay();

        return Excel::download(new AbsenExport($startDate, $endDate), 'laporan_absen.xlsx');
    }
}
