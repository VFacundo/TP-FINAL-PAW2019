<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login Fulbito!</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="background"></div>
    <section id="login">
      <h1>Hay Fulbito</h1>
      <figure>
        <img src="img/logoBlack.png" alt="Logo Fulbito!">
      </figure>
      <h2>Ingreso al sistema</h2>
      <form action="login/loginUser" method="post">
        <label>Usuario o email<input type="text" name="email" placeholder="SuCorreo@email.com" required></label>
        <label>Contraseña<input type="password" name="pass" required></label>
        <div>
          <input type="reset" name="borrar" value="Restaurar Campos">
          <input type="submit" name="login" value="Login">
        </div>
      </form>
      <label><?=$mensaje?></label>
      <a href="login/registro">Crear Cuenta</a>
    </section>
  </body>
</html>
