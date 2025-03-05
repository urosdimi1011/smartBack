<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informacije o dodatom uredjaju</title>
</head>
<body class="forma-email">
    <div class="container">
        <h1>Informacije o dodatom uredjaju</h1>

        <p><strong>Id uredjaja</strong> {{ $device['id'] }}</p>
        <p><strong>Ime uredjaja</strong> {{ $device['name'] }}</p>
        <p><strong></strong> {{ $device['email'] }}</p>

        <hr>
    </div>
</body>
</html>
