<?php 
include('conexion/conexion.php');
include('class/classBd.php');

$users = $classBD->getDataTableMultiples(array("empleado" => "*", "areas" => "nombre as nombreareas"),"where empleado.area_id = areas.id");

//exit();
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<link rel="stylesheet" href="css/bootstrap.css">
 	<title>PruebaLuisCoronado</title>
 </head>
 <body id="showPage">
 
<section class="row">
  <article class="col-sm-6">


          <form action="" method="post"  name="formUsers" id="formUsers" autocomplete="off">
          	<h2 class="modal-title">Crear empleado</h2>
            <section class="row mT-sm">
               <article class="col-sm-6">
                  <label for="nombre" class="control-label">* Nombre Completo:</label>
                  <input type="text" class="form-control "  name="nombre" id="nombre" value="" data-error="Debes completar el nombre">
                </article>
                <article class="col-sm-6">
                  <label for="email" class="control-label">* Correo:</label>
                  <input type="email" class="form-control "  name="email" id="email" value="" data-error="Debes completar el email">
                </article>
            </section>
            <section class="row mT-sm">
               <article class="col-sm-6">
                  <label for="sexo" class="control-label">* Sexo:</label>
                  <br>
                   <label for="dewey">Masculino</label>
                  <input type="radio" id="sexo" name="sexo" value="M" checked >
                  <hr>
                   <label for="dewey">Femenino</label>
                  <input type="radio" id="sexo" name="sexo" value="F">
                </article>         
                <article class="col-sm-6">
                  <label for="area_id" class="control-label">* Area:</label>
                  <?php $areas = $classBD->getDataTable("areas"); ?>
                  <select class="form-control" name="area_id" id="area_id">
                  	<option value="">Seleccione..</option>
                  	<?php foreach ($areas as $keyarea) { ?>
                      <option value="<?php echo $keyarea['id'] ?>"><?php echo $keyarea['nombre'] ?></option>
                  		<?php } ?>
                    </select>
                </article>   
            </section>
            <section class="row mT-sm">
            	<article class="col-sm-6">
            		</article> 
               <article class="col-sm-6">
                  <label for="boletin" class="control-label">* Roles:</label><hr>
                  <?php $roles = $classBD->getDataTable("roles"); 
                  	$count = 0;
                   foreach ($roles as $keyroles) { ?>
                  	<label for="cbox2"><?php echo $keyroles['nombre'] ?></label>
                  	<input type="checkbox" name="roles" id="roles[<?php echo $count; ?>]" value="<?php echo $keyroles['id'] ?>">
                  	<br> 
                  <?php 
                  	$count++; } ?>
                </article> 
            </section>
            <section class="row mT-sm">
                <article class="col-sm-6">
                  <label for="descripcion" class="control-label">* descripcion:</label>
                  <textarea type="tex" class="form-control" name="descripcion" id="descripcion" data-error="Debes completar la descripcion"></textarea>
                </article>   
               <article class="col-sm-6">
               	<br>
               	<hr>
               	 <label for="descripcion" class="control-label">* Deseo recibir boletin informativo:</label>
                  <input type="checkbox" name="boletin" id="boletin" value="1" checked> 
                </article>         
            </section>
            <section class="row mT-sm">
              <article class="col-sm-6">                  
                </article>
            </section>
            <section class="row mT-sm">
               <article class="col-sm-3">
                  <input type="hidden" name="idUser" id='idUser' value="">
                  <div id="saveForm"  class="btn-save" >Guardar</div>
            </section>
          </form>


  </article>
</section>




<hr>
<section class="row mT-md">
  <article class="col-sm-12" id="tablecontents">
    <table id="tbla-consulta" class="table table-condensed table-striped" data-button="false" data-select="excel,pdf" >
      <thead>
        <tr>          
          <th>nombre <i class="fab fa-mdb"></i></th>
          <th>email</th>
          <th>sexo</th>
          <th>area</th>
          <th>roles</th>
          <th>descripcion</th>
          <th>boletin</th>
          <th>Editar</th>
          <th>Borrar</th>          
        </tr>
      </thead>
      <tbody>
        <?php 
      foreach ($users as $key) {  

      	//$roles = $classBD->getDataTable("empleado_rol", "where empleado_id = ".$key['id']);
      	$roles = $classBD->getDataTableMultiples(array("empleado_rol" => "*", "roles" => "*"), "where empleado_rol.rol_id = roles.id and empleado_rol.empleado_id = ".$key['id']);
      	

        $editar = array(
                    'idUser' => $key['id'],
                    'nombre' => $key['nombre'],
                    'email' => $key['email'],
                    'sexo' => $key['sexo'],	
                    'area_id' => $key['area_id'],
                    'boletin' => $key['boletin'],
                    'descripcion' => $key['descripcion']
                  );
        $editarJs  = base64_encode(json_encode($editar));
        $delete  = array(
                      'val' => 'ELIMINARempleado',
                      'tabla' => 'empleado',
                      'columna' => 'id',
                      'id' => $key['id'],
                      'funcion' => 'cargarPage',
                      'msj_confirmacion' => "¿Desea eliminar definitivamente este Usuario ?"
                    );
          $deleteJs  = base64_encode(json_encode($delete));

          $texboletin = "";
          	if($key['boletin'] == 1){
				$texboletin = "Si";
          	}else{
          		$texboletin = "No";
          	}
         ?>
        <tr>   
          <td><?php echo $key['nombre'] ?></td>    
          <td><?php echo $key['email'] ?></td>    
          <td><?php echo $key['sexo'] ?></td>    
          <td><?php echo $key['nombreareas'] ?></td>    
          <td><?php foreach ($roles as $keyroles) { echo $keyroles['nombre']."<br>"; } ?></td>    
          <td><?php echo $key['descripcion'] ?></td> 
          <td><?php echo $texboletin ?></td> 
          <td align="center">
          	<i onclick="editarFormulario('<?php echo $editarJs ?>')" ><img src="https://img1.freepng.es/20180920/eqx/kisspng-computer-icons-editing-portable-network-graphics-i-edit-profile-svg-png-icon-free-download-194863-5ba3457963b929.9651381015374268094085.jpg"></i>
          </td> 
          <td align="center">
          	<i onclick="confirmarAction('<?php echo $deleteJs ?>',borrarRegistro)" ><img src="https://www.pikpng.com/pngl/m/247-2474264_png-file-svg-delete-icon-svg-clipart.png"></i>
          </td>  
        </tr>
       <?php } ?>
      </tbody>
    </table>
  </article>
</section>

</body>
<script type="text/javascript" src ="js/jquery.js"></script>
<script type="text/javascript" src = "js/ajaxJquery.js"></script>
<script type="text/javascript" src = "js/functions.js"></script>
<script type="text/javascript" src = "js/bootstrap.js"></script>
 </html>
