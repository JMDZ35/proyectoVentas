<?php ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?=base_url('publico/media/indice.ico')?>" type="image/x-icon"/>  
  <title>Intranet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">    
      <link rel="stylesheet" href="<?=base_url('publico/login/css/style.css')?>">
</head>
<body>
  <div class="cont">
  <div class="demo">
    <div class="login">
      <div class="login__check"></div>
        <form action="<?= site_url('login/login') ?>" method="post">      
            <div class="login__form">          
                <div class="login__row">
                    <input name="user" type="text" class="login__input name" placeholder="Usuario"/>
                </div>
                <div class="login__row">
                    <input name="pass" type="password" class="login__input pass" placeholder="Password"/>
                </div>
                    <input type="submit"  class="login__submit" value="Ingresar" >
        
        <p class="login__signup">Olvide la contraseña &nbsp;<a>Click aqui</a></p>
      </div>
</form>
    </div>
  </div>
</div>

  

    <script src="<?=base_url('publico/login/js/index.js')?>"></script>
  

  
</body>
</html>