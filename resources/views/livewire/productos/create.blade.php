<!-- Modal -->
<div wire:ignore.self class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create New Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="codigo">Codigo</label>
                <input wire:model="codigo" type="text" class="form-control" id="codigo" placeholder="Codigo">@error('codigo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nombre">Nombre del producto</label>
                <input wire:model="nombre" type="text" class="form-control" id="nombre" placeholder="Nombre">@error('nombre') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="costo">Costo</label>
                <input wire:model="costo" type="number" step="0.01" class="form-control" id="costo" wire:change="precio()">@error('costo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="porcentage_ganancia">Porcentaje de Ganancia</label>
                <input wire:model="porcentage_ganancia" type="number" step="0.01" class="form-control" id="porcentage_ganancia"  wire:change="precio()">@error('porcentage_ganancia') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="precio_venta">Precio de venta</label>
                <input wire:model="precio_venta" type="number" readonly step="0.01" class="form-control" id="precio_venta" wire:click="precio()">@error('precio_venta') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="gravable">¿Es gravable?</label>
                <select wire:model="gravable" type="text" class="form-control" id="gravable">
                    <option value="">Seleccione:</option>
                    <option value="si">SI</option>
                    <option value="no">NO</option>
                <select>
                    @error('gravable')<span class="error text-danger">{{ $message }}</span> @enderror
            </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save</button>
            </div>
        </div>
    </div>
</div>
