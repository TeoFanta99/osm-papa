@extends('layouts.app')
@section('content')


@include('components.sidebar')

<div class="main-content p-3">
    <h1 class="d-block">{{$consultant->name}} {{$consultant->lastname}}</h1>
    <span><i><u>Consulente {{$consultant->level->name}}</u></i></span>
    <br>

    {{-- LOGICA DI CREAZIONE MESE E ANNO --}}
    @php
    $currentMonth = date('F');
    $currentYear = date('Y');
    setlocale(LC_TIME, 'it_IT');
    $currentMonth = strftime('%B');
    $currentYear = strftime('%Y');

    @endphp

    <br>
    <div class="container">
        {{-- <h2 class="statsTitle">
            Statistiche
            dal {{ $startDateFormatted }}
            al {{ $endDateFormatted }}
        </h2>
        <br> --}}

        <div class="section-container">
            <span class="fs-4">Somme incassate nel mese di {{$currentMonth}}: {{ number_format($currentMonthCashed, 2,
                ',', '.') }} €
            </span>
            <br>
            <span class="fs-4">Somme ancora da incassare: {{ number_format($notCashed, 2, ',', '.') }} €
            </span>
            <br>
            @php
            $totaliPrevisti = $currentMonthCashed + $notCashed;
            @endphp
            <span class="fs-4">Totali previsti: {{ number_format($totaliPrevisti, 2, ',', '.') }} €
            </span>
            <br><br>

            <div class="commissionsInfo">
                <div>
                    <span class="fs-4">Provvigioni maturate nel mese di {{$currentMonth}}:
                        {{ number_format($commissionsCashed, 2, ',', '.') }} €</span>
                </div>
                <div>
                    <span class="fs-4">Provvigioni da maturare: {{ number_format($commissionsNotCashed, 2, ',', '.')
                        }} €</span>
                </div>
                @php
                $totalCommissions = $commissionsCashed + $commissionsNotCashed;
                @endphp
                <div>
                    <span class="fs-4">Totali previsti: {{ number_format($totalCommissions, 2, ',', '.')
                        }} €</span>
                </div>
            </div>
            <br>
            <div class="invoicesInfo">

            </div>

            <br><br><br>

            {{-- Form di filtraggio per data --}}
            <h3>Ricerca per periodo: dal {{ $startDateFormatted }} al {{ $endDateFormatted }}</h3>
            <br>
            <form method="GET" action="{{ route('show.consultant', $consultant->id) }}" class="filter-form">
                <div class="form-group">
                    <label style="margin-right: 5px" for="start_date">Dal:</label>
                    <input class="date-input" type="date" id="inputStartDate" name="inputStartDate"
                        value="{{ request('inputStartDate', $inputStart) }}">
                </div>
                <br>
                <div class="form-group">
                    <label style="margin-right: 5px" for="end_date">Al:</label>
                    <input class="date-input" type="date" id="inputEndDate" name="inputEndDate"
                        value="{{ request('inputEndDate', $inputEnd) }}">
                </div>
                <br>
                <button type="submit" class="filterBtn">Filtra</button>
            </form>

            {{-- Risultato del filtraggio --}}
            <div class="filteredResults">
                <span class="fs-5">Somme incassate: {{ number_format($filteredDateCashed, 2,
                    ',', '.') }} € </span>
                <br>
                <span class="fs-5">Somme ancora da incassare: {{ number_format($filteredDateNotCashed, 2,
                    ',', '.') }} €</span>
                <br>
                @php
                $filteredDateTotalCashed = $filteredDateCashed + $filteredDateNotCashed;
                @endphp
                <span class="fs-5">Totali previsti: {{ number_format($filteredDateTotalCashed, 2,
                    ',', '.') }} €</span>
                <br><br>


                <span class="fs-5">Provvigioni maturate: {{ number_format($filteredDateCommissionsCashed, 2,
                    ',', '.') }} €</span>
                <br>
                <span class="fs-5">Provvigioni da maturare: {{ number_format($filteredDateCommissionsNotCashed, 2,
                    ',', '.') }} €</span>
                <br>
                @php
                $filteredDateCommissionsCashed = $filteredDateCommissionsCashed + $filteredDateCommissionsNotCashed;
                @endphp
                <span class="fs-5">Totali previsti: {{ number_format($filteredDateCommissionsCashed, 2,
                    ',', '.') }} €</span>
            </div>
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

    .filterBtn {
        background-color: #4a6da7;
        color: white;
        border: none;
        padding: 10px 20px;

        &:hover {
            background-color: #35517e;
        }
    }

    .section-container {
        width: 100%;
    }

    .form-group {
        width: 40%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 15px;
    }

    .date-input {
        border: none;
        height: 40px;
        width: 100%;
        padding: 5px;
        border-radius: 5px;
        display: inline-block;
    }

    .filter-form {
        width: 100%;
        display: flex;
    }

    .commissionsInfo {
        /* width: 50%; */
    }


    /* MEDIA QUERY */
    @media all and (max-width: 840px) {

        .statsTitle {
            text-align: center;
        }

        .section-container {
            display: block;

            form {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;

                .form-group {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-bottom: 10px;
                }
            }

            .commissionsInfo {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-top: 50px;
            }
        }
    }
</style>