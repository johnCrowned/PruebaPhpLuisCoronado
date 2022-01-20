document.getElementById('saveForm').addEventListener('click',function(){

var favorite = [];
            $.each($("input[name='roles']:checked"), function(){
                favorite.push($(this).val());
            });

	if($('#nombre').val() == ""){
		alert("Porfavor complete el nombre");
	}else{
		if($('#email').val() == ""){
		alert("Porfavor complete el correo");
		}else{
			if($('#area_id').val() == ""){
			alert("Porfavor seleccione el area");
			}else{
				if(favorite == ""){
				alert("Porfavor seleccione los roles");
				}else{
					if($('#descripcion').val() == ""){
					alert("Porfavor ingrese la descripcion");
					}else{
						var formData = new FormData();
						formData.append('val','INSERTARempleado');
						formData.append('id', $('#idUser').val());
						formData.append('nombre', $('#nombre').val());
						formData.append('email', $('#email').val());
						formData.append('sexo', $('input[name=sexo]:checked', '#formUsers').val());
						formData.append('area_id', $('#area_id').val());
						formData.append('boletin', $('input:checkbox[name=boletin]:checked').val());
						formData.append('roles', favorite);
						formData.append('descripcion', $('#descripcion').val());
						ajaxJquery(validacionPhp,formData,'POST', showdataUsersup);

					}
				}
			}
		}

	}

  },false);
function showdataUsersup(rest){
	alert(rest['msj']);
	$('#idUser').val('');
    $('#nombre').val('');
    $('#email').val('');
    $('#sexo').val('');
    $('#area_id').val('');
    $('#boletin').val('');
    $('#descripcion').val('');
	$('#tablecontents').html(rest['html']);
	console.log(rest);
}