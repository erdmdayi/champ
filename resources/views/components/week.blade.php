<table class="table table-responsive sm:text-left">
    <thead>
    <tr class="table-dark">
        @if(count($nextMatch) < 1)
            <th scope="col">The matches are completed</th>
        @else
            <th scope="col">Week <span id="weekCount">1</span></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($nextMatch as $key => $match)
        <tr>
            <td>
                {{$match->homeTeam->name}} : {{$match->awayTeam->name}}
                @if($key == 0)
                    <input type="hidden" id="weekCountInput" value="{{$match->week}}">
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
