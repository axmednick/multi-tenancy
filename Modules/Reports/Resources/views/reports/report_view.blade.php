<h1>Report for {{ $user->name }}</h1>
<p>Period: {{ $start->format('d-m-Y') }} to {{ $end->format('d-m-Y') }}</p>

<table border="1" cellspacing="0" cellpadding="5">
    <thead>
    <tr>
        <th>Task ID</th>
        <th>Task Title</th>
        <th>Completed At</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($tasks as $task)
    <tr>
        <td>{{ $task->task_id }}</td>
        <td>{{ $task->task->title ?? '-' }}</td>
        <td>{{ $task->created_at->format('d-m-Y H:i') }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="3">No completed tasks</td>
    </tr>
    @endforelse
    </tbody>
</table>
