@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Task List -->
    <ul class="list-group mb-4">
        @foreach ($tasks as $task)
            <li class="list-group-item d-flex justify-content-between align-items-center {{ $task->completed ? 'completed' : '' }}">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal_form" value="1">
                    <input type="hidden" name="title" value="{{ $task->title }}">
                    <input type="hidden" name="description" value="{{ $task->description }}">
                    <input type="hidden" name="deadline" value="{{ $task->deadline }}">
                    <div class="form-check">
                        <input type="checkbox" name="completed" class="form-check-input" id="task-{{ $task->id }}" {{ $task->completed ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label" for="task-{{ $task->id }}">{{ $task->title }}</label>
                    </div>
                </form>
                <div>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editTaskModal-{{ $task->id }}">Edit</button>
                </div>
            </li>
        @endforeach
    </ul>
</div>

<!-- Edit Task Modals -->
@foreach ($tasks as $task)
    <div class="modal fade" id="editTaskModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel-{{ $task->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel-{{ $task->id }}">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="modal_form" value="1">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $task->title) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description', $task->description) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" value="{{ old('deadline', $task->deadline) }}">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="completed" name="completed" {{ old('completed', $task->completed) ? 'checked' : '' }}>
                            <label class="form-check-label" for="completed">Completed</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
