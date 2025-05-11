@extends('layouts.master') <!-- Adjust layout as needed -->

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>الاختبار: {{ $quiz->title }}</h4>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 text-center">
                        <p class="lead"><strong>المادة:</strong> {{ $quiz->subject->name }} | <strong>الصف:</strong> {{ $quiz->myClass->name }}</p>
                        <div class="alert alert-info d-inline-block" role="alert">
                            <i class="fas fa-clock"></i> المدة المتبقية: <span id="quiz-timer">{{ $quiz->duration_minutes }}:00</span>
                        </div>
                    </div>

                    <form id="quiz-form" action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
                        @csrf

                        @foreach($quiz->questions as $index => $question)
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-light">
                                    <strong>السؤال {{ $loop->iteration }}:</strong> {{ $question->content }}
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($question->options as $option)
                                            <li class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="answers[{{ $question->id }}]"
                                                           value="{{ $option->id }}"
                                                           id="option-{{ $option->id }}"
                                                           required>
                                                    <label class="form-check-label" for="option-{{ $option->id }}">
                                                        {{ $option->content }}
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane"></i> إرسال الإجابات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const timerElement = document.getElementById('quiz-timer');
        let totalSeconds = {{ $quiz->duration_minutes * 60 }};
        let timerInterval = setInterval(() => {
            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                document.getElementById('quiz-form').submit();
                return;
            }

            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            totalSeconds--;
        }, 1000);
    });
</script>
@endsection

@section('styles')
<style>
    .list-group-item {
        padding: 0.75rem 1.25rem;
    }

    .form-check-label {
        font-size: 1rem;
    }

    .card-header {
        font-size: 1.1rem;
    }

    .alert-info {
        font-size: 1.1rem;
    }
</style>