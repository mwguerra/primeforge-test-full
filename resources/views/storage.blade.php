<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Storage Test - {{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; background: #1a1a2e; color: #eee; padding: 2rem; min-height: 100vh; }
        h1 { font-size: 1.5rem; margin-bottom: 1.5rem; color: #e94560; }
        .info { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .info-card { background: #16213e; padding: 1rem; border-radius: 8px; }
        .info-card label { font-size: .75rem; color: #888; text-transform: uppercase; }
        .info-card span { display: block; margin-top: .25rem; font-size: .9rem; word-break: break-all; }
        .alert { padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #1b4332; color: #95d5b2; }
        .alert-error { background: #4a1e1e; color: #f8a0a0; }
        .upload-form { background: #16213e; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
        .upload-form input[type=file] { flex: 1; min-width: 200px; }
        .upload-form button { background: #e94560; color: #fff; border: none; padding: .5rem 1.5rem; border-radius: 6px; cursor: pointer; font-size: .9rem; }
        .upload-form button:hover { background: #c81e45; }
        table { width: 100%; border-collapse: collapse; background: #16213e; border-radius: 8px; overflow: hidden; }
        th, td { padding: .75rem 1rem; text-align: left; border-bottom: 1px solid #1a1a2e; }
        th { background: #0f3460; font-size: .8rem; text-transform: uppercase; color: #aaa; }
        td a { color: #e94560; text-decoration: none; }
        td a:hover { text-decoration: underline; }
        .delete-btn { background: #c0392b; color: #fff; border: none; padding: .3rem .8rem; border-radius: 4px; cursor: pointer; font-size: .8rem; }
        .delete-btn:hover { background: #e74c3c; }
        .empty { text-align: center; padding: 2rem; color: #666; }
        .back { display: inline-block; margin-bottom: 1.5rem; color: #e94560; text-decoration: none; }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <a href="/" class="back">&larr; Back to home</a>
    <h1>PrimeForge Storage Test</h1>

    <div class="info">
        <div class="info-card">
            <label>Filesystem Disk</label>
            <span>{{ $disk }}</span>
        </div>
        <div class="info-card">
            <label>S3 Endpoint</label>
            <span>{{ $endpoint ?? 'Not configured' }}</span>
        </div>
        <div class="info-card">
            <label>Bucket</label>
            <span>{{ $bucket ?? 'Not configured' }}</span>
        </div>
    </div>

    @session('success')
        <div class="alert alert-success">{{ $value }}</div>
    @endsession

    @session('error')
        <div class="alert alert-error">{{ $value }}</div>
    @endsession

    <form method="POST" action="/upload" enctype="multipart/form-data" class="upload-form">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    @if(count($files) > 0)
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Size</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>{{ $file['name'] }}</td>
                        <td>{{ number_format($file['size'] / 1024, 1) }} KB</td>
                        <td><a href="{{ $file['url'] }}" target="_blank">View</a></td>
                        <td>
                            <form method="POST" action="/files/{{ $file['name'] }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">No files uploaded yet...</div>
    @endif
</body>
</html>
