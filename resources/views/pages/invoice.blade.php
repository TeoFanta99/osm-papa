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

                <form action="{{route('update.servicesold')}}" method="POST" class="mb-5">
                    @csrf
                    @method('PUT')

                    {{-- TABELLA EDITABILE --}}
                    <table class="table w-100 mb-3">
                        <thead>
                            <tr>
                                <th class="border border-dark" style="width: 400px; background: gray; color: white">
                                    Servizio
                                </th>
                                <th class="border border-dark" style="width: 150px; background: gray; color: white">
                                    Prezzo
                                    unitario
                                    (€)
                                </th>
                                <th class="border border-dark" style="width: 200px; background: gray; color: white">
                                    Erogato
                                    da
                                </th>
                            </tr>
                        </thead>
                        <tbody class="services-container">
                            @foreach ($servicesSold as $serviceSold)
                            <input type="hidden" name="servicesSold[{{ $serviceSold->id }}][id]"
                                value="{{ $serviceSold->id }}">
                            <tr class="service-row">
                                <td class="border border-dark">
                                    {{$serviceSold->service->name}}
                                </td>
                                <td class="border border-dark">
                                    {{$serviceSold->price}}
                                </td>
                                <td class="border border-dark">
                                    <select name="servicesSold[{{ $serviceSold->id }}][delivered_by]"
                                        class="delivered_by">
                                        <option value="">Nessuno</option>
                                        @foreach ($consultants as $consultant)
                                        <option value="{{$consultant->id}}" @if($serviceSold->delivered_by != null &&
                                            $consultant->id == $serviceSold->delivered_by)
                                            selected @endif>
                                            {{$consultant->name}} {{$consultant->lastname}}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end">
                        <input type="submit" value="SALVA" style="width">
                    </div>
                </form>


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

                <div class="button_container">
                    <button class="ms_button mb-4" id="edit-installments">
                        <a href=" #">MODIFICA RATE</a>
                    </button>
                    <button class="ms_button">
                        <a href="{{route('index.installments', $invoice->id)}}">CREA NUOVA RATA</a>
                    </button>
                </div>

                <div class="installment-card-container d-flex flex-wrap mb-4">
                    <form action="{{route('update.installments', $invoice->id)}}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')

                        <input id="updateInstallmentsBtn" type="submit" value="Salva">

                        <div class="form-container">
                            @foreach ($installments as $installment)
                            <a href="#" class="col-12 col-lg-6 col-xxl-4" style="text-decoration: none; color: black;">
                                <div class=" p-1">

                                    <div class="installment-card p-3">
                                        <span>Rata n. {{ $loop->iteration }}</span>
                                        <br><br>

                                        {{-- PREZZO DELLA RATA --}}
                                        <label for="amount_{{ $installment->id }}">Totale rata: </label>
                                        <span class="info-span">{{$installment->amount}} €</span>
                                        <input class="info-input" type="number"
                                            name="installments[{{ $installment->id }}][amount]"
                                            id="amount_{{ $installment->id }}" value="{{$installment->amount}}">
                                        <br><br>

                                        {{-- SCADENZA DELLA RATA --}}
                                        <label for="expire_date_{{ $installment->id }}">Data di scadenza: </label>
                                        <span class="info-span">{{ date('Y-m-d',
                                            strtotime($installment->expire_date))}}</span>
                                        <input class="info-input" type="date"
                                            name="installments[{{ $installment->id }}][expire_date]"
                                            id="expire_date_{{ $installment->id }}"
                                            value="{{ date('Y-m-d', strtotime($installment->expire_date)) }}">
                                        <br><br>

                                        {{-- STATO PAGAMENTO --}}
                                        <label for="paid_{{ $installment->id }}">È stata pagata? </label>
                                        <span class="info-span {{$installment->paid ? 'text-success' : 'text-danger'}}"
                                            style="font-weight: bold">
                                            {{$installment->paid ? 'Pagata' : 'Non pagata'}}
                                        </span>
                                        <select class="info-input" name="installments[{{ $installment->id }}][paid]"
                                            id="paid_{{ $installment->id }}">
                                            <option value="0" {{ $installment->paid == 0 ? 'selected' : '' }}>No
                                            </option>
                                            <option value="1" {{ $installment->paid == 1 ? 'selected' : '' }}>Sì
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.delivered_by').forEach(function(select) {
            select.addEventListener('change', function() {
                var consultantId = this.value;
                console.log('Consultant ID:', consultantId);
            });
        });

        document.getElementById('edit-installments').addEventListener('click', function() {
            let installmentSpans = document.querySelectorAll('.info-span');
            let installmentInputs = document.querySelectorAll('.info-input');
            let updateInstallmentsBtn = document.getElementById('updateInstallmentsBtn');
            installmentSpans.forEach(function(span) {
                span.style.display = 'none';
            });
            installmentInputs.forEach(function(input) {
                input.style.display = 'inline-block';
            });
            updateInstallmentsBtn.style.display = 'inline-block';
        });
    });
</script>
@endpush

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

                .installment-card {
                    border: 1px solid green;
                    border-radius: 15px;
                    cursor: pointer;
                }
            }
        }
    }

    .info-input {
        display: none;
    }

    form {
        width: 100%;

        .form-container {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
        }

        #updateInstallmentsBtn {
            display: none;
        }
    }

    #edit-installments {
        max-width: 150px;
    }
</style>