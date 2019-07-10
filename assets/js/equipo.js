function eliminarJugador(confirmar,jugador){
	if(confirmar !== null){
		if(confirmar){
			var xhr = new XMLHttpRequest(),
				formData = new FormData(),
				items = document.querySelectorAll(".lista6Row.equipo");
			formData.append('id_jugador',jugador);
			xhr.open('POST', 'http://localhost/equipo/borrarjugadorequipo');
			xhr.onload = function() {
				console.log('JUG borrado', xhr.responseText);
				if(xhr.responseText.includes(200)){
					msgNotificar("Jugador borrado exitosamente","Borrar jugador");
				}else {
					msgNotificar("No fue posible borrar el jugador","Borrar jugador");
				}
			}
			xhr.send(formData);
			document.querySelector("#msgConfirmar").remove();
			var salir = false;
			for(var i = 0;i<items.length;i++){
				console.log("item",items[i]);
				for(var j = 0;j<items[i].getElementsByTagName("input").length;j++){
					if((items[i].getElementsByTagName("input")[j].type == "hidden")&&(items[i].getElementsByTagName("input")[j].value == jugador)){
						var borrar = items[i];
						borrar.parentElement.removeChild(borrar);
						break;
					}
				}
				if(salir) break;
			}
		}else{
			document.querySelector("#msgConfirmar").remove();
		}
	}else{
		var li = event.target.parentElement,
			ul = li.parentElement,
			name = ul.children[1].children[0].disabled = false,
			edad = ul.children[2].children[0].disabled = false,
			user = ul.children[3].children[0].disabled = false,
			old_data = {name: ul.children[1].children[0].value,
						edad: ul.children[2].children[0].value,
						user: ul.children[3].children[0].value,
						id: ul.lastChild.value
						};
						
		msgConfirmar("¿Desea realmente eliminar a el jugador "+old_data.name+"("+old_data.edad+")?","Eliminar Jugador",old_data,"eliminarJugador(true,"+old_data.id+")","eliminarJugador(false,-1)");
	}
}


function agregarFila(equipo){
	var ul = event.target.parentElement.parentElement.parentElement,
		li = event.target.parentElement.parentElement;
	li.outerHTML = '';
	if(equipo){
		ul.innerHTML += '<li class="lista6Row equipo">'+
							'<ul>'+
								'<li><img src="/img/avatar1.png" alt="Imagen de jugador"></li>'+
								'<li><input type="text" name="j_nombre" placeholder="Ingrese el nombre"disabled></li>'+
								'<li><input type="number" name="j_edad" value="" min="5" max="100" disabled></li>'+
								'<li><input name="j_usr" placeholder="Usuario (opcional)" autocomplete="off" disabled></li>'+
								'<li><label class="boton icon-pencil" onclick="editarJugador()"></label></li>'+
								'<li><label class="boton icon-trash" onclick="eliminarJugador()"></label></li>'+
							'</ul>'+
						'</li>'+
						'<li class="add">'+
							'<div>'+
								'<label class="boton" onclick="agregarFila(true)">Agregar jugador</label>'+
							'</div>'+
						'</li>';
	}else{
		ul.innerHTML += '<li class="lista6Row equipo">'+
					'<ul>'+
						'<li><img src="/img/avatar1.png" alt="Imagen de jugador"></li>'+
						'<li><input type="text" name="j_nombre" placeholder="Ingrese el nombre"></li>'+
						'<li><input type="number" name="j_edad" value="" min="5" max="100"></li>'+
						'<li><input name="j_usr" placeholder="Usuario (opcional)" autocomplete="off"></li>'+
						'<li><label class="boton icon-pencil" onclick="editarJugador()"></label></li>'+
						'<li><label class="boton icon-trash" onclick="eliminarJugador()"></label></li>'+
					'</ul>'+
				'</li>'+
				'<li class="add">'+
					'<div>'+
						'<label class="boton" onclick="agregarFila(false)">Agregar jugador</label>'+
					'</div>'+
				'</li>';
	}

}

function subirImgEquipo(){
	var blobFile = document.getElementById('inputSb').files[0],
	formData = new FormData(),
	xhr = new XMLHttpRequest(),img_src;
	formData.append('imgFile',blobFile);
	xhr.open('POST', 'http://localhost/equipo/cambiarimg');
	xhr.onload = function() {
		if(!xhr.responseText.includes(400)){
			img_src = "/img/";
			document.querySelector('#miequipoImagen>img').src = img_src+xhr.responseText.trim();
		}else{
			msgNotificar("No se pudo subir la imagen correctamente, porfavor intente denuevo","Subir Imagen");
		}
	}
	xhr.send(formData);
}

function editNombreEquipo(){
	var nombreEquipo = equipo.eq_nombre,
			old_value,
			xhr = new XMLHttpRequest(),
			formData = new FormData(),
			btnEdit = event.target;
			old_value = nombreEquipo.value
			if(nombreEquipo.disabled){
				nombreEquipo.disabled = false;
				btnEdit.classList.remove('icon-pencil');
				btnEdit.classList.add('icon-ok');
				cancelBtn(btnEdit.parentElement,"cancelEditNameTeam('"+old_value+"')");
			}else {
				nombreEquipo.disabled = true;
				btnEdit.classList.remove('icon-ok');
				btnEdit.classList.add('icon-pencil');

				formData.append('nombre_equipo', nombreEquipo.value);
				xhr.open('POST', 'http://localhost/equipo/editnombreequipo');
				xhr.onload = function() {
					if(xhr.responseText != 400){
						nombreEquipo.value = xhr.responseText;
					}else{
						msgNotificar("No se pudo cambiar el nombre del equipo. La longitud debe ser mayor a 4 caracteres y el nombre no debe coincidir con uno existente", "Cambiar nombre de equipo");
					}
				}
				xhr.send(formData);
				document.querySelector('#contenedorEquipo > form > label label:last-child').remove();
			}

}

function cancelEditNameTeam(old_name){
	var lab = event.target.parentElement,
		btn = lab.lastChild.remove();
	document.getElementsByName("eq_nombre")[0].disabled=true;
	document.getElementsByName("eq_nombre")[0].value = old_name;
	lab.lastChild.classList.remove('icon-ok');
	lab.lastChild.classList.add('icon-pencil');
}

function cancelBtn(li,funcion){
	var cancel = document.createElement('label');
		cancel.classList.add('boton');
		cancel.classList.add('cancelEdition');
		cancel.innerHTML = 'X';
		cancel.setAttribute("onclick",funcion);
		li.appendChild(cancel);
}

function editarJugador(){
	var button = event.target,
		cancel = document.createElement('label'),
		li = event.target.parentElement,
		ul = li.parentElement,
		name = ul.children[1].children[0].disabled = false,
		edad = ul.children[2].children[0].disabled = false,
		user = ul.children[3].children[0].disabled = false,
		id = event.target.parentElement.parentElement.children[3].children[0].disabled = false,
		old_data = {name: ul.children[1].children[0].value,
					edad: ul.children[2].children[0].value,
					user: ul.children[3].children[0].value,
					id: ul.lastChild.value
					};
		localStorage.setItem(old_data.id,JSON.stringify(old_data));

	cancelBtn(li,"cancelarEdicion("+old_data.id+")");
	button.classList.remove('icon-pencil');
	button.classList.add('icon-ok');
	button.setAttribute("onclick","confirmarEdicion()");

	/*
	formData.append('user', user);
	formData.append('name', name);
	xhr.open('POST', 'http://localhost/equipo/borrarjugador');
	xhr.onload = function() {
		console.log('User borrado', xhr.responseText);
	}
	xhr.send(formData);
	event.target.parentElement.parentElement.parentElement.remove();*/
}

function cancelarEdicion(id){
	var li = event.target.parentElement,
		edit = li.children[0],
		name = event.target.parentElement.parentElement.children[1].children[0],
		edad = event.target.parentElement.parentElement.children[2].children[0],
		user = event.target.parentElement.parentElement.children[3].children[0],
		old_data = JSON.parse(localStorage.getItem(id));
		name.disabled = true;
		edad.disabled = true;
		user.disabled = true;
		name.value = old_data.name;
		edad.value = old_data.edad;
		user.value = old_data.user;
		localStorage.setItem(id,'');
	li.children[1].remove();
	edit.classList.add('icon-pencil');
	edit.classList.remove('icon-ok');
	edit.setAttribute("onclick","editarJugador()");
}

function confirmarEdicion(){
	var xhr = new XMLHttpRequest(),
		formData = new FormData(),
		li = event.target.parentElement,
		edit = li.children[0],
		name = event.target.parentElement.parentElement.children[1].children[0],
		edad = event.target.parentElement.parentElement.children[2].children[0],
		user = event.target.parentElement.parentElement.children[3].children[0],
		formData = new FormData();

	formData.append('user', user.value);
	formData.append('name', name.value);
	formData.append('edad', edad.value);
	try{
		var id_jugador = event.target.parentElement.parentElement.children[6].value;
		formData.append('id_jugador',id_jugador);
	}catch{
		console.log('Salio por el id_jugador');
	}
	xhr.open('POST', 'http://localhost/equipo/editarjugadorequipo');
	xhr.onload = function() {
		if(xhr.responseText.includes(200)){
			msgNotificar("Jugador editado correctamente.","Editar jugador");
		}else {
			msgNotificar("No se pudo editar el jugador.","Editar jugador");
		}
	}
	xhr.send(formData);

	li.children[1].remove();
	edit.classList.add('icon-pencil');
	edit.classList.remove('icon-ok');
	edit.setAttribute("onclick","editarJugador()");
	name.disabled = true;
	edad.disabled = true;
	user.disabled = true;
}

function aceptarDesafio(confirmar,turno){
	if(confirmar !== null){
		if(confirmar){
			var xhr = new XMLHttpRequest(),
				formData = new FormData(),
				items = document.querySelectorAll(".lista6Row.equipo");
			formData.append('id_turno',turno);
			xhr.open('POST', 'http://localhost/equipo/aceptardesafio');
			xhr.onload = function() {
				console.log('Desafio aceptado: ', xhr.responseText);
				if(xhr.responseText.includes(200)){
					msgNotificar("El desafio fue aceptado exitosamente","Solicitud de desafio");
				}else {
					msgNotificar("El desafio no pudo ser aceptado, porfavor intentelo denuevo.","Solicitud de desafio");
				}
			}
			xhr.send(formData);
			document.querySelector("#msgConfirmar").remove();
			var salir = false;
			for(var i = 0;i<items.length;i++){
				console.log("item",items[i]);
				for(var j = 0;j<items[i].getElementsByTagName("input").length;j++){
					if((items[i].getElementsByTagName("input")[j].type == "hidden")&&(items[i].getElementsByTagName("input")[j].value == turno)){
						var borrar = items[i];
						borrar.parentElement.removeChild(borrar);
						break;
					}
				}
				if(salir) break;
			}
		}else{
			document.querySelector("#msgConfirmar").remove();
		}
	}else{
		var li = event.target.parentElement.parentElement,
			ul = li.parentElement,
			name =  ul.children[1].innerText,
			edad =  ul.children[2].innerText,
			fecha = ul.children[3].innerText,
			lugar = ul.children[4].innerText;
			old_data = {name:  ul.children[1].innerText,
						edad:  ul.children[2].innerText,
						fecha: ul.children[3].innerText,
						lugar: ul.children[4].innerText,
						id: ul.lastChild.children[0].value
						};
		msgConfirmar("¿Desea aceptar el desafio contra "+old_data.name+" en "+old_data.lugar+" ("+old_data.fecha+")?","Solicitud de desafio",old_data,"aceptarDesafio(true,"+old_data.id+")","aceptarDesafio(false,-1)");
	}
}
function rechazarDesafio(confirmar,turno){
	if(confirmar !== null){
		if(confirmar){
			var xhr = new XMLHttpRequest(),
				formData = new FormData(),
				items = document.querySelectorAll(".lista6Row.equipo");
			formData.append('id_turno',turno);
			xhr.open('POST', 'http://localhost/equipo/rechazardesafio');
			xhr.onload = function() {
				console.log('Desafio Rechazado: ', xhr.responseText);
				if(xhr.responseText.include(200)){
					msgNotificar("El desafio fue rechazado exitosamente","Solicitud de desafio");
				}else {
					msgNotificar("El desafio no pudo ser rechazado, porfavor intentelo denuevo.","Solicitud de desafio");
				}
			}
			xhr.send(formData);
			document.querySelector("#msgConfirmar").remove();
			var salir = false;
			for(var i = 0;i<items.length;i++){
				console.log("item",items[i]);
				for(var j = 0;j<items[i].getElementsByTagName("input").length;j++){
					if((items[i].getElementsByTagName("input")[j].type == "hidden")&&(items[i].getElementsByTagName("input")[j].value == turno)){
						var borrar = items[i];
						borrar.parentElement.removeChild(borrar);
						break;
					}
				}
				if(salir) break;
			}
		}else{
			document.querySelector("#msgConfirmar").remove();
		}
	}else{
		var li = event.target.parentElement.parentElement,
			ul = li.parentElement,
			name =  ul.children[1].innerText,
			edad =  ul.children[2].innerText,
			fecha = ul.children[3].innerText,
			lugar = ul.children[4].innerText,
			old_data = {name:  ul.children[1].innerText,
						edad:  ul.children[2].innerText,
						fecha: ul.children[3].innerText,
						lugar: ul.children[4].innerText,
						id: ul.lastChild.children[0].value
						};
		msgConfirmar("¿Desea rechazar el desafio de "+old_data.name+" en "+old_data.lugar+" ("+old_data.fecha+")?","Solicitud de desafio",old_data,"rechazarDesafio(true,"+old_data.id+")","rechazarDesafio(false,-1)");
	}
}