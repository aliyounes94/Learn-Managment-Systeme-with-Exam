@extends('layouts.master') <!-- Adjust based on your layout -->

@section('content')
<div class="container mt-5">
@if(Qs::userIsTeamSAT())
 <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quizzes List</h2>
        <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Create New Quiz
        </a>
    </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">All Quizzes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Duration (minutes)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizzes as $quiz)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $quiz->title }}</td>
                                <td>{{ $quiz->description ?? 'No description' }}</td>
                                <td>{{ $quiz->myClass->name ?? 'Not available' }}</td>
                                <td>{{ $quiz->subject->name ?? 'Not available' }}</td>
                                <td>{{ $quiz->duration_minutes }}</td>

                                <td class="text-center">
    <div class="d-flex flex-wrap gap-2 justify-content-center">
        <!-- Attempt Button -->
     @if(auth()->user()->user_type=='student')    <a href="{{ route('quizzes.attempt', $quiz->id) }}" 
           class="btn btn-outline-success btn-sm rounded-pill px-3" 
           title="Attempt Quiz" 
           data-bs-toggle="tooltip" 
           data-bs-placement="top">
            <i class="fas fa-play-circle me-1"></i> Attempt
        </a>

        @endif
        @if(Qs::userIsTeamSA())
       <!-- Delete Button -->
       <form action="{{ route('quizzes.destroy', $quiz->id) }}" 
              method="POST" 
              class="d-inline"
              onsubmit="return confirm('Are you sure you want to delete this quiz?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="btn btn-outline-danger btn-sm rounded-pill px-3" 
                    title="Delete Quiz" 
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top">
                <i class="fas fa-trash-alt me-1"></i> Delete
            </button>
        </form>
        @endif
    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No quizzes found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $quizzes->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table thead th {
        vertical-align: middle;
        text-align: center;
    }
    .table td {
        vertical-align: middle;
        text-align: center;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection