<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Registration;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $programType = $request->query('program_type');
        $departmentId = $request->query('department_id');
        $status = $request->query('status');
        $applicantStatus = $request->query('applicant_status');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = Registration::with(['user', 'department', 'preferredDepartment', 'leader', 'institution']);

        if ($programType) {
            $query->where('program_type', $programType);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($status) {
            $query->where('status', $status);
        }
        
        if ($applicantStatus) {
            $query->where('applicant_status', $applicantStatus);
        }

        if ($dateFrom) {
            $query->whereDate('start_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('end_date', '<=', $dateTo);
        }

        $registrations = $query->latest()->get();
        $departments = Department::all();

        // Rekapitulasi Statistik
        $stats = [
            'total' => Registration::count(),
            'mahasiswa' => Registration::where('applicant_status', 'Mahasiswa')->count(),
            'siswa' => Registration::where('applicant_status', 'Siswa')->count(),
            'pkl' => Registration::where('program_type', 'PKL')->count(),
            'kp' => Registration::where('program_type', 'KP')->count(),
            'magang' => Registration::where('program_type', 'Magang')->count(),
            'pending' => Registration::where('status', 'pending')->count(),
            'approved' => Registration::where('status', 'approved')->count(),
            'completed' => Registration::where('status', 'completed')->count(),
        ];

        return view('admin.reports.index', compact(
            'registrations', 
            'departments', 
            'stats', 
            'programType', 
            'departmentId', 
            'status', 
            'applicantStatus',
            'dateFrom',
            'dateTo'
        ));
    }
}
