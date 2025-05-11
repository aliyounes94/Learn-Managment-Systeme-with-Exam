<?php

namespace App\Http\Controllers\SupportTeam;
use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\MyClass;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\StudentRecord;
use App\Repositories\ExamRepo;
use App\Models\Subject;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // List all quizzes
    protected $exam;
    public function __construct(ExamRepo $exam)
    {
      
        $this->exam = $exam;
    }
    public function index()
    {
        $query = Quiz::with(['questions', 'questions.options']);
        if (auth()->user()->user_type=='student') { // Replace with your actual role check
            $studentRecord = auth()->user()->student; // Assuming StudentRecord model exists
            $query->where('class_id', $studentRecord->my_class_id); // Filter by student's class
        }
    
       
        $quizzes =  $query ->paginate(10);
        return view('pages.support_team.quizzes.index', compact('quizzes'));
    }
// Show a quiz for students to attempt
public function attempt($quiz_id)
{
    $quiz = Quiz::with(['questions', 'questions.options'])->findOrFail($quiz_id);


    return view('pages.support_team.quizzes.attempt', compact('quiz'));
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = MyClass::all(); // Replace with your class model
        $subjects = Subject::all(); // Replace with your subject model
        return view('pages.support_team.quizzes.create', compact('classes', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'class_id' =>  $request->my_class_id,
            'subject_id' => $request->subject_id,
            'duration_minutes' => $request->duration_minutes,
        ]);
        $data['name']= $request->title;
        $data['term']=1;
        $data['year'] = Qs::getSetting('current_session');
        $this->exam->create($data);

        foreach ($request->questions as $questionData) {
            $question = $quiz->questions()->create([
                'content' => $questionData['content'],
                'marks' => $questionData['marks']
            ]);

            foreach ($questionData['options'] as $option) {
                $question->options()->create([
                    'content' => $option['content'],
                    'is_correct' => isset($option['is_correct']) ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('quizzes.index')->with('success', 'Quiz created successfully');
    }
    // Submit student answers

    public function submit(Request $request, $quiz_id)
    {
        $quiz = Quiz::with(['questions.options'])->findOrFail($quiz_id);
        $student_id = auth()->user()->student->id; // Adjust based on your auth logic
    // ✅ Fetch student answers
   
        $total_marks = 0;
        $correct_answers = 0;
    
        foreach ($request->answers as $question_id => $option_id) {
            $question = $quiz->questions->firstWhere('id', $question_id);
            $option = $question->options->firstWhere('id', $option_id);
    
            StudentAnswer::create([
                'student_id' => $student_id,
                'quiz_id' => $quiz_id,
                'question_id' => $question_id,
                'option_id' => $option_id,
            ]);
    
            if ($option && $option->is_correct) {
                $total_marks += $question->marks;
                $correct_answers++;
            }
        } $studentAnswers = StudentAnswer::where('quiz_id', $quiz_id)
        ->where('student_id', $student_id)
        ->with('option')
        ->get();

    
        return view('pages.support_team.quizzes.result', compact('quiz', 
        'total_marks', 
        'correct_answers', 
        'studentAnswers'
    ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Mark $mark)
    {
     //  Mark::where('student_id', $student_id)
     //   ->where('my_class_id',  $quiz_id)->where('my_class_id',  $quiz_id)->where();

      //  $quizzes = Quiz::with(['questions', 'questions.options'])->paginate(10);
     //   return view('pages.support_team.quizzes.index', compact('quizzes'))->with('success', 'Mark updated successfully.');;
    }

    public function saveGrade(Request $request, $quiz_id)
{
    $exam_id=EXAM::where('name', $request->exam_name)->value('id');
   $section_id=ExamRecord::where('student_id', auth()->user()->id)
   ->where('my_class_id',  $request->my_class_id)
   ->where('exam_id', $exam_id)
   ->value('section_id');
    $mark= Mark::where('student_id', auth()->user()->id)
  ->where('exam_id', $exam_id)
  ->where('my_class_id',  $request->my_class_id)
 ->where('subject_id', $request->subject_id)
 ->where('section_id',  $section_id)

 ->update([
     'exm' => $request->total_marks,
    
 ]);

    // Create or update the mark
 /*   Mark::updateOrCreate(
        [
            'student_id' => $student_id,
            'quiz_id' => $quiz_id,
        ],
        [
            'total_marks' => $total_marks,
         
        ]
    );
**/
    return redirect()->route('quizzes.index')
                     ->with('success', 'Saved  new mark successfully'  );
}
      public function destroy(Quiz $quiz)
    {
        // Optional: Authorization check
        // $this->authorize('delete', $quiz);
    
        $quiz->delete();
    
        return redirect()->route('quizzes.index')
                         ->with('success', 'Quiz deleted successfully.');
    }
    
   
}
