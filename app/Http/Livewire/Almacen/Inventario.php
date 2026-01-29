<?php

namespace App\Http\Livewire\Almacen;

use App\Models\Areas;
use App\Models\categoria_inventario;
use App\Models\Inventario as ModelsInventario;
use App\Models\Productos;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Inventario extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $opcion = 0;
    public $crearproducto = false;
    public $crearcategoria = false;
    public $image;
    public $nombre = "";
    public $descripcion = "";
    public $nombre_categoria = "";
    public $descripcion_categoria = "";
    public $precio = 0;
    public $idcategoriaseleccionada;
    public $productos;
    public $producto_seleccinado;
    public $editar_producto = false;
    public $categoria;
    public $categorias;
    public $editar_categoria = false;
    public $busqueda;
    public $categoria_busqueda;
    public $idcategoria_seleccionada;
    protected $listeners = ['render' => 'render', 'editarProducto' => 'editarProducto', 'editarCategoria' => 'editarCategoria'];
    protected function rulesCategoria()
    {
        return [
            'nombre_categoria' => 'required|min:3',
            'descripcion_categoria' => 'nullable|min:5',
        ];
    }

    protected function messages()
    {
        return [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio.',
            'nombre_categoria.min' => 'El nombre debe tener al menos 3 caracteres.',
            'descripcion_categoria.min' => 'La descripción debe tener al menos 5 caracteres.',
        ];
    }
    protected function rulesProducto()
    {
        return [
            'nombre' => 'required|min:3',
            'descripcion' => 'required|min:5',
            'categoria' => 'required',
            'precio' => 'required|numeric|min:0',
        ];
    }
    public function render()
    {
        $this->categorias = categoria_inventario::where('estado', 'Activo')->get();
        $listaproductos = ModelsInventario::where('estado', 'Activo')
            ->where(function ($query) {
                if (is_numeric($this->busqueda)) {
                    $query->where('idproducto', $this->busqueda);
                } else {
                    $query->where('nombre', 'ilike', '%' . $this->busqueda . '%');
                }
            })->where('categoria', 'ilike', '%' . $this->categoria_busqueda . '%')
            ->orderBy('nombre')
            ->paginate(10);
        return view('livewire.almacen.inventario',  [
            'listaproductos' => $listaproductos
        ]);
    }
    public function setOpcion($number)
    {
        $this->opcion = $number;
        $this->render();
    }
    public function editarProducto($idproducto)
    {
        $this->producto_seleccinado = ModelsInventario::find($idproducto);
        $this->nombre = $this->producto_seleccinado->nombre;
        $this->categoria = $this->producto_seleccinado->categoria;
        $this->descripcion = $this->producto_seleccinado->descripcion;
        $this->precio = $this->producto_seleccinado->precio;
        $this->editar_producto = true;
    }
    public function eliminarProducto($idproducto) {}
    public function GuardarProducto()
    {
        if ($this->producto_seleccinado) {
            $this->validate($this->rulesProducto());

            $nuevo = $this->producto_seleccinado;
            $anterior_nombre = $nuevo->nombre;
            $nuevo->nombre = $this->nombre;
            $nuevo->descripcion = $this->descripcion;
            $nuevo->precio = $this->precio;
            $nuevo->categoria = $this->categoria;
            $nuevo->estado = "Activo";
            $nuevo->save();
            $sucursales = Areas::where('estado', 'Activo')->get();
            foreach ($sucursales as $sucursal) {
                $producto = Productos::where('nombre', $anterior_nombre)->where('sucursal', $sucursal->area)->first();
                $producto->nombre =  $nuevo->nombre;
                $producto->precio = $this->precio;
                $producto->save();
            }
        } else {
            $this->validate($this->rulesProducto());
            $verificar = Productos::where('nombre', $this->nombre)->first();
            if ($verificar) {
                $this->emit('error', '¡El producto ya es existente!');
            } else {
                $ultimoIdInventario = ModelsInventario::max('idproducto');
                $nuevoIdInventario = $ultimoIdInventario < 1000 ? 1000 : $ultimoIdInventario + 1;
                $nuevo = new ModelsInventario;
                $nuevo->idproducto =  $nuevoIdInventario;
                $nuevo->nombre = $this->nombre;
                $nuevo->descripcion = $this->descripcion;
                $nuevo->precio = $this->precio;
                $nuevo->categoria = $this->categoria;
                $nuevo->estado = "Activo";
                $nuevo->save();
                $sucursales = Areas::where('estado', 'Activo')->get();
                foreach ($sucursales as $sucursal) {
                    $producto = new Productos;
                    $producto->estado = 'Activo';
                    $producto->nombre = $this->nombre;
                    $producto->descripcion = $this->descripcion;
                    $producto->precio = $this->precio;
                    $producto->cantidad = 0;
                    $producto->sucursal = $sucursal->area;
                    $producto->idinventario = $nuevoIdInventario;
                    $producto->fechainicio =  Carbon::now()->toDateString();
                    $producto->save();
                }

                $this->emit('alert', '¡Producto creado!');
            }
        }
        $this->reset();
        $this->render();
    }
    public function  editarCategoria($idcategoria)
    {
        $this->idcategoria_seleccionada = $idcategoria;
        $categoria = categoria_inventario::find($idcategoria);
        $this->nombre_categoria = $categoria->nombre;
        $this->descripcion_categoria = $categoria->descripcion;
        $this->editar_categoria = true;
    }
    public function  GuardarCategoria()
    {
        $this->validate($this->rulesCategoria());
        if ($this->editar_categoria  == true) {
            $categoria = categoria_inventario::find($this->idcategoria_seleccionada);
            $categoria->nombre = $this->nombre_categoria;
            $categoria->descripcion = $this->descripcion_categoria;
            $categoria->save();
        } else {
            categoria_inventario::create([
                'nombre' => $this->nombre_categoria,
                'descripcion' => $this->descripcion_categoria,
                'estado' => 'Activo'
            ]);
        }

        $this->reset();
        $this->render();
    }
}
