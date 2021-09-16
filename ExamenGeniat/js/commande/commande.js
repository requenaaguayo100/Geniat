const Commande = ()=>{
	const commande = {
		initButtons: ()=>{
			$('addline').on('click',(e)=>{
				e.preventDefault();

			});
		},
		getProducts : ()=>{
			excecuteCallServer('/custom/controller/commande/getProduct.php',{nIdProduct: '123456'},(response)=>{
				const data = response.data;
				console.log(data);
			})
		}
	}
	const initCommande = ()=>{
		commande.initButtons();
		commande.getProducts();
	}
	initCommande();
}


