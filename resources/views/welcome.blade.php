@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    @auth
    <div class="button_container">
        <a href="{{route('create.newInvoice')}}">
            <button>NUOVA FATTURA</button>
        </a>
    </div>
    @endauth
</div>

@endsection

<style scoped lang="scss">
    .button_container {
        margin: 30px;

        a {
            background-color: #4a6da7;
            padding: 20px;
            text-decoration: none;
            cursor: pointer;

            &:hover {
                background-color: #35517e;
            }

            button {
                background: none;
                color: white;
                border: none;
                cursor: pointer;
            }
        }
    }
</style>