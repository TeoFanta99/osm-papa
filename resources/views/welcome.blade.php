@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    @auth
    <div class="button_container">
        <button class="ms_button">
            <a href="{{route('index.newevent')}}">AGGIUNGI NUOVO EVENTO</a>
        </button>
    </div>
    @endauth
</div>

@endsection

<style scoped lang="scss">
    .button_container {
        margin: 30px;

        button {
            background-color: #4a6da7;
            padding: 20px;

            a {
                color: white;
                text-decoration: none;
            }
        }
    }
</style>