<?php

namespace Kalimeromk\Crm;

use Illuminate\Support\Facades\Facade;

/**
 * @see SkeletonClass
 */
class CrmFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'crm';
    }
}
