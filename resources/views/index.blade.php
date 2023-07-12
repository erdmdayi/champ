@extends('layout.app')

@section('content')
    <div class="row justify-content-center align-content-center">
        <div class="col-lg-6 col-12">
            <h3 class="sm:text-left mb-5 text-dark">Tournament Teams</h3>
            <table class="table table-responsive sm:text-left">
                <thead>
                    <tr class="table-dark">
                        <th scope="col">Team Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{$team->name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{route('fixture')}}" class="btn btn-info">Generate Fixture</a>
        </div>
    </div>
@endsection
