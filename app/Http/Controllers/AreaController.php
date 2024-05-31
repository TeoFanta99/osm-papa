<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Consultant;
use App\Models\ServiceSold;
use App\Models\Invoice;
use App\Models\Installment;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $consultants = Consultant :: all();
        $servicesSold = ServiceSold :: all();
        $invoices = Invoice :: all();
        $installments = Installment :: all();

        Carbon::setLocale('it'); // Imposta la lingua italiana per Carbon
        $currentDate = Carbon::now();
        $currentDay = Carbon::now()->day;
        $currentMonth = Carbon::now()->month;
        $currentMonthName = Carbon::now()->translatedFormat('F');
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear -1;
        $months = [
            '01' => 'Gennaio', '02' => 'Febbraio', '03' => 'Marzo', '04' => 'Aprile',
            '05' => 'Maggio', '06' => 'Giugno', '07' => 'Luglio', '08' => 'Agosto',
            '09' => 'Settembre', '10' => 'Ottobre', '11' => 'Novembre', '12' => 'Dicembre'
        ];


        // recupero i dati dal filtro, impostando di default il mese corrente
        $monthYear = $request->input('monthYear', $currentYear . '-' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT));

        // recupero mese e anno selezionati
        $selectedMonth = substr($monthYear, 5, 2);
        $selectedYear = substr($monthYear, 0, 4);
        $selectedMonthName = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->translatedFormat('F Y');

        $filteredResults = $filteredInstallments = $installments->filter(function ($installment) use ($selectedMonth, $selectedYear) {
            return $installment->updated_at->format('m') == $selectedMonth && $installment->updated_at->format('Y') == $selectedYear;
        });

        return view ('pages.myArea', compact('consultants', 'servicesSold', 'invoices', 'installments', 'currentDate', 'currentDay', 'currentMonth', 'currentYear', 'lastYear', 'months', 'monthYear', 'filteredResults', 'currentMonthName', 'selectedMonthName'));
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
