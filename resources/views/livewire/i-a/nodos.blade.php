<div class="container mt-4">
    <!-- Loader -->
    <div id="loader" class="mt-3 text-center" style="display:none;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        </div>
        <p class="mt-2">Verificando rostro, por favor espera...</p>
    </div>
    <div class="gap-2 mt-3 d-flex" style="justify-content: center;">
        <strong>
            <h4>Verificación Facial</h4>
        </strong>
    </div>


    <div class="p-3 card">

        <!-- Vista previa del video -->
        <video id="video" autoplay playsinline style="width: 100%; border-radius: 8px;"></video>

        <!-- Canvas interno -->
        <canvas id="canvas" style="display:none;"></canvas>

        <!-- Foto tomada -->
        <img id="preview" style="display:none; width:100%; margin-top:15px; border-radius:8px;" />

        <!-- Botones -->
        <div class="gap-2 mt-3 d-flex" style="justify-content: center;">
            <button id="startBtn" class="btn btn-primary btn-sm">⏺ Iniciar cámara</button>
            <button id="captureBtn" class="btn btn-success btn-sm" disabled>📸 Tomar foto</button>
            <button id="compareBtn" class="btn btn-warning btn-sm" disabled>🔍 Comparar Foto</button>
        </div>

    </div>
</div>

<script>
    let video = document.getElementById("video");
    let canvas = document.getElementById("canvas");
    let preview = document.getElementById("preview");
    let loader = document.getElementById("loader");

    let startBtn = document.getElementById("startBtn");
    let captureBtn = document.getElementById("captureBtn");
    let compareBtn = document.getElementById("compareBtn");

    let stream = null;
    let capturedPhoto = null;

    // Iniciar cámara
    startBtn.addEventListener("click", async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user"
                },
                audio: false
            });

            video.style.display = "block";
            preview.style.display = "none";

            video.srcObject = stream;
            captureBtn.disabled = false;

        } catch (error) {
            alert("No se pudo acceder a la cámara: " + error);
        }
    });

    // Tomar foto
    captureBtn.addEventListener("click", () => {

        // Ajustar tamaño
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        let ctx = canvas.getContext("2d");
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        capturedPhoto = canvas.toDataURL("image/jpeg", 0.9);

        // Mostrar solo la foto
        preview.src = capturedPhoto;
        preview.style.display = "block";

        // Ocultar video
        video.style.display = "none";

        // Habilitar comparación
        compareBtn.disabled = false;

        // Apagar cámara
        if (stream) {
            stream.getTracks().forEach(t => t.stop());
        }

        console.log("Foto Base64:", capturedPhoto);
    });

    // Comparar foto
    compareBtn.addEventListener("click", async () => {

        if (!capturedPhoto) {
            alert("Primero toma una foto.");
            return;
        }

        loader.style.display = "block"; // Mostrar cargando
        compareBtn.disabled = true;

        const firmaGuardada = "{{ Auth::user()->path }}";
        const firmaID = "{{ Auth::user()->id }}";

        const response = await fetch("{{ route('firma.verificar') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                canvas: capturedPhoto,
                firma_guardada: firmaGuardada,
                userID: firmaID
            })
        });

        const data = await response.json();

        loader.style.display = "none"; // Ocultar loader

        if (data.is_similar) {
            alert("💚 Coincide con tu rostro registrado!");
            // Espera 8 segundos y recarga
            setTimeout(() => {
                location.reload();
            }, 4000);

        } else {
            alert("❌ La foto NO coincide con tu rostro.");
        }

        compareBtn.disabled = false;
    });
</script>
