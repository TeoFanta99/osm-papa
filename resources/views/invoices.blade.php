@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <h2>Tutte le fatture</h2>


    <div class="ms_container gg" style="width: 98%; margin: 0 auto">
        <div class="headerRow">
            <div class="col_id border border-dark" style="display: flex; align-items: center; justify-content: center">#
            </div>
            <div class="col_date border border-dark"
                style="display: flex; align-items: center; justify-content: center">Data
            </div>
            <div class="col_client border border-dark"
                style="display: flex; align-items: center; justify-content: center">
                Cliente</div>
            <div class="col_consultant border border-dark"
                style="display: flex; align-items: center; justify-content: center">Consul.</div>
            <div class="col_services border border-dark" style="text-align: center">
                <div
                    style="border-bottom: 1px solid black; height: 50%; display: flex; align-items: center; justify-content: center">
                    Servizi</div>
                <div style="display: flex; height: 50%">
                    <div class="col-6"
                        style="border-right: 1px solid black; font-size: 12px; display: flex; align-items: center; justify-content: center">
                        Nome servizio</div>
                    <div class="col-1"
                        style="border-right: 1px solid black; font-size: 12px; display: flex; align-items: center; justify-content: center">
                        Q.tà</div>
                    <div class="col-2"
                        style="border-right: 1px solid black; font-size: 12px; display: flex; align-items: center; justify-content: center">
                        Prezzo</div>
                    <div class="col-3"
                        style="font-size: 12px; display: flex; align-items: center; justify-content: center">Tot
                        servizio
                    </div>
                </div>
            </div>
            <div class="col_net border border-dark"
                style="text-align: center; display: flex; align-items: center; justify-content: center">Netto</div>
            <div class="col_iva border border-dark"
                style="text-align: center; display: flex; align-items: center; justify-content: center">IVA</div>
            <div class="col_totalPrice border border-dark"
                style="text-align: center; display: flex; align-items: center; justify-content: center">Totale</div>
            <div class="col_installments border border-dark" style="text-align: center">
                <div
                    style="border-bottom: 1px solid black; height: 50%; display: flex; align-items: center; justify-content: center">
                    Rate</div>
                <div style="display: flex; height: 50%">
                    <div class="col-7"
                        style="border-right: 1px solid black; font-size: 12px; height: 100%; display: flex; align-items: center; justify-content: center">
                        Totale rata
                    </div>
                    <div class="col-5"
                        style="font-size: 12px; height: 100%; display: flex; align-items: center; justify-content: center">
                        Scadenza</div>
                </div>
            </div>
        </div>

        @foreach ($invoices as $invoice)

        <div>
            <a class="ms_row d-flex" href="{{route('show.invoice', $invoice->id)}}"
                style="text-decoration: none; color: black">
                @php
                // IMPONIBILE, IVA E TOTALE FATTURA
                $imponibile = isset($invoice->price) ? number_format(floatval($invoice->price), 2, '.', '') : 0;
                $iva = number_format(floatval($imponibile * 0.22), 2, '.', '');
                $totaleFattura = number_format(floatval($imponibile) + floatval($iva), 2, '.', '');

                // RAGGRUPPO I SERVICESOLD PER SERVICE_ID
                $groupedServices = $servicesSold->where('invoice_id', $invoice->id)->groupBy('service_id');
                $installments = $invoice->installments;
                @endphp

                {{-- ID --}}
                <div class="col_id border border-dark"
                    style="display: flex; justify-content: center; align-items: center; text-decoration: none">
                    {{$invoice->id}}
                </div>

                {{-- DATA --}}
                <div class="col_date border border-dark"
                    style="display: flex; justify-content: center; align-items: center">{{
                    date('j/n/y',
                    strtotime($invoice->invoice_date))}}
                </div>

                {{-- CLIENTE --}}
                <div class="col_client border border-dark"
                    style="display: flex; justify-content: center; align-items: center; text-align: center">
                    {{$invoice->client->name}}
                </div>

                {{-- CONSULENTE --}}
                <div class="col_consultant border border-dark"
                    style="display: flex; justify-content: center; align-items: center; text-align: center">
                    {{$invoice->client->consultant->name}} {{$invoice->client->consultant->lastname}}
                </div>

                {{-- SERVIZI --}}
                <div class="col_services border border-dark">
                    @foreach ($groupedServices as $serviceId => $services)

                    @php
                    $serviceName = $services[0]->service->name;
                    $servicePrice = $services[0]->price;
                    $serviceQuantity = $services->count();
                    $totalPricePerService = $servicePrice * $serviceQuantity;
                    @endphp

                    <div style="display: flex;">
                        <div class="col-6" style="border: 1px solid lightgray">{{$serviceName}}</div>
                        <div class="col-1" style="border: 1px solid lightgray">{{$serviceQuantity}}</div>
                        <div class="col-2" style="border: 1px solid lightgray">{{$servicePrice}}</div>
                        <div class="col-3" style="border: 1px solid lightgray">{{$totalPricePerService}}</div>
                    </div>
                    @endforeach
                </div>

                {{-- NETTO --}}
                <div class="col_net border border-dark"
                    style="display: flex; justify-content: center; align-items: center">
                    {{$imponibile}}
                </div>

                {{-- IVA --}}
                <div class="col_iva border border-dark"
                    style="display: flex; justify-content: center; align-items: center">
                    {{$iva}}
                </div>

                {{-- TOTALE LORDO --}}
                <div class="col_totalPrice border border-dark"
                    style="display: flex; justify-content: center; align-items: center">
                    {{$totaleFattura}}
                </div>

                {{-- RATE --}}
                <div class="col_installments border border-dark">
                    @foreach ($installments as $installment)
                    <div style="display: flex;">
                        <div class="col-5"
                            style="border: 1px solid lightgray; display: flex; justify-content: center; align-items: center; font-size: 10px">
                            {{$installment->amount}}
                        </div>
                        <div class="col-2"
                            style="border: 1px solid lightgray; @if ($installment->paid) background-color: green; @else background-color:red; @endif">
                        </div>
                        <div class="col-5"
                            style="border: 1px solid lightgray; font-size: 10px; display: flex; justify-content: center; align-items: center">
                            {{
                            date('j/n/y',
                            strtotime($installment->expire_date))}}</div>
                    </div>
                    @endforeach
                </div>
            </a>

        </div>

        @endforeach
    </div>



</div>
@endsection

@push('scripts')
{{-- <script>
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
</script> --}}
@endpush

<style scoped lang="scss">
    h2 {
        padding: 30px;
    }

    .headerRow {
        display: flex;
        justify-content: center;
        background-color: gray;
        color: white;
        font-weight: bold;
        min-height: 70px;
    }

    .col_id {
        width: 2%;
        text-decoration: none;
    }

    .col_date {
        width: 5%;
    }

    .col_client {
        width: 9%;
    }

    .col_consultant {
        width: 8%;
    }

    .col_services {
        width: 40%;
        display: flex;
        flex-direction: column;
    }

    .col_net {
        width: 7%;
    }

    .col_iva {
        width: 6%;
    }

    .col_totalPrice {
        width: 7%;
    }

    .col_installments {
        width: 16%;
    }

    .ms_row:hover {
        background-color: #d38eb2;
    }

    /* MEDIA QUERY */
    @media all and (max-width: 1350px) {

        .headerRow {
            .col_id {
                font-size: 12px;
            }

            .col_date {
                font-size: 12px;
            }

            .col_client {
                font-size: 12px;
            }

            .col_consultant {
                font-size: 12px;
            }

            .col_services {
                font-size: 12px;
            }

            .col_net {
                font-size: 12px;
            }

            .col_iva {
                font-size: 12px;
            }

            .col_totalPrice {
                font-size: 12px;
            }

            .col_installments {
                font-size: 12px;
            }
        }



        .ms_row {

            .col_id {
                font-size: 10px;
            }

            .col_date {
                font-size: 10px;
            }

            .col_client {
                font-size: 10px;
            }

            .col_consultant {
                font-size: 10px;
            }

            .col_services {
                font-size: 10px;
            }

            .col_net {
                font-size: 10px;
            }

            .col_iva {
                font-size: 10px;
            }

            .col_totalPrice {
                font-size: 10px;
            }

            .col_installments {
                font-size: 10px;
            }
        }
    }
</style>