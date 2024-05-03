<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Consultant;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\ServiceSold;

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

        $invoices = collect();
        foreach ($clients as $client) {
            $invoices = $invoices->merge(Invoice::where('client_id', $client->id)->get());
        }

        $currentMonth = Carbon::now()->format('m');
        $totalVSD = 0;
        $totalVSS = 0;

        // Totale VSS del mese corrente
        foreach ($invoices as $invoice) {
            if (Carbon::parse($invoice->invoice_date)->format('m') == $currentMonth){
            $totalVSS += $invoice->price;
            }
        }

        // Totale VSD del mese corrente
        foreach ($servicesSold as $serviceSold) {
            if (Carbon::parse($serviceSold->issue_date)->format('m') == $currentMonth && $serviceSold->delivered_by == $consultant->id) {
                $totalVSD += $serviceSold->price;
            }
        }

        return view('pages.consultant', compact('consultant', 'clients', 'invoices', 'servicesSold', 'totalVSD', 'totalVSS', 'currentMonth'));
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
