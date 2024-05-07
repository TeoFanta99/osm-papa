@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <h2>Creazione fattura</h2>
    <form method="POST" enctype="multipart/form-data">

        @csrf
        @method("POST")

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
        <br><br><br><br><br>


        <button type="button" id="addServiceBtn" onclick="addServiceRow()">Aggiungi</button><br><br>

        <table class="table w-100">
            <thead class="d-block">
                <tr>
                    <th class="border border-dark" style="width: 500px; background: gray; color: white">Servizio
                    </th>
                    <th class="border border-dark" style="width: 100px; background: gray; color: white">Quantità
                    </th>
                    <th class="border border-dark" style="width: 150px; background: gray; color: white">Prezzo unitario
                        (€)
                    </th>
                    <th class="border border-dark" style="width: 150px; background: gray; color: white">Prezzo totale
                        (€)
                    </th>
                </tr>
            </thead>
            <tbody class="services-container d-block">
                <tr class="service-row">
                    <td class="border border-dark">
                        <select class="service-select" name="service_id[]"
                            onchange="updateUnitPrice(this); updateTotalPrice(this)">
                            <option value="" disabled selected>Scegli un servizio</option>
                            @foreach ($services as $service)
                            <option value="{{$service->id}}" data-price="{{$service->price}}">{{$service->name}}
                            </option>
                            @endforeach
                        </select>
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
                        <input disabled type="number" class="totalPrice-input" name="price[]" value="" step="0.01">
                    </td>
                </tr>
            </tbody>
        </table>

        <br><br><br>
        <input class="create-btn" type="submit" value="Crea">
    </form>
</div>

@endsection

{{-- pusha lo script fino all'app.blade, che se lo recupererà con @stack --}}
@push('scripts')

<script>
    let serviceIndex = 1;
    
    function addServiceRow() {
        let newRow = document.createElement('tr');
        newRow.classList.add('service-row');

        newRow.innerHTML = `
            <td class="border border-dark">
                <select class="service-select" name="service_id[${serviceIndex}]" onchange="updatePrice(this)">
                    @foreach ($services as $service)
                    <option value="{{$service->id}}" data-price="{{$service->price}}">{{$service->name}}</option>
                    @endforeach
                </select>
            </td>
            <td class="border border-dark">
                <input type="hidden" class="service-price" name="services_price[${serviceIndex}]" value="{{$service->price}}">
                <input type="number" class="services_quantity" name="services_quantity[]" value="1" min="1" onchange="updatePrice(this)">
            </td>
            <td class="border border-dark">
                <input type="number" class="price-input" name="price[]" value="{{$services[0]->price}}" step="0.01">
            </td>`;

        document.querySelector('.services-container').appendChild(newRow);
        serviceIndex++;
    }

    function updateUnitPrice(element) {
        let row = element.closest('.service-row');
        let serviceSelect = row.querySelector('.service-select');
        let selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        let priceInput = row.querySelector('.price-input');

        if (selectedOption) {
            let price = parseFloat(selectedOption.getAttribute('data-price'));
            priceInput.value = price.toFixed(2);
        }      
    }

    function updateTotalPrice(element) {
        let row = element.closest('.service-row');
        let quantityInput = row.querySelector('.services_quantity');
        let priceInput = row.querySelector('.price-input');
        let totalPriceInput = row.querySelector('.totalPrice-input');

        let quantity = parseInt(quantityInput.value);
        let price = parseFloat(priceInput.value);

        if(!isNaN(quantity) && !isNaN(price)) {
            let totalPrice = quantity * price;
            totalPriceInput.value = totalPrice.toFixed(2);
        } else {
            totalPriceInput.value = 0;
        }
    }
</script>
@endpush


<style scoped lang="scss">
    .main-content {
        padding: 30px;
    }

    .create-btn {
        padding: 20px 50px;
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
        /* font-size: 25px; */
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

    th {}
</style>