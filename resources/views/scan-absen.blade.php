<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        #reader {
            width: 300px;
            height: 300px;
            margin-bottom: 20px;
        }
        #status {
            margin-bottom: 10px;
            font-weight: bold;
        }
        button {
            margin-top: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Scan QR Code untuk Absensi</h1>
    
    <div id="status"></div>
    
    <div id="reader"></div>
    
    <button id="switchCamera" style="display: none;">Ganti Kamera</button>

    <form id="absen-form" action="{{ route('process-absen') }}" method="POST">
        @csrf
        <input type="hidden" name="qrcode" id="result">
    </form>

    <script>
        const statusDiv = document.getElementById('status');
        const switchCameraButton = document.getElementById('switchCamera');
        let currentCamera = 'environment';
        let html5QrCode;

        function setStatus(message, isError = false) {
            statusDiv.textContent = message;
            statusDiv.style.color = isError ? 'red' : 'green';
        }

        function loadScript(url) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = url;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        async function checkCameraSupport() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const cameras = devices.filter(device => device.kind === 'videoinput');
                return cameras.length > 0;
            } catch (error) {
                console.error('Error checking camera support:', error);
                return false;
            }
        }

        async function initializeScanner() {
            if (typeof Html5Qrcode === 'undefined') {
                setStatus('Error: QR Scanner library not loaded', true);
                return;
            }

            const hasCameraSupport = await checkCameraSupport();
            if (!hasCameraSupport) {
                setStatus('Error: No camera detected or camera access is not supported', true);
                return;
            }

            html5QrCode = new Html5Qrcode("reader");
            switchCameraButton.style.display = 'block';
            startScanner('environment');
        }

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`QR Code detected: ${decodedText}`);
            document.getElementById('result').value = decodedText;
            document.getElementById('absen-form').submit();
        }

        function onScanError(error) {
            console.warn(`QR Code scanning error: ${error}`);
            // We don't set this as status to avoid constant error messages
            // setStatus(`Error scanning QR Code: ${error}`, true);
        }

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        function startScanner(facingMode) {
            html5QrCode.start(
                { facingMode: facingMode },
                config,
                onScanSuccess,
                onScanError
            ).then(() => {
                setStatus(`Scanner started. Using ${facingMode} camera.`);
                currentCamera = facingMode;
            }).catch(err => {
                console.error(`Failed to start scanner: ${err}`);
                setStatus(`Failed to start scanner: ${err}. Try refreshing the page or using a different browser.`, true);
            });
        }

        switchCameraButton.onclick = () => {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    const newCamera = currentCamera === 'environment' ? 'user' : 'environment';
                    startScanner(newCamera);
                }).catch(err => {
                    console.error(`Failed to switch camera: ${err}`);
                    setStatus(`Failed to switch camera: ${err}`, true);
                });
            }
        };

        // Load the script and initialize the scanner
        loadScript('https://unpkg.com/html5-qrcode/html5-qrcode.min.js')
            .then(() => {
                console.log('HTML5 QR Code library loaded successfully');
                initializeScanner();
            })
            .catch(err => {
                console.error('Failed to load HTML5 QR Code library:', err);
                setStatus('Failed to load QR Scanner library. Please check your internet connection and refresh the page.', true);
            });
    </script>
</body>
</html>