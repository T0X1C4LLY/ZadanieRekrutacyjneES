<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PetAPIClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'petAPIClient';
    }
}
