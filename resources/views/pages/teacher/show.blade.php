@extends('layouts.master') <!-- Adjust layout as needed -->

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>تقييم المعلم: {{ $teacher->name }}</h2>
        <a href="{{ route('teacher.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> العودة إلى القائمة
        </a>
    </div>

    <!-- Teacher Info -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>معلومات المعلم</strong>
        </div>
        <div class="card-body">
            <p><strong>الاسم:</strong> {{ $teacher->name }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $teacher->email }}</p>
            <p><strong>الصف:</strong> {{ optional($teacher->myClass)->name ?? 'غير متوفر' }}</p>
            <p><strong>المادة:</strong> {{ optional($teacher->subject)->name ?? 'غير متوفر' }}</p>
        </div>
    </div>

    <!-- Average Rating -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <strong>متوسط التقييم</strong>
        </div>
        <div class="card-body text-center">
            <div class="mb-3">
                @php
                    $avg = round($teacher->evaluationsReceived->avg('rating'), 1) ?? 0;
                @endphp

                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star{{ $i <= $avg ? '' : '-regular' }} text-warning fa-lg"></i>
                @endfor

                <h4 class="mt-2">{{ $avg }}/5</h4>
            </div>
        </div>
    </div>

    <!-- Individual Evaluations -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong>التقييمات الفردية</strong>
        </div>
        <div class="card-body">
            @if($teacher->evaluationsReceived->isNotEmpty())
                <ul class="list-group">
                    @foreach($teacher->evaluationsReceived as $evaluation)
                        <li class="list-group-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>الطالب:</strong> 
                                    {{ optional($evaluation->student)->name ?? 'غير معروف' }}
                                </div>
                                <div>
                                    <strong>التقييم:</strong>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $evaluation->rating ? '' : '-regular' }} text-warning"></i>
                                    @endfor
                                </div>
                            </div>

                            <p class="mt-2">
                                <strong>التعليق:</strong><br>
                                {{ $evaluation->comment ?? 'لا يوجد تعليق' }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info mb-0">لا يوجد تقييمات لهذا المعلم</div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js " crossorigin="anonymous"></script>
@endsection