<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Registration;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $period = \App\Services\QuotaService::getActivePeriod();
        $departments = Department::with(['slotQuotas' => function ($q) use ($period) {
            $q->where('period', $period);
        }])->get();

        // Estimasi Periode (Rentang tanggal magang dari pendaftar yang sudah Diterima)
        $earliestDate = Registration::where('status', 'approved')->min('start_date');
        $latestDate = Registration::where('status', 'approved')->max('end_date');

        return view('public.index', compact('departments', 'period', 'earliestDate', 'latestDate'));
    }

    public function info()
    {
        $departments = Department::all();
        return view('public.info', compact('departments'));
    }
}
