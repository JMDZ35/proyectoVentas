<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 Libreria dedicada a la Confirmacion de permisos de usuario 
 * para un metodo o Clase(se verifica en el __constructor)
 * 
 * 
 * El nivel mas alto de acceso es 1.. y cada numero mayor representa un menor nivel de acceso 2
 * el 0  representa que no tiene nivel de acceso o no tiene session activa
 */


class  SessionSeguridad {
    
    
    public static  function GetAccesLvl() {
        try {
            return (!empty($_SESSION['session_user_acces_rol']))? $_SESSION['session_user_acces_rol']:0; 
        } catch (Exception $exc) {
            return 0;
        } 
    }
    
    public static function CheckAccesLvl($rol_requerido) {
        try {
            $valor = (!empty($_SESSION['session_user_acces_rol']))? $_SESSION['session_user_acces_rol']:0; 
            // si el valor es mayor al nivel requerido, no posee permisos (1 es el mayor 5 menor)
            if($valor > $rol_requerido  ){   
                return false;  
            }else if ($valor == 0) {
                // si no tiene session o el nivel no esta asignado
                return FALSE;
            } 
        } catch (Exception $exc) {
            return false;
        } 
        return true;
    }
    
    /*
     * verifica si tiene permisos, y de lo contrario realiza una redireccion o muestra 
     * pantalla de acceso denegado, 
     * $nivel acceso es el requerido por la aplicacion
     * $accion si es false muestra pagina de error. True para redirigir
     */
    public static function CheckAccesLvlRedirect($rol_requerido,$accion = false) { 
        
        if(SELF::CheckAccesLvl($rol_requerido)){
            return true;
        }
        
        $ultimaUrl = (!empty($_SESSION['session_user_last_url']))? $_SESSION['session_user_last_url']:false;
        $CI =& get_instance(); 
        if($accion and !empty($ultimaUrl) ){
            redirect ($ultimaUrl);
        }else{
            $CI->load->view('errors/html/error_acces_denied'); 
        } 
    }
    
}
