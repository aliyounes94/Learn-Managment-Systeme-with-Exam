<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Evaluation;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Exam;
use App\Repositories\UserRepo;
class TeacherEvaluationController extends Controller
{
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of teacher evaluations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // Fetch all users where user_type is 'teacher'
    $teachers = User::where('user_type', 'teacher')
    ->with('evaluationsReceived') // Load evaluations relationship
    ->get()
    ->map(function ($teacher) {
        // Get average rating or default to 0
        $avg = $teacher->evaluationsReceived->avg('rating');
        $teacher->average_rating = $avg ? round($avg, 1) : 0;
        return $teacher;
    });

return view('pages.teacher.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new evaluation.
     *
     * @param  int  $teacher_id
     * @return \Illuminate\Http\Response
     */
    public function create($teacher_id)
    {
        $teacher = User::where('id', $teacher_id)->where('user_type', 'teacher')->firstOrFail();
        
        // Get all students for dropdown
        $students = User::where('user_type', 'student')->get();
     
        return view('pages.teacher.create', compact('teacher', 'students'));
    }

    /**
     * Store a newly created evaluation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string'
        ]);

        $student_id = auth()->id();

        Evaluation::updateOrCreate(
            [
                'teacher_id' => $request->teacher_id,
                'student_id' => $student_id
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment
            ]
        );

        return redirect()->route('teacher.index')
                         ->with('success', 'تم تحديث التقييم بنجاح');
    }

    /**
     * Display evaluations for a specific teacher.
     *
     * @param  int  $teacher_id
     * @return \Illuminate\Http\Response
     */
    public function show($teacher_id)
{
    $teacher = User::where('id', $teacher_id)
                   ->with(['evaluationsReceived' => function ($query) {
                       $query->with('student')->orderBy('created_at', 'desc');
                   }])
                   ->firstOrFail();

    return view('pages.teacher.show', compact('teacher'));
}
    /**
     * Show the form for editing an evaluation.
     *
     * @param  int  $evaluation_id
     * @return \Illuminate\Http\Response
     */
    public function edit($evaluation_id)
    {
        $evaluation = Evaluation::with(['teacher', 'student'])
            ->findOrFail($evaluation_id);
            
        return view('support_team.evaluations.edit', compact('evaluation'));
    }

    /**
     * Update the specified evaluation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $evaluation_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $evaluation_id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string'
        ]);

        $evaluation = Evaluation::findOrFail($evaluation_id);
        $evaluation->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('teacher.evaluations.show', $evaluation->teacher_id)
            ->with('success', 'تم تحديث التقييم بنجاح');
    }

    /**
     * Manage evaluations by class, section, subject, and exam.
     *
     * @param  int|null  $exam_id
     * @param  int|null  $class_id
     * @param  int|null  $section_id
     * @param  int|null  $subject_id
     * @return \Illuminate\Http\Response
     */
    public function manage($exam_id = null, $class_id = null, $section_id = null, $subject_id = null)
    {
        $query = User::where('user_type', 'teacher');
        
        if ($class_id) {
            $query->whereHas('evaluationsReceived', function($q) use ($class_id) {
                $q->where('class_id', $class_id);
            });
        }

        if ($subject_id) {
            $query->whereHas('evaluationsReceived', function($q) use ($subject_id) {
                $q->where('subject_id', $subject_id);
            });
        }

        $teachers = $query->with(['evaluationsReceived' => function ($q) use ($class_id, $subject_id, $exam_id) {
            $q->when($class_id, function ($query) use ($class_id) {
                return $query->where('class_id', $class_id);
            })->when($subject_id, function ($query) use ($subject_id) {
                return $query->where('subject_id', $subject_id);
            })->when($exam_id, function ($query) use ($exam_id) {
                return $query->where('exam_id', $exam_id);
            });
        }])->get();

        $classes = MyClass::all();
        $subjects = Subject::all();
        $exams = Exam::all();

        return view('support_team.evaluations.manage', compact(
            'teachers', 'classes', 'subjects', 'exams', 'class_id', 'subject_id', 'exam_id'
        ));
    }

    /**
     * Display tabulation of evaluations.
     *
     * @param  int|null  $exam_id
     * @param  int|null  $class_id
     * @param  int|null  $section_id
     * @return \Illuminate\Http\Response
     */
    public function tabulation($exam_id = null, $class_id = null, $section_id = null)
    {
        $teachers = User::where('user_type', 'teacher')->with(['evaluationsReceived' => function ($q) use ($exam_id, $class_id) {
            $q->when($exam_id, function ($query) use ($exam_id) {
                return $query->where('exam_id', $exam_id);
            })->when($class_id, function ($query) use ($class_id) {
                return $query->where('class_id', $class_id);
            });
        }])->get();

        $classes = MyClass::all();
        $exams = Exam::all();

        return view('support_team.evaluations.tabulation', compact(
            'teachers', 'classes', 'exams', 'class_id', 'exam_id', 'section_id'
        ));
    }

    /**
     * Display evaluations for a specific teacher with filters.
     *
     * @param  int  $teacher_id
     * @return \Illuminate\Http\Response
     */
    public function showTeacherEvaluations($teacher_id)
    {
        $teacher = User::where('id', $teacher_id)
            ->where('user_type', 'teacher')
            ->with(['evaluationsReceived' => function ($query) {
                $query->with('student', 'subject', 'exam')
                    ->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        $average_rating = round($teacher->evaluationsReceived->avg('rating'), 1);
        $total_ratings = $teacher->evaluationsReceived->count();

        return view('support_team.evaluations.teacher', compact(
            'teacher', 'average_rating', 'total_ratings'
        ));
    }

    /**
     * Display evaluations for a specific student.
     *
     * @param  int  $student_id
     * @return \Illuminate\Http\Response
     */
    public function showStudentEvaluations($student_id)
    {
        $student = User::where('id', $student_id)
            ->where('user_type', 'student')
            ->with(['evaluationsGiven' => function ($query) {
                $query->with('teacher', 'subject', 'exam')
                    ->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        return view('support_team.evaluations.student', compact('student'));
    }

    /**
     * Delete a specific evaluation.
     *
     * @param  int  $evaluation_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($evaluation_id)
    {
        $evaluation = Evaluation::findOrFail($evaluation_id);
        $teacher_id = $evaluation->teacher_id;
        
        $evaluation->delete();

        return redirect()->route('teacher.evaluations.show', $teacher_id)
            ->with('success', 'تم حذف التقييم بنجاح');
    }

    /**
     * Bulk select for evaluations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkSelect(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:my_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        $teachers = User::where('user_type', 'teacher')->get();
        
        if ($request->class_id) {
            $teachers = $teachers->filter(function ($teacher) use ($request) {
                return $teacher->evaluationsReceived->where('class_id', $request->class_id)->isNotEmpty();
            });
        }

        if ($request->subject_id) {
            $teachers = $teachers->filter(function ($teacher) use ($request) {
                return $teacher->evaluationsReceived->where('subject_id', $request->subject_id)->isNotEmpty();
            });
        }

        $classes = MyClass::all();
        $subjects = Subject::all();

        return view('support_team.evaluations.bulk', compact('teachers', 'classes', 'subjects'));
    }
}