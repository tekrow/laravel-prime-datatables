<?php

namespace Tekrow\PrimevueDatatables;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tekrow\PrimevueDatatables\Skeleton\SkeletonClass
 */
class PrimevueDatatablesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-prime-datatables';
    }
}
