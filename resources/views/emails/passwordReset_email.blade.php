<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #EEEEEE;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        p {
            color: #636363;
            font-size: 1em;
        }

        .end {
            line-height: 2em;
        }

        .pa-1 {
            padding: 1em;
        }

        .py-1 {
            padding-top: 1em;
            padding-bottom: 1em;
        }

        .email-header, .email-body, .email-footer {
            font-family: "Roboto", sans-serif;
        }

        .email-header {
            text-align: center;
            color: white;
            position: absolute;
            top: 0;
            width: 100%;
            min-height: 20em;
            background: #4398f0;
        }

        .email-header h1 {
            padding: 1em;
        }

        .email-body {
            position: relative;
            padding: 2em;
            max-width: 46em;
            top: 8em;
            background: white;
            z-index: 2;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }

        .email-body h2 {
            text-align: center;
            font-size: 3em;
            letter-spacing: 0.2em;
        }

        .email-footer {
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="email-header">
    <h1>{{$applicationName}}</h1>
</div>
<div class="center pa-1">
    <div class="email-body">
        <p>Zdravíme,
            Tento e-mail jste obdrželi, protože jsme obdrželi žádost o resetování hesla k vašemu účtu. Pro resetování klikněte na toto tlačítko:</p>
        <div class="center py-2">
            <a href="{{$actionUrl}}" style="display: inline-block; padding: 1.4em 2em; color: white; font-size: 1em; background-color:
                 #4398f0;
                text-decoration: none; border-radius: 4px;">
                Resetovat heslo
            </a>
        </div>
        <p style="font-size: 0.8em">Pokud vám nefunguje tlačítko, můžete použít tento
            odkaz:<a href="{{$actionUrl}}">{{$actionUrl}}</a></p>
        <p>Pokud jste tuto žádost o resetování hesla neprováděli, prosím ignorujte tento email.</p>
        <p class="end">Děkujeme,
            <br>Team {{$applicationName}}</p>
    </div>
</div>
<div class="email-footer">
    <p>&copy; {{ date('Y') }}.</p>
</div>
</body>
</html>
