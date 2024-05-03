@extends('layouts.app')
@section('content')

@include('components.sidebar')
<div class="main-content d-flex flex-column align-items-center">

    <h1>I tuoi consulenti</h1>
    <div class="ms_container d-flex flex-wrap">
        @foreach ($consultants as $consultant)
        <a class="consultantNameCard col-12 col-md-6 col-lg-4" href="{{route('show.consultant', $consultant->id)}}">
            {{$consultant->name}} {{$consultant ->lastname}}
        </a>
        @endforeach
    </div>

</div>

@endsection

<style scoped lang="scss">
    h1 {
        padding: 30px;
    }

    .ms_container {
        width: 90%;

        .consultantNameCard {
            text-decoration: none;
            color: black;
            border: 1px solid black;
            min-height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;

            &:hover {
                background-color: rgb(181, 181, 181);
            }
        }
    }
</style>