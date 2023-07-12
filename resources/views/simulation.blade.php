@extends('layout.app')

@section('content')
    <h3 class="text-center mb-5">Simulation</h3>
    <div class="row">
        <div class="col-lg-6 col-12" id="standing">
            @include('components.standing', ['standing' => $standing])
        </div>
        <div class="col-lg-3 col-12" id="nextMatch">
            @include('components.week', ['nextMatch' => $nextMatch])
        </div>
        <div class="col-lg-3 col-12" id="prediction">
            @include('components.prediction', ['prediction' => $prediction])
        </div>
    </div>
    <hr>
    <div class="row mt-4">
        <div class="col-lg-4 text-center">
            <button id="playAll" type="button" class="btn btn-info" @if(count($nextMatch) < 1) disabled @endif>Play All Weeks</button>
        </div>
        <div class="col-lg-4 text-center">
            <button id="playMatch" type="button" class="btn btn-info" @if(count($nextMatch) < 1) disabled @endif>Play Next Week</button>
        </div>
        <div class="col-lg-4 text-center">
            <a href="{{route('fixture')}}" class="btn btn-danger">Reset Data</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#weekCount').html($('#weekCountInput').val());
        });
        $(document).on('click','#playAll',function(e){
            e.preventDefault();
            $(this).attr('disabled','disabled');
            axios.get('/simulate-all-matches')
                .then(function (response) {
                    console.dir(response.data.data);
                    alert(response.data.message);
                    location.reload();
                })
                .catch(function (error) {
                    $('#playAll').removeAttr('disabled');
                    var response = error.response;
                    console.dir(response);
                });
        });

        $(document).on('click','#playMatch',function(e){
            e.preventDefault();
            $(this).attr('disabled','disabled');
            axios.get('/simulate-match')
                .then(function (response) {
                    console.dir(response);
                    alert(response.data.message);
                    if(response.data.data.champion == false){
                        $('#playMatch').removeAttr('disabled');

                    }  else {
                        $('#playAll').attr('disabled','disabled');
                    }
                    $('#nextMatch').html(response.data.data.nextMatch);
                    $('#standing').html(response.data.data.standing);
                    $('#prediction').html(response.data.data.prediction);
                    $('#weekCount').html($('#weekCountInput').val());
                })
                .catch(function (error) {
                    $('#new_comment button').removeAttr('disabled');
                    var response = error.response;
                    console.dir(response)
                });
        });
    </script>
@endpush
