<?php

namespace App\Observers;

use App\Models\Thing;
use Illuminate\Support\Str;

class ThingObserver
{
    /**
     * Handle the thing "created" event.
     *
     * @param  \App\Thing  $thing
     * @return void
     */
    public function creating(Thing $thing)
    {
        $thing->uuid = (string) Str::uuid();
    }

    /**
     * Handle the thing "deleted" event.
     *
     * @param  \App\Thing  $thing
     * @return void
     */
    public function deleted(Thing $thing)
    {
        $thing->timers->each(function($timer){
            $timer->delete();
        });
    }
}
