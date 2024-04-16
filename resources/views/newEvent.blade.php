@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <h2>Crea un nuovo movimento</h2>
    <form method="POST" enctype="multipart/form-data">

        @csrf
        @method("POST")

        <label for="service">Servizio venduto</label>
        <br>
        <select name="service" id="service">
            @foreach ($services as $service)
            <option value="{{$service->id}}" data-price="{{$service->price}}">{{$service ->name}}</option>
            @endforeach
        </select>
        <input type="text" id="price" name="price" value="{{$services[0]->price}}">
        <br><br>
        <label for="client">Cliente</label>
        <br>
        <select name="client" id="client">
            @foreach ($clients as $client)
            <option value="">{{$client ->name}}</option>
            @endforeach
        </select>
        <br><br>
        <label for="sold_by">Consulente venditore</label>
        <br>
        <select name="sold_by" id="sold_by">
            @foreach ($consultants as $consultant)
            <option value="">{{$consultant ->name}} {{$consultant -> lastname}}</option>
            @endforeach
        </select>
        <br><br>
        <label for="delivered_by">Consulente erogatore</label>
        <br>
        <select name="delivered_by" id="delivered_by">
            @foreach ($consultants as $consultant)
            <option value="">{{$consultant ->name}} {{$consultant -> lastname}}</option>
            @endforeach
        </select>
        <br><br>
        <label for="purchase_date">Data di scadenza</label>
        <input type="datetime" name="purchase_date" id="purchase_date">
        <br><br><br>
        <input class="create-btn" type="submit" value="Crea">
    </form>
</div>

@endsection

{{-- pusha lo script fino all'app.blade, che se lo recupererà con @stack --}}
@push('scripts')
<script>
    document.getElementById('service').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let price = selectedOption.getAttribute('data-price');
        document.getElementById('price').value = price;
    });
</script>
@endpush


<style scoped lang="scss">
    .main-content {
        padding: 30px;
    }

    .create-btn {
        padding: 20px 50px;
    }
</style>