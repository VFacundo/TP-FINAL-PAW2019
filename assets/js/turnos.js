var document = document || {},
    console = console || {},
    window = window || {},
    turnos = turnos || {},
    btnEditar,
    divForm;

turnos.init = function(){
  window.addEventListener("DOMContentLoaded", function() {
      btnEditar = document.getElementById('btnEditar');
      divForm = document.getElementById('nuevoTurno');
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
  }else {
    divForm.style.display='flex';
    turnos.getCanchas();
  }
}

turnos.crearTurno = function(){
  var formData = new FormData(),
      form = document.turno,
      tipo_turno = form.tipo_turno.value,
      fecha_turno = form.fecha_turno.value,
      horario_turno = form.horario_turno.value,
      cancha_turno = form.cancha_turno.value,
      xhr = new XMLHttpRequest(),
      data = [tipo_turno,fecha_turno,horario_turno,cancha_turno];

      if(data.indexOf(null)==-1){
        console.log("OK");
        formData.append('tipo_turno',tipo_turno);
        formData.append('fecha_turno',fecha_turno);
        formData.append('horario_turno',horario_turno);
        formData.append('cancha_turno',cancha_turno);

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
