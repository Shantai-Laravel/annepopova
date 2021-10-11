<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'email' => "Вы ввели неправильный электронный адрес или пароль, повторите еще раз",
    'forgotpass' => [
      'email' => "Неправильный электронный адрес",
      'code' => "На вашу почту был код для изменения пароля",
      'codeErr' => "Неправильный код",
      "codeSuccess" => "Код был успешно введен",
      'pass' => "Пароль был успешно изменен",
      'subject' => "Восстановление пароля",
      'message' => "Здравствуйте, введите этот код для сброса пароля - "
    ],
    'register' => [
      'captcha' => "Подтвердите что вы человек",
      'code' => "На вашу почту был выслан код, введите его пожалуйста",
      'success' => "Вы были успешно зарегистрированы, но для использования всего функционала перейди по ссылке, отправленной на почту!",
      'subject' => "Регистрация",
      'message' => "Здравствуйте, перейдите по ссылке для завершения регистрации - http://admin.likemedia.top",
      "email" => "Ваш email - ",
      "password" => "Ваш пароль - ",
      "changePass" => "Здравствуйте, перейдите по ссылке для завершения регистрации, а также для смены пароля по желанию - http://admin.likemedia.top"
    ]

];
