<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberReport;
use App\Models\NonMemberReport;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function member($id)
    {
        $data = Member::with('category')->find($id);
        if (!$data) {
            return abort(404, 'Data tidak ditemukan');
        }
        return view('invoice.member', ['data' => $data]);
    }

    public function non_member($id)
    {
        $data = NonMemberReport::with('category')->find($id);
        if (!$data) {
            return abort(404, 'Data tidak ditemukan');
        }
        return view('invoice.non_member', ['data' => $data]);
    }
}
