<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miss Beaty Queen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Calistoga&family=Gothic+A1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap">
    <style>
        @font-face {
            font-family: 'NT Marley Medium';
            src: url('mbp/fonts/NT-Marley-Medium.otf') format('opentype');
            font-weight: normal;
            font-style: normal;
        }

        .poping {
            font-family: "Calistoga", serif;
            font-weight: 400;
            font-style: normal;
        }

        body {
            background-color: #000;
            color: white;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .diagonal-line {
            position: fixed;
            width: 200%;
            height: 4px;
            background: gold;
            box-shadow: 0 0 10px gold;
        }

        .diagonal-line-only {
            height: 4px;
            background: gold;
            box-shadow: 0 0 10px gold;
            width: 20%;
        }

        .diagonal-line.top-left {
            top: 20%;
            left: 0;
            transform: rotate(45deg);
        }

        .diagonal-line.bottom-left {
            bottom: 20%;
            left: 0;
            transform: rotate(-45deg);
        }

        .diagonal-line.top-right {
            top: 20%;
            right: 0;
            transform: rotate(-45deg);
        }

        .diagonal-line.bottom-right {
            bottom: 20%;
            right: 0;
            transform: rotate(45deg);
        }

        @media (max-width: 1200px) {

            .diagonal-line.top-left,
            .diagonal-line.top-right {
                top: 10%;
            }

            .diagonal-line.bottom-left,
            .diagonal-line.bottom-right {
                bottom: 10%;
            }
        }

        /* Efecto de destellos para un toque de película */
        @keyframes shimmer {
            0% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.5;
            }
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.05);
            pointer-events: none;
            z-index: 1;
            animation: shimmer 1.3s infinite;
        }

        body::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            pointer-events: none;
            z-index: 0;
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

            .diagonal-line-only {
                width: 50%;
            }
        }

        .great {
            font-family: 'Great Vibes', cursive;
        }

        .logo {
            width: 350px;
        }

        .images {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .images div {
            margin: 0px;
        }

        .images img {
            width: 300px;
            border-radius: 10px;
        }

        .btn-comprar {
            background: linear-gradient(to bottom, #ffd700, #ffd900be);
            border: none;
            color: black;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 12px;
            margin-top: 20px;
            cursor: pointer;
        }

        .btn-comprar:hover {
            opacity: 0.8;
        }

        /* Preloader Styles */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: black;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .preloader img {
            width: 150px;
        }
    </style>
</head>

<body>
    <div class="preloader" id="preloader">
        <img src="mbq/logo.png" alt="Cargando...">
    </div>
    <div class="diagonal-line top-left"></div>
    <div class="diagonal-line bottom-right"></div>
    <div class="particles" id="particles"></div>
    <img src="mbq/logo.png" alt="Logo" class="logo">
    <div class="poping">
        <span
            style="background: linear-gradient(to bottom, #ffd700, #ffffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Unete a nosotros en el certamen de belleza
        </span>, donde celebramos la elegancia y el talento de nuestras participantes.
    </div>
    <div class="poping">
        <span
            style="background: linear-gradient(to bottom, #ffd700, #ffffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Este evento no solo destaca la belleza,
        </span> sino también incluye show de artistas y la presencia de auspiciadores de diversas empresas.
    </div>

    <img src="mbq/miss.png" alt="Logo" class="logo" style="width:150px; height: 250px">
    <div class="diagonal-line-only"></div>
    <div class="text poping">
        <h2>
            <span
                style="background: linear-gradient(to bottom, #efdc75, #ffffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Show de
            </span> artistas
        </h2>
    </div>
    <div class="diagonal-line-only"></div>
    <div class="images" style="margin-top: 2%;">
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
    <a href="/comprar-entradas" class="btn-comprar poping" style="color:#ffffff; margin-bottom:5%;">Compra tu
        entrada</a>
    <script>
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            preloader.style.display = 'none';
        });

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

</html>
