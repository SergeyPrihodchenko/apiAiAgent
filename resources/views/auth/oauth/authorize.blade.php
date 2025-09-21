<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="@route('passport.authorizations.approve')" method="post">
        @csrf
        <input type="text" name="state" id="">
        <input type="text" name="client_id" id="">
        <input type="text" name="auth_token" id="">
        <button type="submit">approve</button>
    </form>
    <form action="@route(passport.authorizations.deny)" method="delete">
        @csrf
        <input type="text" name="state" id="">
        <input type="text" name="client_id" id="">
        <input type="text" name="auth_token" id="">
        <button type="submit">Deny</button> 
    </form>
</body>
</html>