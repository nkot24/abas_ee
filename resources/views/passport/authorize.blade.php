<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Authorize Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px; max-width: 420px; width: 100%; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        p { color: #555; font-size: 14px; }
        ul { padding-left: 20px; color: #333; font-size: 14px; }
        .actions { display: flex; gap: 12px; margin-top: 24px; }
        button { flex: 1; padding: 10px 16px; border-radius: 6px; border: none; font-size: 14px; cursor: pointer; }
        .approve { background: #2563eb; color: #fff; }
        .deny { background: #e5e7eb; color: #111; }
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ $client->name }} wants to access your account</h1>
        <p>This will allow {{ $client->name }} to:</p>
        <ul>
            @forelse ($scopes as $scope)
                <li>{{ $scope->description ?? $scope->id }}</li>
            @empty
                <li>Access your account</li>
            @endforelse
        </ul>

        <div class="actions">
            <form method="post" action="{{ route('passport.authorizations.approve') }}">
                @csrf
                <input type="hidden" name="state" value="{{ $request->query('state') }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button type="submit" class="approve">Authorize</button>
            </form>
            <form method="post" action="{{ route('passport.authorizations.deny') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="state" value="{{ $request->query('state') }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button type="submit" class="deny">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
