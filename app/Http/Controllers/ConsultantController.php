<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Level;
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
        $levels = Level :: all();

        return view ('pages.newConsultant', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'level_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $path = public_path('consultants.json');

        $consultants = [];
        if (File::exists($path)) {
            $consultants = json_decode(File::get($path), true);
        }

        // aggiungo il nuovo consulente all'array
        $consultants[] = $validatedData;

        // scrivo il nuovo contenuto nel file JSON
        File::put($path, json_encode($consultants, JSON_PRETTY_PRINT));

        // Redirect alla pagina dei consulenti
        return redirect()->route('pages.consultants');
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

        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $currentDate = Carbon::now()->endOfDay();


        // 1° e ultimo giorno del mese corrente
        $currentStartOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $currentEndOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // campi input "dal" - "al"
        $inputStart = $request -> input('inputStartDate', $currentStartOfMonth);
        $inputEnd = $request -> input('inputEndDate', $currentEndOfMonth);


        // valori per l'h2
        $startDateFormatted = Carbon :: parse($inputStart)->format('d/m/Y');
        $endDateFormatted = Carbon :: parse($inputEnd)->format('d/m/Y');

        
        // somme incassate nel mese corrente
        $currentMonthCashed = 0;
        foreach ($installments as $installment) {
            if ($installment->paid && 
            $installment->updated_at >= $currentStartOfMonth && 
            $installment->updated_at <= $currentEndOfMonth) {
                $currentMonthCashed += $installment->amount;
            }
        }

        // somme incassate nel periodo filtrato
        $filteredDateCashed = 0;
        foreach ($installments as $installment) {
            if ($installment->paid &&
                $installment->updated_at >= $inputStart &&
                $installment->updated_at <= $inputEnd) {
                $filteredDateCashed += $installment->amount;
            }
        }




        // somme da incassare
        $notCashed = 0;
        foreach ($installments as $installment) {
            if ($installment->paid == false && $installment->expire_date <= $currentEndOfMonth) {
                $notCashed += $installment->amount;
            }
        }

        // somme da incassare nel periodo filtrato
        $filteredDateNotCashed = 0;
        foreach ($installments as $installment) {
            if ($installment->paid == false &&
                $installment->expire_date >= $inputStart &&
                $installment->expire_date <= $inputEnd) {
                $filteredDateNotCashed += $installment->amount;
            }
        }



        // provvigioni maturate
        $vssCommissionsCashed = 0;
        foreach ($vssCommissions as $vssCommission) {
            if ($vssCommission->servicePerInstallment->installment->paid &&
            $vssCommission->servicePerInstallment->installment->updated_at >= $currentStartOfMonth) {
                $vssCommissionsCashed += $vssCommission->value;
            }
        }
        $vsdCommissionsCashed = 0;
        foreach ($vsdCommissions as $vsdCommission) {
            if ($vsdCommission->servicePerInstallment->installment->paid &&
            $vsdCommission->servicePerInstallment->installment->updated_at >= $currentStartOfMonth) {
                $vsdCommissionsCashed += $vsdCommission->value;
            }
        }
        $commissionsCashed = $vssCommissionsCashed + $vsdCommissionsCashed;


        // provvigioni maturate nel periodo filtrato
        $filteredDateVssCommissionsCashed = 0;
        foreach ($vssCommissions as $vssCommission) {
            if ($vssCommission->servicePerInstallment->installment->paid &&
            $vssCommission->servicePerInstallment->installment->updated_at >= $inputStart &&
            $vssCommission->servicePerInstallment->installment->updated_at <= $inputEnd) {
                $filteredDateVssCommissionsCashed += $vssCommission->value;
            }
        }
        $filteredDateVsdCommissionsCashed = 0;
        foreach ($vsdCommissions as $vsdCommission) {
            if ($vsdCommission->servicePerInstallment->installment->paid &&
            $vsdCommission->servicePerInstallment->installment->updated_at >= $inputStart && 
            $vsdCommission->servicePerInstallment->installment->updated_at <= $inputEnd) {
                $filteredDateVsdCommissionsCashed += $vsdCommission->value;
            }
        }
        $filteredDateCommissionsCashed = $filteredDateVssCommissionsCashed + $filteredDateVsdCommissionsCashed;



        // provvigioni da maturare
        $vssCommissionsNotCashed = 0;
        foreach ($vssCommissions as $vssCommission) {
            if ($vssCommission->servicePerInstallment->installment->paid == false &&
            $vssCommission->servicePerInstallment->installment->expire_date <= $currentEndOfMonth) {
                $vssCommissionsNotCashed += $vssCommission->value;
            }
        }
        $vsdCommissionsNotCashed = 0;
        foreach ($vsdCommissions as $vsdCommission) {
            if ($vsdCommission->servicePerInstallment->installment->paid == false &&
            $vsdCommission->servicePerInstallment->installment->expire_date <= $currentEndOfMonth) {
                $vsdCommissionsNotCashed += $vsdCommission->value;
            }
        }
        $commissionsNotCashed = $vssCommissionsNotCashed + $vsdCommissionsNotCashed;
       
        
        // provvigioni da maturare nel periodo filtrato
        $filteredDateVssCommissionsNotCashed = 0;
        foreach ($vssCommissions as $vssCommission) {
            if ($vssCommission->servicePerInstallment->installment->paid == false &&
            $vssCommission->servicePerInstallment->installment->expire_date <= $inputStart &&
            $vssCommission->servicePerInstallment->installment->expire_date <= $inputEnd) {
                $filteredDateVssCommissionsNotCashed += $vssCommission->value;
            }
        }
        $filteredDateVsdCommissionsNotCashed = 0;
        foreach ($vsdCommissions as $vsdCommission) {
            if ($vsdCommission->servicePerInstallment->installment->paid == false &&
            $vsdCommission->servicePerInstallment->installment->expire_date <= $inputStart &&
            $vsdCommission->servicePerInstallment->installment->expire_date <= $inputEnd) {
                $filteredDateVsdCommissionsNotCashed += $vsdCommission->value;
            }
        }
        $filteredDateCommissionsNotCashed = $filteredDateVssCommissionsNotCashed + $filteredDateVsdCommissionsNotCashed;


        return view('pages.consultant', compact('consultant', 'clients', 'currentMonth', 'currentYear', 'installments','currentStartOfMonth', 'currentEndOfMonth', 'inputStart', 'inputEnd', 'startDateFormatted', 'endDateFormatted', 'currentMonthCashed', 'notCashed', 'commissionsCashed', 'commissionsNotCashed', 'filteredDateCashed', 'filteredDateNotCashed', 'filteredDateCommissionsCashed', 'filteredDateCommissionsNotCashed'));
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
