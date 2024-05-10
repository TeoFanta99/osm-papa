@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">

    <form action="{{route('store.invoice')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("POST")

        <div class="step1">
            <h3>STEP 1: Inserisci i dati della fattura</h3>
            <label for="client">Cliente</label>
            <br>
            <select name="client" id="client">
                @php
                $clients = $clients->sortBy('name');
                @endphp
                @foreach ($clients as $client)
                <option value="{{$client->id}}">{{$client ->name}}</option>
                @endforeach
            </select><br><br>
            <label for="invoice_date">Data fattura</label><br>
            <input type="date" name="invoice_date" id="invoice_date" value="{{ date('Y-m-d') }}">
            <br><br><br>


            <button type="button" id="addServiceBtn" onclick="addServiceRow()">Aggiungi</button><br><br>

            <table class="table w-100">
                <thead class="d-block">
                    <tr>
                        <th class="border border-dark" style="width: 500px; background: gray; color: white">Servizio
                        </th>
                        <th class="border border-dark" style="width: 100px; background: gray; color: white">Quantità
                        </th>
                        <th class="border border-dark" style="width: 150px; background: gray; color: white">Prezzo
                            unitario
                            (€)
                        </th>
                        <th class="border border-dark" style="width: 150px; background: gray; color: white">Prezzo
                            totale
                            (€)
                        </th>
                    </tr>
                </thead>
                <tbody class="services-container d-block">
                    <tr class="service-row">
                        <td class="border border-dark" style="width: 500px">
                            <input type="text" class="service-input"
                                oninput="filterServices(this); updateUnitPrice(this)" list="serviceList"
                                placeholder="Cerca servizio" style="width: 75%">
                            <input type="hidden" class="service-id" name="service_id[]" value="">
                            <datalist id="serviceList">
                                @foreach ($services as $service)
                                <option value="{{$service->name}}" data-price="{{$service->price}}"
                                    data-id="{{$service->id}}">
                                    @endforeach
                            </datalist>
                        </td>

                        <td class="border border-dark">
                            <input type="hidden" class="service-price" name="services_price[]"
                                value="{{$service->price}}">
                            <input type="number" class="services_quantity" name="services_quantity[]" value="1" min="1"
                                onchange="updateTotalPrice(this)">
                        </td>
                        <td class="border border-dark">
                            <input type="number" class="price-input" name="price[]" value="" step="0.01"
                                onchange="updateTotalPrice(this)">
                        </td>
                        <td class="border border-dark">
                            <input disabled type="number" class="totalPrice-input" name="totalPrice[]" value=""
                                step="0.01">
                            <input type="hidden" class="total-price-hidden" name="total_price[]" value="">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> <br> <br>

        <div class="step2">
            <h3>STEP 2: Scegli il numero di rate</h3>
            <label for="">Quante rate vuoi creare?</label>
            <select name="numberOfInstallments" id="numberOfInstallments" onchange="manageNumOfInstallments()">
                @for ($i = 1; $i < 11; $i++) <option value="{{$i}}">
                    {{$i}}
                    </option>
                    @endfor
            </select> <br><br>
            <div id="new_installment_form">

            </div>
        </div>

        <br><br>
        {{-- <a class="comeBackBtn mx-2" href="{{route('welcome')}}">
            INDIETRO
        </a> --}}
        <input class="create-btn" type="submit" value="CREA">
    </form>

</div>

@endsection

{{-- pusha lo script fino all'app.blade, che se lo recupererà con @stack --}}
@push('scripts')

<script>
    window.onload = function() {
    // Recupera tutti gli elementi con la classe .service-input e chiama updateTotalPrice per ciascuno di essi
    let serviceInputs = document.querySelectorAll('.service-input');
    serviceInputs.forEach(function(input) {
        updateTotalPrice(input);
    });
};
    document.addEventListener("DOMContentLoaded", (event) => {
        manageNumOfInstallments();
    });

    let serviceIndex = 1;
    
    function addServiceRow() {
        let newRow = document.createElement('tr');
        newRow.classList.add('service-row');

        newRow.innerHTML = `
        <td class="border border-dark" style="width: 500px">
                        <input type="text" class="service-input" oninput="filterServices(this); updateUnitPrice(this)"
                            list="serviceList" placeholder="Cerca servizio" style="width: 75%">
                        <input type="hidden" class="service-id" name="service_id[]" value="">
                        <datalist id="serviceList">
                            @foreach ($services as $service)
                            <option value="{{$service->name}}" data-price="{{$service->price}}"
                                data-id="{{$service->id}}">
                                @endforeach
                        </datalist>
                    </td>

                    <td class="border border-dark">
                        <input type="hidden" class="service-price" name="services_price[]" value="{{$service->price}}">
                        <input type="number" class="services_quantity" name="services_quantity[]" value="1" min="1"
                            onchange="updateTotalPrice(this)">
                    </td>
                    <td class="border border-dark">
                        <input type="number" class="price-input" name="price[]" value="" step="0.01"
                            onchange="updateTotalPrice(this)">
                    </td>
                    <td class="border border-dark">
                        <input disabled type="number" class="totalPrice-input" name="totalPrice[]"
                            value="" step="0.01">
                        <input type="hidden" class="total-price-hidden" name="total_price[]" value="">
                    </td>`;

        document.querySelector('.services-container').appendChild(newRow);
        serviceIndex++;
    }

    function updateUnitPrice(element) {
        let row = element.closest('.service-row');
        let serviceSelect = row.querySelector('.service-input');
        let selectedOption = row.querySelector(`option[value="${element.value}"]`);
        let priceInput = row.querySelector('.price-input');
        let serviceIdInput = row.querySelector('.service-id');

        if (selectedOption) {
            let price = parseFloat(selectedOption.getAttribute('data-price'));
            priceInput.value = price.toFixed(2);

            let serviceId = selectedOption.getAttribute('data-id');
            serviceIdInput.value = serviceId;
            console.log('ID del servizio selezionato:', serviceId);
        }
        
        updateTotalPrice(row.querySelector('.services_quantity'));
    }

    function updateTotalPrice(element) {
        let row = element.closest('.service-row');
        let quantityInput = row.querySelector('.services_quantity');
        let priceInput = row.querySelector('.price-input');
        let totalPriceInput = row.querySelector('.totalPrice-input');
        let totalPriceHidden = row.querySelector('.total-price-hidden');

        let quantity = parseInt(quantityInput.value);
        let price = parseFloat(priceInput.value);
        let totalPrice = price * quantity;

        if(!isNaN(quantity) && !isNaN(price)) {
            let totalPrice = quantity * price;
            totalPriceInput.value = totalPrice.toFixed(2);
            totalPriceHidden.value = totalPrice.toFixed(2);

        } else {
            totalPriceInput.value = 0;
        }

        manageNumOfInstallments();
    }

    function filterServices(input) {
        let dataList = document.getElementById("serviceList");
        let options = dataList.getElementsByTagName("option");
        let filter = input.value.toLowerCase();

        for (let i = 0; i < options.length; i++) {
            let option = options[i];
            let serviceName = option.value.toLowerCase();
            if (serviceName.indexOf(filter) > -1) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        }
    }
    
    function manageNumOfInstallments() {
        let select = document.getElementById('numberOfInstallments');
        let container = document.getElementById('new_installment_form');
        container.innerHTML = '';

        let numOfInstallments = select.value;

        let invoicePrice = 0;

        let totalPriceInputs = document.querySelectorAll('.totalPrice-input');
        totalPriceInputs.forEach(function(input) {
            invoicePrice += parseFloat(input.value);
        });

        let installmentExpireDate = document.getElementById('invoice_date');
        let installmentPrice = invoicePrice / parseFloat(numOfInstallments);

        let rowDiv = document.createElement('div');
        rowDiv.classList.add('row');

        for (let i = 0; i < numOfInstallments; i++) {
            let colDiv = document.createElement('div');
            colDiv.classList.add('installment-col');
            colDiv.classList.add('col');
            colDiv.classList.add('col-12');
            colDiv.classList.add('col-md-6');
            colDiv.classList.add('col-xl-4');
            
            let div = document.createElement('div');
            div.style.padding = '5px';
            div.style.border = '1px solid black';
            div.innerHTML = `
                <h5>Rata ${i + 1}</h5>
                <span for="amount_${i + 1}" class="me-3">Totale rata</span>
                <input style="width: 35%" type="number" name="amount[]" id="amount_${i + 1}" class="mb-3" required step="0.01" value=""><br><br>

                <span for="expire_date_${i + 1}" class="me-3">Scadenza</span>
                <input type="date" name="expire_date[]" id="expire_date_${i + 1}" class="mb-3" required><br><br>
            `;

            div.querySelector(`#amount_${i + 1}`).value = installmentPrice.toFixed(2);
            div.querySelector(`#expire_date_${i + 1}`).value = installmentExpireDate.value;
                
            colDiv.appendChild(div);
            rowDiv.appendChild(colDiv);
        }

        container.appendChild(rowDiv);
    }

</script>
@endpush


<style scoped lang="scss">
    .main-content {
        padding: 30px;
    }

    .create-btn {
        padding: 18px 50px;
        background-color: rgb(28, 192, 28);
        border: none;
        color: white;
    }

    .create-btn:hover {
        background-color: rgb(97, 255, 97);
    }

    #addServiceBtn {
        background-color: #4a6da7;
        padding: 5px 15px;
        color: white;
        cursor: pointer;
        border: none;
    }

    #addServiceBtn:hover {
        background-color: #35517e;
    }

    .service-select {
        background: white;
        border: 1px solid black;
        max-width: 500px;
        padding: 10px;
        border-radius: 5px;
    }

    .price-input,
    .totalPrice-input {
        border: 1px solid black;
        border-radius: 5px;
        max-width: 150px;
        padding: 10px;
    }

    .services_quantity {
        max-width: 100px;
        padding: 10px;
        border: 1px solid black;
        border-radius: 5px;
    }

    .comeBackBtn {
        background-color: #fc8200;
        border: none;
        width: 150px;
        padding: 20px 30px;
        text-decoration: none;
        text-align: center;
        color: white;
    }

    .comeBackBtn:hover {
        background-color: rgb(255, 197, 90);
    }
</style>