var validacionPhp = "class/validacionPhp"; 
function getJson(data){
	return JSON.parse(data);
}
function confirmarAction(rawJs, funcionAceptar){	
	js = JSON.parse( decodeStr(rawJs) );
	var aler = '<div class="msjAlertaIn">'+
		'<div class="animated fadeInDown cuerpoAlert">'+
			'<div class="demo-card-wide mdl-card mdl-shadow--2dp">'+
			  '<div class="mdl-card__title ">'+
			    '<h2 class="mdl-card__title-text">Confirma la acción</h2>'+
			  '</div>'+
			  '<div class="mdl-card__supporting-text" >'+
			    js['msj_confirmacion']+
			  '</div>'+	
			  '<div class="mdl-card__actions mdl-card--border">'+
			    '<a class="btn-save" id="AceptarDelete" >Aceptar</a>'+
			    ' <a class="btn-danger" id="CancelarDelete" >Cancelar</a>'+
			  '</div>'+
			'</div>'+
		'</div>'+
	'</div>';
	// $('#'+id).addClass('animated zoomInUp');
	$('#showPage').append(aler);
	document.getElementById('CancelarDelete').addEventListener('click',function(){
		$('.cuerpoAlert').removeClass('fadeInDown');
		$('.cuerpoAlert').addClass('zoomOutUp'); 
		setTimeout(function(){
			$('.msjAlertaIn').remove();
		},1000); },false);
	document.getElementById('AceptarDelete').addEventListener('click',function(){
		$('.cuerpoAlert').removeClass('fadeInDown');
		$('.cuerpoAlert').addClass('zoomOutUp'); 
		$('.msjAlertaIn').remove();
		borrarRegistro(rawJs);
	 },false);
}
function validarEmail(valor) {
  if (/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i.test(valor)){
   //alert("La dirección de email " + valor + " es correcta.");
   return true;
  } else {
   //alert("La dirección de email es incorrecta.");
   return false;
  }
}
function encodeStr(str){
	var enc = window.btoa(str);
	return enc;
}
function decodeStr(enc){
	var dec = window.atob(enc)
	return dec;
}
function ajaxJquery(page,jsonSend,metod,funcion,datalast){	
      $.ajax({
        type:metod,
        url: page+'.php',
        data:jsonSend,
        cache: false,
        contentType: false,
        processData: false,
        success:function(data){
          // console.log(data);
          if(typeof funcion != 'undefined'){
          	funcion(data,datalast);
          }
         },
        error: function(data){
          console.log(data);
          if(data.status == 404){
          	alerta("Esta pagina no ha sido encontrada, error 404 ","Error 404");
          }
        }
      });
}
function borrarRegistro(deleteJs){
	js = JSON.parse( decodeStr(deleteJs) );
	console.log(js);
	var formData = new FormData();
		formData.append('val',js['val']);
		formData.append('tabla',js['tabla']);
		formData.append('columna',js['columna']);
		formData.append('id',js['id']);
	ajaxJquery(validacionPhp, formData, 'POST', showdataUsersup, js);	

}
function editarFormulario(formEncode,funcion){
	// moveSection(id,"showPage")
	// $("<body>").animate({ scrollTop:0 }, 'slow');
	var json = JSON.parse(decodeStr(formEncode));
	for(var i in json){
	console.log($("#"+i)[0], typeof $("#"+i)[0]);
		if($("#"+i) && typeof $("#"+i)[0] != 'undefined'){
console.log($("#"+i)[0]['type']);
			if($("#"+i)[0]['localName'] == 'img'){
				$("#"+i).attr('src',json[i]);
			}else if($("#"+i)[0]['type'] == 'radio' || $("#"+i)[0]['type'] == 'checkbox'){
					$("input[id="+i+"][value='"+json[i]+"']").prop("checked",true);
			}else{
				$("#"+i).val(json[i]);
			}	
		}
	}
	if(typeof funcion == 'function'){
		funcion(json);
	}
}
