@extends('layouts.app')
@section('content')


@include('components.sidebar')
<div class="main-content">
    <div class="container_macro">
        <div class="section-container">
            <div class="ms_card card">
                <span>siamo le rate della fattura n. {{$invoice->id}}</span>
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