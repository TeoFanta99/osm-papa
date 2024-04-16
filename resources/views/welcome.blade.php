@extends('layouts.app')
@section('content')

<div class="main_container d-flex">
    <div class="left-menu">

        <div class="ms_btn dashboard-btn">
            <a href="">
                <i class="fa-solid fa-cube"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="ms_btn profile-btn">
            <a href="">
                <i class="fa-solid fa-user"></i>
                <span>Profilo</span>
            </a>
        </div>
        <div class="ms_btn consultants-btn">
            <a href="">
                <i class="fa-solid fa-people-group"></i>
                <span>Consulenti</span>
            </a>
        </div>
        <div class="ms_btn clients-btn">
            <a href="">
                <i class="fa-solid fa-building"></i>
                <span>Clienti</span>
            </a>
        </div>
        <div class="ms_btn stats-btn">
            <a href="">
                <i class="fa-solid fa-chart-line"></i>
                <span>Statistiche</span>
            </a>
        </div>


    </div>
    <main>
        main page
    </main>
</div>


@endsection