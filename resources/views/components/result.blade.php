<hr class="mb-3">
<div class="col-12">
    <h3 class="text-center mb-3">Results</h3>
</div>
@foreach($fixture as $week => $matches)
    <div class="col-lg-3 col-12 ">
        <table class="table table-responsive sm:text-left">
            <thead>
            <tr class="table-dark">
                <th scope="col">Week {{$week}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($matches as $match)
                <tr>
                    <td>{{ $match['home_team'] }} <b>{{ $match['score'] }}</b> {{ $match['away_team'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endforeach
