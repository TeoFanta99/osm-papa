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
    $startDateFormatted = $startDate->locale('it')->isoFormat('LL');
    $endDateFormatted = $endDate->locale('it')->isoFormat('LL');
    @endphp

    <br><br><br>
    <div class="container">
        <h2 class="statsTitle">Dal {{$startDateFormatted}} al {{$endDateFormatted}}</h2>
        <br>

        <div class="section-container">

            {{-- Form di filtraggio per data --}}
            {{-- <form method="GET" action="{{ route('show.consultant', $consultant->id) }}" class="filter-form">
                <div class="form-group">
                    <label style="margin-right: 5px" for="start_date">Dal:</label>
                    <input class="date-input" type="date" id="start_date" name="start_date"
                        value="{{ date('Y-m-d', strtotime($startDate)) }}">
                </div>
                <br>
                <div class="form-group">
                    <label style="margin-right: 5px" for="end_date">Al:</label>
                    <input class="date-input" type="date" id="end_date" name="end_date"
                        value="{{ date('Y-m-d', strtotime($endDate)) }}">
                </div>
                <br>
                <button type="submit" class="filterBtn">Filtra</button>
            </form> --}}
            <br><br>
            {{-- questa voce deve rimanere sempre uguale, contare le rate, non le invoices --}}
            <span>Somme incassate dal {{ date('d/m/Y', strtotime($currentMonthFirstDay)) }} (1° giorno del
                mese corrente) ad oggi:
                {{ number_format($totalInstallmentsCurrentMonth, 2, ',', '.')}} €</span>
            <br><br>
            <span>Somme da incassare (dal 1° giorno del DB all'ultimo giorno del mese corrente): </span>
            <br><br>


            {{-- Risultati del filtraggio --}}
            <div class="filteredResults">
                <div class="commissionsInfo">
                    <div>
                        <span class="fs-4">Provvigioni maturate dal {{ date('d/m/Y', strtotime($currentMonthFirstDay))
                            }} (1° giorno del mese corrente) ad oggi: {{$commissionsPaid}} €</span>
                    </div>
                    <div>
                        <span class="fs-4">Provvigioni da maturare (dal 1° giorno del DB all'ultimo giorno del mese
                            corrente): {{$commissionsNotPaid}} €</span>
                    </div>
                </div>
                <br>
                <div class="invoicesInfo">

                </div>
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