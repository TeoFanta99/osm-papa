@extends('layouts.app')
@section('content')

@include('components.sidebar')
<div class="main-content d-flex flex-column align-items-center">

    <h1 style="margin: 30px">Nuova nota</h1>

    <form action="{{route('store.note')}}" method="POST" class="ms_container">
        @csrf
        @method('POST')

        <label for="">Titolo</label>
        <input type="text" name="title">
        @if ($errors->has('title'))
        <div class="error">{{ $errors->first('title') }}</div>
        @endif
        <br><br>
        <label style="margin-top: 40px" for="">Descrizione</label>
        <textarea name="description" rows="8" cols="80"></textarea>
        @if ($errors->has('description'))
        <div class="error">{{ $errors->first('description') }}</div>
        @endif

        <input style="margin-top: 40px" type="submit" value="CREA">
    </form>


</div>

@endsection

<style scoped lang="scss">
    .ms_container {
        display: flex;
        flex-direction: column;

    }

    .error {
        color: red;
        font-weight: bold;
    }
</style>