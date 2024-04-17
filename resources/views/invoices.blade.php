@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <h2>Tutte le fatture</h2>

    <table>
        <thead>
            <tr>
                <th class="border border-dark">N. Fattura</th>
                <th class="border border-dark">Cliente</th>
                <th class="border border-dark">Servizio</th>
                <th class="border border-dark">Prezzo</th>
                <th class="border border-dark">Venditore</th>
                <th class="border border-dark">Erogatore</th>
                <th class="border border-dark">Data fattura</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)

            <tr class="clickable-row" data-href="{{route('show.invoice', $invoice -> id)}}">
                <td class="border border-dark">
                    {{$invoice->id}}
                </td>
                <td class="border border-dark">{{$invoice->client->name}}</td>
                <td class="border border-dark">{{$invoice->service->name}}</td>
                <td class="border border-dark">
                    @if ($invoice->price)
                    {{$invoice->price}} €
                    @endif
                </td>
                <td class="border border-dark">
                    @php
                    $soldByConsultant = $consultants->where('id', $invoice->sold_by)->first();
                    @endphp
                    @if ($soldByConsultant)
                    {{$soldByConsultant->name}} {{$soldByConsultant->lastname}}
                    @else
                    Consulente non trovato
                    @endif
                </td>
                <td class="border border-dark">
                    @php
                    $deliveredByConsultant = $consultants->where('id', $invoice->delivered_by)->first();
                    @endphp
                    @if ($deliveredByConsultant)
                    {{$deliveredByConsultant->name}} {{$deliveredByConsultant->lastname}}
                    @else
                    Consulente non trovato
                    @endif
                </td>
                <td class="border border-dark">{{$invoice->invoice_date}}</td>

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