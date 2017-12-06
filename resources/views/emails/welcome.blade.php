hello, {{$user->name}}
Thank you for your register. please verify your email using this link:
{{route('verify', ['token' => $user->verify_token])}}
