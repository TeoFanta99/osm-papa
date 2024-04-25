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
        <input type="date" name="invoice_date" id="invoice_date">
        <br><br><br><br><br>
        {{-- <label for="service">Servizio</label>
        <br>
        <select name="service" id="service">
            @foreach ($services as $service)
            <option value="{{$service->id}}" data-price="{{$service->price}} €">{{$service ->name}}</option>
            @endforeach
        </select>
        <input type="text" id="price" name="price" value="{{$services[0]->price}} €"
            style="border: 1px solid black; border-radius: 5px;">
        <br><br> --}}

        <button id="addServiceBtn">AGGIUNGI SERVIZIO</button><br><br>
        <table>
            <thead>
                <tr>
                    <th class="border border-dark">Servizio</th>
                    <th class="border border-dark">Quantità</th>
                    <th class="border border-dark">Prezzo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-dark">
                        <select name="service" id="service" style="background: white; border: 1px solid black">
                            @foreach ($services as $service)
                            <option value="{{$service->id}}" data-price="{{$service->price}}">{{$service ->name}}
                            </option>
                            @endforeach
                        </select>

                    </td>
                    <td class="border border-dark">
                        <input type="number" class="quantity" name="quantity" value="1" min="1"
                            onchange="updatePrice(this)">
                    </td>
                    <td class="border border-dark">
                        <input type="number" class="price" name="price" value="{{$services[0]->price}}"
                            style="border: 1px solid black; border-radius: 5px;">
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
    // document.getElementById('service').addEventListener('change', function() {
    //     let selectedOption = this.options[this.selectedIndex];
    //     let price = selectedOption.getAttribute('data-price');
    //     document.getElementById('price').value = price;
    // });

    document.getElementById('addServiceBtn').addEventListener('click', function(event) {
        event.preventDefault();
        let table = document.querySelector('table');
        let tbody = table.querySelector('tbody');
        let clone = tbody.rows[0].cloneNode(true);
        tbody.appendChild(clone);
    });

    document.querySelectorAll('.quantity').forEach(input => {
        input.addEventListener('change', function() {
            let selectedOption = this.parentNode.previousElementSibling.querySelector('option:checked');
            updatePrice(selectedOption);
        });
    });

    function updatePrice(selectedOption) {
        let quantityInput = document.querySelector('.quantity');
        let quantity = quantityInput.value;

        if (selectedOption) {
            let price = selectedOption.getAttribute('data-price');
            let totalPrice = quantity * price;

            // Imposta il valore direttamente nell'input del prezzo
            quantityInput.parentNode.nextElementSibling.querySelector('.price').value = totalPrice;
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

    th {
        padding: 12px 20px;
        background-color: gray;
        color: white;
    }

    td {
        padding: 15px;
    }
</style>