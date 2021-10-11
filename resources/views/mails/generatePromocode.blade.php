<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Promocode</title>
    </head>
    <body>
        <h5>{{ trans('front.thanks.gift') }}</h5>

        <p>{{ trans('front.thanks.promocode', ["name" => $promocode->name, "treshold" => $promocode->treshold, "date" => date("j F Y", strtotime($promocode->valid_to)), "discount" => $promocode->discount]) }}</p>

        </table>
    </body>
</html>
