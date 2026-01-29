<div>
    {{-- Selección del usuario actual --}}
    <h3>Conectarte como:</h3>
    <select wire:model="myName" id="myName">
        <option value="">Selecciona tu nombre</option>
        @foreach($userlist as $user)
            <option value="{{ $user['name'] }}">{{ $user['name'] }}</option>
        @endforeach
    </select>
    <button onclick="connect()">Conectar</button>

    <hr>

    {{-- Lista de usuarios para llamar --}}
    <h4>Usuarios disponibles para llamar</h4>
    <ul>
        @foreach($userlist as $user)
            @if($user['name'] !== $myName)
                <li style="display: flex; justify-content: space-between; align-items: center;">
                    {{ $user['name'] }}
                    <button onclick="startCall('{{ $user['name'] }}')">
                        📞 Llamar
                    </button>
                </li>
            @endif
        @endforeach
    </ul>

    <pre id="log"></pre>

    {{-- Script --}}
    <script>
        let ws;
        let pc;
        let localStream;

        function log(msg) {
            document.getElementById("log").textContent += msg + "\n";
        }

        function connect() {
            const name = document.getElementById("myName").value;
            ws = new WebSocket("ws://spamiora.ddns.net:8081");

            ws.onopen = () => {
                log("✅ Conectado al servidor WebSocket");
                ws.send(JSON.stringify({ type: "register", name }));
            };

            ws.onmessage = async (event) => {
                const data = JSON.parse(event.data);
                log("📩 " + event.data);

                if (data.type === "answer") {
                    const remoteDesc = new RTCSessionDescription(data.answer);
                    await pc.setRemoteDescription(remoteDesc);
                }

                if (data.type === "candidate") {
                    try {
                        await pc.addIceCandidate(new RTCIceCandidate(data.candidate));
                    } catch (e) {
                        console.error("Error ICE", e);
                    }
                }
            };

            ws.onerror = (e) => log("❌ Error WebSocket");
            ws.onclose = () => log("🔌 WebSocket cerrado");
        }

        async function startCall(to) {
            const from = document.getElementById("myName").value;

            localStream = await navigator.mediaDevices.getUserMedia({ audio: true });

            pc = new RTCPeerConnection();

            localStream.getTracks().forEach(track => pc.addTrack(track, localStream));

            pc.onicecandidate = (event) => {
                if (event.candidate) {
                    ws.send(JSON.stringify({
                        type: "candidate",
                        to: to,
                        candidate: event.candidate
                    }));
                }
            };

            const offer = await pc.createOffer();
            await pc.setLocalDescription(offer);

            ws.send(JSON.stringify({
                type: "call",
                from: from,
                to: to,
                offer: offer
            }));

            log("📞 Llamada enviada a " + to);
        }
    </script>
</div>
