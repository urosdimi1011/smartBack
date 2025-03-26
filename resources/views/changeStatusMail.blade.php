<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informacije o promeni statusa uredjaja</title>
</head>
<body class="forma-email">
<div class="container">
    <h1>Informacije o promeni statusa uredjaja</h1>

    <p>Uspesno ste promenili status uredjaja <strong>{{$device['name']}}</strong>(id: {{$device['board']}} i pin: {{$device['pin']}}) u {{$device['status'] ? 'Ukljuceno' : 'Iskljuceno'}}.</p>

    <hr>
</div>
</body>
</html>
