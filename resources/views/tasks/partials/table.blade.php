<div class="table-scroll" role="region" aria-label="Tasks table, scroll horizontally on small screens" tabindex="0">
    <table>
        <caption class="sr-only">Task titles, scheduled dates, and status</caption>
        <thead><tr><th scope="col">Task ID</th><th scope="col">Task</th><th scope="col">Date</th><th scope="col">Status</th></tr></thead>
        <tbody>
            @foreach ($tasks as $task)
            <tr>
                <th scope="row" class="record-id">{{ $task->id }}</th>
                <td class="record-name task-title">{{ $task->title }}</td>
                <td>{{ $task->task_date->format('M d, Y') }}</td>
                <td><span class="status status-{{ $task->status }}">{{ ucwords(str_replace('_', ' ', $task->status)) }}</span></td>
            </tr>
            @endforeach
            @if ($tasks->isEmpty())
            <tr><td colspan="4" class="empty-state">{{ $emptyMessage }}</td></tr>
            @endif
        </tbody>
    </table>
</div>
