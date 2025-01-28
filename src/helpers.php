<?php

if (!function_exists('vsm_captcha_src')) {

    /**
     * @return string
     */
    function vsm_captcha_src(): string
    {
        return app('vsm-captcha')->srcImage();
    }
}

if (!function_exists('vsm_captcha_check')) {

    /**
     * @param string|int $answer
     * @return bool
     */
    function vsm_captcha_check($answer): bool
    {
        return app('vsm-captcha')->check($answer);
    }
}
