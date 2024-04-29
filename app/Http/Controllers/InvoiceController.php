<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Consultant;
use App\Models\Client;
use App\Models\Service;
use App\Models\ServiceSold;
use App\Models\Installment;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // recupera le fatture e le ordina per id decrescente
        $invoices = Invoice :: orderBy('id', 'desc')->get();

        $consultants = Consultant :: all();

        return view ('invoices', compact('invoices', 'consultants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $consultants = Consultant :: all();
        $clients = Client :: all();
        $services = Service :: all();

        return view ('newInvoice', compact('consultants', 'clients', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $totalQuantity = 0;
        $totalPrice = 0;

        // recupero i dati del cliente e del consulente di riferimento

        // crea nuova fattura
        $invoice = new Invoice();
        $invoice -> client_id = $data['client'];
        $invoice -> invoice_date = $request -> invoice_date;
        $invoice -> paid = false;
        $invoice -> save();

        $consultantId = $invoice->client->consultant_id;

        // ciclo foreach per ogni row di servizi presente
        foreach ($request->services_quantity as $index => $quantity) {

            $pricePerUnit = $request -> price[$index] / $quantity;

            for ($i = 0; $i < $quantity; $i++) {

                $serviceSold = new ServiceSold();
                $serviceSold->service_id = $request->service_id[$index];
                $serviceSold->invoice_id = $invoice->id;
                $serviceSold->price = $pricePerUnit;
                $serviceSold->issue_date = $request->invoice_date;
                $serviceSold->delivered_by = $consultantId;
                $serviceSold->save();

                $totalPrice += $pricePerUnit;
            }

            $totalQuantity += $quantity;

        }

        $invoice->services_quantity = $totalQuantity;
        $invoice->price = $totalPrice;
        $invoice->save();



        // Creazione della nuova rata associata alla fattura
        $installment = new Installment();
        $installment->invoice_id = $invoice->id; 
        $installment->amount = $invoice -> price; 
        $installment->expire_date = $request->invoice_date;
        $installment->paid = false;
        $installment->save();
        
        return redirect() -> route('index.invoices')->with('success', 'Fattura e rata create con successo!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice :: find($id);
        $installments = Installment :: where ('invoice_id', $invoice->id) -> get();
        $servicesSold = ServiceSold :: where ('invoice_id', $invoice->id) -> get();
        $consultants = Consultant :: all();
        
        $client = $invoice->client;
        $consultant_id = $client ? $client->consultant_id : null;
        $services = Service :: all();

        return view ('pages.invoice', compact('invoice', 'installments', 'servicesSold', 'services', 'client', 'consultants', 'consultant_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
