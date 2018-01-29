$("#bandejaprincipal").load("cargaDatos");



$("#btnRegistrar").click(function () {



    var nombre = $("#txtNombre").val();
    var apepat = $("#txtPaterno").val();
    var apemat = $("#txtMaterno").val();
    var credito = $("#credito").val();
    var email = $("#email").val();
    var telefono = $("#telefono").val();
    var nacimiento = $("#nacimiento").val();
    var dni = $("#txtDNI").val();
   
   
    if (nombre === '') {
        $('#result_error').html("<font color='red'>Campo Nombre (*) Obligatorio</font>");
        return true;
    } else if (apepat === '') {
        $('#result_error').html("<font color='red'>Campo Apellido paterno (*) Obligatorio</font>");
        return true;
    } else if (apemat === '') {
        $('#result_error').html("<font color='red'>Campo Apellido materno (*) Obligatorio</font>");
        return true;
    } else if (credito === '') {
        $('#result_error').html("<font color='red'>Campo credito           (*) Obligatorio</font>");
        return true;
    } else if (email === '') {
        $('#result_error').html("<font color='red'>Campo email          (*) Obligatorio</font>");
        return true;
    } else if (telefono === '') {
        $('#result_error').html("<font color='red'>Campo telefono            (*) Obligatorio</font>");
        return true;
    }  else if (nacimiento === '') {
        $('#result_error').html("<font color='red'>Campo nacimiento              (*) Obligatorio</font>");
        return true;
    } else if (dni === '') {
        $('#result_error').html("<font color='red'>Campo dni            (*) Obligatorio</font>");
        return true;
    }  else {
        
        $.ajax({
            type: "POST",
            url: "crear",
            data: $("#crearUsuario").serialize(),
            success: function () {
                //$("result_error").html("<font color ='green'>REGISTRO CORRECTO</font>");                    
                $("#bandejaprincipal").load("cargaDatos");
               // $("#crearusuario")[0].reset();
                //$('#result_error').html("");
                return false;

            }
        });
    }
        return true;
  
    

});