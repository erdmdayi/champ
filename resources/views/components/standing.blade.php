<table class="table table-responsive sm:text-left">
    <thead>
    <tr class="table-dark">
        <th scope="col">Team Name</th>
        <th scope="col">P</th>
        <th scope="col">W</th>
        <th scope="col">D</th>
        <th scope="col">L</th>
        <th scope="col">GD</th>
        <th scope="col">PT</th>
    </tr>
    </thead>
    <tbody>
    @foreach($standing as $item)
        <tr>
            <td>{{$item->team->name}}</td>
            <td>{{$item->played}}</td>
            <td>{{$item->won}}</td>
            <td>{{$item->draw}}</td>
            <td>{{$item->lose}}</td>
            <td>{{$item->goal_drawn}}</td>
            <td>{{$item->points}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
