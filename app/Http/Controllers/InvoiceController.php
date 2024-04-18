<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientService;
use App\Models\Consultant;
use App\Models\Installment;
use App\Models\Client;
use App\Models\Service;


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
        $invoices = ClientService::orderBy('id', 'desc')->get();

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
        $services = Service::all();
        $clients = Client::all();
        $consultants = Consultant::all();

        return view('newInvoice', compact('services', 'clients', 'consultants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request -> all();

        $invoice = new ClientService();
        $invoice->price = $data['price'];
        $invoice->invoice_date = $data['invoice_date'];
        $invoice->sold_by = $data['sold_by'];
        $invoice->delivered_by = $data['delivered_by'];
        $invoice->client_id = $data['client'];
        $invoice->service_id = $data['service'];
        $invoice->paid = false;

        $invoice -> save();
        
        return redirect() -> route('index.invoices', $invoice->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = ClientService::find($id);
        $consultants = Consultant :: all();
        $installments = Installment::where('client_service_id', $id)->get();

        return view ('pages.invoice', compact('invoice', 'consultants', 'installments'));
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
