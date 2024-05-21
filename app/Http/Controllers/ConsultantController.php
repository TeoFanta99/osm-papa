<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Consultant;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\ServiceSold;
use App\Models\VssCommission;
use App\Models\VsdCommission;

class ConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultants = Consultant::all();

        return view('consultants', compact('consultants'));
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
        $consultant = Consultant :: find($id);
        $clients = Client :: where('consultant_id', $consultant->id)->get();
        $servicesSold = ServiceSold :: all();
        $vssCommissions = VssCommission :: where('consultant_id', $consultant->id)->get();
        $vsdCommissions = VsdCommission :: where('consultant_id', $consultant->id)->get();

        $currentMonth = Carbon::now()->format('m');

        // COMMISSIONI PAGATE
        $vssPaid = $vssCommissions->filter(function ($commission) {
            return $commission->servicePerInstallment->installment->paid;
        })->sum('value');

        $vsdPaid = $vsdCommissions->filter(function ($commission) {
            return $commission->servicePerInstallment->installment->paid;
        })->sum('value');


        // COMMISSIONI NON PAGATE
        $vssUnpaid = $vssCommissions->filter(function ($commission) {
            return !$commission->servicePerInstallment->installment->paid;
        })->sum('value');

        $vsdUnpaid = $vsdCommissions->filter(function ($commission) {
            return !$commission->servicePerInstallment->installment->paid;
        })->sum('value');


        $commissionsAlreadyPaid = $vssPaid + $vsdPaid;
        $commissionsNotPaid = $vssUnpaid + $vsdUnpaid;

        return view('pages.consultant', compact('consultant', 'clients', 'servicesSold', 'currentMonth', 'commissionsAlreadyPaid', 'commissionsNotPaid'));
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
