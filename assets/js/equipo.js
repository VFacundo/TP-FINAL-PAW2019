function eliminarJugador(){
	var confirmar = true;
	if(confirmar){
		var id_jugador = event.target.parentElement.parentElement.children[6].value,
			xhr = new XMLHttpRequest(),
			formData = new FormData();
		formData.append('id_jugador',id_jugador);
		xhr.open('POST', 'http://localhost/equipo/borrarjugadorequipo');
		xhr.onload = function() {
			console.log('JUG borrado', xhr.responseText);
			if(xhr.responseText==200){
				alert("Jugador Borrado!");
			}else {
				alert("No fue Posible Realizar Esta Accion");
			}
		}
		xhr.send(formData);
		event.target.parentElement.parentElement.parentElement.remove();
	}
}

function agregarFila(equipo){
	var ul = event.target.parentElement.parentElement.parentElement,
		li = event.target.parentElement.parentElement;
	console.log(li);
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
								'<span class="boton" onclick="agregarFila(true)">Agregar jugador</span>'+
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
						'<span class="boton" onclick="agregarFila(false)">Agregar jugador</span>'+
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
		if(xhr.responseText != 400){
			img_src = "/img/";
			document.querySelector('#miequipoImagen>img').src = img_src+xhr.responseText.trim();
		}else{
			alert("No se pudo Completar Esa accion!");
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
						alert("No se pudo Completar Esa accion!");
					}
				}
				xhr.send(formData);
				document.querySelector('#contenedorEquipo > form > label label:last-child').remove();
			}

}

function cancelEditNameTeam(old_name){
	console.log(event.target);
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
		console.log(li);
		console.log(old_data);
		console.log(ul.parentElement);

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
		console.log(old_data);
		console.log(name);
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
		if(xhr.responseText == 200){
			alert("Jugador Editado!");
		}else {
			alert("No fue Posible Realizar Esta Accion");
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
