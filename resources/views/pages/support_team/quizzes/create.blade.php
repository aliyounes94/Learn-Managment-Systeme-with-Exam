@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Create New Quiz</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('quizzes.store') }}" method="POST">
                        @csrf

                        <!-- Title -->
                        <div class="form-group mb-3">
                            <label for="title">Quiz Title</label>
                            <input type="text" name="title" id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-3">
                            <label for="description">Quiz Description (Optional)</label>
                            <textarea name="description" id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Class Selection -->
                        <div class="form-group mb-3">
                            <label for="my_class_id">Select Class</label>
                            <select name="my_class_id" id="my_class_id"
                                    class="form-control @error('my_class_id') is-invalid @enderror" required>
                                <option value="">Choose a Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('my_class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('my_class_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div class="form-group mb-3">
                            <label for="subject_id">Select Subject</label>
                            <select name="subject_id" id="subject_id"
                                    class="form-control @error('subject_id') is-invalid @enderror" required>
                                <option value="">Choose a Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div class="form-group mb-3">
                            <label for="duration_minutes">Duration (in minutes)</label>
                            <input type="number" name="duration_minutes" id="duration_minutes"
                                   class="form-control @error('duration_minutes') is-invalid @enderror"
                                   value="{{ old('duration_minutes') }}" min="1" required>
                            @error('duration_minutes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Questions Section -->
                        <div id="questions-container" class="mb-4">
                            <h5>Questions</h5>
                            <div class="question-card border p-3 mb-3 rounded shadow-sm bg-light">
                                <div class="form-group mb-2">
                                    <label>Question 1</label>
                                    <input type="text" name="questions[0][content]" class="form-control"
                                           placeholder="Question Content" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Marks</label>
                                    <input type="number" name="questions[0][marks]" class="form-control"
                                           placeholder="Marks" min="1" required>
                                </div>
                                <div class="options-container">
                                    <label>Options:</label>
                                    <div class="option-row d-flex align-items-center mb-2">
                                        <input type="text" name="questions[0][options][0][content]"
                                               class="form-control me-2" placeholder="Option 1">
                                        <div class="form-check">
                                            <input type="checkbox" name="questions[0][options][0][is_correct]"
                                                   class="form-check-input">
                                            <label class="form-check-label">Correct</label>
                                        </div>
                                    </div>
                                    <div class="option-row d-flex align-items-center mb-2">
                                        <input type="text" name="questions[0][options][1][content]"
                                               class="form-control me-2" placeholder="Option 2">
                                        <div class="form-check">
                                            <input type="checkbox" name="questions[0][options][1][is_correct]"
                                                   class="form-check-input">
                                            <label class="form-check-label">Correct</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-success add-option">Add Option</button>
                            </div>
                        </div>

                        <!-- Add Question Button -->
                        <button type="button" id="add-question" class="btn btn-primary mb-3">Add Question</button>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Save Quiz</button>
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
        const container = document.getElementById('questions-container');
        let questionIndex = 1;

        document.getElementById('add-question').addEventListener('click', () => {
            const questionCard = document.createElement('div');
            questionCard.className = 'question-card border p-3 mb-3 rounded shadow-sm bg-light';

            questionCard.innerHTML = `
                <div class="form-group mb-2">
                    <label>Question ${questionIndex + 1}</label>
                    <input type="text" name="questions[${questionIndex}][content]" class="form-control" placeholder="Question Content" required>
                </div>
                <div class="form-group mb-2">
                    <label>Marks</label>
                    <input type="number" name="questions[${questionIndex}][marks]" class="form-control" placeholder="Marks" min="1" required>
                </div>
                <div class="options-container">
                    <label>Options:</label>
                    <div class="option-row d-flex align-items-center mb-2">
                        <input type="text" name="questions[${questionIndex}][options][0][content]" class="form-control me-2" placeholder="Option 1">
                        <div class="form-check">
                            <input type="checkbox" name="questions[${questionIndex}][options][0][is_correct]" class="form-check-input">
                            <label class="form-check-label">Correct</label>
                        </div>
                    </div>
                    <div class="option-row d-flex align-items-center mb-2">
                        <input type="text" name="questions[${questionIndex}][options][1][content]" class="form-control me-2" placeholder="Option 2">
                        <div class="form-check">
                            <input type="checkbox" name="questions[${questionIndex}][options][1][is_correct]" class="form-check-input">
                            <label class="form-check-label">Correct</label>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-success add-option">Add Option</button>
                <button type="button" class="btn btn-sm btn-danger remove-question ms-2">Remove Question</button>
            `;

            // Add option button
            questionCard.querySelector('.add-option').addEventListener('click', function () {
                const optionsContainer = this.closest('.question-card').querySelector('.options-container');
                const optionCount = optionsContainer.querySelectorAll('.option-row').length;
                const newOption = document.createElement('div');
                newOption.className = 'option-row d-flex align-items-center mb-2';
                newOption.innerHTML = `
                    <input type="text" name="questions[${questionIndex}][options][${optionCount}][content]" class="form-control me-2" placeholder="Option ${optionCount + 1}">
                    <div class="form-check">
                        <input type="checkbox" name="questions[${questionIndex}][options][${optionCount}][is_correct]" class="form-check-input">
                        <label class="form-check-label">Correct</label>
                    </div>
                `;
                optionsContainer.appendChild(newOption);
            });

            // Remove question button
            questionCard.querySelector('.remove-question').addEventListener('click', function () {
                container.removeChild(questionCard);
            });

            container.appendChild(questionCard);
            questionIndex++;
        });
    });
</script>
@endsection