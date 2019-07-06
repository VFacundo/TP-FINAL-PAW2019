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
				cancelBtn(btnEdit.parentElement,"cancelEditNameTeam()");
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
						nombreEquipo.value = old_value;
						console.log(old_value);
						alert("No se pudo Completar Esa accion!");
					}
				}
				xhr.send(formData);
			}

}

function cancelEditNameTeam(){
	var lab = event.target.parentElement,
			btn = lab.lastChild.remove();
		document.getElementsByName("eq_nombre")[0].disabled=true;
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
		name = event.target.parentElement.parentElement.children[1].children[0].disabled = false,
		edad = event.target.parentElement.parentElement.children[2].children[0].disabled = false,
		user = event.target.parentElement.parentElement.children[3].children[0].disabled = false;


	//cancel.classList.add('boton');
//	cancel.classList.add('cancelEdition');
	//cancel.innerHTML = 'X';
	//cancel.setAttribute("onclick","cancelarEdicion()");
//	li.appendChild(cancel);
	cancelBtn(li,"cancelarEdicion()");
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

function cancelarEdicion(){
	var li = event.target.parentElement,
		edit = li.children[0],
		name = event.target.parentElement.parentElement.children[1].children[0].disabled = true,
		edad = event.target.parentElement.parentElement.children[2].children[0].disabled = true,
		user = event.target.parentElement.parentElement.children[3].children[0].disabled = true;
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
		id_jugador = event.target.parentElement.parentElement.children[6].value,
		formData = new FormData();

	formData.append('user', user.value);
	formData.append('name', name.value);
	formData.append('edad', edad.value);
	formData.append('id_jugador',id_jugador);
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
