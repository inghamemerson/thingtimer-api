<?php

namespace App\Http\Controllers\Api;

use App\Models\Timer;
use App\Models\Thing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TimerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'fart';
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'thing_id' => 'required|exists:things,id',
            'started_at' => 'required|string',
            'ended_at' => 'nullable|string'
        ]);

        $params = array_filter($request->input());

        return Timer::create($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function show(Timer $timer)
    {
        return $timer;
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Timer  $timer
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Timer $timer)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timer $timer)
    {
        $this->validate($request, [
            'started_at' => 'nullable|string',
            'ended_at' => 'nullable|string'
          ]);

          $params = array_filter($request->input());

          $timer->update($params);
          $timer->save();

          return $timer->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timer $timer)
    {
        return $timer->delete();
    }
}
