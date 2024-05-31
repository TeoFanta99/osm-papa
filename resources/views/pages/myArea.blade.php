@extends('layouts.app')
@section('content')

@include('components.sidebar')
<div class="main-content d-flex flex-column align-items-center">

    <h1 style="margin: 30px">La mia area</h1>

    <div class="headerContainer">

        {{-- Filtro per mese --}}
        <form action="{{route('index.area')}}" method="GET" style="margin: 0; margin-right: 30px">
            <select name="monthYear" id="monthYear">
                @foreach ([$lastYear, $currentYear] as $year)
                @foreach ($months as $num => $name)
                <option value="{{$year}}-{{$num}}" {{ $monthYear==$year . '-' . $num ? 'selected' : '' }}>
                    {{$name}} {{$year}}
                </option>
                @endforeach
                @endforeach
            </select>
            <button class="filterBtn" type="submit">Filtra</button>
        </form>

        <div class="buttonContainer">
            <button class="pageBtn" onclick="showSection('sommeIncassate')">Somme incassate e provvigioni</button>
        </div>
        <div class="buttonContainer">
            <button class="pageBtn" onclick="showSection('sommeDaIncassare')">Somme da incassare</button>
        </div>
        <div class="buttonContainer">
            <button class="pageBtn" onclick="showSection('fattureSospese')">Fatture in sospeso</button>
        </div>
        <div class="buttonContainer">
            <button class="pageBtn" onclick="showSection('riepilogo')">Riepilogo</button>
        </div>
    </div>


    <div class="sectionsContainer" style="margin-top: 20px;">

        {{-- Sezione 1: Somme incassate e Provvigioni --}}
        <div class="section" id="sommeIncassate" style="flex-direction: column">
            <h3 style="padding: 20px">Somme incasssate e provvigioni</h3>

            <div class="macroContainer" style="display: flex">
                {{-- @php
                $totalPaid = 0;
                @endphp --}}

                <div class="tablesContainer">
                    <div class="table">
                        <div class="tableHeader">
                            <div class="headerCol SI_Service">Servizio</div>
                            <div class="headerCol SI_Client">Cliente</div>
                            <div class="headerCol SI_Consultant">Consulente</div>
                            <div class="headerCol SI_Invoice">FT</div>
                            <div class="headerCol SI_Price">Prezzo</div>
                            <div class="headerCol SI_PaidDate">Pagato il</div>
                            {{-- <div class="headerCol SI_TotalPaid">Tot Pagato</div> --}}
                            <div class="headerCol SI_Vss">VSS</div>
                            <div class="headerCol SI_Vsd">VSD</div>
                        </div>

                        @php
                        $groupedServices = [];
                        @endphp

                        @foreach ($filteredResults as $installment)
                        @if ($installment->paid)
                        @foreach ($installment->servicePerInstallments as $service)

                        @php
                        $serviceName = $service->serviceSold->service->name;
                        if(!isset($groupedServices[$serviceName])) {
                        $groupedServices[$serviceName] = [
                        'service' => $service,
                        'clientName' => $installment->invoice->client->name,
                        'consultantName' => $installment->invoice->client->consultant->name,
                        'invoiceNumber' => $installment->invoice->invoice_number,
                        'price' => 0,
                        'paidDate' => $installment->updated_at,
                        // 'totalPaid' => 0,
                        'vss' => 0,
                        'vsd' => 0,
                        ];
                        }
                        $groupedServices[$serviceName]['price'] += $service->price;
                        // $groupedServices[$serviceName]['totalPaid'] += $service->price;
                        if ($installment->invoice->client->consultant_id == $service->vssCommission->consultant_id) {
                        $groupedServices[$serviceName]['vss'] += $service->vssCommission->value;
                        }
                        if ($installment->invoice->client->consultant_id == $service->vsdCommission->consultant_id) {
                        $groupedServices[$serviceName]['vsd'] += $service->vsdCommission->value;
                        }
                        @endphp
                        @endforeach
                        @endif
                        @endforeach

                        @php
                        $totalCashedPerMonth = 0;
                        @endphp
                        @foreach ($groupedServices as $serviceData)
                        <div class="tableRow">
                            <div class="rowCol SI_Service" style="font-size: 10px">
                                {{ $serviceData['service']->serviceSold->service->name }}
                            </div>
                            <div class="rowCol SI_Client">{{ $serviceData['clientName'] }}</div>
                            <div class="rowCol SI_Consultant">{{ $serviceData['consultantName'] }}</div>
                            <div class="rowCol SI_Invoice">{{ $serviceData['invoiceNumber'] }}</div>
                            <div class="rowCol SI_Price">{{ number_format($serviceData['price'], 2, ',', '.') }}</div>
                            <div class="rowCol SI_PaidDate">{{ date('d/m/Y', strtotime($serviceData['paidDate'])) }}
                            </div>
                            {{-- <div class="rowCol SI_TotalPaid">{{ number_format($serviceData['totalPaid'], 2, ',',
                                '.') }}
                            </div> --}}
                            @php
                            $totalCashedPerMonth += $serviceData['price'];
                            @endphp
                            <div class="rowCol SI_Vss">
                                {{ number_format($serviceData['vss'], 2, ',', '.') }}
                            </div>
                            <div class="rowCol SI_Vsd">
                                {{ number_format($serviceData['vsd'], 2, ',', '.') }}
                            </div>
                        </div>
                        @endforeach <br>
                        <div class="tablerow">
                            <div class="rowCol" style="width: 75%">Somme incassate nel mese di
                                {{strtoupper($selectedMonthName)}}
                            </div>
                            <div class="rowCol"
                                style="width: 25%; background-color: green; color: rgb(255, 255, 255); display: flex; justify-content: center; align-items: center; font-weight: bold">
                                {{number_format($totalCashedPerMonth, 2, ',', '.')}} €
                            </div>
                        </div>
                    </div>
                </div>

                <table class="summaryConsultantTable">
                    <thead>
                        <th style="padding: 10px; width: 200px">Consulente</th>
                        <th style="padding: 10px; width: 200px">Somme incassate</th>
                        <th style="padding: 10px; width: 200px">Provvigioni maturate</th>
                    </thead>
                    <tbody>
                        @foreach ($consultants as $consultant)
                        @php

                        // Calcolo somme
                        $clients = $consultant->clients;
                        $cashed = 0;
                        foreach ($clients as $client) {
                        $invoices = $client->invoices;

                        foreach ($invoices as $invoice) {
                        $installmentsPaid = $invoice ->installments()
                        ->where('paid', true)
                        ->whereYear('updated_at', substr($monthYear, 0, 4))
                        ->whereMonth('updated_at', substr($monthYear, 5, 2))
                        ->get();
                        $cashed += $installmentsPaid->sum('amount');
                        }
                        }

                        // Calcolo provvigioni
                        $vss = $consultant->vssCommissions;
                        $vsd = $consultant->vsdCommissions;
                        $totalVss = 0;
                        $totalVsd = 0;
                        $totalCommissions = 0;
                        foreach ($vss as $commission) {
                        if($commission->servicePerInstallment->installment->paid &&
                        $commission->servicePerInstallment->installment->updated_at->format('Y-m') == $monthYear) {
                        $totalVss += $commission->value;
                        };
                        }
                        foreach ($vsd as $commission) {
                        if($commission->servicePerInstallment->installment->paid &&
                        $commission->servicePerInstallment->installment->updated_at->format('Y-m') == $monthYear) {
                        $totalVsd += $commission->value;
                        };
                        }
                        $totalCommissions = $totalVss + $totalVsd;
                        @endphp
                        <tr>
                            <td>{{$consultant->name}} {{ $consultant->lastname }}</td>
                            <td>{{ number_format($cashed, 2, ',', '.') }} €</td>
                            <td>{{ number_format($totalCommissions, 2, ',', '.') }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Sezione 2: Somme da incassare --}}
        <div class="section" id="sommeDaIncassare" style="flex-direction: column">
            <h3 style="padding: 20px">Somme da incassare</h3>

            <div class="macroContainer" style="display: flex">
                <div class="tablesContainer">
                    <div class="table" style="display: inline-block">
                        <div class="tableHeader">
                            <div class="headerCol invoiceDate">Data fattura</div>
                            <div class="headerCol invoiceNumber">FT</div>
                            <div class="headerCol invoiceClient">Cliente</div>
                            <div class="headerCol invoiceConsultant">Consulente</div>
                            <div class="headerCol invoiceDescription">Descrizione</div>
                            <div class="headerCol invoiceNet">Netto</div>
                        </div>

                        @php
                        $stillToCash = 0;
                        @endphp

                        @foreach ($installments as $installment)
                        @if ($installment->paid == false)
                        <div class="tableRow">
                            <div class="rowCol invoiceDate">{{ date('d/m/Y',
                                strtotime($installment->invoice->invoice_date))}}</div>
                            <div class="rowCol invoiceNumber">{{$installment->invoice->invoice_number}}</div>
                            <div class="rowCol invoiceClient">{{$installment->invoice->client->name}}</div>
                            <div class="rowCol invoiceConsultant">{{$installment->invoice->client->consultant->name}}
                                {{ substr($installment->invoice->client->consultant->lastname, 0, 1) }}.</div>
                            <div class="rowCol invoiceDescription">
                                <ul style="background: none; padding-left: 15px">
                                    @php
                                    $displayedServices = [];
                                    @endphp

                                    @foreach ($installment->servicePerInstallments as $service)

                                    @php
                                    $serviceName = $service->serviceSold->service->name;
                                    @endphp
                                    @if (!in_array($serviceName, $displayedServices))
                                    <li>{{ $serviceName }}</li>
                                    @php
                                    $displayedServices[] = $serviceName;
                                    @endphp

                                    @endif
                                    @endforeach
                                </ul>

                            </div>

                            <div class="rowCol invoiceNet">{{number_format($installment->amount, 2, ',', '.')}}</div>
                            @php
                            $stillToCash += $installment->amount;
                            @endphp
                        </div>
                        @endif
                        @endforeach <br>

                        <div class="tablerow">
                            <div class="rowCol" style="width: 75%">Importi da incassare previsti per fine
                                {{ strtoupper($currentMonthName) }}
                                (escluse eventuali fatture ancora da fare)</div>
                            <div class="rowCol"
                                style="width: 25%; background-color: rgb(155, 22, 22); color: white; display: flex; justify-content: center; align-items: center; font-weight: bold">
                                {{number_format($stillToCash, 2, ',', '.')}} €</div>
                        </div>
                    </div>
                </div>

                <div class="summaryConsultantTable">
                    <div class="table">
                        <div class="tableHeader">
                            <div class="headerCol w-50">Consulente</div>
                            <div class="headerCol w-50">Da incassare</div>
                        </div>

                        @foreach ($consultants as $consultant)
                        <div class="tableRow">
                            <div class="rowCol w-50">{{$consultant->name}} {{ $consultant->lastname }}</div>

                            @php
                            // Calcolo somme
                            $clients = $consultant->clients;
                            $notCashed = 0;
                            foreach ($clients as $client) {
                            $invoices = $client->invoices;
                            foreach ($invoices as $invoice) {
                            $installmentsNotPaid = $invoice->installments()->where('paid', false)->get();
                            $notCashed += $installmentsNotPaid->sum('amount');
                            }
                            }
                            @endphp

                            <div class="rowCol w-50">{{number_format($notCashed, 2, ',', '.')}} €</div>
                        </div>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>

        {{-- Sezione 3: Fatture in sospeso --}}
        <div class="section" id="fattureSospese">
            <h3>Fatture in sospeso</h3>
        </div>

        {{-- Sezione 4: Riepilogo --}}
        <div class="section" id="riepilogo" style="flex-direction: column">
            {{-- <h3 style="padding: 20px">Riepilogo</h3> --}}

            <div class="macroContainer" style="display: flex">
                <div class="summaryConsultantTable" style="width: 45%; padding: 20px">
                    <h3>RIEPILOGO PER CONSULENTE</h3>
                    <div class="table">
                        <div class="tableHeader">
                            <div class="headerCol" style="width: 30%">Consulente</div>
                            <div class="headerCol" style="width: 20%">Da incassare</div>
                            <div class="headerCol" style="width: 20%">Incassati</div>
                            <div class="headerCol" style="width: 20%">Totali previsti</div>
                        </div>

                        @foreach ($consultants as $consultant)
                        <div class="tableRow">
                            <div class="rowCol" style="width: 30%">{{$consultant->name}} {{ $consultant->lastname }}
                            </div>

                            @php
                            // Calcolo somme
                            $clients = $consultant->clients;
                            $notCashed = 0;
                            foreach ($clients as $client) {
                            $invoices = $client->invoices;
                            foreach ($invoices as $invoice) {
                            $installmentsNotPaid = $invoice->installments()->where('paid', false)->get();
                            $notCashed += $installmentsNotPaid->sum('amount');
                            }
                            }
                            @endphp

                            <div class="rowCol" style="width: 20%">{{number_format($notCashed, 2, ',', '.')}} €</div>
                            <div class="rowCol" style="width: 20%">{{number_format($cashed, 2, ',', '.')}} €</div>
                            @php
                            $totaliPrevisti = $notCashed + $cashed;
                            @endphp
                            <div class="rowCol" style="width: 20%">{{number_format($totaliPrevisti, 2, ',', '.')}} €
                            </div>
                        </div>
                        @endforeach
                    </div>


                </div>

                <div class="summaryConsultantTable" style="width: 45%; padding: 20px">
                    <h3>RIEPILOGO TOTALE</h3>
                    <div class="table">
                        <div class="tableHeader">
                            <div class="headerCol" style="width: 70%"></div>
                            <div class="headerCol" style="width: 30%">Totale</div>
                        </div>
                        <div class="tableRow">
                            <div class="rowCol" style="width: 70%">Totale mese da incassare</div>
                            <div class="rowCol" style="width: 30%">{{number_format($stillToCash, 2, ',', '.')}} €</div>
                        </div>
                        <div class="tableRow">
                            <div class="rowCol" style="width: 70%">Totale mese incassato al
                                {{$currentDate->format('d/m/Y')}}</div>
                            <div class="rowCol" style="width: 30%">{{number_format($totalCashedPerMonth, 2, ',', '.')}}
                                €
                            </div>
                        </div>
                        @php
                        $expectedTotal = $stillToCash + $totalCashedPerMonth;
                        @endphp
                        <div class="tableRow">
                            <div class="rowCol" style="width: 70%">Totale previsto per {{strtoupper($currentMonthName)}}
                                {{$currentYear}}
                            </div>
                            <div class="rowCol" style="width: 30%">{{number_format($expectedTotal, 2, ',', '.')}} €
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>


    </div>

</div>

@endsection


@push('scripts')
<script>
    // Al caricamento della pagina nascondi tutte le sezioni eccetto quella in fondo a questa funzione:
    window.onload = function() {
        let sections = document.querySelectorAll('.sectionsContainer > .section');
        sections.forEach(function(section) {
            section.style.display = 'none';
        });
        
        document.getElementById('riepilogo').style.display = 'flex';
    };

    // Funzione per switchare tra le pagine
    function showSection(sectionId) {
    let sections = document.querySelectorAll('.sectionsContainer > .section');
    sections.forEach(function(section) {
        section.style.display = 'none';
    });

    let sectionToShow = document.getElementById(sectionId);
    sectionToShow.style.display = 'flex';
    }   
</script>
@endpush


<style scoped lang="scss">
    .headerContainer {
        display: flex;
        align-items: center;
        width: 100%;
        gap: 30px;
        background-color: #ff78be;
        padding: 10px;


        .pageBtn {
            border: none;
            background-color: white;
            color: black;
            padding: 10px;

            &:hover {
                background-color: #46e3ff;
                color: white;
            }

            &:focus {
                background-color: #46e3ff;
                color: white;
            }
        }

    }

    #monthYear {
        background-color: #ffffff;
        color: rgb(0, 0, 0);
        border: 1px solid black;
        /* border: none; */
        padding: 7px;
        cursor: pointer;
    }

    .filterBtn {
        background-color: green;
        border: none;
        color: white;
        padding: 5px;

        &:hover {
            background-color: lightgreen;
        }
    }

    .sectionsContainer {
        width: 100%;

        .section {
            displaY: none;
            width: 100%;

            .tablesContainer {
                padding: 0 20px;
                width: 70%;

                .table {
                    margin-right: 10px;
                    display: inline-block;

                }
            }

            .tableHeader {
                display: flex;

                .headerCol {
                    padding: 8px;
                    border: 1px solid black;
                    background-color: gray;
                    color: white;
                    font-weight: bold;
                    font-size: 15px;
                }
            }

            .tableRow {
                display: flex;

                .rowCol {
                    border: 1px solid black;
                    padding: 5px;
                }
            }

            .summaryConsultantTable {
                width: 20%;
            }

            .summaryConsultantTable th,
            .summaryConsultantTable td {
                border: 1px solid black;
            }

            .summaryConsultantTable th {
                background-color: lightblue;
                padding: 10px;
            }

            .summaryConsultantTable td {
                padding: 5px 10px;
            }
        }

        /* REGOLE PER "SOMME INCASSATE E PROVVIGIONI" */
        .SI_Service {
            width: 20%;
        }

        .SI_Client {
            width: 13%;
        }

        .SI_Consultant {
            width: 19%;
        }

        .SI_Invoice {
            width: 5%;
        }

        .SI_price {
            width: 10%;
        }

        .SI_PaidDate {
            width: 12%;
        }

        /* .SI_TotalPaid {
            width: 10%;
        } */

        .SI_Vss {
            width: 10%;
        }

        .SI_Vsd {
            width: 11%;
        }



        /* REGOLE PER "SOMME DA INCASSARE" */
        .invoiceDate {
            width: 19%
        }

        .invoiceNumber {
            width: 5%
        }

        .invoiceClient {
            width: 19%
        }

        .invoiceConsultant {
            width: 14%
        }

        .invoiceDescription {
            width: 33%
        }

        .invoiceNet {
            width: 10%
        }

    }
</style>