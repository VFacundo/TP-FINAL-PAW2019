<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Registro Fulbito!</title>
    <link rel="stylesheet" href="styles/inicial.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <h1>LOGIN</h1>
    <figure>
      <img src="resources/logo.png" alt="Logo Fulbito!">
    </figure>
    <form id="form" class="" action="login/loginUser" method="post">
      <label>User/Mail<input type="text" name="email" placeholder="example@mail" required></label><br>
      <label>Pass<input type="password" name="pass" value="user" required></label><br><br>
      <input type="submit" name="install" id="login" value="Login!"></button>
      <input type="reset" name="borrar" value="Borrar"></button>
    </form>
    <label><?=$mensaje?></label>
  </body>
</html>
