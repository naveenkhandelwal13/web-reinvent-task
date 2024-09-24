@foreach ($tasks as $task)
    <tr class="{{ $task->status == '0' ? 'table-danger' : ''}}">
        <td>{{ $task->id }}</td>
        <td>{{ $task->task_name }}</td>
        <td >{{ $task->status == '1' ? 'Completed' : ($task->status == '0' ? 'Trashed' : '')}}</td>
        <td>

        @if ($task->status == 'null')
        <a data-id="{{ $task->id }}" class="bg-success text-white btn update-button" data-action="1" href="javascript:void(0)"><i class="fa fa-check-square-o"></i></a>
        @endif
        @if ($task->status == 'null' || $task->status == 1)
            <a data-id="{{ $task->id }}" class="bg-danger text-white btn trash-button delete-btn" data-action="0" href="javascript:void(0)"><i class="fa fa-remove"></i></a>

        @endif
        </td>
    </tr>
@endforeach