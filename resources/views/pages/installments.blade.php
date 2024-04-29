@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card card">
                <h3>Crea una nuova rata</h3>

                <div id="new_installment_form">
                    <form method="POST" action="{{route('store.installments')}}" enctype="multipart/form-data">

                        @csrf
                        @method("POST")

                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                        <label for="amount">Totale rata</label>
                        <input type="number" name="amount" id="amount">
                        <br>

                        <label for="expire_date">Data di scadenza</label>
                        <input type="date" name="expire_date" id="expire_date">
                        <br>

                        <label for="paid">È stata pagata?</label>
                        <select name="paid" id="paid">
                            <option value="0">No</option>
                            <option value="1">Sì</option>
                        </select>
                        <br>

                        <input class="mt-4" type="submit" value="Crea">
                    </form>
                </div>


                <button class="ms_button" style="max-width: 150px">
                    <a href="{{route('show.invoice', $invoice->id)}}">TORNA INDIETRO</a>
                </button>



            </div>
        </div>
    </div>
</div>

@endsection



<style scoped lang="scss">
    .container_macro {

        display: flex;
        justify-content: center;

        .section-container {
            width: 90%;
            margin-top: 100px;
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

                input {
                    width: 30%;
                }

                .installment-card {
                    border: 1px solid green;
                    border-radius: 15px;
                    cursor: pointer;
                }
            }
        }


    }
</style>