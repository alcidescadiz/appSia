<div>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#consulta">
  Generar consulta SQL
</button>
   <div class="modal" id="consulta" tabindex="-1" aria-hidden="true" >
     <div class="modal-dialog">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="exampleModalLabel">appSia</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body"  >
          <textarea name="consulta" required rows="10" cols="50" style="width: 95%; font-size: 18px; font-weight: bolder"  class="form-control" > </textarea>
         </div>
         <div style="width: 90%">
           <ul style="">
             <li > <b> Campos de la tabla {{$nombre}}: {{$campos}} </b></li>
             <li >select * from {{$nombre}} </li>
             <li >select * from {{$nombre}} where id = '2'</li>
             <li >select sum(id) as suma from {{$nombre}}  </li>
             <li >delete from {{$nombre}} where id = '2' </li>
            </ul>
          </div>
          <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
           <button type="submit" class="btn btn-info">Generar consulta SQL</button>
         </div>
       </div>
     </div>
   </div>
</div>