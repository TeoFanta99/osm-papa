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


        <button type="button" id="addServiceBtn" onclick="addServiceRow()">AGGIUNGI SERVIZIO</button><br><br>
        <div class="container">
            <table class="table w-100">
                <thead class="d-block">
                    <tr>
                        <th class="border border-dark" style="width: 500px; background: gray; color: white">Servizio
                        </th>
                        <th class="border border-dark" style="width: 100px; background: gray; color: white">Quantità
                        </th>
                        <th class="border border-dark" style="width: 150px; background: gray; color: white">Prezzo
                            (€)
                        </th>
                    </tr>
                </thead>
                <tbody class="services-container d-block">
                    <tr class="service-row">
                        <td class="border border-dark">
                            <select class="service-select" name="service_id[]" onchange="updatePrice(this)">
                                @foreach ($services as $service)
                                <option value="{{$service->id}}" data-price="{{$service->price}}">{{$service->name}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="border border-dark">
                            <input type="hidden" class="service-price" name="services_price[]"
                                value="{{$service->price}}">
                            <input type="number" class="services_quantity" name="services_quantity[]" value="1" min="1"
                                onchange="updatePrice(this)">
                        </td>
                        <td class="border border-dark">
                            <input type="number" class="price-input" name="price[]" value="{{$services[0]->price}}"
                                step="0.01">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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

    function updatePrice(element) {
        let row = element.closest('.service-row');
        let serviceSelect = row.querySelector('.service-select');
        let selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        let quantityInput = row.querySelector('.services_quantity');
        let priceInput = row.querySelector('.price-input');

        if (selectedOption) {
            let price = parseFloat(selectedOption.getAttribute('data-price'));
            let quantity = parseInt(quantityInput.value);
            let totalPrice = quantity * price;

            if (!isNaN(totalPrice)) {
                priceInput.value = totalPrice;
            } else {
                priceInput.value = 0;
            }

            let serviceId = selectedOption.value;
            console.log('Service ID:', serviceId);
        }
    }

    console.log();
</script>
@endpush


<style scoped lang="scss">
    .main-content {
        padding: 30px;
    }

    .create-btn {
        padding: 20px 50px;
    }

    #addServiceBtn {
        background-color: #4a6da7;
        padding: 10px;
        color: white;
        cursor: pointer;
        border: none;
        font-size: 13px;
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

    .price-input {
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