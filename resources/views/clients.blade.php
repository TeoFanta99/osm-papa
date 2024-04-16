@extends('layouts.app')
@section('content')

@include('components.sidebar')
<div class="main-content">

    <h1>I tuoi clienti</h1>

    <div class="ms_container d-flex flex-wrap">
        @foreach ($clients as $client)
        <div class="ms_col col-12 col-md-6 col-lg-4">
            <a href="#">{{$client -> name}}</a>
        </div>
        @endforeach

    </div>

</div>

@endsection

<style scoped lang="scss">
    .main-content {
        align-items: center;

        h1 {
            padding: 30px;
        }

        .ms_container {
            width: 90%;

            .ms_col {
                border: 1px solid black;
                min-height: 200px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
        }
    }
</style>