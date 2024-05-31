@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">

            @php
            $nettoFattura = (float) $invoice->price;
            $ivaFattura = $nettoFattura * 22 / 100;
            $lordoFattura = $nettoFattura + $ivaFattura;
            @endphp

            <div class="ms_card">

                <div class="titleContainer">
                    <form action="{{ route('update.invoice', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <span class="title">FATTURA N° </span>
                        <span class="title" id="invoiceNumberDisplay">{{$invoice->invoice_number}}</span>
                        <input type="text" name="invoiceNumberInput" id="invoiceNumberInput"
                            value="{{ $invoice->invoice_number }}">
                        <br><br>
                        @if ($errors->any())
                        <div class="errorInvoiceNumber">
                            @foreach ($errors->all() as $error)
                            <span class="fs-5"> {{ $error }}</span>
                            @endforeach
                            </ul>
                        </div><br>
                        @endif
                        <a href="#" class="editInvoiceNumber" id="editInvoiceNumber">Modifica n° Fattura</a>
                        <input hidden type="number" name="maxInvoiceNumber" id="maxInvoiceNumber"
                            value="{{ $invoiceMaxNumber }}">
                        @if ($invoice->invoice_number == null)
                        <input type="checkbox" name="autoFillInvoiceNumber" id="autoFillInvoiceNumber"
                            class="autoFillCheckbox">
                        <label class="autoFillCheckbox">Compila automaticamente</label> <br><br>
                        @endif

                        <input class="saveBtn2" id="saveBtn2" type="submit" value="Salva">
                    </form>
                </div>

                <br><br><br>

                {{-- Raggruppa i servizi venduti per service_id --}}
                @php
                $groupedServices = $servicesSold->groupBy("service_id");
                @endphp

                {{-- TABELLA PER COMPUTER --}}
                <table class="desktopTable table mb-3">
                    <thead>
                        <tr>
                            <th class="border border-dark" style="background: gray; color: white">
                                Servizio
                            </th>
                            <th class="border border-dark" style="background: gray; color: white">
                                Quantità
                            </th>
                            <th class="border border-dark text-center" style="background: gray; color: white">
                                Totale netto
                            </th>
                        </tr>
                    </thead>
                    <tbody class="services-container">
                        @foreach ($groupedServices as $serviceId => $arrayOfServices)
                        <tr class="service-row">
                            <td class="border border-dark">
                                {{$arrayOfServices->first()->service->name}}
                            </td>
                            <td class="border border-dark">
                                {{$arrayOfServices->count()}}
                            </td>
                            <td class="border border-dark">
                                {{ number_format($arrayOfServices->first()->price * $arrayOfServices->count(), 2,
                                ',',
                                '.')
                                }} €
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- TABELLA PER SMARTPHONE --}}
                <div class="phoneTable table mb-3">

                    <div class="border border-dark"
                        style="background: gray; color: white; padding: 10px; font-weight: bold;">
                        <span>
                            Servizi in fattura
                        </span>
                    </div>

                    <div class="services-container">
                        @foreach ($groupedServices as $serviceId => $arrayOfServices)
                        <div class="service-row">
                            <div class="border border-dark p-4" style="background-color: white;">
                                Servizio: {{$arrayOfServices->first()->service->name}}
                                <br><br>
                                Quantità: {{$arrayOfServices->count()}}
                                <br><br>
                                Totale netto: {{ number_format($arrayOfServices->first()->price *
                                $arrayOfServices->count(), 2,
                                ',',
                                '.')
                                }} €
                                <br><br>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>

                <span>Totale Netto Fattura: {{ number_format($nettoFattura, 2, ',', '.') }} €</span><br>
                <span>IVA: {{ number_format($ivaFattura, 2, ',', '.') }} €</span><br>
                <span>Totale Fattura: {{ number_format($lordoFattura, 2, ',', '.') }} €</span>

            </div>

            {{-- Somma il valore delle rate della fattura --}}
            @php
            $totalInstallments = $invoice->installments->sum('amount');
            @endphp

            @if ($invoice->price != $totalInstallments)
            <span class="border border-3 border-danger" style="padding: 30px; width: 90%; margin-left: 30px">
                Attenzione! La somma del netto delle rate non corrisponde al totale netto della fattura
                <br><br>
                Totale Netto Fattura: {{ number_format($invoice->price, 2, ',', '.') }} € <br>
                Totale Netto Rate: {{ number_format($totalInstallments, 2, ',', '.') }} €
            </span>
            @endif


            <div class="ms_card mt-3">
                <div class="installment-card-container d-flex flex-wrap mb-4">
                    <form action="{{route('update.installments', $invoice->id)}}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')

                        <a href="#" class="ms_button mb-4 installmentsBtn" id="edit-installments">
                            MODIFICA PAGAMENTI E PROVVIGIONI
                        </a>

                        <input id="updateInstallmentsBtn" class="saveBtn" type="submit" value="SALVA">

                        <div class="form-container row" style="padding: 10px;">
                            @foreach ($installments as $installment)
                            <div class="col-12 mt-4" style="display:flex; flex-wrap: wrap; border: 1px solid black;">
                                <div class="col-12 installment-card">
                                    <span><b>Rata n. {{ $loop->iteration }}</b></span>
                                    <br><br>

                                    {{-- Calcolo il prezzo della rata --}}
                                    @php
                                    $imponibile = $installment->amount;
                                    $iva = $imponibile * 0.22;
                                    $totale = $imponibile + $iva;
                                    @endphp

                                    <label for="amount_{{ $installment->id }}">Netto: {{ number_format($imponibile, 2,
                                        ',', '.') }} €
                                    </label>
                                    <br>
                                    <label for="amount_{{ $installment->id }}">IVA: {{ number_format($iva, 2, ',', '.')
                                        }} €
                                    </label>
                                    <br><br>
                                    <label for="amount_{{ $installment->id }}">Totale rata: </label>
                                    <span>{{ number_format($totale, 2, ',', '.') }} €</span>
                                    <br><br><br>

                                    {{-- SCADENZA DELLA RATA --}}
                                    <label for="expire_date_{{ $installment->id }}">Data di scadenza: </label>
                                    <span class="info-span">{{ date('d-m-Y',
                                        strtotime($installment->expire_date))}}</span>
                                    <input class="info-input" type="date"
                                        name="installments[{{ $installment->id }}][expire_date]"
                                        id="expire_date_{{ $installment->id }}"
                                        value="{{ date('Y-m-d', strtotime($installment->expire_date)) }}">
                                    <br><br>

                                    {{-- DATA DI PAGAMENTO --}}
                                    @if ($installment->paid)
                                    <label for="updated_at_{{$installment->updated_at}}">
                                        <u>Pagamento effettuato il {{date('d-m-Y',
                                            strtotime($installment->updated_at))}}</u>
                                    </label>
                                    <br><br>
                                    @endif


                                    {{-- STATO PAGAMENTO --}}
                                    <label for="paid_{{ $installment->id }}">Stato: </label>
                                    <span class="info-span {{$installment->paid ? 'text-success' : 'text-danger'}}"
                                        style="font-weight: bold">
                                        {{$installment->paid ? 'Pagata' : 'Non pagata'}}
                                    </span>
                                    <select class="info-input" name="installments[{{ $installment->id }}][paid]"
                                        id="paid_{{ $installment->id }}">
                                        <option value="0" {{ $installment->paid == 0 ? 'selected' : '' }}>Non pagata
                                        </option>
                                        <option value="1" {{ $installment->paid == 1 ? 'selected' : '' }}>Pagata
                                        </option>
                                    </select> <br>
                                </div>


                                {{-- SEZIONE SERVIZI A SCHERMO --}}
                                <div class="servicesTable" style="width: 100%; margin-bottom: 20px; padding: 20px">
                                    <div class="headerRow">
                                        <div class="border border-dark service_col">Servizio</div>
                                        <div class="border border-dark price_col">Prezzo del servizio</div>
                                    </div>

                                    {{-- LOGICA PER RAGGRUPPARE I SERVIZI DI OGNI RATA PER SERVICE_ID --}}
                                    @php
                                    $groupedServicesPerInstallment =
                                    $installment->servicePerInstallments->groupBy(function ($servicePerInstallment) {
                                    return $servicePerInstallment->serviceSold->service_id;
                                    });
                                    @endphp

                                    @foreach ($groupedServicesPerInstallment as $serviceId => $services)
                                    @php
                                    $totalPrice = $services->sum('price');
                                    @endphp
                                    <div style="display:flex">
                                        <div class="service_col bg-white border border-dark">
                                            {{$services->first()->serviceSold->service->name}}
                                        </div>
                                        <div class="price_col bg-white border border-dark">
                                            {{ number_format($totalPrice, 2, ',', '.') }} €
                                        </div>
                                    </div>
                                    @endforeach
                                </div>


                                {{-- SEZIONE SERVIZI EDITABILE --}}
                                <div class="editableServicesTable"
                                    style="width: 100%; display: none; margin-bottom: 20px; padding: 20px">
                                    <div class="headerRow">
                                        <div class="border border-dark service_editable_col">Servizio</div>
                                        <div class="border border-dark price_editable_col">Prezzo del servizio</div>
                                        <div class="border border-dark vss_editable_col">VSS</div>
                                        <div class="border border-dark vsd_editable_col">VSD</div>
                                    </div>

                                    @php
                                    $servicesPerInstallment = $installment->servicePerInstallments;

                                    @endphp
                                    @foreach ($servicesPerInstallment as $servicePerInstallment)
                                    <div style="display:flex">
                                        <div class="service_editable_col bg-white border border-dark">
                                            {{$servicePerInstallment->serviceSold->service->name}}
                                        </div>
                                        <div class="price_editable_col bg-white border border-dark">
                                            <input type="number" name="prices[{{ $servicePerInstallment->id }}]" id=""
                                                style="width: 100%" value="{{$servicePerInstallment->price}}"
                                                step="0.01">
                                        </div>
                                        <div class="vss_editable_col bg-white border border-dark">

                                            <select name="vss_consultants[{{ $servicePerInstallment->id }}]" id=""
                                                style="width: 100%">

                                                <option value="">Nessuno</option>
                                                @foreach ($consultants as $consultant)
                                                <option value="{{$consultant->id}}" @if ($consultant->id ==
                                                    $servicePerInstallment->vssCommission->consultant_id)
                                                    selected
                                                    @endif
                                                    >
                                                    {{$consultant->name}} {{$consultant->lastname}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="vsd_editable_col bg-white border border-dark">
                                            <select name="vsd_consultants[{{ $servicePerInstallment->id }}]" id=""
                                                style="width: 100%">
                                                <option value="">Nessuno</option>
                                                @foreach ($consultants as $consultant)
                                                <option value="{{$consultant->id}}" @if ($consultant->id ==
                                                    $servicePerInstallment->vsdCommission->consultant_id)
                                                    selected
                                                    @endif>{{$consultant->name}}
                                                    {{$consultant->lastname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>

                            </div>
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

        document.getElementById('editInvoiceNumber').addEventListener('click', function() {
            event.preventDefault();
            let editInvoiceNumber = document.getElementById('editInvoiceNumber');
            let invoiceNumberInput = document.getElementById('invoiceNumberInput');
            let invoiceNumberDisplay = document.getElementById('invoiceNumberDisplay');
            let saveBtn2 = document.getElementById('saveBtn2');
            let autoFillCheckbox = document.querySelectorAll('.autoFillCheckbox');
            invoiceNumberInput.style.display = 'inline-block';
            saveBtn2.style.display = 'inline-block';
            invoiceNumberDisplay.style.display = 'none';
            editInvoiceNumber.style.display = 'none';
            autoFillCheckbox.forEach(function(element) {
                element.style.display = 'inline-block';
            });
        });


        document.getElementById('edit-installments').addEventListener('click', function() {
            let installmentSpans = document.querySelectorAll('.info-span');
            let installmentInputs = document.querySelectorAll('.info-input');
            let updateInstallmentsBtn = document.getElementById('updateInstallmentsBtn');
            let servicePriceSpans = document.querySelectorAll('.price-span');
            let servicesTables = document.querySelectorAll('.servicesTable');
            let editableServicesTables = document.querySelectorAll('.editableServicesTable');

            installmentSpans.forEach(function(span) {
                span.style.display = 'none';
            });
            installmentInputs.forEach(function(input) {
                input.style.display = 'inline-block';
            });
            updateInstallmentsBtn.style.display = 'inline-block';
            servicePriceSpans.forEach(function(span) {
                span.style.display = 'none';
            });


            // SWITCH TRA TABELLA A SCHERMO E TABELLA EDITABILE
            servicesTables.forEach(function(table) {
                table.style.display = 'none';
            });

            editableServicesTables.forEach(function(table) {
                table.style.display = 'inline-block';
            });

        }); 

        function autoFillInvoiceNumber() {
            let maxInvoiceNumber = document.getElementById('maxInvoiceNumber').value;
            let newMaxInvoiceNumber = parseInt(maxInvoiceNumber) + 1;
            document.getElementById('invoiceNumberInput').value = newMaxInvoiceNumber;
        }

        // Assegna l'evento onclick alla checkbox
        document.getElementById('autoFillInvoiceNumber').addEventListener('click', autoFillInvoiceNumber);

    });

</script>
@endpush

<style scoped lang="scss">
    .container_macro {
        display: flex;
        justify-content: center;

        .section-container {
            width: 100%;
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;

            .ms_card {
                width: 100%;
                min-height: 200px;
                padding: 30px;

                .titleContainer {
                    .title {
                        font-size: 25px;
                        font-weight: bold;
                    }

                    #invoiceNumberInput {
                        display: none;
                        width: 50px;
                    }

                    .errorInvoiceNumber {
                        border: 2px solid red;
                        padding: 10px;
                        width: 50%;

                    }
                }

                .phoneTable {
                    display: none;
                    padding: 10px;
                }

                .installment-card {
                    cursor: pointer;
                    padding: 20px;
                }
            }
        }
    }

    .info-input {
        display: none;
        width: 70%
    }

    .saveBtnDiv {
        display: flex;
        justify-content: flex-end;
    }

    .saveBtn {
        padding: 18px;
        background-color: rgb(28, 192, 28);
        border: none;
        color: white;
    }

    .saveBtn2 {
        background-color: rgb(28, 192, 28);
        border: none;
        color: white;
        padding: 8px;
        font-size: 14px;
        display: none;
    }

    .autoFillCheckbox {
        display: none;
    }

    .saveBtn:hover {
        background-color: rgb(97, 255, 97);
    }

    .commissionsBtn {
        padding: 5px 10px;
        font-size: 13px;
        background-color: rgb(28, 156, 192);
        border: none;
        text-decoration: none;
        color: white;
    }

    .commissionsBtn:hover {
        background-color: rgb(97, 189, 255);
    }


    form {
        width: 100%;

        #updateInstallmentsBtn {
            display: none;
        }
    }

    .installmentsBtn {
        max-width: 150px;
        background-color: #4a6da7;
        border: none;
        padding: 10px;

        a {
            text-decoration: none;
            color: white;
        }
    }

    #edit-installments {
        color: white;
        text-decoration: none;
        padding: 20px;
        margin-right: 10px;
    }

    .installmentsBtn:hover {
        background-color: #35517e;
    }

    .headerRow {
        display: flex;
        justify-content: center;
        background-color: gray;
        color: white;
        font-weight: bold;
    }

    .service_col {
        width: 50%;
        padding: 10px;
    }

    .price_col {
        width: 50%;
        padding: 10px;
    }

    .service_editable_col {
        width: 40%;
        padding: 10px;
    }

    .price_editable_col {
        width: 20%;
        padding: 10px;
    }

    .vss_editable_col {
        width: 20%;
        padding: 10px;
    }

    .vsd_editable_col {
        width: 20%;
        padding: 10px;
    }

    .editInvoiceNumber {
        border: 1px solid black;
        padding: 10px;
        background-color: #29c7d9;
        color: white;
        text-decoration: none;
        font-size: 13px;
        border: none;
    }


    /* MEDIA QUERY */
    @media all and (max-width: 752px) {

        .container_macro {

            .section-container {

                .ms_card {
                    width: 100%;
                }
            }
        }
    }


    @media all and (max-width: 688px) {
        .container_macro {
            .section-container {
                .ms_card {
                    .desktopTable {
                        display: none;
                    }

                    .phoneTable {
                        display: block;
                    }
                }
            }
        }

        .saveBtnDiv {
            justify-content: center;

            .saveBtn {
                width: 90%;
                padding: 5px 10px;
                background-color: rgb(28, 192, 28);
                border: none;
                color: white;
            }
        }


    }


    @media all and (max-width: 526px) {
        .container_macro {
            .section-container {
                .ms_card {
                    padding: 1px;

                    .installment-card {
                        cursor: pointer;
                        padding: 20px;
                    }
                }
            }
        }

        #create-installments {
            display: block;
            margin-bottom: 20px;
        }

        .installmentsBtn {
            width: 100%;
            margin-left: 20px;
        }

        .saveBtnDiv {
            margin-bottom: 50px;
        }
    }
</style>