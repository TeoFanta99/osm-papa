@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <h2>Tutte le fatture</h2>

    <table>
        <thead>
            <tr>
                <th class="border border-dark">#</th>
                <th class="border border-dark">Data fattura</th>
                <th class="border border-dark" style="max-width: 100px">Cliente</th>
                <th class="border border-dark">Consulente</th>
                <th class="border border-dark">Servizi</th>
                <th class="border border-dark">Imponibile</th>
                <th class="border border-dark">Iva</th>
                <th class="border border-dark">Tot. Fattura</th>
                <th class="border border-dark">Rate</th>
                <th class="border border-dark">Stato</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)

            {{-- CALCOLO IMPONIBILE, IVA E TOTALE FATTURA --}}
            @php
            $imponibile = isset($invoice->price) ? number_format(floatval($invoice->price), 2, '.', '') : 0;
            $iva = number_format(floatval($imponibile * 0.22), 2, '.', '');
            $totaleFattura = number_format(floatval($imponibile) + floatval($iva), 2, '.', '');
            @endphp


            {{-- CONTEGGIO RATE --}}
            @php
            $clientServiceId = $invoice->client_service_id;
            $installmentsCount = $invoice->installments()->count();
            @endphp

            <tr class="clickable-row" data-href="{{route('show.invoice', $invoice -> id)}}">
                <td class="border border-dark">
                    {{$invoice->id}}
                </td>
                <td class="border border-dark">{{ date('d-m-Y',
                    strtotime($invoice->invoice_date))}}</td>

                <td class="border border-dark" style="max-width: 100px">{{$invoice->client->name}}</td>
                <td class="border border-dark">{{$invoice->client->consultant->name}}
                    {{$invoice->client->consultant->lastname}}</td>
                <td class="border border-dark">

                    {{-- LOGICA PER RAGGRUPPARE I SERVICESOLD IN BASE AL SERVIZIO VENDUTO --}}
                    @php
                    $groupedServices = $servicesSold->where('invoice_id', $invoice->id)->groupBy('service_id');
                    $countedServices = $groupedServices->map(function ($services) {
                    return [
                    'name' => $services->first()->service->name,
                    'count' => $services->count()
                    ];
                    });
                    @endphp

                    @foreach ($countedServices as $service)
                    @if ($service['count'] >0)
                    <li>{{ $service['count'] }}x {{ $service['name'] }}</li>
                    @endif
                    @endforeach
                </td>

                <td class="border border-dark">
                    {{$imponibile}} €
                </td>
                <td class="border border-dark">{{$iva}} €</td>
                <td class="border border-dark">{{$totaleFattura}} €</td>
                <td class="border border-dark">{{ $installmentsCount }}</td>
                <td class="border border-dark">{{$invoice->paid ? 'Pagata' : 'Non pagata'}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection

@push('scripts')
<script>
    // ascolta l'evento quando l'HTML è completamente caricato
    document.addEventListener("DOMContentLoaded", function() {

        // seleziona tutti gli elementi nell'HTML con classe "clickable-row" e li salva in una variabile "rows"
        let rows = document.querySelectorAll(".clickable-row");

        // prende questi elementi, ci itera sopra e gli passa un aragomento "row", la singola riga
        rows.forEach(function(row) {

            // aggiungi alla riga l'evento "click"
            row.addEventListener("click", function() {

                // al click recupera il valore di "data-href", che conterrà l'URL della pagina
                let href = row.getAttribute("data-href");

                // reindirizza l'utente alla pagina cliccata
                window.location.href = href;
            });
        });
    });

</script>
@endpush

<style scoped lang="scss">
    h2 {
        padding: 30px;
    }

    table {
        margin: 30px;

        tr {
            cursor: pointer;
        }

        th {
            background-color: gray;
            color: white;
            font-weight: bold;
            padding: 10px;
        }

        td {
            padding: 5px;
        }
    }
</style>