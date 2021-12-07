<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedore;

class Proveedores extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $rif, $nombre, $email, $direccion, $telefono, $productos, $estatus;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.proveedores.view', [
            'proveedores' => Proveedore::latest()
						->orWhere('rif', 'LIKE', $keyWord)
						->orWhere('nombre', 'LIKE', $keyWord)
						->orWhere('email', 'LIKE', $keyWord)
						->orWhere('direccion', 'LIKE', $keyWord)
						->orWhere('telefono', 'LIKE', $keyWord)
						->orWhere('productos', 'LIKE', $keyWord)
						->orWhere('estatus', 'LIKE', $keyWord)
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
		$this->rif = null;
		$this->nombre = null;
		$this->email = null;
		$this->direccion = null;
		$this->telefono = null;
		$this->productos = null;
		$this->estatus = null;
    }

    public function store()
    {
        $this->validate([
		'rif' => 'required',
		'nombre' => 'required',
		'email' => 'required',
		'direccion' => 'required',
		'telefono' => 'required',
		'productos' => 'required',
		'estatus' => 'required',
        ]);

        Proveedore::create([ 
			'rif' => $this-> rif,
			'nombre' => $this-> nombre,
			'email' => $this-> email,
			'direccion' => $this-> direccion,
			'telefono' => $this-> telefono,
			'productos' => $this-> productos,
			'estatus' => $this-> estatus
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Proveedore Successfully created.');
    }

    public function edit($id)
    {
        $record = Proveedore::findOrFail($id);

        $this->selected_id = $id; 
		$this->rif = $record-> rif;
		$this->nombre = $record-> nombre;
		$this->email = $record-> email;
		$this->direccion = $record-> direccion;
		$this->telefono = $record-> telefono;
		$this->productos = $record-> productos;
		$this->estatus = $record-> estatus;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'rif' => 'required',
		'nombre' => 'required',
		'email' => 'required',
		'direccion' => 'required',
		'telefono' => 'required',
		'productos' => 'required',
		'estatus' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Proveedore::find($this->selected_id);
            $record->update([ 
			'rif' => $this-> rif,
			'nombre' => $this-> nombre,
			'email' => $this-> email,
			'direccion' => $this-> direccion,
			'telefono' => $this-> telefono,
			'productos' => $this-> productos,
			'estatus' => $this-> estatus
            ]);

            $this->resetInput();
            $this->updateMode = false;
			$this->emit('closeModalupdate');
			session()->flash('message', 'Proveedore Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Proveedore::where('id', $id);
            $record->delete();
        }
    }
}
