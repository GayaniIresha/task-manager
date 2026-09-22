<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->tasks()->with('categories');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        if ($request->get('sort') === 'due_date') {
            $query->orderByRaw('due_date IS NULL, due_date ASC');
        } else {
            $query->latest();
        }

        $tasks = $query->get();
        $categories = auth()->user()->categories()->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $categories = auth()->user()->categories()->get();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
            'categories' => 'nullable|array',
            'new_category' => 'nullable|string|max:255',
        ]);

        $task = auth()->user()->tasks()->create($request->only('title', 'description', 'status', 'due_date'));

        $categoryIds = $request->input('categories', []);

        if ($request->filled('new_category')) {
            $category = auth()->user()->categories()->firstOrCreate(['name' => $request->new_category]);
            $categoryIds[] = $category->id;
        }

        $task->categories()->sync($categoryIds);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        $categories = auth()->user()->categories()->get();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
            'categories' => 'nullable|array',
            'new_category' => 'nullable|string|max:255',
        ]);

        $task->update($request->only('title', 'description', 'status', 'due_date'));

        $categoryIds = $request->input('categories', []);

        if ($request->filled('new_category')) {
            $category = auth()->user()->categories()->firstOrCreate(['name' => $request->new_category]);
            $categoryIds[] = $category->id;
        }

        $task->categories()->sync($categoryIds);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}