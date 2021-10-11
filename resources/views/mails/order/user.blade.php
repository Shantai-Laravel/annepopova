<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <meta charset="utf-8">
        <!-- utf-8 works for most cases -->
        <meta name="viewport" content="width=device-width">
        <!-- Forcing initial-scale shouldn't be necessary -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Use the latest (edge) version of IE rendering engine -->
        <meta name="x-apple-disable-message-reformatting">
        <!-- Disable auto-scale in iOS 10 Mail entirely -->
        <title></title>
        <!-- The title tag shows in email notifications, like Android 4.4. -->
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
        <!-- CSS Reset : BEGIN -->
        <style>
            body,html{margin:0 auto!important;padding:0!important;height:100%!important;width:100%!important;background:#f1f1f1}*{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}*{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}div[style*="margin: 16px 0"]{margin:0!important}table,td{mso-table-lspace:0!important;mso-table-rspace:0!important}table{border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important}img{-ms-interpolation-mode:bicubic}a{text-decoration:none}.aBn,.unstyle-auto-detected-links *,[x-apple-data-detectors]{border-bottom:0!important;cursor:default!important;color:inherit!important;text-decoration:none!important;font-size:inherit!important;font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}.a6S{display:none!important;opacity:.01!important}.im{color:inherit!important}img.g-img+div{display:none!important}@media only screen and (min-device-width:320px) and (max-device-width:374px){u~div .email-container{min-width:320px!important}}@media only screen and (min-device-width:375px) and (max-device-width:413px){u~div .email-container{min-width:375px!important}}@media only screen and (min-device-width:414px){u~div .email-container{min-width:414px!important}}div.email-container{width:100%;overflow:hidden;display:block;background-image:url(https://annepopova.com/fronts/img/icons/fonTechniquePages.png);background-repeat:repeat;padding-left:15px;padding-right:15px}.addit,p{text-align:center;font-family:'Source Sans Pro';font-size:20px;color:#2f2f2f;letter-spacing:-.05px;text-align:left;line-height:25px;margin:0}.userName{font-family:'Source Sans Pro';font-size:50px;color:#000;letter-spacing:-.08px;text-align:left;margin-bottom:30px}.logo{display:block;background-image:url(https://annepopova.com/fronts/img/icons/logoAfter.png);background-size:110px 110px;background-position:50px center;background-repeat:no-repeat;height:130px;margin-top:30px}.gift{font-family:'Source Sans Pro';font-size:30px;color:#2f2f2f;letter-spacing:-.06px;text-align:left;margin-top:20px;margin-bottom:5px}.addit{margin-top:30px}a.butt{display:block;height:40px;line-height:40px;font-size:20px;text-transform:uppercase;background-color:#4b483d;width:245px;text-align:center;color:#fff;margin-left:0;margin-top:10px;font-family:'Source Sans Pro'}.buttGroups{display:flex;width:650px;margin-left:0}.buttGroups .butt{margin-right:20px}.miss{margin-top:20px}.ignore{margin-top:30px;margin-bottom:60px}.logo2{background-image:url(https://annepopova.com/fronts/img/icons/logo2.png);background-repeat:no-repeat;background-size:100%;height:40px;width:150px;margin-left:0}ul{display:block;padding-left:17px;list-style:none}ul a,ul li{font-family:'Source Sans Pro';font-size:18px;color:#2f2f2f;letter-spacing:-.05px;text-align:left;line-height:25px;margin:0;list-style:none}
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="logo"></div>
            <div class="userName">{{ trans('vars.Email-templates.emailBodyHello') }} {{ $user->name }}</div>
            <p>
                {{ trans('vars.Email-templates.subjectOrderEmail') }} <a href="https://annepopova.com">https://annepopova.com</a>
            </p>
            <p>
                {{ trans('vars.Email-templates.emailOrderBodyTrackingLink') }} <a href="https://annepopova.com/en/account/history">annepopova.com/en/account/history</a>
            </p>
            @if ($promocode)
                <h5 class="gift">{{ trans('vars.PagesThankYou.giftTitle') }}</h5>
                <p>
                    {{ trans('vars.PagesThankYou.EnterPromocode') }}
                    {{ $promocode->name }}
                    {{ trans('vars.PagesThankYou.whenYouMake') }}
                    {{ $promocode->treshold }}
                    {{ trans('vars.PagesThankYou.euroOrMore') }}
                    {{ date("j F Y", strtotime($promocode->valid_to)) }}
                    {{ trans('vars.PagesThankYou.andEnjoy') }}
                    {{ $promocode->discount }}
                    {{ trans('vars.PagesThankYou.off') }}
                </p>
            @endif

            <div class="addit">
                {{ trans('vars.Email-templates.emailOrderBodyLoggedUserCabinet') }}
            </div>
            <a href="https://annepopova.com/en/account/history" class="butt">details</a>
            <p class="miss">
                {{ trans('vars.Email-templates.emailBodyNewInOutlet') }}
            </p>
            <div class="buttGroups">
                <a class="butt" href="{{ '/' . $lang->lang. '/homewear' }}">{{ trans('vars.General.HomewearStore') }}</a>
                <a class="butt" href="{{ '/' . $lang->lang. '/bijoux' }}">{{ trans('vars.General.BijouxBoutique') }}</a>
            </div>
            <p class="ignore">
                {{ trans('vars.Email-templates.emailBodyIgnoreMessage') }}
            </p>
            <p style="text-align: left">{{ trans('vars.Email-templates.emailBodySignature') }}</p>
            <ul class="info">
                <li><a href="https://annepopova.com">{{ trans('vars.General.brandName') }}</a></li>
                <li><a href="mailto:{{ trans('vars.Contacts.email') }}">{{ trans('vars.FormFields.fieldEmail') }}: {{ trans('vars.Contacts.queriesPaymentShippingReturnsEmail') }}</a></li>
                <li><a href="tel:{{ trans('vars.Contacts.phoneNumber') }}">{{ trans('vars.FormFields.fieldphone') }}: {{ trans('vars.Contacts.queriesPaymentShippingReturnsPhone') }}</a></li>
                <li><a href="{{ trans('vars.Contacts.facebook') }}">Facebook: {{ trans('vars.Contacts.facebook') }}</a></li>
            </ul>
        </div>
    </body>
</html>
