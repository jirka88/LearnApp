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
            box-shadow: 8px 8px gray;
        }

        .email-body h2 {
            text-align: center;
            font-size: 2em;
            letter-spacing: 0.2em;
        }

        .email-footer {
            padding: 20px;
            text-align: center;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        p {
            color: #636363;
            font-size: 1.2em;
        }

        .end {
            line-height: 2em;
        }

        .pa-1 {
            padding: 1em;
        }

        .py-2 {
            padding-top: 2em;
            padding-bottom: 2em;
        }

    </style>
</head>
<body>
<div class="email-header">
    <h1>{{$applicationName}}</h1>
</div>
<div class="center pa-1">
    <div class="email-body">
        <h2>Vítejte!</h2>
        <p>Děkujeme Vám za registraci na LearnApp. Prosím, ověřte svou emailovou adresu kliknutím na následující
            tlačítko:</p>
        <div class="center py-2">
            <a href="{{$actionUrl}}" style="display: inline-block; padding: 1.4em 2em; color: white; font-size: 1em; background-color:
                 #4398f0;
                text-decoration: none; border-radius: 4px;">
                Ověřit emailovou adresu
            </a>
        </div>
        <p style="font-size: 0.8em">Pokud vám nefunguje tlačítko, můžete použít tento
            odkaz:<a href="{{$actionUrl}}">{{$actionUrl}}</a></p>
        <p>Pokud jste tuto žádost o ověření emailu neprováděli, prosím ignorujte tento email.</p>
        <p class="end">Děkujeme,
            <br>Team {{$applicationName}}</p>
    </div>
</div>
<div class="email-footer">
    <p>&copy; {{ date('Y') }}.</p>
</div>
</body>
</html>
