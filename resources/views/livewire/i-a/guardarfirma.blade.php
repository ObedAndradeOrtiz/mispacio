<div>
    <div class="container mt-5">
        <h3 class="mb-3">Dibuja tu firma</h3>

        <div class="p-3 shadow-sm card" style="max-width: 500px; margin: auto;">
            <canvas id="signaturePad" width="500" height="150" class="border rounded"
                style="background-color: #f9f9f9;"></canvas>

            <div class="mt-3 d-flex justify-content-between">
                <button id="clearBtn" class="btn btn-secondary btn-sm">Limpiar</button>
                <button id="saveBtn" class="btn btn-primary btn-sm">Guardar Firma</button>
            </div>

            <form id="signatureForm" method="POST" action="{{ route('guardar.firma') }}">
                @csrf
                <input type="hidden" name="firma" id="firmaInput">
            </form>
        </div>
    </div>

    <style>
        canvas {
            cursor: crosshair;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
    </style>

    <script>
        const canvas = document.getElementById("signaturePad");
        const ctx = canvas.getContext("2d");

        let drawing = false;
        let lastX = 0;
        let lastY = 0;

        function startDrawing(e) {
            drawing = true;
            lastX = e.offsetX;
            lastY = e.offsetY;
        }

        function stopDrawing() {
            drawing = false;
            ctx.beginPath(); // separa los trazos
        }

        function draw(e) {
            if (!drawing) return;

            ctx.strokeStyle = "#000";
            ctx.lineWidth = 2;
            ctx.lineCap = "round";

            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();

            lastX = e.offsetX;
            lastY = e.offsetY;
        }

        canvas.addEventListener("mousedown", startDrawing);
        canvas.addEventListener("mouseup", stopDrawing);
        canvas.addEventListener("mouseout", stopDrawing);
        canvas.addEventListener("mousemove", draw);

        document.getElementById("clearBtn").addEventListener("click", () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        document.getElementById("saveBtn").addEventListener("click", () => {
            const dataUrl = canvas.toDataURL('image/png');
            document.getElementById("firmaInput").value = dataUrl;
            document.getElementById("signatureForm").submit();
        });
    </script>

</div>



<div>

    <div class="container mt-5">
        <h3 class="mb-3">Verificar tu firma</h3>

        <div class="p-3 shadow-sm card" style="max-width: 500px; margin: auto;">
            <canvas id="signaturePad" width="500" height="150" class="border rounded"
                style="background-color: #fff;"></canvas>

            <div class="mt-3 d-flex justify-content-between">
                <button id="clearBtn" class="btn btn-secondary btn-sm">Limpiar</button>
                <button id="verifyBtn" class="btn btn-primary btn-sm">Verificar Firma</button>
            </div>
        </div>

        <!-- Canvas invisible para la firma guardada -->
        <canvas id="canvasGuardado" width="500" height="150" style="display:none;"></canvas>
    </div>

    <script>
        const canvas = document.getElementById("signaturePad");
        const ctx = canvas.getContext("2d");

        let drawing = false;
        let lastX = 0;
        let lastY = 0;

        // --------------------
        // Dibujar
        // --------------------
        function startDrawing(e) {
            drawing = true;
            lastX = e.offsetX;
            lastY = e.offsetY;
        }

        function stopDrawing() {
            drawing = false;
            ctx.beginPath();
        }

        function draw(e) {
            if (!drawing) return;
            ctx.strokeStyle = "#000";
            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            lastX = e.offsetX;
            lastY = e.offsetY;
        }

        canvas.addEventListener("mousedown", startDrawing);
        canvas.addEventListener("mouseup", stopDrawing);
        canvas.addEventListener("mouseout", stopDrawing);
        canvas.addEventListener("mousemove", draw);

        // --------------------
        // Limpiar
        // --------------------
        document.getElementById("clearBtn").addEventListener("click", () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        // --------------------
        // Verificar firma (enviar al backend)
        // --------------------
        document.getElementById("verifyBtn").addEventListener("click", async () => {
            // Verifica que haya trazos
            const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let hasStroke = false;
            for (let i = 0; i < imgData.data.length; i += 4) {
                if (imgData.data[i] < 250 || imgData.data[i + 1] < 250 || imgData.data[i + 2] < 250) {
                    hasStroke = true;
                    break;
                }
            }
            if (!hasStroke) {
                alert("Por favor dibuja tu firma antes de verificar.");
                return;
            }

            // Convertir canvas a Base64
            const canvasBase64 = canvas.toDataURL();
            const firmaGuardada = "{{ Auth::user()->firma }}";
            // Enviar al backend
            const response = await fetch("{{ route('firma.verificar') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    canvas: canvasBase64,
                    firma_guardada: firmaGuardada

                })
            });

            const data = await response.json();
            if (data.is_similar) {
                alert("¡La firma se parece!");
            } else {
                alert("No coincide!");
            }
        });
    </script>

</div>





