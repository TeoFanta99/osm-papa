<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Installment;
use App\Models\Invoice;

class InstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $invoice = Invoice::find($id);
        $installment = Installment :: find($id);
        $installments = Installment::where('invoice_id', $id)->get();

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
            'invoice_id' => 'required|numeric',
        ]);

        // recupera l'ID dell'invoice (invoice_id) dalla richiesta
        $invoiceID = $request->invoice_id;

        // crea nuova rata
        $installment = new Installment();
        $installment->amount = $request->amount;
        $installment->expire_date = $request->expire_date;
        $installment->paid = $request->paid;
        $installment->invoice_id = $invoiceID;
        
        $installment->save();
        
        // Redirect alla pagina specifica della fattura
        return redirect()->route('show.invoice', $invoiceID);

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
        $installmentsData = $request->input('installments');

        foreach ($installmentsData as $installmentId => $data) {
        $installment = Installment::findOrFail($installmentId);

            $installment->update([
                'amount' => $data['amount'],
                'expire_date' => $data['expire_date'],
                'paid' => $data['paid'],
            ]);
        }

        $invoice = Invoice::findOrFail($id);

        return redirect()->route('show.invoice', $invoice->id);
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
