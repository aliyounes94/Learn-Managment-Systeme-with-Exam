@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    تقيم المعلم: {{ $teacher->name }}
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.evaluations.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">

                        <!-- Rating -->
                        <div class="form-group mb-4">
                            <label>التقييم (من 1 إلى 5)</label>
                            <select name="rating" class="form-control" required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Comment -->
                        <div class="form-group mb-4">
                            <label>تعليق (اختياري)</label>
                            <textarea name="comment" class="form-control" rows="4">{{ old('comment') }}</textarea>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i> حفظ التقييم
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
<script src="https://kit.fontawesome.com/a076d05399.js " crossorigin="anonymous"></script>
@endsection