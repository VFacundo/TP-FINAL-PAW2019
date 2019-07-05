var document = document || {},
    console = console || {},
    window = window || {},
    turnos = turnos || {},
    btnEditar,
    divForm,
    currentTab = 0;

turnos.init = function(){
  window.addEventListener("DOMContentLoaded", function() {
    btnEditar = document.getElementById('btnEditar');
    divForm = document.getElementById('nuevoTurno');

	var tipoTurno = divForm.getElementsByTagName('form')[0].tipo_turno;
	tipoTurno.addEventListener('change',function(){
		if(tipoTurno.value == 1){
			//tipoTurno.value=1;
			document.querySelector("#eq_rival").style.display = "inline-block";
		}else{
			document.querySelector("#eq_rival").style.display = "none";
		}
	});
  });
}

turnos.horariosDisponibles = function(){
  var xhr = new XMLHttpRequest(),
  cancha_turno = document.turno.cancha_turno.value,
  fecha_turno = document.turno.fecha_turno.value,
  horario_turno = document.turno.horario_turno,
  formData = new FormData(),response;

  formData.append("cancha_turno",cancha_turno);
  formData.append("fecha_turno",fecha_turno);

  xhr.open('POST', 'http://localhost/turnos/horariosdisponibles');
  xhr.onload = function() {

  console.log(horario_turno);
  response = xhr.responseText;
    if(response){
      horario_turno.innerHTML = xhr.responseText;
      console.log(xhr.responseText);
    }

  }
    xhr.send(formData);

}

turnos.getCanchas = function(){
  var xhr = new XMLHttpRequest(),
  form = document.turno.cancha_turno;

  xhr.open('POST', 'http://localhost/turnos/getcanchas');
  xhr.onload = function() {
  form.innerHTML = xhr.responseText;
  console.log(form);
  console.log(xhr.responseText);
  }
    xhr.send(" ");
}

turnos.toggle = function(){
  var style = divForm.style.display;
	if(style == 'flex'){
		divForm.style.display = 'none';
	}else{
		divForm.style.display='flex';
		turnos.getCanchas();
		turnos.showTab(currentTab);
	}
}

turnos.crearTurno = function(){
  var formData = new FormData(),
      form = document.turno,
      tipo_turno = form.tipo_turno.value,
      fecha_turno = form.fecha_turno.value,
      horario_turno = form.horario_turno.value,
      cancha_turno = form.cancha_turno.value,
      eq_rival = form.eq_rival,
      xhr = new XMLHttpRequest(),
      data = [tipo_turno,fecha_turno,horario_turno,cancha_turno];

      if(data.indexOf(null)==-1){
        console.log("OK");
        formData.append('tipo_turno',tipo_turno);
        formData.append('fecha_turno',fecha_turno);
        formData.append('horario_turno',horario_turno);
        formData.append('cancha_turno',cancha_turno);
          if(tipo_turno == 1){
            formData.append('equipo_rival',eq_rival.value);
          }

        xhr.open('POST', 'http://localhost/turnos/nuevoturno');
        xhr.onload = function() {
        console.log('Info Enviada');
        xhr.responseText;
        window.location.reload();
        }
        xhr.send(formData);
        console.log("ENVIA3");
      }else{
        alert("Los Datos ingresados son Incorrectos!");
      }
}



turnos.showTab = function(n) {
	var x = document.getElementsByClassName("stepTab");
	x[n].style.display = "flex";
	if (n == 0) {
		document.getElementById("prevBtn").style.display = "none";
	} else {
		document.getElementById("prevBtn").style.display = "inline";
	}
	if (n == (x.length - 1)) {
		//Termino!
		var divResumen = document.querySelector("#resumenTurno"),
			form = document.turno,
			tipo_turno = form.tipo_turno.selectedOptions[0],
			fecha_turno = form.fecha_turno.value,
			horario_turno = form.horario_turno.value,
			cancha_turno = form.cancha_turno.selectedOptions[0].innerHTML;

		document.getElementById("nextBtn").style.display = "none";
		divResumen.children[0].innerHTML += ' '+tipo_turno.innerHTML;
		if (tipo_turno.value == 1){
			divResumen.children[0].innerHTML += ' Equipo rival: '+form.eq_rival.value;
		}
		divResumen.children[1].innerHTML += ' '+cancha_turno;
		divResumen.children[2].innerHTML += ' '+fecha_turno;
		divResumen.children[3].innerHTML += ' '+horario_turno;
	} else {
		document.getElementById("nextBtn").style.display = "inline";
		document.getElementById("nextBtn").innerHTML = "Siguiente";
	}
	turnos.updStepIndicator(n)
}

turnos.updStepIndicator = function(n) {
	// This function removes the "active" class of all steps...
	var i, x = document.getElementsByClassName("step");
	for (i = 0; i < x.length; i++) {
	x[i].classList.remove("active");
	}
	//... and adds the "active" class to the current step:
	x[n].classList.add("active");
}


turnos.nextPrevTab = function(n) {
  var x = document.getElementsByClassName("stepTab");
  if (n == 1 && !turnos.validateForm()) return false;
  x[currentTab].style.display = "none";
  currentTab = currentTab + n;
  if (currentTab >= x.length) {
    document.querySelector("#nuevoTurno form").submit();
    return false;
  }
  turnos.showTab(currentTab);
}

//Solo verifica que esten llenos los input. TODO: Agregar alguna verificacion extra
turnos.validateForm = function () {
  var x, y, z, i, valid = true;
  x = document.getElementsByClassName("stepTab");
  y = x[currentTab].getElementsByTagName("input");
  z = x[currentTab].getElementsByTagName("select");
  for (i = 0; i < y.length; i++) {
    if (y[i].value == "") {
      y[i].className += " invalid";
      valid = false;
    }
  }
  for (i = 0; i < z.length; i++) {
    if (z[i].value == "") {
      z[i].className += " invalid";
      valid = false;
    }
  }
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid;
}
