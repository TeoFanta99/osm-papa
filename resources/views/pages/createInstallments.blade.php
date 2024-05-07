@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card card">
                <h3 class="mb-5">Crea una nuova rata</h3>

                <div id="new_installment_form">

                    <form method="POST" action="{{route('store.installments')}}" enctype="multipart/form-data">
                        @csrf
                        @method("POST")

                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                        <div style="width: 50%; border: 1px solid black">

                            <span for="amount" class="me-3">Totale rata</span>
                            <input type="number" name="amount" id="amount" class="mb-3"><br><br>


                            <span for="expire_date" class="me-3">Data di scadenza</span>
                            <input type="date" name="expire_date" id="expire_date" class="mb-3"><br><br>


                            <span for="paid" class="me-3">È stata pagata?</span>

                            <select name="paid" id="paid" class="mb-3">
                                <option value="0">No</option>
                                <option value="1">Sì</option>
                            </select>


                        </div>


                        <input class="mt-5 createBtn" type="submit" value="CREA">
                    </form>
                </div>

                <a class="comeBackBtn" href="{{route('show.invoice', $invoice->id)}}">
                    <button class="ms_button">Indietro</button>
                </a>


            </div>
        </div>
    </div>
</div>

@endsection



@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleziona l'elemento select
        let selectElement = document.getElementById('numbersOfInstallments');

        // Aggiungi un listener per l'evento change
        selectElement.addEventListener('change', function(event) {
            // Leggi il valore selezionato
            let selectedValue = event.target.value;

            // Stampa il valore selezionato in console
            console.log(selectedValue);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Hello World');
    });

</script>
@endpush



<style>
    .container_macro {

        display: flex;
        justify-content: center;

        .section-container {
            width: 90%;
            margin-top: 20px;
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;


            .ms_card {
                width: 90%;
                min-height: 200px;
                border: 1px solid black;
                padding: 30px;

                .invoice-number-style {
                    font-size: 30px;
                    text-align-last: right;
                    margin-right: 10px;
                }

                .paid-style {
                    margin-bottom: 30px;
                }

                .installment-card {
                    border: 1px solid green;
                    border-radius: 15px;
                    cursor: pointer;
                }
            }
        }
    }

    .createBtn {
        width: 150px;
        background-color: #4a6da7;
        border: none;
        padding: 10px;
        color: white;

    }

    .createBtn:hover {
        background-color: #35517e;
    }

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



    /* MEDIA QUERY */
    @media all and (max-width: 425px) {

        label,
        input,
        select {
            display: block;
        }

        #amount {
            max-width: 75%;
        }
    }
</style>