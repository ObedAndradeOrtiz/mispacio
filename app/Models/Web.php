<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Web extends Model
{
    use HasFactory;
    protected $fillable=[
   'iduser',
   'colorFondo',
   'tipoLetra',
   'anguloFondo',
   'titulo',
   'bibliografia',
   'efectoTitulo',
   'efectoSubtitulo',
   'alineacionTitulo',
   'alineacionSubtitulo',
   'alineacionPerfil',
   'sizeTitle',
   'sizeSubtitle',
   'sizeBorde',
   'colorTitle',
   'colorSubtitle',
   'colorBorde',
   'imagePath',
   'estado',
   'nombrepagina',
   'imagePresentacion',
   'sizePresentacion',
   'efectoPresentacion',
   'nombrepaginageneral',
   'numeroWss',
   'codigoPais',
   'posicionIcono',
   'estadoFacebook',
   'estadoInsta',
   'estadoWss',
   'estadoTiktok',
   'rutaFacebook',
   'rutaInsta',
   'rutaWss',
   'rutaTiktok',
   'enlaceFacebook',
   'enlaceInsta',
   'enlaceWss',
   'enlaceTiktok',
   'fondoNeon',
   'tipoIcono',
   'tipoIconoColor',
   'colorIconoNeon',
   'estadoIconoNeon',
   'clickTiktok',
   'clickFacebook',
   'clckWss',
   'clickInsta',
'colorBordeCategoria','cantidadVisitas','estadoCarrito','estiloFormaWeb'];
}
