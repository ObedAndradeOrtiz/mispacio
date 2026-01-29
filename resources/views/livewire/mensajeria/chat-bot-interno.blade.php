<div class="Messenger_messenger">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <div class="Messenger_header" style="background-color: #061E5C; color: rgb(255, 255, 255);">
        <h4 class="Messenger_prompt" style="color: whitesmoke"></h4>
        <span class="chat_close_icon" style="color: #fff"><strong>-</strong></span>
    </div>
    <div class="Messenger_content">
        <div class="Messages">

            <div class="Messages_list">

                <div>
                    <div class="list-group rounded-0">

                        @if ($respuesta == 0)
                            <div class="row">
                                <h6> <strong>¿Cual de nuestros servicio te interesa? 😉</strong> </h6>
                                <label class="btn btn-primary mt-2" style="font-size: 12px; border-radius: 18px;"
                                    wire:click="tema('Desarrollo Y Venta De Productos Físicos O Digitales')"><strong>Desarrollo
                                        Y Venta De Productos <br> Físicos O Digitales</strong> </label>
                                <label class="btn btn-primary mt-2" style="font-size: 12px; border-radius: 18px;"
                                    wire:click="tema('Proceso De Consultoria Comercial')"><strong>Proceso De <br>
                                        Consultoria Comercial</strong></label>
                                <label class="btn btn-primary mt-2" style="font-size: 12px; border-radius: 18px;"
                                    wire:click="tema('Soluciones De Diseño Y Desarrollo Web')"><strong>Soluciones De
                                        Diseño <br> Y Desarrollo Web</strong></label>
                                <label class="btn btn-primary mt-2" style="font-size: 12px; border-radius: 18px;"
                                    wire:click="tema('Soluciones De Marketing')"><strong>Soluciones De
                                        Marketing</strong></label>
                                <label class="btn btn-primary mt-2" style="font-size: 12px; border-radius: 18px;"
                                    wire:click="tema('Analisis De Negocio')"><strong>Analisis De
                                        Negocio</strong></label>
                                <label class="btn btn-primary mt-2" style="font-size: 12px; border-radius: 18px;"
                                    wire:click="tema('Diseño UX & U')"><strong>Diseño UX & UI</strong></label>
                                <label class="btn btn-primary mt-2" style="font-size: 12px; border-radius: 18px;"
                                    wire:click="tema('Otro')"><strong>Otros</strong></label>
                            </div>
                        @endif
                        @if ($respuesta == 1)
                            <div class="row">
                                <h6> <strong>¡Un gusto saber que te interesa nuestro servicio de <label
                                            style="color: #FFB30E;"
                                            style="font-weight: 700;">{{ $tema }}</label> 🥳! </strong> </h6>
                                <br>
                                <p style="color: #061E5C; font-size: 10px;"><strong>Por favor déjanos comunicarnos
                                        contigo y <br> rellena este formulario</strong> 👇</p>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label style="color: #061E5C; font-size: 10px;" for="email"
                                                class="form-label"><strong>Nombre completo:</strong></label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label style="color: #061E5C; font-size: 10px;" for="email"
                                                class="form-label"><strong>Email:</strong></label>
                                            <input type="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label style="color: #061E5C; font-size: 10px;" for="email"
                                                class="form-label"><strong>Teléfono:</strong></label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label style="color: #061E5C; font-size: 10px;" for="password"
                                                class="form-label"><strong>Déjanos un mensaje:</strong></label>
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <label class="btn btn-primary mt-2 ml-2"
                                        style="font-size: 12px; border-radius: 18px;"
                                        wire:click="guardar"><strong>Enviar</strong> </label>
                                </div>
                            </div>
                        @endif
                        @if ($respuesta == 2)
                            <div class="row">
                                <h6> <strong>¡Mensaje enviado con exito! ✅ </strong> </h6>
                                <br>
                                <p style="color: #061E5C; font-size: 10px;"><strong>Puedes comunicarte directamente con
                                        estos linkl</strong> 👇</p>
                                <div class="row">
                                    <a style="text-decoration: none " href="https://www.instagram.com/bolivianbusinesscenter"><i
                                            class="bi bi-instagram"></i>Instagram</a>
                                            <a style="text-decoration: none"
                                            href="https://api.whatsapp.com/send?phone=59177035251&text=Hola%20BBC,%20Quisiera%20más%20información."><i
                                                class="bi bi-whatsapp"></i>WhatsApp</a>
                                </div>
                                <label class="btn btn-primary mt-2 ml-2"
                                style="font-size: 12px; border-radius: 18px;"
                                wire:click="retornar"><strong>Retornar</strong> </label>
                            </div>
                        @endif


                    </div>
                </div>


            </div>
        </div>

    </div>
</div>
