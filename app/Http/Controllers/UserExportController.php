<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use PDF;

class UserExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new UsersExport($request->from_date, $request->to_date),
            'users.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $users = User::when($request->from_date && $request->to_date, function ($q) use ($request) {
            $q->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date   . ' 23:59:59'
            ]);
        })->limit(1000)->get(); // PDF limit (important!)

        $pdf = PDF::loadView('users.pdf', compact('users'));
        return $pdf->download('users.pdf');
    }
}
