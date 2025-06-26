<!DOCTYPE html>
<html>
<head>
    <title>Регистрация нового пользователя</title>
</head>
<body>
    <h1>Новый пользователь</h1>
    <p>Имя: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
    <p>Дата регистрации: {{ $user->created_at->format('d.m.Y H:i') }}</p>
</body>
</html>