<div class="left-menu">

    <div class="ms_btn dashboard-btn">
        <a href="{{route('welcome')}}">
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
    <div class="ms_btn profile-btn">
        <a href="{{route('index.area')}}">
            <i class="fa-solid fa-globe"></i>
            <span>La mia area</span>
        </a>
    </div>
    <div class="ms_btn consultants-btn">
        <a href="{{ route('index.consultants') }}">
            <i class="fa-solid fa-people-group"></i>
            <span>Consulenti</span>
        </a>
    </div>
    <div class="ms_btn clients-btn">
        <a href="{{ route('index.clients') }}">
            <i class="fa-solid fa-building"></i>
            <span>Clienti</span>
        </a>
    </div>
    <div class="ms_btn purchases-btn">
        <a href="{{route('index.invoices')}}">
            <i class="fa-solid fa-credit-card"></i>
            <span>Pagamenti</span>
        </a>
    </div>
    <div class="ms_btn stats-btn">
        <a href="">
            <i class="fa-solid fa-chart-line"></i>
            <span>Statistiche</span>
        </a>
    </div>
    <div class="ms_btn stats-btn">
        <a href="{{route('index.notes')}}">
            <i class="fa-regular fa-note-sticky"></i>
            <span>Note</span>
        </a>
    </div>
    <div class="ms_btn">
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>{{ __('Logout') }}</span>
        </a>
    </div>


</div>

<style>
    @import url("{{ asset('scss/app.scss') }}");
</style>