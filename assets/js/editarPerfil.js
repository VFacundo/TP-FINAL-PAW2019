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
        actualizarImg();
      }
      xhr.send(formData);
  }

  function actualizarImg(){
    var img = document.getElementById("imgPerfil"),
    xhr = new XMLHttpRequest(),response;
    xhr.open('POST', 'http://localhost/perfil/getImg');
    xhr.onload = function() {
    console.log('Solicito Img... ');
    response = xhr.responseText;
    if(response!='error'){
      console.log(response);
      response = response.trim();//quito espacios
      img.src = 'img/'+response;
    }
    }
    xhr.send('');
  }
