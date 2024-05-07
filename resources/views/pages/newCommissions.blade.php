@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card card p-3">
                <h3 class="mb-5">Crea nuove provvigioni per la rata n. {{$installment->id}}</h3>

                @php
                $installmentNet = $installment->amount;
                $iva = $installmentNet * 22 / 100;
                $installmentTotalPrice = $installmentNet + $iva;
                @endphp

                <span style="margin-bottom: 30px">La somma di tutte le provvigioni dovrà essere pari a
                    {{$installmentNet}} € (IVA esclusa).
                </span>

                {{-- SELECT PER CREARE LE PROVVIGIONI --}}
                <label for="">Quante provvigioni vuoi creare?</label>
                <select name="numberOfCommissions" id="numberOfCommissions" style="width: 15%; margin-bottom: 30px">
                    @for ($i = 1; $i < 11; $i++) <option>{{$i}}</option>
                        @endfor
                </select>

                <form method="POST" action="{{route('store.commissions')}}" enctype="multipart/form-data">
                    @csrf
                    @method("POST")

                    <input type="hidden" name="installment_id" value="{{ $installment->id }}">


                    {{-- CONTENITORE DI TUTTI I FORM DI CREAZIONE DELLE PROVVIGIONI --}}
                    <div id="commissionFormsContainer">
                    </div>


                    <input class="mt-5 createBtn" type="submit" value="CREA">
                </form>

                <a class="comeBackBtn" href="{{route('show.invoice', $invoice->id)}}">
                    <button class="ms_button">Indietro</button>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Ottieni il numero di commissionForms iniziali
    let initialNumberOfCommissions = parseInt(document.getElementById('numberOfCommissions').value);
    let commissionFormsContainer = document.getElementById('commissionFormsContainer');

    // Aggiungi i commissionForms iniziali
    for (let i = 0; i < initialNumberOfCommissions; i++) {
        let commissionForm = document.createElement('div');
        commissionForm.classList.add('commissionForm'); // Aggiungi la classe commissionForm

        commissionForm.innerHTML = `
            <label for="price${i}" class="me-3">Totale provvigione</label>
            <input type="number" name="commissions[${i}][price]" id="price${i}" class="mb-3" step="0.01" min="0"><br><br>

            <label for="servicesList${i}" class="me-3 mb-2">Servizi da includere:</label>
            <ul style="list-style-type: none">
            @foreach ($servicesSold as $serviceSold)
            <li>
                <input type="checkbox" id="service{{$serviceSold->service->id}}_${i}" name="commissions[${i}][services][]" value="{{$serviceSold->service->id}}">
                <label for="service{{$serviceSold->service->id}}_${i}">{{$serviceSold->service->name}}</label>   
            </li>
            @endforeach
            </ul>

            <br><br>
            
            <label for="soldByConsultant${i}" class="me-3">Consulente venditore</label>
            <select name="commissions[${i}][sold_by]" id="soldByConsultant${i}" class="mb-3">
                <option value="">Nessuno</option>
                @foreach($consultants as $consultant)
                    <option value="{{$consultant->id}}">{{$consultant->name}} {{$consultant->lastname}}</option>
                @endforeach  
            </select>
            <br>
            <label for="deliveredByConsultant${i}" class="me-3">Consulente erogatore</label>
            <select name="commissions[${i}][delivered_by]" id="deliveredByConsultant${i}" class="mb-3">
                <option value="">Nessuno</option>
                @foreach($consultants as $consultant)
                    <option value="{{$consultant->id}}">{{$consultant->name}} {{$consultant->lastname}}</option>
                @endforeach  
            </select>
        `;
        commissionFormsContainer.appendChild(commissionForm);
    }
});

    document.getElementById('numberOfCommissions').addEventListener('change', function() {
        let numberOfCommissions = parseInt(this.value);
        let commissionFormsContainer = document.getElementById('commissionFormsContainer');
        commissionFormsContainer.innerHTML = '';

        for (let i = 0; i < numberOfCommissions; i++) {
            let commissionForm = document.createElement('div');
            commissionForm.classList.add('commissionForm');

            commissionForm.innerHTML = `
            <label for="price${i}" class="me-3">Totale provvigione</label>
            <input type="number" name="commissions[${i}][price]" id="price${i}" class="mb-3" step="0.01" min="0"><br><br>

            <label for="servicesList${i}" class="me-3 mb-2">Servizi da includere:</label>
            <ul style="list-style-type: none">
            @foreach ($servicesSold as $serviceSold)
            <li>
                <input type="checkbox" id="service{{$serviceSold->service->id}}_${i}" name="commissions[${i}][services][]" value="{{$serviceSold->service->id}}">
                <label for="service{{$serviceSold->service->id}}_${i}">{{$serviceSold->service->name}}</label>   
            </li>
            @endforeach
            </ul>

            <br><br>
            
            <label for="soldByConsultant${i}" class="me-3">Consulente venditore</label>
            <select name="commissions[${i}][sold_by]" id="soldByConsultant${i}" class="mb-3">
                <option value="">Nessuno</option>
                @foreach($consultants as $consultant)
                    <option value="{{$consultant->id}}">{{$consultant->name}} {{$consultant->lastname}}</option>
                @endforeach  
            </select>
            <br>
            <label for="deliveredByConsultant${i}" class="me-3">Consulente erogatore</label>
            <select name="commissions[${i}][delivered_by]" id="deliveredByConsultant${i}" class="mb-3">
                <option value="">Nessuno</option>
                @foreach($consultants as $consultant)
                    <option value="{{$consultant->id}}">{{$consultant->name}} {{$consultant->lastname}}</option>
                @endforeach  
            </select>
        `;
            commissionFormsContainer.appendChild(commissionForm);
        }
    });
</script>
@endpush


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
</style>