document.addEventListener('DOMContentLoaded', (event) => {
    var masDetalles = document.getElementsByClassName("masDetalles");
	for(var i=0; masDetalles.length > i; i++){
		masDetalles[i].addEventListener('click', function (){mostrarDetalle()});
		console.log(masDetalles[i]);
	}
	toggleContenedor(document.getElementById("contenedorEquipo"));
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


function toggleContenedor(active){
	var equipo = document.getElementById("contenedorEquipo"),
	solicitudes = document.getElementById("contenedorSolicitudes"),
	jugador = document.getElementById("contenedorComoJugador");

	equipo.style.display = 'none';
	solicitudes.style.display = 'none';
	jugador.style.display = 'none';
	active.style.display = 'block';
}

function showSection(index){
	var equipo = document.getElementById("contenedorEquipo"),
		solicitudes = document.getElementById("contenedorSolicitudes"),
		jugador = document.getElementById("contenedorComoJugador"),
		active = document.querySelector(".btnActive"),
		botones = document.getElementsByClassName("btnEquipo");
	if(active.innerText == "MI EQUIPO" && !(index == 1)){
		if(index==2){
			for (var i = 0; i < botones.length; i++){
				if(botones[i].innerText == "MIS SOLICITUDES"){
					active.classList.remove("btnActive");
					botones[i].classList.add("btnActive");
					toggleContenedor(solicitudes);
					break;
				}
			}
		}else{
			for (var i = 0; i < botones.length; i++){
				if(botones[i].innerText == "MIS EQUIPOS JUGADOR"){
					active.classList.remove("btnActive");
					botones[i].classList.add("btnActive");
					toggleContenedor(jugador);
					break;
				}
			}
		}
	}
	if(active.innerText == "MIS SOLICITUDES" && !(index == 2)){
		if(index==1){
			for (var i = 0; i < botones.length; i++){
				if(botones[i].innerText == "MI EQUIPO"){
					active.classList.remove("btnActive");
					botones[i].classList.add("btnActive");
					toggleContenedor(equipo);
					break;
				}
			}
		}else{
			for (var i = 0; i < botones.length; i++){
				if(botones[i].innerText == "MIS EQUIPOS JUGADOR"){
					active.classList.remove("btnActive");
					botones[i].classList.add("btnActive");
					toggleContenedor(jugador);
					break;
				}
			}
		}
	}
	if(active.innerText == "MIS EQUIPOS JUGADOR" && !(index == 3)){
		if(index==1){
			for (var i = 0; i < botones.length; i++){
				if(botones[i].innerText == "MI EQUIPO"){
					active.classList.remove("btnActive");
					botones[i].classList.add("btnActive");
					toggleContenedor(equipo);
					break;
				}
			}
		}else{
			for (var i = 0; i < botones.length; i++){
				if(botones[i].innerText == "MIS SOLICITUDES"){
					active.classList.remove("btnActive");
					botones[i].classList.add("btnActive");
					toggleContenedor(solicitudes);
					break;
				}
			}
		}
	}
}