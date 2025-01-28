<?php

namespace REAZON\VerySimpleMathCaptcha;

use Exception;
use Illuminate\Http\Response;

class VsmCaptcha
{
	private $question;
	private $answer;

	function __construct()
	{
		$exp = session('vsm-captcha.exp');

		if (empty($exp) || $exp < time()) {
			$num1 = rand(1, 9);
			$num2 = rand(1, 9);

			$this->question = "{$num1} + {$num2} =";
			$this->answer = $num1 + $num2;

			$this->storeInSession();
		} else {
			$this->loadFromSession();
		}
	}

	private function storeInSession()
	{
		$exp = config('vsm-captcha.exp');

		session()->put('vsm-captcha', [
			'question' => $this->question,
			'answer' => $this->answer,
			'exp' => strtotime("+{$exp} minutes"),
		]);
	}

	private function loadFromSession()
	{
		$this->question = session('vsm-captcha.question');
		$this->answer = session('vsm-captcha.answer');
	}

	public function make($code)
	{
		try {
			$data = $this->simpleDecode(base64_decode($code));

			$this->question = $data['question'];
			$this->answer = $data['answer'];

			$this->storeInSession();

			$font = __DIR__ . '/resources/ttfonts/Arial.ttf';

			$width = config('vsm-captcha.width');
			$height = config('vsm-captcha.height');

			$image = imagecreatetruecolor($width, $height);

			$colorConf = $this->hexColorToRgb(config('vsm-captcha.fontColor'));
			$txtColor = imagecolorallocate($image, $colorConf['r'], $colorConf['g'], $colorConf['b']);

			$bgColorConf = $this->hexColorToRgb(config('vsm-captcha.bgColor'));
			$bgColor = imagecolorallocate($image, $bgColorConf['r'], $bgColorConf['g'], $bgColorConf['b']);

			imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

			$textWidth = floor(($width / 5.7692) * 5);

			$txtSize = round(($width / 5.7692) * 1.2254);
			$textX = floor(($width - $textWidth) / 2);
			$textY = floor(($height + $txtSize) / 2);
			imagettftext($image, $txtSize, 0, $textX, $textY, $txtColor, $font, $data['question']);

			ob_start();
			imagepng($image);
			$buffer = ob_get_contents();
			ob_end_clean();
			imagedestroy($image);

			return new Response($buffer, 200, [
				'Content-Type' => 'image/png',
				'Content-Disposition' => 'inline; filename="captcha.png"',
			]);
		} catch (Exception $ex) {
			abort(404);
		}
	}

	public function srcImage()
	{
		return url('vsm-captcha/' . base64_encode($this->simpleEncode([
			'question' => $this->question,
			'answer' => $this->answer,
		])));
	}

	public function check($answer)
	{
		$check = session('vsm-captcha.answer') == $answer;

		session()->remove('vsm-captcha');

		return $check;
	}

	private function simpleEncode($data)
	{
		return str_rot13(base64_encode(serialize($data)));
	}

	private function simpleDecode($data)
	{
		return unserialize(base64_decode(str_rot13($data)));
	}

	private function hexColorToRgb($hexColor)
	{
		if (strlen($hexColor) == 4) {
			$hr = substr($hexColor, 1, 1);
			$hg = substr($hexColor, 2, 1);
			$hb = substr($hexColor, 3, 1);

			$hexColor = "#{$hr}{$hr}{$hg}{$hg}{$hb}{$hb}";
		}

		list($r, $g, $b) = sscanf($hexColor, "#%02x%02x%02x");

		return ['r' => $r, 'g' => $g, 'b' => $b];
	}
}
