<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Installment;
use App\Models\Invoice;
use App\Models\ServicePerInstallment;
use App\Models\VssCommission;
use App\Models\VsdCommission;

class InstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
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
       //
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
        $installmentAmounts = [];

        foreach ($installmentsData as $installmentId => $data) {
        $installment = Installment::findOrFail($installmentId);

            $installment->update([
                // 'amount' => $data['price'],
                'expire_date' => $data['expire_date'],
                'paid' => $data['paid'],
            ]);
        }

        // recupera tutti gli input il cui nome inizia con la prima parola scritta tra parentesi
        $prices = $request->input('prices', []);
        $vssConsultants = $request->input('vss_consultants', []);
        $vsdConsultants = $request->input('vsd_consultants', []);


        foreach ($prices as $servicePerInstallmentId => $price) {
            $servicePerInstallment = ServicePerInstallment::find($servicePerInstallmentId);
            if ($servicePerInstallment) {
                $servicePerInstallment->price = $price;
                $servicePerInstallment->save();

                // recupero l'installment id e lo uso per distinguere il totale di una installment dall'altro
                $installmentId = $servicePerInstallment->installment_id;
                if (!isset($installmentAmounts[$installmentId])) {
                    $installmentAmounts[$installmentId] = 0;
                }
                $installmentAmounts[$installmentId] += $price;


                // aggiorna il consultant_id del vss
                $vssConsultantId = $vssConsultants[$servicePerInstallmentId] ?? null;
                $vssCommission = VssCommission::where('service_per_installment_id', $servicePerInstallmentId)->first();
                if ($vssCommission) {
                    $vssCommission->consultant_id = $vssConsultantId;
                    $vssCommission->save();
                }

                // aggiorna il consultant_id del vsd
                $vsdConsultantId = $vsdConsultants[$servicePerInstallmentId] ?? null;
                $vsdCommission = VsdCommission::where('service_per_installment_id', $servicePerInstallmentId)->first();
                if ($vsdCommission) {
                    $vsdCommission->consultant_id = $vsdConsultantId;
                    $vsdCommission->save();
                }
            }

        }

        foreach ($installmentAmounts as $installmentId => $amount) {
            $installment = Installment::findOrFail($installmentId);
            $installment->amount = $amount;
            $installment->save();
        }
    

        $invoice = Invoice::findOrFail($id);

        // Verifico se tutte le rate sono state pagate e, in caso, aggiorno il valore paid di invoice
        $allInstallmentsPaid = $invoice->installments()->where('paid', false)->count() === 0;
        if ($allInstallmentsPaid) {
            $invoice->paid = true;
            $invoice->save();
        }
        

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
