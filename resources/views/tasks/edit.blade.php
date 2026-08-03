@extends('tasks.layout')

@section('content')
    <h1 class="mb-4">✏️ Edit Task</h1>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-control">{{ $task->description }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="completed" value="1" class="form-check-input" id="completedCheck" {{ $task->completed ? 'checked' : '' }}>
            <label class="form-check-label" for="completedCheck">Completed</label>
        </div>

        <button type="submit" class="btn btn-primary">Update Task</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection