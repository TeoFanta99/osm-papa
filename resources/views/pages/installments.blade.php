@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="invoice-container">
            <div class="ms_card card">
                <h2 class="mb-4">GESTIONE DELLE RATE</h2>

                <ul>
                    @foreach ($installments as $installment)
                    <li>
                        {{$installment->amount}}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection


<style scoped lang="scss">
    .container_macro {

        display: flex;
        justify-content: center;

        .invoice-container {
            width: 80%;
            margin-top: 100px;
            margin-bottom: 40px;
            display: flex;
            justify-content: center;

            .ms_card {
                width: 80%;
                min-height: 500px;
                border: 1px solid black;

                .invoice-number-style {
                    font-size: 30px;
                    text-align-last: right;
                    margin-right: 10px;
                }

                .deliver-style {
                    margin-bottom: 30px;
                }
            }
        }
    }
</style>