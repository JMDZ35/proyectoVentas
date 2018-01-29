<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
                     parent::__construct();         
    }
     
    public function index()
    {                   
                    $this->load->view('plantillas_base/login/body');
    }
    
    public function login() {
        try {            
                    $propiedades = new stdClass()                   ; 
                    $propiedades->webCasSession = new stdClass()    ;                          
                    $this->session->unset_userdata('webCasSession') ;
                    $this->load->library('encryption')              ;
                    $user =  $this->input->post('user')             ; 
                    $pass =  $this->input->post('pass')             ;                                   
                    if(empty($user) || empty($pass)){
                        $this->session->set_flashdata('flashdata_respuesta','Datos Invalidos, Intente Nuevamente.');
                        redirect('login');
                    }
                    $this->load->model("Usuarios_model",'',TRUE)    ;
                    $this->load->model("Modulos_model",'',TRUE)     ;
                  //  $this->load->model("Ubicaciones_model",'',TRUE) ;
                    $usuario = $this->Usuarios_model->login($user,$pass);
                 
                    if(count($usuario)>0){ 
                        $usuario = $usuario[0];
                        $newdata = array(
                            'session_user_id'         => $usuario->CODIGO               ,
                            'session_user_acces_rol'  => $usuario->ROLES                ,
                            'session_user_nombre'     => $usuario->USUARIO              ,
                            'session_user_documento'  => $usuario->DOCUMENTO            , 
                            'session_user_usuario'    => $usuario->USUARIO             ,
                            'session_user_email'      => $usuario->CORREO               , 
                            'session_user_logged_in'  => TRUE                           , 
                        );
                        //print_r($newdata); die();
                    $propiedades->webCasSession->usuario = (object) $usuario  ; 
                    $propiedades->webCasSession->usuario->logged_in = true    ;              
                    $modulos = ($usuario->ROLES == 1)? $this->Modulos_model->GetModulosTotales(): $this->Modulos_model->GetModulosDisponibles($usuario->CODIGO  ,$usuario->ROLES   )    ;
                    $newdata = array('session_modulos' => array( ) );
                    $propiedades->webCasSession->modulos = array();
                    if($usuario->ROLES == 1){
                    for ($i=0;$i<count($modulos);$i++) {
                        $modulo =& $modulos[$i];
                        
                        $newdata['session_modulos'][$i] =
                            array(
                                'id'                            => $modulo->id                          ,
                                'self_WebModulos_id_parent'     => $modulo->self_WebModulos_id_parent   ,
                                'WebModulosGrupos_id'           => $modulo->WebModulosGrupos_id         ,
                                'WebUsuariosRol_id'             => $modulo->WebUsuariosRol_id           ,
                                'titulo'                        => $modulo->titulo                      ,
                                'descripcion'                   => $modulo->descripcion                 ,
                                'esPanelInicial'                => $modulo->esPanelInicial              ,
                                'uri'                           => $modulo->uri                         , 
                                'html_clases'                   => $modulo->html_clases                 ,
                                'status'                        => $modulo->status                      ,
                                'isVisible'                     => $modulo->isVisible                   ,
                                'modus'                         => $modulo->modus                      
                            );
                        
                        $propiedades->webCasSession->modulos[$i] = $modulo;
                    }
                    }else{
                    for ($i=0;$i<count($modulos);$i++) {
                        $modulo =& $modulos[$i];
                        
                        $newdata['session_modulos'][$i] =
                            array(
                                'id'                            => $modulo->id                          ,
                                'self_WebModulos_id_parent'     => $modulo->self_WebModulos_id_parent   ,
                                'WebModulosGrupos_id'           => $modulo->WebModulosGrupos_id         ,
                                'WebUsuariosRol_id'             => $modulo->WebUsuariosRol_id           ,
                                'titulo'                        => $modulo->titulo                      ,
                                'descripcion'                   => $modulo->descripcion                 ,
                                'esPanelInicial'                => $modulo->esPanelInicial              ,
                                'uri'                           => $modulo->uri                         , 
                                'html_clases'                   => $modulo->html_clases                 ,
                                'status'                        => $modulo->status                      ,
                                'isVisible'                     => $modulo->isVisible                   ,
                                'modus'                         => $modulo->modus                       ,
                                'permiso'                       => $modulo->permiso
                            );
                        
                        $propiedades->webCasSession->modulos[$i] = $modulo;
                    }                        
                    }
                //    print_r($newdata); die();
                
                    $modulosGrupos = $this->Modulos_model->GetModulosGrupos($usuario->ROLES)    ;
                    $newdata = array('session_modulosGrupos' => array());
                    $propiedades->webCasSession->modulosGrupos = array();
                    for ($i=0;$i<count($modulosGrupos);$i++) {
                        $modulosGrupo =& $modulosGrupos[$i];
                        $newdata['session_modulosGrupos'][$i] =
                            array(
                                'id'                    => $modulosGrupo->id                ,
                                'titulo'                => $modulosGrupo->titulo            , 
                                'descripcion'           => $modulosGrupo->descripcion       ,
                                'WebUsuariosRol_id'     => $modulosGrupo->WebUsuariosRol_id ,  
                            ); 
                    $propiedades->webCasSession->modulosGrupos[$i] = $modulosGrupo;
                    }                                                 
                                                    
                    $this->session->set_userdata(  (array) $propiedades     );                
                    switch ($usuario->ROLES){
                    case 1: redirect('Panel/vistaDirector'); break;
                    case 2: redirect('Panel/vistaDirector'); break;
                    case 3: redirect('Panel/vistaSubDirector'); break;
                    case 4: redirect('GestionVentas/index'); break;
                    case 5: redirect('Panel/vistaAuxiliar'); break;
                    case 6: redirect('GestionAlumno/consultarHorario'); break;        
                    }
                
            } else{
                    redirect('login');
            }
          
        } catch (Exception $exc) {
                    $this->session->sess_destroy(); 
                    redirect('login');
        } 
    }    
    public function logout() {
                    $this->session->sess_destroy();
                    $this->login();
    }                        
    public function standar()
    {   
  
                    $this->load->model("Permisos_model",'',TRUE);
                    $data = $this->Permisos_model->GetModulosDisponibles () ; 
                    var_dump($data);      
    }

    public function autoconsulta()
    {   
                    $this->load->model("Test_model",'',TRUE);
                    var_dump($this->Test_model->Get());
                    $this->load->view('welcome_message');
    }
}
?>