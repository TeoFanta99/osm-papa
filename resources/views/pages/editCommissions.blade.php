@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card card p-3">
                <h3 class="mb-5">Gestisci i servizi per la rata n. {{$installment->id}}</h3>

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
                                <th class="border border-dark headerRow hoverable">Prezzo del servizio
                                    <div class="hover-note">Prezzo del servizio sul quale verrà calcolata la
                                        provvigione. Il prezzo è da intendersi IVA esclusa.
                                        <br><br>
                                        NON È IL VALORE DELLA PROVVIGIONE
                                    </div>
                                </th>
                                <th class="border border-dark headerRow">VSS</th>
                                <th class="border border-dark headerRow">VSD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicesSold as $index => $serviceSold)
                            @php
                            $price = $serviceSold->price / $numberOfInstallments;
                            $priceWithDecimals = number_format($price, 2, ',', '.');
                            @endphp
                            <tr>
                                <td class="border border-dark">
                                    <input readonly type="text" name="service_id[{{ $index }}]"
                                        value="{{ $serviceSold->service->name }}" style="width: 100%">
                                </td>
                                <td class="border border-dark">
                                    <input type="text" name="price[{{$index}}]" value="{{$priceWithDecimals}}"
                                        class="price-input">
                                </td>
                                <td class="border border-dark">
                                    <select name="sold_by[{{$index}}]">
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
                                    <select name="delivered_by[{{$index}}]">
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

<script>
    let priceInputs = document.querySelectorAll('.price-input');
    priceInputs.forEach(function(input) {
        input.addEventListener('input', function(event) {
            let value = event.target.value;
            if (isNaN(value)) {
                event.target.parentNode.querySelector('.error-message').style.display = 'block';
            } else {
                event.target.parentNode.querySelector('.error-message').style.display = 'none';
            }
        });
    });
</script>



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

    .hoverable {
        position: relative;
    }

    .hover-note {
        display: none;
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-10%);
        background-color: #ffef5d;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        z-index: 1;
        color: black;
    }

    .hoverable:hover .hover-note {
        display: block;
    }
</style>