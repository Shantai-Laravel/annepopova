<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Individual Delivery</title>
    </head>
    <body>
        <h5>New user:</h5>

        <table>
            <tr>
                <td>Name</td>
                <td>
                    {{ $feedback->first_name }}
                </td>
            </tr>
            <tr>
                <td>Phone</td>
                <td>
                    {{ $feedback->phone }}
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>
                    {{ $feedback->email }}
                </td>
            </tr>
        </table>

        </table>
    </body>
</html>
