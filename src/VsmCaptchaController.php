<?php

namespace REAZON\VerySimpleMathCaptcha;

use Illuminate\Routing\Controller;
use REAZON\VerySimpleMathCaptcha\VsmCaptcha;

class VsmCaptchaController extends Controller
{
    public function showCaptcha(VsmCaptcha $captcha, $code)
    {
        if (ob_get_contents()) {
            ob_clean();
        }

        return $captcha->make($code);
    }
}
