hello, {{$user->name}}
You changed your email, so we need to verify this new address. please use the link below:
{{route('verify', $user->verify_token)}}
