<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login Fulbito!</title>
    <link rel="stylesheet" href="styles/inicial.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <h1>REGISTRO</h1>
    <figure>
      <img src="resources/logo.png" alt="Logo Fulbito!">
    </figure>
    <form id="form" class="" action="registroUser" method="post">
      <label>Mail<input type="email" name="email" placeholder="Example@mail" required></label><br>
      <label>Pass<input type="password" name="pass" value="contrasenia" required></label><br>
      <label>Nombre<input type="text" name="nombre" placeholder="Nombre" required></label><br>
      <label>UserName<input type="text" name="username" placeholder="User Name" required></label><br>
      <label>Edad<input type="number" name="edad" value="" min="10" max="100" required></label><br>
      <label>Telefono: <input type="tel" name="tel" placeholder="542346454545" maxlength="12"></label><br>
      <input type="submit" name="install" id="login" value="Registrarme!"></button>
      <input type="reset" name="borrar" value="Borrar"></button>
    </form>
    <label><?=$mensaje?></label>
  </body>
</html>
