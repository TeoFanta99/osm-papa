@extends('layouts.app')
@section('content')

@include('components.sidebar')
<div class="main-content">

    <h1>CONSULENTI:</h1>

    <div class="ms_container d-flex flex-wrap">
        @foreach ($consultants as $consultant)
        <div class="ms_col col-12 col-md-6">
            {{$consultant -> name}} {{$consultant -> lastname}}
        </div>
        @endforeach
        @foreach ($consultants as $consultant)
        <div class="ms_col col-12 col-md-6">
            {{$consultant -> name}} {{$consultant -> lastname}}
        </div>
        @endforeach
    </div>

</div>

@endsection

<style scoped lang="scss">
    .ms_col {
        border: 1px solid black;
        min-height: 200px
    }
</style>