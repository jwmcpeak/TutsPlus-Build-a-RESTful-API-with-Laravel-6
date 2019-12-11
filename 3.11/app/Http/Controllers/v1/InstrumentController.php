<?php

namespace App\Http\Controllers\v1;

use App\Instrument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\v1\InstrumentService;


class InstrumentController extends Controller
{
    protected $service;
    function __construct(InstrumentService $service) {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->service->all(request()->input());   

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
     * @param  \App\Instrument  $instrument
     * @return \Illuminate\Http\Response
     */
    public function show(Instrument $instrument)
    {
        return $this->service->single($instrument, request()->input());

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Instrument  $instrument
     * @return \Illuminate\Http\Response
     */
    public function edit(Instrument $instrument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Instrument  $instrument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instrument $instrument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Instrument  $instrument
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instrument $instrument)
    {
        //
    }
}
