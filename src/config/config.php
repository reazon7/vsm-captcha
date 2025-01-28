<?php

return [
	/* disable captcha */
	'disable' => env('VSM_CAPTCHA_DISABLE', false),
	/* width of captcha image */
	'width' => 120,
	/* height of captcha image */
	'height' => 30,
	/* background color */
	'bgColor' => '#ffffff',
	/* font color */
	'fontColor' => '#d70040',
	/* expirate in (minutes) */
	'exp' => 30,
];
