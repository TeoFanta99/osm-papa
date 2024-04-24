<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Installment;
use App\Models\ClientService;

class InstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $invoice = ClientService::find($id);
        $installment = Installment :: find($id);
        $installments = Installment::where('client_service_id', $id)->get();

        return view('pages.installments', compact('installments', 'invoice', 'installment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valida i dati della richiesta
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'expire_date' => 'required|date',
            'paid' => 'required|boolean',
            'client_service_id' => 'required|numeric',
        ]);

        // recupera l'ID dell'invoice (client_service_id) dalla richiesta
        $clientServiceId = $request->client_service_id;

        // crea nuova rata
        $installment = new Installment();
        $installment->amount = $request->amount;
        $installment->expire_date = $request->expire_date;
        $installment->paid = $request->paid;
        $installment->client_service_id = $clientServiceId;
        
        $installment->save();
        
        // Redirect alla pagina specifica della fattura
        return redirect()->route('show.invoice', $clientServiceId);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $invoice = ClientService::find($id);
        
        $installments = Installment::where('client_service_id', $invoice->id)->get();

        // Flag per verificare se tutte le rate sono pagate
        $allInstallmentsPaid = true;
        
        foreach ($installments as $installment) {
            $data = [
                'amount' => $request->input('amount_' . $installment->id),
                'expire_date' => $request->input('expire_date_' . $installment->id),
                'paid' => $request->input('paid_' . $installment->id),
            ];
            
            $installment->update($data);

            // Controlla se la rata corrente è pagata
            if (!$installment->paid) {
                $allInstallmentsPaid = false;
            }
        }

        
        // Se tutte le rate sono pagate, imposta 'paid' su 'true' per la fattura
        if ($allInstallmentsPaid) {
            $invoice->paid = true;
        } else {
            $invoice->paid = false;
        }

        $invoice->save();
        
        $invoiceID = $invoice->id;

        return redirect()->route('show.invoice', $invoiceID);
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
