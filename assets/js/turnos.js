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

turnos.toggle = function(){
  var style = divForm.style.display;
  if(style == 'block'){
    divForm.style.display = 'none';
  }else {
    divForm.style.display='block';
  }
}
