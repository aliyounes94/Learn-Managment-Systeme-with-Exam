@extends('layouts.master') <!-- Adjust layout as needed -->

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-success text-white text-center">
                    <h3>نتائج الاختبار</h3>
                </div>
                <div class="card-body p-4">

                    <!-- Quiz Summary -->
                    <div class="mb-4">
                        <h5>الاختبار: <strong>{{ $quiz->title }}</strong></h5>
                        <p class="text-muted">المادة: {{ $quiz->subject->name }} | الصف: {{ $quiz->myClass->name }}</p>
                    </div>

                    <!-- Student Info -->
                    @if(isset($student))
                        <div class="mb-4">
                            <h6>الطالب:</h6>
                            <p><strong>{{ $student->name }}</strong> (رقم الطالب: {{ $student->id }})</p>
                        </div>
                    @endif

                    <!-- Score Summary -->
                    <div class="alert alert-{{ $total_marks > 0 ? 'success' : 'danger' }} mb-4">
                        <h5 class="mb-1">إجمالي العلامات:</h5>
                        <p class="mb-0">
                            {{ $correct_answers }} إجابة صحيحة من أصل {{ count($quiz->questions) }} سؤال
                            <br>
                            <strong>العلامة الكلية: {{ $total_marks }} / {{ $quiz->questions->sum('marks') }}</strong>
                            <br>
                            <small>نسبة النجاح: {{ number_format(($total_marks / $quiz->questions->sum('marks')) * 100, 2) }}%</small>
                        </p>
                    </div>

                    <!-- Question Breakdown -->
                    <h5 class="mb-3">تفاصيل الأسئلة</h5>
                    <div class="accordion mb-4" id="questionAccordion">
                        @foreach($quiz->questions as $index => $question)
                            @php
                                $studentAnswer = $studentAnswers->firstWhere('question_id', $question->id);
                                $selectedOption = $studentAnswer ? $studentAnswer->option : null;
                                $isCorrect = $selectedOption && $selectedOption->is_correct;
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $isCorrect ? 'bg-success text-white' : 'bg-danger text-white' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapse{{ $index }}">
                                        سؤال {{ $index + 1 }}: {{ $question->content }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $index }}">
                                    <div class="accordion-body">
                                        <p><strong>الإجابة المقدمة:</strong> {{ $selectedOption ? $selectedOption->content : 'لم تُجب' }}</p>
                                        <p><strong>الإجابة الصحيحة:</strong>
                                            @foreach($question->options as $option)
                                                @if($option->is_correct)
                                                    {{ $option->content }}
                                                @endif
                                            @endforeach
                                        </p>
                                        <p><strong>العلامة:</strong> {{ $isCorrect ? $question->marks : 0 }} / {{ $question->marks }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Retry Button -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('quizzes.attempt', $quiz->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-redo"></i> إعادة الاختبار
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">
                            العودة إلى القائمة
                        </a>
                    </div>
 <!-- New Save Grade Button -->
 <form action="{{ route('quizzes.save-grade', $quiz->id) }}" method="POST" class="ms-2">
            @csrf
            <input type="hidden" name="student_id" value="{{ auth()->user()->student->id }}">
            <input type="hidden" name="total_marks" value="{{ $total_marks }}">
            <input type="hidden" name="my_class_id" value="{{ $quiz->class_id }}">
            <input type="hidden" name="subject_id" value="{{ $quiz->subject_id }}">
            <input type="hidden" name="exam_name" value="{{ $quiz->title }}">>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Save Grade
            </button>
        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .accordion-button {
        font-weight: bold;
    }
    .accordion-button:not(.collapsed) {
        box-shadow: none !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection