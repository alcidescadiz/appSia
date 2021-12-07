<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="codigo">Id</label>
                <input wire:model="codigo" type="text" readonly class="form-control" id="codigo" placeholder="Codigo">@error('codigo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input wire:model="nombre" type="text" class="form-control" id="nombre" placeholder="Nombre">@error('nombre') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="costo">Costo</label>
                <input wire:model="costo" type="number" step="0.01" class="form-control" id="costo" wire:change="precio()" placeholder="Costo">@error('costo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="porcentage_ganancia">Porcentaje de ganancia</label>
                <input wire:model="porcentage_ganancia" type="number" step="0.01" class="form-control" wire:change="precio()" id="porcentage_ganancia" placeholder="Porcentage Ganancia">@error('porcentage_ganancia') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="precio_venta">Precio de venta</label>
                <input wire:model="precio_venta" type="number" step="0.01" class="form-control" id="precio_venta" placeholder="Precio Venta" readonly>@error('precio_venta') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="gravable">Gravable</label>
                <select wire:model="gravable" type="text" class="form-control" id="gravable" placeholder="Gravable">
                    <option value="si">SI</option>
                    <option value="no">NO</option>
                </select>
                @error('gravable') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="foto">Link imagen</label>
                <input wire:model="foto" type="text" class="form-control" id="foto" placeholder="Foto">@error('foto') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="update()" data-bs-dismiss="modal" class="btn btn-primary">Save</button>
            </div>
       </div>
    </div>
</div>