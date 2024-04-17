@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <h2>Tutte le fatture</h2>

    <ol>
        @foreach ($invoices as $invoice)
        <li>
            {{$invoice->clientService->client->name}}
        </li>
        @endforeach
    </ol>
</div>

@endsection

<style scoped lang="scss">
    h2 {
        padding: 30px;
    }
</style>