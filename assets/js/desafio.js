function desafioTurno(){
  var  id_turno = event.target.parentElement.previousSibling.value,
        xhr = new XMLHttpRequest(),
        formData = new FormData();
  formData.append('id_turno',id_turno);
  xhr.open('POST', 'http://localhost/buscarpartido/desafiar');
  xhr.onload = function() {
  alert(xhr.responseText);
  }
  xhr.send(formData);
}
