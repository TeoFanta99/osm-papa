@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="invoice-container">
            <div class="ms_card card">
                <span class="invoice-number-style"><b>Fattura n. {{$invoice->id}}</b></span>
                <span class="client-style">Cliente: {{$invoice->client->name}}</span>
                <span class="service-style">Servizio: {{$invoice->service->name}}</span>
                <span class="price-style">Prezzo:
                    @if ($invoice->price)
                    {{$invoice->price}} €
                    @else
                    <i>nessuno</i>
                    @endif
                </span>
                <span class="seller-style">Consulente Venditore:
                    @php
                    $soldByConsultant = $consultants->where('id', $invoice->sold_by)->first();
                    @endphp
                    @if ($soldByConsultant)
                    {{$soldByConsultant->name}} {{$soldByConsultant->lastname}}
                    @else
                    Consulente non trovato
                    @endif
                </span>
                <span class="deliver-style">Consulente Erogatore:
                    @php
                    $deliveredByConsultant = $consultants->where('id', $invoice->delivered_by)->first();
                    @endphp
                    @if ($deliveredByConsultant)
                    {{$deliveredByConsultant->name}} {{$deliveredByConsultant->lastname}}
                    @else
                    Consulente non trovato
                    @endif
                </span>

                <div class="button_container">
                    <button class="ms_button">
                        <a href="{{route('create.installments', $invoice->id)}}">GESTISCI RATEIZZAZIONE</a>
                    </button>
                </div>

                {{-- logica che mi conta il numero di rate associate a questo client_service_id --}}
                @php
                $clientServiceId = $invoice->client_service_id;
                $installmentsCount = $invoice->installments()->count();
                @endphp

                <span>Numero di rate: {{ $installmentsCount }}</span>

            </div>
        </div>
    </div>
</div>

@endsection


<style scoped lang="scss">
    .container_macro {

        display: flex;
        justify-content: center;

        .invoice-container {
            width: 80%;
            margin-top: 100px;
            margin-bottom: 40px;
            display: flex;
            justify-content: center;

            .ms_card {
                width: 80%;
                min-height: 500px;
                border: 1px solid black;

                .invoice-number-style {
                    font-size: 30px;
                    text-align-last: right;
                    margin-right: 10px;
                }

                .deliver-style {
                    margin-bottom: 30px;
                }

                input {
                    width: 30%;
                }
            }
        }
    }
</style>