<?php

namespace quimeboule\colissimo\Facades;

use Illuminate\Support\Facades\Facade;

class colissimo extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'colissimo';
    }
}
