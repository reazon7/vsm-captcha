## Very Simple Math Captcha for Laravel

This package is intended for devs that does not want their user to be confused looking at some obscure captcha image but still want to have a captcha for whatever reason. And ofcourse it's not as secure as an obscure one.

## Installation

  ```
  composer require reazon/vsm-captcha
  ```

## Configs

to use your own settings, publish the config file. `config/vsm-captcha.php`

  ```
  $ php artisan vendor:publish
  ```

## Usage Example

### View

use it in view by make an image tag with source from function `vsm_captcha_src()`

  ```
  <img src="{{ vsm_captcha_src() }}" alt="captcha">
  <input type="number" name="captcha" maxlength="2">
  ```

### Validation

to validate the form with captcha input just add `vsm_captcha` to validation rules

  ```
  validator()->make(request()->all(), [
    'captcha' => 'required|vsm_captcha',
  ])->validate();
  ```
