<div>
<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#eliminar{{$ide}}">
    Eliminar
</button>
   <div class="modal" id="eliminar{{$ide}}" tabindex="-1" aria-hidden="true" >
     <div class="modal-dialog">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="exampleModalLabel">appSia</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">
           <h3>¿Desea eliminar este registro?</h3>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
           <button type="submit" class="btn btn-primary">Aceptar</button>
         </div>
       </div>
     </div>
   </div>
</div>