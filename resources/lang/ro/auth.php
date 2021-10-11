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
    'email' => "Ați introdus o adresă sau o parolă incorectă de e-mail, încercați din nou",
    'forgotpass' => [
      'email' => "Adresă de e-mail greșită",
      'code' => "Un cod de resetare a parolei a fost trimis la e-mail",
      'codeErr' => "Codul incorect",
      "codeSuccess" => "Codul a fost introdus cu succes",
      'pass' => "Parola a fost modificată cu succes.",
      'subject' => "Recuperarea parolei",
      'message' => "Bună ziua, introduceți acest cod pentru a vă reseta parola -"
    ],
    'register' => [
      'captcha' => "Confirmați că sunteți un om",
      'code' => "Codul dvs. a fost trimis la e-mail, introduceți-l vă rog",
      // 'success' => "Ați fost înregistrat cu succes, dar pentru a utiliza toate funcționalitățile mergeți la link-ul trimis la e-mail!",
      'success' => "You have been successfully registered. Please check Your email to  get the promocode",
      'subject' => "Formular de înregistrare",
      'message' => "Bună ziua, urmați linkul pentru a finaliza înregistrarea - http://admin.likemedia.top",
      "email" => "E-mailul dvs. - ",
      "password" => "Parola ta - ",
      "changePass" => "Bună ziua, faceți clic pe link pentru a finaliza înregistrarea, precum și pentru a schimba parola la alegere - http://admin.likemedia.top"
    ]

];
