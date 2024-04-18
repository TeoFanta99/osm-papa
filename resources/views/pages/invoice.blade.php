@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
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
                <span class="paid-style">Fattura pagata interamente: {{$invoice->paid ? 'Sì' : 'No'}}</span>
            </div>

            <div class="ms_card card">
                <h3>RATE</h3>

                {{-- logica che mi conta il numero di rate associate a questo client_service_id --}}
                @php
                $clientServiceId = $invoice->client_service_id;
                $installmentsCount = $invoice->installments()->count();
                @endphp

                <span class="mb-3">Numero di rate: {{ $installmentsCount }}</span>

                <div class="installment-card-container d-flex flex-wrap mb-4">
                    @foreach ($installments as $installment)
                    <a href="#" class="col-12 col-lg-6 col-xxl-4" style="text-decoration: none; color: black;">
                        <div class=" p-1">
                            <div class="installment-card p-3">
                                <span>Rata n. {{ $loop->iteration }}</span>
                                <br><br>
                                <span>Totale rata: {{$installment->amount}} €</span>
                                <br><br>
                                <span>Scadenza: {{$installment->expire_date}}</span>
                                <br><br>
                                <span>Stato pagamento: </span>
                                <span class="{{$installment->paid ? 'text-success' : 'text-danger'}}"
                                    style="font-weight: bold">
                                    {{$installment->paid ? 'Pagata' : 'Non pagata'}}
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="button_container">
                    <button class="ms_button">
                        <a href="{{route('index.installments', $invoice->id)}}">GESTISCI RATEIZZAZIONE</a>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection


<style scoped lang="scss">
    .container_macro {

        display: flex;
        justify-content: center;

        .section-container {
            width: 90%;
            margin-top: 100px;
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;


            .ms_card {
                width: 90%;
                min-height: 200px;
                border: 1px solid black;
                padding: 30px;

                .invoice-number-style {
                    font-size: 30px;
                    text-align-last: right;
                    margin-right: 10px;
                }

                .paid-style {
                    margin-bottom: 30px;
                }

                input {
                    width: 30%;
                }

                .installment-card {
                    border: 1px solid green;
                    border-radius: 15px;
                    cursor: pointer;
                }
            }
        }


    }
</style>