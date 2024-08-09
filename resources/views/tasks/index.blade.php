<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">To-Do List</h1>

        <form action="{{ route('tasks.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="input-group">
                <input type="text" name="title" class="form-control" placeholder="Add new task" required>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Add</button>
                </div>
            </div> 
        </form>

        <ul class="list-group mb-4">
            @foreach ($tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center {{ $task->completed ? 'completed' : '' }}">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <div class="form-check">
                            <input type="checkbox" name="completed" class="form-check-input" id="task-{{ $task->id }}" {{ $task->completed ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="task-{{ $task->id }}">{{ $task->title }}</label>
                        </div>
                    </form>
                    <div>
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editTaskModal">Edit</button>

                        <!-- Delete Button -->
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;" onsubmit="return confirm (  'Do you want to delete task?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" > Delete</button>
                        </form>
                    </div>

                    <!-- Edit Task Modal -->
                    <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel-{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editTaskModalLabel-{{ $task->id }}">Edit Task</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="title-{{ $task->id }}">Task Title</label>
                                            <input type="text" name="title" class="form-control" id="title-{{ $task->id }}" value="{{ $task->title }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <hr>

        <!-- Completed Tasks -->
        <ul class="list-group">
            @foreach ($tasks->where('completed', true) as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center completed">
                    <span>{{ $task->title }}</span>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;" onsubmit="return confirm ('do you want to delete complete task?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>

    <style>
        .completed {
            text-decoration: line-through;
        }
    </style>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>

