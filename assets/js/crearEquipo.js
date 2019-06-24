function agregarJugador(){
  var target = event.target;
  target.disabled=true;
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

  if(cantJ>=5){
    xhr.open('POST', 'http://localhost/equipo/nuevoequipo');
    xhr.onload = function() {
    console.log('Info Enviada');
      if(xhr.responseText!='ok'){
        alert(xhr.responseText);
      }
    }
    xhr.send(formData);
  }else {
    alert("Su Equipo Debe contar con Al menos 5 Jugadores!");
  }
}
