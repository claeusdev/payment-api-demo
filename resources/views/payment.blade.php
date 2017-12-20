@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h4>Payment for {{ $amount . " " .$currency  }} </h4>
            @if($has_error)
                <div class="alert alert-danger">
                    {!! $error !!}
                </div>
            @else
                
                <div class="alert alert-success">
                    {{ $success }}
                </div>
            @endif

            
        </div>
    </div>
</div>
@endsection