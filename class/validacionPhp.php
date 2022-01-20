<?php
ini_set('display_errors',1);
include('../conexion/conexion.php');
include('classBd.php');

/*----------------------------------------------------------------------[ fin val]------------------------------------------------*/
#																																	#
#																																	#
#																																	#
/*----------------------------------------------------------------------[ fin val]------------------------------------------------*/
if($_POST['val'] == 'ELIMINARempleado'){
	// print_r($_POST);
	$delete = $classBD->deleteDataTable($_POST);
	if($delete == true){
		$resp['insert'] = 1;
		$resp['msj'] = "Se actualizaron los datos correctamente";
	}else{
		$resp['insert'] = 0;
		$resp['msj'] = "No se actualizaron los datos";
	}
		$users = $classBD->getDataTableMultiples(array("empleado" => "*", "areas" => "nombre as nombreareas"),"where empleado.area_id = areas.id");
		$resp['html'] = '<table id="tbla-consulta" class="table table-condensed table-striped" data-button="false" data-select="excel,pdf" >
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
      <tbody>';
      foreach ($users as $key) { 
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

          $functions = "confirmarAction('".$deleteJs."', borrarRegistro)";
		
		$rolesresp = "";
          foreach ($roles as $keyroles) { 
          	$rolesresp .= $keyroles['nombre'].'<br>'; 
          	}

          	$texboletin = "";
          	if($key['boletin'] == 1){
				$texboletin = "Si";
          	}else{
          		$texboletin = "No";
          	}


        $resp['html'] .= '<tr>   
          <td>'.$key['nombre'].'</td>    
          <td>'.$key['email'].'</td> 
          <td>'.$key['sexo'].'</td>  
          <td>'.$key['nombreareas'].'</td>    
          <td>'.$rolesresp.'</td>   
          <td>'.$key['descripcion'].'</td> 
          <td>'.$texboletin.'</td> 
          <td align="center">
          	<i onclick=editarFormulario("'.$editarJs.'") ><img src="https://img1.freepng.es/20180920/eqx/kisspng-computer-icons-editing-portable-network-graphics-i-edit-profile-svg-png-icon-free-download-194863-5ba3457963b929.9651381015374268094085.jpg"></i>
          </td> 
          <td align="center">
          	<i onclick="'.$functions.'" ><img src="https://www.pikpng.com/pngl/m/247-2474264_png-file-svg-delete-icon-svg-clipart.png"></i>
          </td>  
        </tr>'; 

    } 
    $resp['html'] .= '</tbody></table>';
	
	header('Content-Type: application/json');
	echo json_encode($resp,true);
}
/*----------------------------------------------------------------------[ fin val]------------------------------------------------*/
#																																	#
#																																	#
#																																	#
/*----------------------------------------------------------------------[ fin val]------------------------------------------------*/
if ($_POST['val'] == 'INSERTARempleado') {
	unset($_POST['val']);

	
	//unset($_POST['saveForm']);
	//$_POST['fecha'] = date("Y-m-d");
	if($_POST['boletin'] == ""){
	$_POST['boletin'] = 0;
	}
	$usr = $_POST['id'];
	$roles = explode(",", $_POST['roles']);
	unset($_POST['id']);
	unset($_POST['roles']);
	
	$resp = array('insert' => 0,'postData'=>$_POST,'msj' => "Error al guardar");
	if(empty($usr)){
		$condicion = " where email = '{$_POST['email']}' ";
		$data = $classBD->getDataTable("empleado", $condicion);
		if(count($data) > 0){
			$resp = array('insert' => 0,'msj' => "Ya existe un usuario con este correo");
			$save = false;
		}else{

			$save = $classBD->insertarDataTable("empleado", $_POST);
			$idinsert = $classBD->getMaxIdTable("empleado", "id");	
			for ($i=0; $i < count($roles); $i++) { 
				$save = $classBD->insertarDataTable("empleado_rol", array("empleado_id" => $idinsert['lastId'], "rol_id" => $roles[$i]));			
			}
		}
	}else{
		$save = $classBD->actualizarDataTable("empleado", $_POST, "where id = {$usr}");

		$delete  = array(
                      'tabla' => 'empleado_rol',
                      'columna' => 'empleado_id',
                      'id' => $usr);
			$classBD->deleteDataTable($delete);
			for ($i=0; $i < count($roles); $i++) { 
				$save = $classBD->insertarDataTable("empleado_rol", array("empleado_id" => $usr, "rol_id" => $roles[$i]));			
			}

	}
	if($save == true){

		

		$resp['insert'] = 1;
		$resp['lastId'] = $idinsert['lastId'];
		$resp['msj'] = "Se actualizaron los datos correctamente";	
		$users = $classBD->getDataTableMultiples(array("empleado" => "*", "areas" => "nombre as nombreareas"),"where empleado.area_id = areas.id");		
		$resp['html'] = '<table id="tbla-consulta" class="table table-condensed table-striped" data-button="false" data-select="excel,pdf" >
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
      <tbody>';
      foreach ($users as $key) { 
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

          $functions = "confirmarAction('".$deleteJs."', borrarRegistro)";
		
		$rolesresp = "";
          foreach ($roles as $keyroles) { 
          	$rolesresp .= $keyroles['nombre'].'<br>'; 
          	}

          	$texboletin = "";
          	if($key['boletin'] == 1){
				$texboletin = "Si";
          	}else{
          		$texboletin = "No";
          	}


        $resp['html'] .= '<tr>   
          <td>'.$key['nombre'].'</td>    
          <td>'.$key['email'].'</td>    
          <td>'.$key['sexo'].'</td> 
          <td>'.$key['nombreareas'].'</td>  
          <td>'.$rolesresp.'</td>   
          <td>'.$key['descripcion'].'</td> 
          <td>'.$texboletin.'</td> 
          <td align="center">
          	<i onclick=editarFormulario("'.$editarJs.'") ><img src="https://img1.freepng.es/20180920/eqx/kisspng-computer-icons-editing-portable-network-graphics-i-edit-profile-svg-png-icon-free-download-194863-5ba3457963b929.9651381015374268094085.jpg"></i>
          </td> 
          <td align="center">
          	<i onclick="'.$functions.'" ><img src="https://www.pikpng.com/pngl/m/247-2474264_png-file-svg-delete-icon-svg-clipart.png"></i>
          </td>  
        </tr>'; 

    } 
    $resp['html'] .= '</tbody></table>';

	
	}
	header('Content-Type: application/json');
	echo json_encode( $resp);
}
/*----------------------------------------------------------------------[ fin val]------------------------------------------------*/
#																																	#
#																																	#
#																																	#
/*----------------------------------------------------------------------[ fin val]------------------------------------------------*/