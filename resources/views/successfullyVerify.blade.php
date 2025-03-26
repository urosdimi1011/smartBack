<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uspesna verifikacija</title>
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
    <script>
        setTimeout(() => {
            window.location.href = "{{ $url }}";
        }, 3000);
    </script>
</head>
<body>
<div class="container">
    <h2>Uspesna verifikacija</h2>
    <p>Uspesno ste se verifikovali, </p>
    <a href="{{ $url }}" class="button">Idi na prijavu</a>
</div>
</body>
</html>
