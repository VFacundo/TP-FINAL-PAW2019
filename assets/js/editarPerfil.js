function editarPerfil() {
  var btnEditar = document.getElementById('btnEditar');
  var divForm = document.getElementById('editarPerfil');
  //divForm.style.display='block';
  var style = divForm.style.display;
    if(style == 'block'){
      divForm.style.display = 'none';
    }else {
      divForm.style.display='block';
    }
  console.log('editando..');
}

function subirImg() {
    var blobFile = document.getElementById('inputSb').files[0];
    var formData = new FormData();
    var xhr = new XMLHttpRequest();
    formData.append("imgFile", blobFile);
      xhr.open('POST', 'http://localhost/perfil/subirImagen');
      xhr.onload = function() {
      console.log('Img Enviada.. ');
      console.log(xhr.responseText);
      }
      xhr.send(formData);
  }
