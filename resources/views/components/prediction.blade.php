<table class="table table-responsive sm:text-left">
    <thead>
    <tr class="table-dark">
        <th scope="col">Championship Prodictions</th>
        <th scope="col">%</th>
    </tr>
    </thead>
    <tbody>
    @foreach($prediction as $team => $item)
        <tr>
            <td>{{$team}}</td>
            <td>{{$item}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
