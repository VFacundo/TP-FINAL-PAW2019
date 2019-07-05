function eliminarJugador(){
	var confirmar = true;
	if(confirmar){
		var name = event.target.parentElement.parentElement.children[1].children[0].value,
			user = event.target.parentElement.parentElement.children[3].children[0].value,
			xhr = new XMLHttpRequest(),
			formData = new FormData();
		formData.append('user', user);
		formData.append('name', name);
		xhr.open('POST', 'http://localhost/equipo/borrarjugador');
		xhr.onload = function() {
			console.log('User borrado', xhr.responseText);
		}
		xhr.send(formData);
		event.target.parentElement.parentElement.parentElement.remove();
	}
}
function editarJugador(){
	var button = event.target,
		cancel = document.createElement('label'),
		li = event.target.parentElement;
		
	console.log(li,cancel,button);
	cancel.classList.add('boton');
	cancel.classList.add('cancelEdition');
	cancel.innerHTML = 'X';
	cancel.setAttribute("onclick","cancelarEdicion()");
	li.appendChild(cancel);
	
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
		edit = li.children[0];
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
		name = event.target.parentElement.parentElement.children[1].children[0].value,
	    edad = event.target.parentElement.parentElement.children[2].children[0].value,
		user = event.target.parentElement.parentElement.children[3].children[0].value,
		formData = new FormData();
		
	formData.append('user', user);
	formData.append('name', name);
	formData.append('edad', edad);
	xhr.open('POST', 'http://localhost/equipo/editarjugador');
	xhr.onload = function() {
		console.log('User Editado', xhr.responseText);
	}
	xhr.send(formData);
	
	li.children[1].remove();
	edit.classList.add('icon-pencil');
	edit.classList.remove('icon-ok');
	edit.setAttribute("onclick","editarJugador()");
}