<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Consultant;
use App\Models\Client;
use App\Models\Service;
use App\Models\ServiceSold;
use App\Models\Installment;
use App\Models\ServicePerInstallment;
use App\Models\VssCommission;
use App\Models\VsdCommission;

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
        $invoices = Invoice::orderBy('id', 'desc')->get();
        $consultants = Consultant::all();
        $servicesSold = ServiceSold::all();
        $services = Service::all();

        return view('invoices', compact('invoices', 'consultants', 'servicesSold', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $consultants = Consultant::all();
        $clients = Client::all();
        $services = Service::all();

        return view('newInvoice', compact('consultants', 'clients', 'services'));
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

        // crea nuova fattura
        $invoice = new Invoice();
        $invoice->client_id = $data['client'];
        $invoice->invoice_date = $request->invoice_date;
        $invoice->paid = false;
        $invoice->save();

        $consultantId = $invoice->client->consultant_id;
        $consultant = Consultant::find($consultantId);

        $totalQuantity = 0;
        $totalPrice = 0;
        $servicesData = [];
        $servicesSold = [];

        // ciclo per ogni servizio presente
        foreach ($request->services_quantity as $index => $quantity) {

            $pricePerUnit = $request->total_price[$index] / $quantity;
            $serviceId = $request->service_id[$index];
            for ($x = 0; $x < $quantity; $x++) {

                $serviceSold = new ServiceSold();
                $serviceSold->service_id = $serviceId;
                $serviceSold->invoice_id = $invoice->id;
                $serviceSold->price = $pricePerUnit;
                $serviceSold->issue_date = $request->invoice_date;
                $serviceSold->save();
                array_push($servicesSold, $serviceSold);

                $totalPrice += $pricePerUnit;
            }
        }

        $numberOfInstallments = $request->numberOfInstallments;
        $installments = [];

        for ($i = 0; $i < $numberOfInstallments; $i++) {

            $installment = new Installment();
            $installment->paid = false;
            $installment->invoice_id = $invoice->id;
            $installment->amount = $request->amount[$i];
            $installment->expire_date = $request->expire_date[$i];
            $installment->save();
            $installments[] = $installment; // stessa cosa di array push

        };
        
        foreach ($servicesSold as $serviceSold) {
            foreach ($installments as $installment) {
                $servicePerInstallment = new ServicePerInstallment();
                $servicePerInstallment->service_sold_id = $serviceSold->id;
                $servicePerInstallment->installment_id = $installment->id;
                $servicePerInstallment->price = $serviceSold->price / $numberOfInstallments;
                $servicePerInstallment->save();

                $vssCommission = new VssCommission();
                $vssCommission->service_per_installment_id = $servicePerInstallment->id;
                $vssCommission->consultant_id = $consultantId;
                $vssCommission->value = $servicePerInstallment->price * 0.15;
                $vssCommission->save();
                
                $vsdCommission = new VsdCommission();
                $vsdCommission->service_per_installment_id = $servicePerInstallment->id;
                $vsdCommission->consultant_id = $consultantId;
                $vsdCommission->value = $servicePerInstallment->price * ($consultant->level_id == 2 ? 0.25 : 0.2);
                $vsdCommission->save();
            }
        }

        $invoice->price = $totalPrice;
        $invoice->save();


        return redirect()->route('index.invoices', $invoice->id)->with('success', 'Fattura e rata create con successo!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::find($id);
        $servicesSold = ServiceSold::where('invoice_id', $invoice->id)->get();

        $installments = Installment::where('invoice_id', $invoice->id)->get();;
        $consultants = Consultant::all();
        $client = $invoice->client;
        $numberOfInstallments = $installments->count();
        // $servicesPerInstallment = ServicePerInstallment::where('installment_id', $installment)

        return view('pages.invoice', compact('invoice', 'installments', 'servicesSold', 'client', 'consultants', 'numberOfInstallments'));
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
