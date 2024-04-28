@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card card">
                <span class="invoice-number-style">N. fattura: <b>{{$invoice->id}}</b></span>
                <span>Data fattura: {{$invoice->invoice_date}}</span>
                <span class="client-style">Cliente: <b>{{$invoice->client->name}}</b></span>
                <span class="seller-style mb-5">Consulente di riferimento:
                    {{$invoice->client->consultant->name}} {{$invoice->client->consultant->lastname}}
                </span>
                <table class="table w-100 mb-5">
                    <thead>
                        <tr>
                            <th class="border border-dark" style="width: 400px; background: gray; color: white">Servizio
                            </th>
                            <th class="border border-dark" style="width: 100px; background: gray; color: white">Quantità
                            </th>
                            <th class="border border-dark" style="width: 150px; background: gray; color: white">Prezzo
                                unitario
                                (€)
                            </th>
                            <th class="border border-dark" style="width: 200px; background: gray; color: white">Erogato
                                da
                            </th>
                        </tr>
                    </thead>

                    <tbody class="services-container">
                        @php
                        $groupedServices = $servicesSold->groupBy(function($item) {
                        return $item->service_id . '_' . $item->price;
                        });
                        @endphp
                        @foreach ($groupedServices as $group)
                        <tr class="service-row">
                            <td class="border border-dark">
                                {{$group[0]->service->name}}
                            </td>
                            <td class="border border-dark">
                                {{$group->count()}}
                            </td>
                            <td class="border border-dark">
                                {{$group[0]->price}}
                            </td>
                            <td class="border border-dark">
                                <select name="delivered_by" id="delivered_by">
                                    @foreach ($consultants as $consultant)
                                    <option value="{{$consultant->id}}" @if($consultant->id == $consultant_id) selected
                                        @endif>
                                        {{$consultant->name}} {{$consultant->lastname}}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                {{-- Prezzo fattura --}}
                @php
                $imponibile = isset($invoice->price) ? number_format(floatval($invoice->price), 2, '.', '') : 0;
                $iva = number_format(floatval($imponibile * 0.22), 2, '.', '');
                $totaleFattura = number_format(floatval($imponibile) + floatval($iva), 2, '.', '');
                @endphp
                <span class="price-style">Imponibile:
                    {{$imponibile}} €
                </span>
                <span>IVA:
                    {{$iva}} €
                </span>
                <span>
                    Totale fattura:
                    {{$totaleFattura}} €
                </span>
                <span class="paid-style mt-3">Fattura pagata interamente: {{$invoice->paid ? 'Sì' : 'No'}}</span>
            </div>

            <div class="ms_card card">
                <h3>RATE E PAGAMENTI</h3>

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
                                <span>Scadenza: {{ date('Y-m-d', strtotime($installment->expire_date)) }}</span>
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
                        <a href="{{route('index.installments', $invoice->id)}}">AGGIORNA</a>
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