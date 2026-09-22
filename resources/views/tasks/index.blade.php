@extends('tasks.layout')

@section('content')
    <h1 class="mb-4">📋 Task Manager</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <a href="{{ route('tasks.create') }}" class="btn btn-success">+ Add New Task</a>

        <form action="{{ route('tasks.index') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="width: auto;">
                <option value="">All Statuses</option>
                <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
            </select>

            <select name="category" class="form-select form-select-sm" style="width: auto;">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ (string)request('category') === (string)$category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            
            <input type="hidden" name="sort" value="{{ request('sort') }}">

            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        </form>
    </div>

    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>
                    Due Date 
                    <a href="{{ route('tasks.index', array_merge(request()->all(), ['sort' => request('sort') === 'due_date' ? '' : 'due_date'])) }}" class="text-white ms-1 text-decoration-none">
                        {!! request('sort') === 'due_date' ? '↑' : '↕' !!}
                    </a>
                </th>
                <th>Categories</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                @php
                    $isOverdue = $task->due_date && $task->due_date->startOfDay()->lt(now()->startOfDay()) && $task->status !== 'done';
                @endphp
                <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                    <td class="{{ $task->status === 'done' ? 'text-decoration-line-through text-muted' : '' }}">
                        {{ $task->title }}
                    </td>
                    <td>{{ $task->description }}</td>
                    <td>
                        {{ $task->due_date ? $task->due_date->format('M d, Y') : '-' }}
                    </td>
                    <td>
                        @foreach ($task->categories as $category)
                            <span class="badge bg-secondary">{{ $category->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @if ($task->status === 'done')
                            <span class="badge bg-success">Done</span>
                        @elseif ($task->status === 'in_progress')
                            <span class="badge bg-primary">In Progress</span>
                        @else
                            <span class="badge bg-warning text-dark">To Do</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No tasks found. Add one!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection