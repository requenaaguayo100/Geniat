const excecuteCallServer = (url,params,fn,failure=function(){}) =>{
     $.ajax({
          url: DOL_URL_ROOT + url,
          type: 'POST',
          dataType: 'json',
          data: params
     })
     .done(function (response) {
          if (!response.success) {
               showDialog(response.message);
          }else{
               fn(response);
          }
      }).fail(function(){
          failure();
      }).always(function(){
      	
      })
}
const showDialog = (text='',divDialog='custom-dilog')=>{
     $('#'+divDialog).html(text);
     $('#'+divDialog).dialog({
           dialogClass: "no-close",
            buttons: [
              {
                text: "ok",
                click: function() {
                  $( this ).dialog( "close" );
                }
              }
            ]
     });
}


const getParasFromForm = (idForm ='')=>{
     let = nameFile = [];
     let = valueFile = [];
     let objetoSertialize = {}
     let arrayForm = $('#'+idForm).serialize().split('&');
     arrayForm.map((value,index) =>{
               nameFile.push(value.split('=')[0]);
               valueFile.push(value.split('=')[1]);
     });
     let params = {};
     $.each(nameFile,(index,value)=>{
          params[value] = valueFile[index] ;
     })
     return params;
}

const validateDate = (date_start, date_end)=>{
     let date_s = date_start.split('/');
     let date_e = date_end.split('/');
     
     if(date_s[0] > date_e[0] || date_s[1] > date_e[1] || date_s[2] > date_e[2]){
          return false;
     }
     return true;
}

const formatDBDate = (date)=>{
     let formatDate = date.split('/');
     formatDate = formatDate[2]+'-'+formatDate[1]+'-'+formatDate[0];
     return formatDate;
}

/*
*fields ->  Json con dos arreglos, Campo y nombre donde:
*	field -> Id del campo que se quiere validar
*	name -> nombre con el usuario identifica el campo a validar
* json example:
*    fields = { field: ['nombre_tarjeta', 'apellidos_tarjeta', 'numero_tarjeta', 'vencimiento_tarjeta', 'ccv_tarjeta', 'codigoPostal_tarjeta'], 
*               name: ['nombre', 'Apellidos', 'Número', 'Vencimiento', 'CVV', 'Codigo Postal'] }	
*/
const validateEmptyField = (fields)=>{
	let count = fields.field.length;
	let i = 0;
	while(count>0){

		if($('#'+fields.field[i]).val() == undefined || myTrim($('#'+fields.field[i]).val()) == ''){
			count = 0;
               showDialog('El campo '+fields.name[i]+' es requerido para continuar');
			$('#'+fields.field[i]).focus();		
     		return false;
		}
		count--;
		i++;
	}
	return true;
}

const myTrim = (txt)=>{
     if(txt != undefined && typeof txt == 'string'){
          return txt.trim();
     }
     else if(txt != undefined && typeof txt == 'number'){
          return txt;
     }
     else{
          return '';
     }
}

const formatNumeric = (value)=>{
     value = value.replace('$','');
     patron = /,/g;
     value  = value.replace(patron,'');
     return value;
}