document.addEventListener('DOMContentLoaded', (event) => {
    var masDetalles = document.getElementsByClassName("masDetalles");
	for(var i=0; masDetalles.length > i; i++){
		masDetalles[i].addEventListener('click', function (){mostrarDetalle()});
	}
});

function mostrarDetalle(){
	var div = (event.target).parentElement.parentElement.querySelector(".detalles");
	if(div.classList.contains("mostrando")){
		div.classList.remove("mostrando");
		console.log("nomostrando");
	}else{
		div.classList.add("mostrando");
		console.log("mostrando");
	}
}
