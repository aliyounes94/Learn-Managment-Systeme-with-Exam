<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\UserRepo;
use App\Models\ExamRecord;
use App\Models\StudentRecord;
class HomeController extends Controller
{
    protected $user;
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }


    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard()
    {$data = [];
        $d=[];
        if(Qs::userIsTeamSAT()){
            $d['users'] = $this->user->getAll();
            $examRecords = ExamRecord::with([
                'student', // Load student details
                'exam',    // Load exam details
                'myClass'  // Load class details
            ])
            ->orderBy('total', 'desc') // Order by total marks descending
            ->take(5) // Get top 5 students (adjust as needed)
            ->get();
    
            // Prepare data for view
            $d['topStudents'] = $examRecords->map(function ($record) {
                return [
                    'student_name' =>$this->user->find( $record->student_id )->name?? 'N/A',
                    'exam_name' => optional($record->exam)->name ?? 'N/A',
                    'class_name' => optional($record->myClass)->name ?? 'N/A',
                    'total_marks' => $record->ave,
                ];
            });
        }
        return view('pages.support_team.dashboard', $d);
    }
}
