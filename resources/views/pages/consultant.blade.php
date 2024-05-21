@extends('layouts.app')
@section('content')


@include('components.sidebar')

<div class="main-content p-3">
    <h1 class="d-block">{{$consultant->name}} {{$consultant->lastname}}</h1>
    <span><i><u>Consulente {{$consultant->level->name}}</u></i></span>
    <br><br><br>

    {{-- LOGICA DI CREAZIONE MESE E ANNO CORRENTE --}}
    @php
    $currentMonth = date('F');
    $currentYear = date('Y');
    setlocale(LC_TIME, 'it_IT');
    $currentMonth = strftime('%B');
    $currentYear = strftime('%Y');
    @endphp

    <h2>Statistiche del mese corrente ({{ $currentMonth }} {{$currentYear}}) </h2>
    <br><br>
    <div>

        {{-- INCASSATO --}}
        <div>
            <span class="fs-4">Provvigioni incassate: {{$commissionsAlreadyPaid}} €</span>
        </div>

        {{-- NON INCASSATO --}}
        <div>
            <span class="fs-4">Provvigioni da incassare: {{$commissionsNotPaid}} €</span>
        </div>
    </div>


    <br><br><br><br><br>
    <a class="comeBackBtn" href="{{route('index.consultants')}}">
        <button class="ms_button">Indietro</button>
    </a>
</div>

@endsection

<style scoped lang="scss">
    .comeBackBtn {
        background-color: #fc8200;
        border: none;
        width: 150px;
        padding: 10px;
        text-decoration: none;
        text-align: center;

        button {
            background: none;
            border: none;
            color: white;
        }
    }

    .comeBackBtn:hover {
        background-color: rgb(255, 197, 90);
    }
</style>