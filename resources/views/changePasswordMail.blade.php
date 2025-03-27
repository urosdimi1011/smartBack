<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            background: #fff;
            padding: 20px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            background: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Promeni svoju lozinku</h2>
    <p>Molimo Vas da unesete svoju lozinku ispod.</p>
    <form action="{{$reset_link}}" method="POST">
        <label for="new_password">Unesite novu lozinku</label>
        <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
        <label for="confirm_password">Potvrdite novu lozinku</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="hidden" name="userId" value="{{$userId}}">
        <button type="submit" class="btn">Reset Password</button>
    </form>
</div>
</body>
</html>
