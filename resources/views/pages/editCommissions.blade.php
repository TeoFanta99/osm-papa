@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card card p-3">
                <h3 class="mb-5">Gestisci le provvigioni per la rata n. {{$installment->id}}</h3>

                @php
                $installmentNet = $installment->amount;
                $iva = $installmentNet * 22 / 100;
                $installmentTotalPrice = $installmentNet + $iva;
                @endphp

                <span style="margin-bottom: 30px">La somma dei servizi dovrà essere pari a
                    {{$installmentNet}} € (IVA esclusa).
                </span>

                <form method="POST" action="{{route('update.commissions', $installment->id)}}"
                    enctype="multipart/form-data">
                    @csrf
                    @method("PUT")

                    <input type="hidden" name="installment_id" value="{{ $installment->id }}">


                    {{-- CONTENITORE DI TUTTI I FORM DI CREAZIONE DELLE PROVVIGIONI --}}
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <th class="border border-dark headerRow">Servizio</th>
                                <th class="border border-dark headerRow">Prezzo (IVA esclusa)</th>
                                <th class="border border-dark headerRow">VSS</th>
                                <th class="border border-dark headerRow">VSD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicesSold as $serviceSold)
                            @php
                            $price = $serviceSold->price / $numberOfInstallments;
                            $priceWithDecimals = number_format($price, 2);
                            @endphp
                            <tr>
                                <td class="border border-dark">
                                    <input readonly type="text" name="service_id[{{ $serviceSold->service->id }}]"
                                        value="{{ $serviceSold->service->name }}" style="width: 100%">
                                </td>
                                <td class="border border-dark">
                                    <input type="number" name="price[{{$serviceSold->service->id}}]"
                                        value="{{$priceWithDecimals}}">
                                </td>
                                <td class="border border-dark">
                                    <select name="sold_by[{{$serviceSold->service->id}}]">
                                        <option value="">Nessuno</option>
                                        @foreach($consultants as $consultant)
                                        <option value="{{$consultant->id}}" @if($consultant->id ==
                                            $invoice->client->consultant_id) selected @endif
                                            >
                                            {{$consultant->name}} {{$consultant->lastname}}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border border-dark">
                                    <select name="delivered_by[{{$serviceSold->service->id}}]">
                                        <option value="">Nessuno</option>
                                        @foreach($consultants as $consultant)
                                        <option value="{{$consultant->id}}" @if($consultant->id ==
                                            $invoice->client->consultant_id) selected @endif>
                                            {{$consultant->name}}
                                            {{$consultant->lastname}}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <input class="mt-5 updateBtn" type="submit" value="AGGIORNA">
                </form>

                <br><br>


                <a class="comeBackBtn" href="{{route('show.invoice', $invoice->id)}}">
                    <button class="ms_button">Indietro</button>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection


<style>
    .comeBackBtn {
        background-color: #fc8200;
        border: none;
        width: 150px;
        padding: 10px;
        text-decoration: none;
        text-align: center;

        button {
            background: none;
            border: none;
            color: white;
        }
    }

    .comeBackBtn:hover {
        background-color: rgb(255, 197, 90);
    }

    .commissionForm {
        width: 90%;
        margin: 10px auto;
        border: 1px solid black;
        padding: 10px;
    }

    .headerRow {
        background-color: gray;
        color: white;
        font-weight: bold;
    }
</style>