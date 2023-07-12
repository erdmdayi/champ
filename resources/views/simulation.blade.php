@extends('layout.app')

@section('content')
    <h3 class="text-center mb-5">Simulation</h3>
    <div class="row">
        <div class="col-lg-6 col-12">
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
        </div>
        <div class="col-lg-3 col-12">
            <table class="table table-responsive sm:text-left">
                <thead>
                <tr class="table-dark">
                    <th scope="col">Week 1</th>
                </tr>
                </thead>
                <tbody>
                @foreach($nextMatch as $key => $match)
                    <tr>
                        <td> {{ $match->homeTeam->name}} : {{ $match->awayTeam->name}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-3 col-12">
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
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-4 text-center">
            <button id="playAll" type="button" class="btn btn-info">Play All Weeks</button>
        </div>
        <div class="col-lg-4 text-center">
            <button type="button" class="btn btn-info">Play Next Week</button>
        </div>
        <div class="col-lg-4 text-center">
            <a href="{{route('fixture')}}" class="btn btn-danger">Reset Data</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        $(document).on('click','#playAll',function(e){
            e.preventDefault();
            $(this).attr('disabled','disabled');
            axios.get('/simulate-all-matches')
                .then(function (response) {
                    console.dir(response.data.fixtures);
                    alert(response.data.message);
                    location.reload();
                })
                .catch(function (error) {
                    $('#new_comment button').removeAttr('disabled');
                    var response = error.response;
                    if (response.status === 422) {
                        $.each(response.data.errors, function (k, v) {
                            console.log(v);
                        });
                    }
                });
        });
    </script>
@endpush
