<?php

namespace App\Http\Controllers\v1;

use App\Instrument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\v1\InstrumentService;

use Illuminate\Validation\ValidationException;


class InstrumentController extends Controller
{
    protected $service;
    function __construct(InstrumentService $service) {
        $this->service = $service;
        $this->middleware('auth:api', [
            'except' => ['index', 'show']
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Instrument  $instrument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instrument $instrument)
    {
        // PUT -- replace; validate
        // PATCH -- partial update

        try {
            if ($request->isMethod('patch')) {
                return $this->service->patch($instrument, $request->input());
            } else {
                return $this->service->put($instrument, $request->input());
            }
        } catch (ValidationException $ve) {
            return response()->json($ve->validator->errors(), 422);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Instrument  $instrument
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instrument $instrument)
    {
        $instrument->delete();
    }
}
