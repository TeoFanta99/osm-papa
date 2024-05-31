@extends('layouts.app')
@section('content')

@include('components.sidebar')
<div class="main-content d-flex flex-column align-items-center">

    <h1 style="margin: 30px">Modifica nota n° {{$note->id}}</h1>

    <form action="{{route('update.note', $note->id)}}" method="POST" class="ms_container">
        @csrf
        @method('PUT')

        <label for="">Titolo</label>
        <input type="text" name="title" value="{{$note->title}}">
        @if ($errors->has('title'))
        <div class="error">{{ $errors->first('title') }}</div>
        @endif
        <br><br>
        <label style="margin-top: 40px" for="">Testo della nota</label>
        <textarea name="description" rows="8" cols="80">{{$note->description}}</textarea>
        @if ($errors->has('description'))
        <div class="error">{{ $errors->first('description') }}</div>
        @endif

        <input class="saveBtn" style="margin-top: 40px" type="submit" value="SALVA">
    </form>
    <div class="btnsContainer">
        <a class="comeBackBtn" href="{{route('index.notes')}}">
            <button class="ms_button">TORNA INDIETRO</button>
        </a>
        <form action="{{ route('delete.note', $note->id) }}" method="POST" class="deleteBtn" id="deleteForm"
            onsubmit="return confirm('Sei sicuro di voler cancellare questa nota?');">
            @csrf
            @method('DELETE')

            <input type="submit" value="ELIMINA QUESTA NOTA">
        </form>
    </div>

</div>

@endsection

<style scoped lang="scss">
    .ms_container {
        display: flex;
        flex-direction: column;
        margin-bottom: 100px;

    }

    .error {
        color: red;
        font-weight: bold;
    }

    .saveBtn {
        padding: 18px;
        background-color: rgb(28, 192, 28);
        border: none;
        color: white;
        width: 50%;
        margin: 0 auto;
    }

    .saveBtn:hover {
        background-color: rgb(97, 255, 97);
    }

    .btnsContainer {
        display: flex;

        .comeBackBtn {
            background-color: #fc8200;
            border: none;
            padding: 10px;
            text-decoration: none;
            text-align: center;
            margin-right: 20px;

            button {
                background: none;
                border: none;
                color: white;
            }
        }

        .comeBackBtn:hover {
            background-color: rgb(255, 197, 90);
        }

        .deleteBtn {
            background-color: red;
            padding: 10px;
            cursor: pointer;
            margin: 0;

            input {
                background: none;
                border: none;
                color: white;
            }
        }

        .deleteBtn:hover {
            background-color: rgb(140, 19, 19);
        }
    }
</style>