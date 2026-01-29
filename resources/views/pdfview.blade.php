{{-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Calistoga&family=Gothic+A1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap">
    <style>
        .poping {
            font-family: "Calistoga", serif;
            font-weight: 400;
            font-style: normal;
        }

        body {
            margin: 0;
            padding: 0;
            background-image: url('fondo.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            color: #fff;

            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: auto;
        }

        .great {
            font-family: 'Great Vibes', cursive;
        }

        .logo {
            margin-top: 50px;
            width: 350px;
            height: auto;
        }

        .images {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .images div {
            margin: 20px;
        }

        .images img {
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .particles {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            bottom: 0;
            background-color: #ffd700;
            border-radius: 50%;
            opacity: 0;
            animation: rise 5s linear infinite;
        }

        @keyframes rise {
            0% {
                transform: translateY(0);
                opacity: 1;
            }

            100% {
                transform: translateY(-1500px);
                opacity: 0;
            }
        }

        @media (max-width: 1200px) {
            .images {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <img src="mbq/logo.png" alt="Logo" class="logo">
    <div class="text poping">
        <span
            style="
            background: linear-gradient(to bottom, #ffd700, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        ">No
            te pierdas</span> este asombroso evento!
    </div>
    <div class="text poping">
        <span
            style="
            background: linear-gradient(to bottom, #efdc75, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        ">Nuestros</span>
        artistas
    </div>

    <div class="images">
        <div>
            <img src="mbq/mafia.png" alt="Imagen 1">
            <br>
            <label class="great">Mafia Latina</label>
        </div>
        <div>
            <img src="mbq/jorge.png" alt="Imagen 2">
            <br>
            <label class="great">Jorge David</label>
        </div>
    </div>
    <div>
        <a href="/comprar-entradas" class="btn btn-success">Comprar entradas</a>
    </div>
    <div class="particles" id="particles"></div>

    <script>
        function createParticle() {
            const particlesContainer = document.getElementById('particles');
            const particle = document.createElement('div');
            particle.className = 'particle';
            const size = Math.random() * 5 + 2 + 'px';
            particle.style.width = size;
            particle.style.height = size;
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = '10s';
            particlesContainer.appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 3000);
        }

        setInterval(createParticle, 300);
    </script>
</body>

</html> --}}
