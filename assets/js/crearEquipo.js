function agregarJugador(){
  var target = event.target,
	  ul = target.parentElement.parentElement,
	  nombre = ul.children[1].querySelector("input"),
	  edad = ul.children[2].querySelector("input"),
	  usuario = ul.children[3].querySelector("input");
  (target.innerHTML === 'Editar')? target.innerHTML = "Agregar" : target.innerHTML = "Editar";
  if(edad != null && usuario != null && nombre != null){
	  if(nombre.disabled){
		  nombre.disabled = false;
		  edad.disabled = false;
		  usuario.disabled = false;
	  }else{
		  nombre.disabled = true;
		  edad.disabled = true;
		  usuario.disabled = true;
	  }
  }
}

function crearEquipo(){
  var form = document.equipo,
  eq_nombre = form.eq_nombre.value,
  j_nombre = form.j_nombre,
  j_edad = form.j_edad,
  j_usr = form.j_usr,
  formData = new FormData(),
  img_equipo =  document.getElementById('inputSb').files[0],
  xhr = new XMLHttpRequest(),
  cantJ=0;

  formData.append('nombreEquipo',eq_nombre);
  formData.append('imgFile',img_equipo);
  for (var i = 0; i < j_nombre.length; i++) {
      if(j_nombre[i].value!=''){
        formData.append('nombre[]',j_nombre[i].value);
        formData.append('edad[]',j_edad[i].value);
        formData.append('user[]',j_usr[i].value);
        cantJ++;
      }
  }

  if(cantJ>=4){
    xhr.open('POST', 'http://localhost/equipo/nuevoequipo');
    xhr.onload = function() {
    console.log('Info Enviada');
      if(xhr.responseText){
        alert(xhr.responseText);
      }
	  window.location.reload();
    }
    xhr.send(formData);
  }else {
    alert("Su Equipo Debe contar con Al menos 5 Jugadores!");
  }
}
