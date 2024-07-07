<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
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

    .email-body span {
        color: black;
        font-weight: bold;
    }

    .email-footer {
        padding: 20px;
        text-align: center;
    }
</style>
<body>
<div class="email-header">
    <h1>{{config('app.name')}}</h1>
</div>
<div class="center pa-1">
    <div class="email-body">
        <h2>Vítejte!</h2>
        <p>Děkujeme <span>{{$userFirstname}} {{$userLastname}}</span>, že jste se zaregistroval/a do naší aplikace
            {{config('app.name')}}! Jsme nadšeni,
            že jste se rozhodl/a
            připojit k naší komunitě a těšíme se, až Vám budeme moci nabídnout ty nejlepší služby.</p>
        <p class="end">Děkujeme ještě jednou za Vaši registraci a těšíme se na Vaši zpětnou vazbu.
            <br>Team {{config('app.name')}}</p>
    </div>
</div>
<div class="email-footer">
    <p>&copy; {{ date('Y') }}.</p>
</div>
</body>
