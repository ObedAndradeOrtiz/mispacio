<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->

<head>
    <style>
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #333;
        }

        textarea {
            padding: 10px;
            margin-bottom: 45px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Saas Startup App HTML Template">
    <meta name="author" content="DynamicLayers">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Truno | Sorteo Gratuito</title>

    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">

    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/elegant-line-icons.css">
    <link rel="stylesheet" href="css/truno-icons.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/slicknav.min.css">
    <link rel="stylesheet" href="css/pricing-table.css">
    <link rel="stylesheet" href="css/odometer.min.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/venobox.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/responsive.css">

    <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>

<body data-spy="scroll" data-target="#mainmenu" data-offset="80">

    <div class="sponsor-section padding">
        <div class="container">
            <div style="display: flex; justify-content:center;">

                <form action="#" method="post" id="raffleForm">
                    <h1>Selector gratuito de sorteo aleatorio</h1>
                    <label for="usernames">Nombres de Participantes (separados por coma):</label>
                    <textarea id="usernames" name="usernames" rows="10" required></textarea>
                    <button style="margin-top:10%;" class="default-btn orange" type="submit" name="submit">Realizar
                        Sorteo</button>
                </form>
            </div>

        </div>
    </div><!--/. sponsor-section -->


    <!-- jQuery Lib -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/vendor/tether.min.js"></script>
    <script src="js/vendor/jquery.slicknav.min.js"></script>
    <script src="js/vendor/owl.carousel.min.js"></script>
    <script src="js/vendor/smooth-scroll.min.js"></script>
    <script src="js/vendor/jquery.ajaxchimp.min.js"></script>
    <script src="js/vendor/pricing-switcher.js"></script>
    <script src="js/vendor/jquery.waypoints.v2.0.3.min.js"></script>
    <script src="js/vendor/odometer.min.js"></script>
    <script src="js/vendor/wow.min.js"></script>
    <script src="js/vendor/venobox.min.js"></script>
    <script src="js/main.js"></script>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("raffleForm").addEventListener("submit", function(event) {
            event.preventDefault();

            const usernamesInput = document.getElementById("usernames");
            const usernames = usernamesInput.value.split(',').map(username => username.trim());

            if (usernames.length === 0) {
                alert("Por favor, ingresa al menos un nombre de usuario.");
                return;
            }

            const randomIndex = Math.floor(Math.random() * usernames.length);
            const winner = usernames[randomIndex];
            let timerInterval
            Swal.fire({
                title: 'Resutado aleatorio en solo segundos!',
                html: 'Resultado en <b></b> millisegundos.',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    Swal.fire({
                        title: `¡Felicidades, ! Eres el ganador del sorteo.`,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    })
                }
            })


        });
    });
</script>

</html>
