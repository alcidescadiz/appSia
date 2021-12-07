<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class Productos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $codigo, $nombre, $costo, $porcentage_ganancia, $precio_venta, $gravable;
    public $updateMode = false;

	public function precio(){	
		if ($this->costo && $this->porcentage_ganancia) {
			$this->precio_venta=$this->costo*1 +($this->costo * $this->porcentage_ganancia/100);
		}else {
			$this->precio_venta=0;
		}
	}
    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.productos.view', [
            'productos' => Producto::latest()
						->orWhere('codigo', 'LIKE', $keyWord)
						->orWhere('nombre', 'LIKE', $keyWord)
						->orWhere('costo', 'LIKE', $keyWord)
						->orWhere('porcentage_ganancia', 'LIKE', $keyWord)
						->orWhere('precio_venta', 'LIKE', $keyWord)
						->orWhere('gravable', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->codigo = null;
		$this->nombre = null;
		$this->costo = null;
		$this->porcentage_ganancia = null;
		$this->precio_venta = null;
		$this->gravable = null;
    }

    public function store()
    {
        $this->validate([
		'codigo' => 'unique:productos|required',
		'nombre' => 'unique:productos|required',
		'costo' => 'required',
		'porcentage_ganancia' => 'required',
		'precio_venta' => 'required',
		'gravable' => 'required',
        ]);

        Producto::create([ 
			'codigo' => $this-> codigo,
			'nombre' => $this-> nombre,
			'costo' => $this-> costo,
			'porcentage_ganancia' => $this-> porcentage_ganancia,
			'precio_venta' => $this-> precio_venta,
			'gravable' => $this-> gravable,
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Producto Successfully created.');
    }

    public function edit($id)
    {
        $record = Producto::findOrFail($id);

        $this->selected_id = $id; 
		$this->codigo = $record-> codigo;
		$this->nombre = $record-> nombre;
		$this->costo = $record-> costo;
		$this->porcentage_ganancia = $record-> porcentage_ganancia;
		$this->precio_venta = $record-> precio_venta;
		$this->gravable = $record-> gravable;
		$this->foto = $record-> foto;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'codigo' => 'required',
		'nombre' => 'required',
		'costo' => 'required',
		'porcentage_ganancia' => 'required|numeric',
		'precio_venta' => 'required',
		'gravable' => 'required',
		'foto' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Producto::find($this->selected_id);
            $record->update([ 
			'codigo' => $this-> codigo,
			'nombre' => $this-> nombre,
			'costo' => $this-> costo,
			'porcentage_ganancia' => $this-> porcentage_ganancia,
			'precio_venta' => $this-> precio_venta,
			'gravable' => $this-> gravable,
			'foto' => $this-> foto,
            ]);

            $this->resetInput();

            $this->updateMode = false;
			$this->emit('closeModalupdate');
			session()->flash('message', 'Producto Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Producto::where('id', $id);
            $record->delete();
        }
    }

}
