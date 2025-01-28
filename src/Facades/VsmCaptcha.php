<?php

namespace REAZON\VerySimpleMathCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class VsmCaptcha extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'vsm-captcha';
    }
}
