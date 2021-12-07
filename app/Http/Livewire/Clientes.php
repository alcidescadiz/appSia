<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;

class Clientes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $cedula, $nombre, $email, $direccion, $telefono, $estatus;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.clientes.view', [
            'clientes' => Cliente::latest()
						->orWhere('cedula', 'LIKE', $keyWord)
						->orWhere('nombre', 'LIKE', $keyWord)
						->orWhere('email', 'LIKE', $keyWord)
						->orWhere('direccion', 'LIKE', $keyWord)
						->orWhere('telefono', 'LIKE', $keyWord)
						->orWhere('estatus', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel(){
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->cedula = null;
		$this->nombre = null;
		$this->email = null;
		$this->direccion = null;
		$this->telefono = null;
    }

    public function store()
    {
        $this->validate([
		'cedula' => 'unique:clientes|required',
		'nombre' => 'required',
		'email' => 'unique:clientes|required',
		'direccion' => 'required',
		'telefono' => 'unique:clientes|required',
        ]);

        Cliente::create([ 
			'cedula' => $this-> cedula,
			'nombre' => $this-> nombre,
			'email' => $this-> email,
			'direccion' => $this-> direccion,
			'telefono' => $this-> telefono,
			'estatus' => 'activo'
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Cliente Successfully created.');
    }

    public function edit($id)
    {
       $record = Cliente::findOrFail($id);

        $this->selected_id = $id; 
		$this->cedula = $record-> cedula;
		$this->nombre = $record-> nombre;
		$this->email = $record-> email;
		$this->direccion = $record-> direccion;
		$this->telefono = $record-> telefono;
		
        $this->updateMode = true;
    
    }

    public function update()
    {
        $this->validate([
            'cedula' => 'required',
            'nombre' => 'required',
            'email' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
        ]);
    
        //dd($validacion); 
        if ($this->selected_id) {
			$record = Cliente::find($this->selected_id);
            $record->update([ 
			'cedula' => $this-> cedula,
			'nombre' => $this-> nombre,
			'email' => $this-> email,
			'direccion' => $this-> direccion,
			'telefono' => $this-> telefono,
			'estatus' => 'activo'
            ]);

            $this->resetInput();
            $this->updateMode = false;
            $this->emit('closeModalupdate');
			session()->flash('message', 'Cliente Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Cliente::where('id', $id);
            $record->delete();
        }
    }
}
