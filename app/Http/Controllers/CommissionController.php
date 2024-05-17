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
    public function edit($installment_id)
    {
        $installment = Installment::findOrFail($installment_id);
        // $commissions = Commission :: where('installment_id', $installment->id)->get();
        $invoice = $installment->invoice;
        $numberOfInstallments = $invoice->installments->count();
        $servicesSold = $invoice->servicesSold;
        $consultants = Consultant :: all();

        return view('pages.editCommissions', compact('installment', 'invoice', 'servicesSold', 'consultants', 'numberOfInstallments'));
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
        $data = $request->all();

        foreach ($data as $serviceSold) {
            dd($data);
        }
        $service = $request->service_id;
        $price = $request->price;
        $soldBy = $request->sold_by;
        $deliveredBy = $request->delivered_by;


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
