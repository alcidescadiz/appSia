<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tipospago;

class Tipospagos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $tipo, $estatus;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.tipospagos.view', [
            'tipospagos' => Tipospago::latest()
						->orWhere('tipo', 'LIKE', $keyWord)
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
		$this->tipo = null;
		$this->estatus = null;
    }

    public function store()
    {
        $this->validate([
            'tipo' => 'unique:tipospagos|required',
        ]);

        Tipospago::create([ 
			'tipo' => $this-> tipo,
			'estatus' => 'activo'
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tipospago Successfully created.');
    }

    public function edit($id)
    {
        $record = Tipospago::findOrFail($id);

        $this->selected_id = $id; 
		$this->tipo = $record-> tipo;
		$this->estatus = $record-> estatus;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'tipo' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Tipospago::find($this->selected_id);
            $record->update([ 
			'tipo' => $this-> tipo,
			'estatus' => 'activo'
            ]);

            $this->resetInput();
            $this->updateMode = false;
            $this->emit('closeModalupdate');
			session()->flash('message', 'Tipospago Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Tipospago::where('id', $id);
            $record->delete();
        }
    }
}
