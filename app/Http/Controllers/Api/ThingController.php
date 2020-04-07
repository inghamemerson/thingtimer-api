<?php

namespace App\Http\Controllers\Api;

use App\Models\Thing;
use App\Models\Timer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ThingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return false;
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
            'title' => 'required|string',
            'quantity' => 'nullable|string'
        ]);

        $params = array_filter($request->input());

        return Thing::create($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thing  $thing
     * @return \Illuminate\Http\Response
     */
    public function show(Thing $thing)
    {
        return $thing;
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Thing  $thing
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Thing $thing)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thing  $thing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thing $thing)
    {

        $this->validate($request, [
            'title' => 'required|string',
            'quantity' => 'nullable|string'
        ]);

        $params = array_filter($request->input());

        $thing->update($params);
        $thing->save();

        return $thing->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thing  $thing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thing $thing)
    {
        return $thing->delete();
    }
}
