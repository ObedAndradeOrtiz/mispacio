<div>
    <video id="cam" autoplay playsinline></video>
    <button id="capture">Comparar con mi rostro</button>
    <canvas id="snap" style="display:none;"></canvas>

    <script>
        const video = document.getElementById('cam');
        const canvas = document.getElementById('snap');
        navigator.mediaDevices.getUserMedia({
            video: true
        }).then(s => video.srcObject = s);

        document.getElementById('capture').onclick = async () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const base64 = canvas.toDataURL('image/jpeg'); // foto actual

            const res = await fetch('/verificar-facial', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    foto: base64
                })
            });
            const json = await res.json();
            alert(JSON.stringify(json, null, 2));
        };
    </script>

</div>
