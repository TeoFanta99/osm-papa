<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Installment;
use App\Models\ServiceSold;
use App\Models\Commission;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($installment_id)
    {
        // $consultants = Consultant :: all();

        $installment = Installment::findOrFail($installment_id);
        $invoice = $installment->invoice;
        $servicesSold = ServiceSold::select('service_id')->where('invoice_id', $invoice->id)->groupBy('service_id')->get();
        $consultants = Consultant :: all();

        return view('pages.newCommissions', compact('installment', 'invoice', 'servicesSold', 'consultants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'installment_id' => 'required|exists:installments,id',
        ]);

        $installmentId = $request->installment_id;
        $installment = Installment::findOrFail($installmentId);
        $invoiceId = $installment->invoice->id;

        foreach ($request->commissions as $commissionData) {

            $commission = new Commission();
            $commission->price = $commissionData['price'];
            $commission->installment_id = $installmentId;

            if (isset($commissionData['sold_by'])) {
                $commission->sold_by = $commissionData['sold_by'];
            }
            if (isset($commissionData['delivered_by'])) {
                $commission->delivered_by = $commissionData['delivered_by'];
            }

            $commission -> save();

            if (isset($commissionData['services'])) {
                $commission->services()->attach($commissionData['services']);
            }
        }

        return redirect()->route('show.invoice', $invoiceId);

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
