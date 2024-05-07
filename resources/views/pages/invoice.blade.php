@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card">
                <form action="{{route('update.servicesold')}}" method="POST">
                    @csrf
                    @method('PUT')

                    <h2>FATTURA N° {{$invoice->id}}</h2>
                    <br>
                    {{-- TABELLA EDITABILE (COMPUTER)--}}
                    <table class="desktopTable table mb-3">
                        <thead>
                            <tr>
                                <th class="border border-dark" style="background: gray; color: white">
                                    Servizio
                                </th>
                                <th class="border border-dark text-center" style="background: gray; color: white">
                                    Netto (€)
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
                                {{-- <td class="border border-dark">
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
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- TABELLA EDITABILE (SMARTPHONE) --}}
                    <div class="phoneTable table mb-3">

                        <div class="border border-dark"
                            style="background: gray; color: white; padding: 10px; font-weight: bold;">
                            <span>
                                Servizi in fattura
                            </span>
                        </div>

                        <div class="services-container">
                            @foreach ($servicesSold as $serviceSold)
                            <input type="hidden" name="servicesSold[{{ $serviceSold->id }}][id]"
                                value="{{ $serviceSold->id }}">
                            <div class="service-row">
                                <div class="border border-dark p-4" style="background-color: white;">
                                    Servizio: {{$serviceSold->service->name}}
                                    <br><br>
                                    Prezzo: {{$serviceSold->price}} €
                                    <br><br>
                                    {{-- Erogato da: <select name="servicesSold[{{ $serviceSold->id }}][delivered_by]"
                                        class="delivered_by">
                                        <option value="">Nessuno</option>
                                        @foreach ($consultants as $consultant)
                                        <option value="{{$consultant->id}}" @if($serviceSold->delivered_by != null &&
                                            $consultant->id == $serviceSold->delivered_by)
                                            selected @endif>
                                            {{$consultant->name}} {{$consultant->lastname}}
                                        </option>
                                        @endforeach
                                    </select> --}}
                                </div>

                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <div class="ms_card mt-3">
                <div class="installment-card-container d-flex flex-wrap mb-4">
                    <form action="{{route('update.installments', $invoice->id)}}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')
                        <button class="ms_button installmentsBtn" id="create-installments">
                            <a href="{{route('index.installments', $invoice->id)}}">NUOVA
                                RATA</a>
                        </button>
                        <button class="ms_button mb-4 installmentsBtn" id="edit-installments">
                            <a href="#" onclick="return false;">MODIFICA PAGAMENTI</a>
                        </button>
                        <input id="updateInstallmentsBtn" class="saveBtn" type="submit" value="SALVA">

                        <div class="form-container row" style="padding: 10px;">
                            @foreach ($installments as $installment)
                            <div class="col-12 mt-4" style="display:flex; flex-wrap: wrap; border: 1px solid black;">
                                <div class="col-12 col-md-6 installment-card">
                                    <span><b>Rata n. {{ $loop->iteration }}</b></span>
                                    <br><br>
                                    {{-- ID della rata: {{$installment->id}} (debug...)
                                    <br><br> --}}
                                    {{-- PREZZO DELLA RATA --}}
                                    @php
                                    $imponibile = $installment->amount;
                                    $iva = $imponibile * 0.22;
                                    $iva = number_format($iva, 2, '.', '');
                                    $totale = $imponibile + $iva;
                                    @endphp
                                    <label class="ivaInclusa" for="amount_{{ $installment->id }}">Netto: {{$imponibile}}
                                        €
                                    </label>
                                    <br>
                                    <label class="iva" for="amount_{{ $installment->id }}">IVA: {{$iva}} €
                                    </label>
                                    <br><br>
                                    <label class="ivaEsclusa" for="amount_{{ $installment->id }}">Totale rata: </label>
                                    <span class="info-span">{{$totale}} €</span>
                                    <input class="info-input" type="number"
                                        name="installments[{{ $installment->id }}][amount]"
                                        id="amount_{{ $installment->id }}" value="{{$installment->amount}}">
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
                                    </select>
                                </div>



                                {{-- SEZIONE PROVVIGIONI --}}
                                <div class="col-12 col-md-6" style="margin-top: 15px; padding: 10px">
                                    <span><b>Provvigioni</b></span>

                                    <a class="ms_button mb-4 commissionsBtn mx-2"
                                        href="{{route('create.newCommissions', ['installment_id' => $installment->id])}}">AGGIUNGI
                                    </a>

                                    <a class="ms_button mb-4 commissionsBtn mx-2" href="#">MODIFICA
                                    </a>
                                    <br>
                                    <ul class="mt-3" style="padding-left: 0px">
                                        @php
                                        $commissionTotal = 0;
                                        @endphp
                                        @foreach ($installment->commissions as $commission)
                                        <ul class="border border-2 border-primary rounded">
                                            <li>Totale: {{ $commission->price }} €</li>
                                            <li>@if (!$commission->sold_by) Nessun consulente
                                                @else
                                                @php
                                                $consultant = $consultants->firstWhere('id', $commission->sold_by);
                                                @endphp
                                                Venditore: {{$consultant->name}} {{$consultant->lastname}}
                                                @endif
                                            </li>
                                            <li>@if (!$commission->delivered_by) Nessun consulente
                                                @else
                                                @php
                                                $consultant = $consultants->firstWhere('id', $commission->delivered_by);
                                                @endphp
                                                Erogatore: {{$consultant->name}} {{$consultant->lastname}}
                                                @endif
                                            </li>
                                            <li>
                                                Servizi:
                                                <ul>
                                                    @for ($i = 0; $i < $commission->services->count(); $i++)
                                                        <li>{{$commission->services[$i]->name}}</li>
                                                        @endfor
                                                </ul>
                                            </li>
                                        </ul>

                                        @php
                                        $commissionTotal += $commission->price;
                                        $commissionTotal = number_format($commissionTotal, 2, '.', '');
                                        @endphp
                                    </ul>

                                    @endforeach
                                    </ul>

                                    @php
                                    $imponibile = $installment->amount;
                                    $iva = $imponibile * 0.22;
                                    $totale = $imponibile + $iva;
                                    @endphp

                                    @if ($commissionTotal == 0)
                                    @elseif ($commissionTotal != $imponibile)
                                    <div class="alert alert-danger mt-3" role="alert">
                                        Netto rata: {{$imponibile}} €
                                        <br>
                                        Somma provvigioni: {{$commissionTotal}} €
                                        <br><br>
                                        Correggere la differenza
                                    </div>
                                    @endif


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
            width: 100%;
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;

            .ms_card {
                width: 100%;
                min-height: 200px;
                padding: 30px;

                .phoneTable {
                    display: none;
                    padding: 10px;
                }

                .installment-card {
                    cursor: pointer;
                    padding: 20px;

                    .info-input {
                        display: none;
                        width: 70%
                    }
                }
            }
        }
    }

    .saveBtnDiv {
        display: flex;
        justify-content: flex-end;
    }

    .saveBtn {
        padding: 5px 10px;
        background-color: rgb(28, 192, 28);
        border: none;
        color: white;
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
            padding: 10px;
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

    .installmentsBtn:hover {
        background-color: #35517e;
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