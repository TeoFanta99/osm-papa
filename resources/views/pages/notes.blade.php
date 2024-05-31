@extends('layouts.app')
@section('content')

@include('components.sidebar')
<div class="main-content d-flex flex-column align-items-center">

    <h1 style="margin: 30px">Note</h1>
    <div class="ms_container">
        <span style="align-self: start; font-weight: bold; padding: 5px">
            Clicca su una nota per <span style="color: orange">modificarla</span> o per <span
                style="color: red">eliminarla</span>
        </span>
        <a class="newNote" href="{{route('create.note')}}">Aggiungi nuova nota</a>

    </div>


    <div class="row" style="width: 90%; margin: 20px 50px;">
        @foreach ($notes as $note)
        <a class="col-12 col-sm-6 col-lg-4 col-xl-3 note" href="{{route('edit.note', $note->id)}}">
            <div>
                <h3>{{ucfirst($note->title)}}</h3>
                <span>{{$note->description}}</span>
                <br><br>
                <span>Data: {{$note->created_at->format('d/m/Y')}}</span>
            </div>
        </a>
        @endforeach
    </div>

</div>

@endsection

<style scoped lang="scss">
    .ms_container {
        display: flex;
        justify-content: space-between;
        width: 90%;

        .newNote {
            background-color: rgb(255, 238, 0);
            border: 1px solid rgb(171, 171, 171);
            color: black;
            text-decoration: none;
            padding: 5px;
            align-self: end;
            /* width: 100%; */

            &:hover {
                background-color: yellow;
            }
        }
    }

    .note {
        border: 1px solid black;
        padding: 20px;
        color: black;
        /* color: yellow */
        text-decoration: none;

        &:hover {
            background-color: rgb(255, 238, 0);
        }
    }
</style>