@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card card">
                <h3>Cosa vuoi fare?</h3>

                <div class="button_container mb-4">
                    <button class="ms_button" id="edit_installment_btn" onclick="showEditInstallmentForm()">Aggiorna
                        pagamenti / Modifica
                        rate</button>
                    <button class="ms_button" id="new_installment_btn" onclick="showNewInstallmentForm()">Crea una nuova
                        rata</button>
                    <button class="ms_button">
                        <a href="{{route('show.invoice', $invoice->id)}}">TORNA INDIETRO</a>
                    </button>
                </div>

                <div id="new_installment_form" style="display: none">
                    <form method="POST" action="{{route('store.installments')}}" enctype="multipart/form-data">

                        @csrf
                        @method("POST")

                        <input type="hidden" name="client_service_id" value="{{ $invoice->id }}">

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

                <form action="{{route('update.installments', $installment)}}" method="POST" id="edit_installment_form"
                    style="display: none;" class="mb-4">
                    @csrf
                    @method('PUT')

                    @if($installments->isEmpty())
                    <span><b>NESSUNA RATA DISPONIBILE</b></span>

                    @else
                    @foreach ($installments as $installment)
                    <label for="amount_{{ $installment->id }}">Totale rata: </label>
                    <input type="number" name="amount_{{ $installment->id }}" id="amount_{{ $installment->id }}"
                        value="{{$installment->amount}}">
                    <br>
                    <label for="expire_date_{{ $installment->id }}">Data di scadenza: </label>
                    <input type="date" name="expire_date_{{ $installment->id }}" id="expire_date_{{ $installment->id }}"
                        value="{{ date('Y-m-d', strtotime($installment->expire_date)) }}">
                    <br>
                    <label for="paid_{{ $installment->id }}">È stata pagata? </label>
                    <select name="paid_{{ $installment->id }}" id="paid_{{ $installment->id }}">
                        <option value="0" {{ $installment->paid == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $installment->paid == 1 ? 'selected' : '' }}>Sì</option>
                    </select>
                    <br><br><br>

                    @endforeach
                    <input type="submit" value="SALVA">
                    @endif

                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showNewInstallmentForm() {
    let newInstallmentForm = document.getElementById('new_installment_form');
    let editInstallmentForm = document.getElementById('edit_installment_form');

    newInstallmentForm.style.display = 'block';
    editInstallmentForm.style.display = 'none';
    }

    function showEditInstallmentForm() {
    let newInstallmentForm = document.getElementById('new_installment_form');
    let editInstallmentForm = document.getElementById('edit_installment_form');

    newInstallmentForm.style.display = 'none';
    editInstallmentForm.style.display = 'block';
    }


</script>
@endpush

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