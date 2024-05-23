<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Consultant;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Installment;
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
    public function show(Request $request, $id)
    {
        $consultant = Consultant :: find($id);
        $clients = Client :: where('consultant_id', $consultant->id)->get();
        $vssCommissions = VssCommission :: where('consultant_id', $consultant->id)->get();
        $vsdCommissions = VsdCommission :: where('consultant_id', $consultant->id)->get();
        $invoices = Invoice::whereIn('client_id', $clients->pluck('id')->toArray())->get();
        $installments = Installment::whereIn('invoice_id', $invoices->pluck('id')->toArray())->get();
        $installmentsCollection = collect($installments);

        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $currentDate = Carbon::now()->endOfDay();
        

        // GESTIONE DEI PARAMETRI INSERITI DALL'UTENTE
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('d-m-Y'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('d-m-Y'));

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate)->endOfDay();
        } else {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfMonth();
        }

        // INFORMAZIONI DAL PRIMO GIORNO DEL MESE AD OGGI
        $currentMonthFirstDay = Carbon::now()->startOfMonth()->startOfDay()->format('d-m-Y');

        $installmentsFromCurrentMonth = $installmentsCollection->filter(function ($installment) use ($currentMonthFirstDay, $currentDate) {
            $updatedAt = Carbon::parse($installment->updated_at);
            return $updatedAt >= $currentMonthFirstDay && $updatedAt <= $currentDate;
        });
        $totalInstallmentsCurrentMonth = $installmentsFromCurrentMonth->sum('amount');


        // Recupera le rate il cui updated_at sia compreso tra startDate e oggi
        $installmentsWithinDateRange = Installment::whereIn('invoice_id', $invoices->pluck('id')->toArray())
        ->whereBetween('updated_at', [$currentMonthFirstDay, Carbon::now()])
        ->get();
        $totalInstallmentsPrice = $installmentsWithinDateRange->sum('amount');


        // VARIABILI CHE CAMBIANO A SECONDA DEL PERIODO INSERITO
        $vssPaid = $vssCommissions->filter(function ($commission) use ($startDate, $endDate) {
            $updatedAt = Carbon::parse($commission->servicePerInstallment->installment->updated_at);
            return  $commission->servicePerInstallment->installment->paid &&
                    $updatedAt->between($startDate, $endDate);
        })->sum('value');
    
        $vsdPaid = $vsdCommissions->filter(function ($commission) use ($startDate, $endDate) {
            $updatedAt = Carbon::parse($commission->servicePerInstallment->installment->updated_at);
            return  $commission->servicePerInstallment->installment->paid &&
                    $updatedAt->between($startDate, $endDate);
        })->sum('value');
    
        $vssUnpaid = $vssCommissions->filter(function ($commission) use ($startDate, $endDate) {
            $expireDate = Carbon::parse($commission->servicePerInstallment->installment->expire_date);
            return  !$commission->servicePerInstallment->installment->paid &&
                    $expireDate->between($startDate, $endDate);
        })->sum('value');
    
        $vsdUnpaid = $vsdCommissions->filter(function ($commission) use ($startDate, $endDate) {
            $expireDate = Carbon::parse($commission->servicePerInstallment->installment->expire_date);
            return  !$commission->servicePerInstallment->installment->paid &&
                    $expireDate->between($startDate, $endDate);
        })->sum('value');

        $commissionsPaid = $vssPaid + $vsdPaid;
        $commissionsNotPaid = $vssUnpaid + $vsdUnpaid;


        return view('pages.consultant', compact('consultant', 'clients', 'currentMonth', 'currentYear', 'commissionsPaid', 'commissionsNotPaid', 'startDate', 'endDate', 'installments', 'totalInstallmentsPrice', 'currentMonthFirstDay', 'totalInstallmentsCurrentMonth'));
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
