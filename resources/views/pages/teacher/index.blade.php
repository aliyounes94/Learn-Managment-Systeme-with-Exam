@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <h2>قائمة المعلمين</h2>
    <div class="row">
        @forelse($teachers as $teacher)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $teacher->name }}</h5>
                        <p class="card-text">{{ $teacher->email }}</p>

                        <!-- Show Average Rating -->
                        <div class="mb-3">
                            <strong>متوسط التقييم:</strong>
                            <div class="d-flex align-items-center mt-2">
                                @php
                                    $rating = $teacher->average_rating ?? 0;
                                @endphp

                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $rating ? '' : '-regular' }} text-warning"></i>
                                @endfor

                                <span class="ms-2">{{ $rating }}/5</span>
                            </div>
                        </div>

                        <!-- Total Ratings -->
                        <div class="mb-3">
                            <small class="text-muted">
                                عدد التقييمات: {{ $teacher->evaluationsReceived->count() }}
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('teacher.evaluations.show', $teacher->id) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> عرض التقييمات
                            </a>

                            @if(auth()->user()->user_type=='student')    <a href="{{ route('teacher.evaluations.create', $teacher->id) }}" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-star-half-alt me-1"></i> تقيم المعلم
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4 text-muted">
                لا يوجد معلمين في النظام
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js " crossorigin="anonymous"></script>
@endsection