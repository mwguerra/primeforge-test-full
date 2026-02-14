<!DOCTYPE html>
<html>
<head>
    <title>Full Stack Demo - PrimeForge Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Full Stack Demo</h1>
        <p class="text-gray-600 mb-4">Redis + Horizon + Reverb working together</p>

        <button onclick="startJob()" id="startBtn" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 font-semibold mb-6">
            Start Job
        </button>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-2">Progress</h2>
            <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                <div id="progressBar" class="bg-blue-500 h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-gray-600 text-sm">Waiting...</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Live Events</h2>
            <div id="events" class="space-y-2">
                <p class="text-gray-500" id="no-events">Waiting for events...</p>
            </div>
        </div>
    </div>

    <script type="module">
        import Echo from 'laravel-echo';
        import Pusher from 'pusher-js';
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: import.meta.env.VITE_REVERB_HOST,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });
        window.Echo.channel('demo-channel')
            .listen('DemoProgress', (e) => {
                document.getElementById('no-events')?.remove();
                document.getElementById('progressBar').style.width = e.progress + '%';
                document.getElementById('progressText').textContent = e.message + ' (' + e.progress + '%)';
                const div = document.createElement('div');
                div.className = 'border-b py-2';
                div.innerHTML = '<span class="text-green-600 font-mono">✓</span> ' + e.message + ' <span class="text-gray-400 text-sm">(' + e.timestamp + ')</span>';
                document.getElementById('events').prepend(div);
            });
    </script>
    <script>
        function startJob() {
            document.getElementById('startBtn').disabled = true;
            document.getElementById('startBtn').textContent = 'Processing...';
            document.getElementById('progressBar').style.width = '0%';
            document.getElementById('progressText').textContent = 'Starting...';
            fetch('/demo/start', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
                .then(r => r.json())
                .then(() => {
                    setTimeout(() => {
                        document.getElementById('startBtn').disabled = false;
                        document.getElementById('startBtn').textContent = 'Start Job';
                    }, 8000);
                });
        }
    </script>
</body>
</html>
