@extends('layout.app')

@section('content')
    <h3 class="text-center mb-5">Generated Fixtures</h3>
    <div class="row">
        @foreach($fixture as $week => $matches)
            <div class="col-lg-3 col-12">
                <table class="table table-responsive sm:text-left">
                    <thead>
                        <tr class="table-dark">
                            <th scope="col">Week {{$week}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matches as $match)
                            <tr>
                                <td>{{ $match['home_team'] }} - {{ $match['away_team'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        <div class="col-md-12">
            <a href="{{route('simulation')}}" class="btn btn-info">Start Simulation</a>
        </div>
    </div>
@endsection
