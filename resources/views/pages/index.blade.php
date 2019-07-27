@extends('layouts.app')
@section('content')
    @include('inc.navbar')
    <div class="container" style="margin-top: 34px;">
            <div class="row">
                <div class="col-10 col-sm-11 col-md-9 col-lg-9 col-xl-8 text-monospace text-dark mx-auto">
                    <h1 class="text-center clear-top" style="font-size: 61px;">
                        Welcome to<strong>&nbsp;{{ config('app.name', 'MCRS') }}.</strong>
                    </h1>
                    <p class="text-center">
                        Mauris egestas tellus non ex condimentum, 
                        ac ullamcorper sapien dictum. Nam consequat 
                        neque quis sapien viverra convallis. In non tempus 
                        lorem.Mauris egestas tellus non ex condimentum, 
                        ac ullamcorper sapien dictum. Nam consequat neque
                        quis sapien viverra convallis. In non tempus lorem. 
                    </p>
                </div>
            </div>
        </div>
@endsection

