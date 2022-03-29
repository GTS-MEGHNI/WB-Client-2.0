<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="color-scheme" content="light">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #edf2f7;
            font-family: 'Poppins', sans-serif;
        }

        table {
            border-spacing: 0;
        }

        td {
            padding: 0;
        }

        img {
            border: 0;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            padding-bottom: 60px;
        }

        .logo-body {
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #4a4a4a;
        }


        .email-body {
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, .1);
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #4a4a4a;
            padding: 24px 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        }

        .passcode-value {
            display: inline-block;
            text-align: center;
            margin-top: 13px;
            font-size: 24px;
            font-weight: 600;
            border: 1px solid #32b3dd;
            background-color: #32B3DC20;
            color: #32b3dd;
            border-radius: 6px;
            letter-spacing: 2px;
            padding: 6px 20px 6px 22px;
        }

        .text {
            display: block;
            padding: 10px 32px;
        }

        .welcome {
            font-size: 20px;
        }

        .passcode-text {
            font-size: 16px;
        }
    </style>
</head>
<body>
<center class="wrapper">
    <table class="logo-body">
        <tr>
            <td>
                <img src={{$logo_url}} alt="logo"
                     style="margin: 30px 0">
            </td>
        </tr>
        <tr>
            <td>
                <table class="email-body">

                    <tr>
                        <td>
                            <span class="text welcome">Bienvenue <strong>{{ $name }}</strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text passcode-text">{{ $sentence }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">
                            <span class="passcode-value" style="margin-bottom: 14px">{{ $passcode }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text" style="font-size: 16px; font-weight: bolder;">Ce code de confirmation est valide pour une durée de {{ $ttl }} min.</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                <span
                    style="display: block;margin-top: 22px">© {{ $year }} {{ $app_name }} | Tous droits réservés</span>
            </td>
        </tr>
    </table>
</center>
</body>
</html>
