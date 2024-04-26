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
            // Ordina i clienti per nome crescente
            $clients = $clients->sortBy('name');
            @endphp
            @foreach ($clients as $client)
            <option value="{{$client->id}}">{{$client ->name}}</option>
            @endforeach
        </select><br><br>
        <label for="invoice_date">Data fattura</label><br>
        <input type="date" name="invoice_date" id="invoice_date" value="{{ date('Y-m-d') }}">
        <br><br><br><br><br>

        <button id="addServiceBtn">AGGIUNGI SERVIZIO</button><br><br>
        <div class="container">
            <table class="table w-100">
                <thead class="d-block">
                    <tr>
                        <th class="border border-dark" style="width: 500px; background: gray; color: white">Servizio
                        </th>
                        <th class="border border-dark" style="width: 100px; background: gray; color: white">Quantità
                        </th>
                        <th class="border border-dark" style="width: 150px; background: gray; color: white">Prezzo (€)
                        </th>
                    </tr>
                </thead>
                <tbody class="d-block">
                    <tr>
                        <td class="border border-dark">
                            <select name="services[]" id="service" onchange="updatePrice()"
                                style="background: white; border: 1px solid black; max-width: 500px; padding: 10px; border-radius: 5px">
                                @foreach ($services as $service)
                                <option value="{{$service->id}}" data-price="{{$service->price}}">{{$service ->name}}
                                </option>
                                @endforeach
                            </select>

                        </td>
                        <td class="border border-dark">
                            <input type="number" class="services_quantity" name="services_quantity" value="1" min="1"
                                onchange="updatePrice()"
                                style="max-width: 100px; padding: 10px; border: 1px solid black; border-radius: 5px">
                        </td>
                        <td class="border border-dark">
                            <input type="number" class="price" name="price" value="{{$services[0]->price}}" step="0.01"
                                style="border: 1px solid black; border-radius: 5px; max-width: 150px; padding: 10px">
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
    document.getElementById('addServiceBtn').addEventListener('click', function(event) {
        event.preventDefault();
        let table = document.querySelector('table');
        let tbody = table.querySelector('tbody');
        let clone = tbody.rows[0].cloneNode(true);
        tbody.appendChild(clone);

        updateEvents();
    });

    function updateEvents() {
        let serviceSelects = document.querySelectorAll('select[name="services[]"]');
        let quantityInputs = document.querySelectorAll('.services_quantity');

        // Aggiungi eventi per il cambio dell'opzione e della quantità
        serviceSelects.forEach(function(select) {
            select.addEventListener('change', updatePrice);
        });

        quantityInputs.forEach(function(input) {
            input.addEventListener('input', updatePrice);
        });
    }

    function updatePrice() {
        let row = this.closest('tr');
        let serviceSelect = row.querySelector('select[name="services[]"]');
        let selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        let quantityInput = row.querySelector('.services_quantity');
        let priceInput = row.querySelector('.price');

        if (selectedOption) {

            let price = parseFloat(selectedOption.getAttribute('data-price'));

            let quantity = parseInt(quantityInput.value);

            let totalPrice = quantity * price;

            priceInput.value = price;

            if (!isNaN(totalPrice)) {
                priceInput.value = totalPrice;
            }
        }
    }

    updateEvents();
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
</style>