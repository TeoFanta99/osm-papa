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
        $data = $request -> all();
        // Creazione della nuova fattura
        $invoice = new Invoice();
        $invoice -> client_id = $data['client'];
        $invoice -> save();

        
        $totalPrice = 0;
        $serviceQuantities = [];

        // Ciclo sui servizi venduti per creare i ServiceSold
        foreach ($request->services as $serviceId) {

            $totalQuantity = 0;

            // Aggiorna il totale delle quantità
            $totalQuantity += $data['services_quantity'];

            // Trova il servizio dal suo ID
            $serviceModel = Service :: find($serviceId);

            // valore dell'input 'price'
            $price = $data['price'];

            // Calcola il prezzo per ServiceSold
            $pricePerUnit = $price / $totalQuantity;
            

            // Crea un record ServiceSold per ogni servizio venduto
            for ($i = 0; $i < $totalQuantity; $i++) {
                $serviceSold = new ServiceSold();
                $serviceSold->invoice_id = $invoice->id;
                $serviceSold->service_id = $serviceModel->id;
                $serviceSold->price = $pricePerUnit;
                $serviceSold->issue_date = $data['invoice_date'];
                $serviceSold->save();

                // aggiorna il prezzo sommando tutti i serviceSold
                $totalPrice += $pricePerUnit;
            }
        }
        // Aggiorna la quantità totale nell'invoice
        $invoice->services_quantity = $totalQuantity;

        // aggiorna il valore 'price' della invoice
        $invoice->price = $totalPrice;
        // dd($totalQuantity);
        $invoice->save();
        


        // Creazione della nuova rata associata alla fattura
        $installment = new Installment();
        $installment->invoice_id = $invoice->id; 
        $installment->amount = $request->price; 
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

        return view ('pages.invoice', compact('invoice', 'installments'));
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
