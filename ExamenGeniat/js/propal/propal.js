const Propal = ()=>{
	const propal = {
        message:'',
		initButtons: ()=>{
			$('#addline').on('click',(e)=>{
				e.preventDefault();
                const parameters = getParasFromForm('addproduct');
                parameters.price_ht = formatNumeric($('#price_ht').val());
                parameters.buying_price = formatNumeric($('#buying_price').val());
                parameters.dp_desc = $('#dp_desc').val();
                if(propal.evaluate(parameters)){
                    propal.savePropaldet(parameters);
                }else{
                    showDialog(propal.message);
                }

			});

            $('#addproduct').on('Submit',(e)=>{
                e.preventDefault();
            })
		},
        evaluate : (parameters)=>{
            if(Number(parameters.price_ht) < Number(parameters.buying_price) ){
                propal.message = 'El precio unitario no puede ser menor al precio de compra ';
                return false;   
            }
            if(parameters.date_start.trim() !='' && parameters.date_end.trim()!=''){
                if(!validateDate($('#date_start').val(), $('#date_end').val()) ){
                    propal.message = 'La fecha inicial no puede ser mayor a la final';
                    return false;
                }
            }
            if(!$('#prod_entry_mode_free').is(':checked') && !$('#prod_entry_mode_predef').is(':checked')){
                $('#prod_entry_mode_free').focus();
                propal.message = 'Seleciona un tipo de entrada';
                return false;
            }
            if($('#prod_entry_mode_free').is(':checked') && $('#dp_desc').val() =='' ){
                $('#dp_desc').focus();
                propal.message = 'Agrega una descripción del producto o servicio';
                return false;
            }
            if($('#prod_entry_mode_free').is(':checked') && ($('#select_type').val() == null || $('#select_type').val() <0) ){
                $('#select_type').focus();
                propal.message = 'selecciona un tipo de producto o servicio';
                return false;
            }
            if($('#prod_entry_mode_predef').is(':checked') && (parameters.idprod =='' || parameters.idprod <= 0 ) && $('#dp_desc').val() ==''){
                $('#select_type').focus();
                propal.message = 'selecciona un producto o servicio o agrega una descripción';
                return false;
            }

            

            return true;
        },
		savePropaldet : (parameters)=>{
			let validate = validateEmptyField(feilds={
                field : ['price_ht', 'qty','buying_price'],
                name : ['Precio unitario', 'Cantidad', 'Precio de compra']
            });
            if(validate){
                excecuteCallServer('/custom/controller/propal/savePropaldet.php',{parameters},(response)=>{
                    const data = response.data;
                    if(data.nCode != 0 ){
                        showDialog(data.sMessage);
                    }else{
                        location.reload();
                    }
                    
                });
            }
		}
	}
	const initPropal = ()=>{
        $('#select_type option[value="-1"]').remove();
        $('#options_duracion option[value="0"]').remove();
		propal.initButtons();
	}
	initPropal();
}


