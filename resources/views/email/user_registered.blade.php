<!DOCTYPE html>
<html>
<head>
    <title>{{ __('new_user_registration') }}</title>
</head>
<body>
<h1>{{ __('new_user') }}</h1>
<p>{{ __('name') }}: {{ $user->name }}</p>
<p>{{ __('email') }}: {{ $user->email }}</p>
<p>{{ __('registration_date') }}: {{ $user->created_at->format('d.m.Y H:i') }}</p>
</body>
</html>
