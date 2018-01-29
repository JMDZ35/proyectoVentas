<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 Libreria dedicada a la redireccion del sitio en caso de no poseer session abierta 
 * y permite acceso a las clases_perdonadas
 */


class SessionCheck {
    /* referencia el nombre de la direccion a onmitir, sea solo por el nombre de la clase o clase y metodo
     * array(
     *      'CLASS' -> referencia las clases
     *      ,array(
     *          'CLASS'=>'Method'
     *      )
     */
    
    private $ci;
    
    private $clases_perdonadas = array(
        'index'
        ,'login'
        ,'migrate'
      //  ,array( 'CLASE','METODO_ESPECIFICO') 
        
        );
    
    /*
     * el constructor verifica si se encuentra en un sitio permitido(Sin necesidad de la session .)
     * y SEGUIDAMENTE verifica que al tener session abierta solo se pueda acceder a los modulos permitidos
     * 
     */
    public function __construct() {
        $this->ci =& get_instance(); 
        
        $dir_actual = (count($this->ci->uri->segment_array())>0)?$this->ci->uri->segment_array():array(1=>'')    ;
         
        foreach ($this->clases_perdonadas as  $value1) { 
            if(is_array($value1)){ 
                if ( (strtolower( $dir_actual[1] ) ==$value1[1]) and (strtolower( $dir_actual[2] ) == $value1[2]) ){
                    return; 
                }
            }else{
                if ( strtolower( $dir_actual[1] ) == $value1  ){ 
                    return; 
                }
            } 
        }
        if(empty($this->ci->session->webCasSession->usuario->logged_in  ) or $dir_actual[1] == ''){ 
            redirect('login/index'); 
            
        }elseif(!$this->tienePermiso()){
            $this->header401();
        }
    }
    
    
    private function tienePermiso(){
        $this->ci->session->webCasSession->modulos ;
        
        $claseMetodo = $this->ci->uri->segment(1)."/".$this->ci->uri->segment(2, 'index') ;
        $claseMetodo = strtolower($claseMetodo);
        
        foreach ($this->ci->session->webCasSession->modulos as $value) {            
            if(strtolower($value->uri) == $claseMetodo){
                return true;//si tiene permiso se deja fluir else muestra error 
            }
        }
        
        return false;
        // 401 acceso denegado asi si estan en ajax, muestra un error de permisos
        // y de estar en pagina principal muestra el html
        $this->ci->output->set_status_header(401)->set_header('HTTP/1.0 401');;
        $html = $this->ci->load->view("errors/html/error_acces_denied",null,TRUE);   
        die($html); 
        
    }


    private function header401() {
        // 401 acceso denegado asi si estan en ajax, muestra un error de permisos
        // y de estar en pagina principal muestra el html
        $this->ci->output->set_status_header(401)->set_header('HTTP/1.0 401');;
        $html = $this->ci->load->view("errors/html/error_acces_denied",null,TRUE);   
        die($html); 
    }





    public static  function GetAccesLvl() {
        try {
            return (!empty($this->ci->session->webCasSession->usuario->webUsuariosRol_id ))? $this->ci->session->webCasSession->usuario->WebUsuariosRol_id:0; 
        } catch (Exception $exc) {
            return 0;
        } 
    }
    
    public static function CheckAccesLvl($rol_requerido) {
        try {
            $valor = (!empty($this->ci->session->webCasSession->usuario->webUsuariosRol_id))? $this->ci->session->webCasSession->usuario->WebUsuariosRol_id:0; 
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
