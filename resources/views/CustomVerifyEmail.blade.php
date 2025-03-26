<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        /* Dodaj stilove prema potrebi */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Email Verification</h2>
    <p>Zdravo,</p>
    <p>Hvala sto se se registrovali, u nastavku je potrebno verifikovati email klikom na dugme ispod</p>
    <a href="{{ $url }}" class="button">Verifikuj Email</a>
</div>
</body>
</html>
