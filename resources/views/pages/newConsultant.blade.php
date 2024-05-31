@extends('layouts.app')
@section('content')

@include('components.sidebar')
<div class="main-content d-flex flex-column align-items-center">

    <h1 style="margin-bottom: 30px">Inserisci nuovo consulente</h1>

    <form action="{{route('store.consultant')}}" method="POST">
        @csrf
        @method('POST')

        <label for="name">Nome: </label>
        <input type="text">
        <br><br>

        <label for="lastname">Cognome: </label>
        <input type="text">
        <br><br>

        <label for="level">Livello: </label>
        <select name="level" id="level">
            @foreach ($levels as $level)
            <option value="{{$level->id}}">{{$level->name}}</option>
            @endforeach
        </select>
        <br><br>
        <input type="submit" value="CREA">
    </form>

</div>

@endsection

<style scoped lang="scss">

</style>