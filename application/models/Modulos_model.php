<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Modulos_model extends CI_Model{
    
    public function __construct() {
        parent::__construct(); 
    }
    
    public function GetModulosDisponibles($usuarioId ,$rol = 1 ) {      
        return  $this->db->select(" webmodulos.* ,webusuariopermisos.permiso") 
            ->from("maeusuarios ") 
            ->join("webusuariopermisos  ", ' webusuariopermisos.WebUsuarios_id = maeusuarios.id_persona  ', 'left') 
            ->join("webmodulos ", 'webmodulos.id = webusuariopermisos.WebModulos_id ', 'left')        
            ->where("webmodulos.status","1")
            ->where("maeusuarios.id_persona", $usuarioId )
            ->order_by('webmodulos.titulo', 'ASC')->get()->result_object()  ;  
    }    
    public function GetModulosTotales(  ) { 
        return  $this->db->select("  * ")->from("webmodulos")->order_by('id', 'ASC')
                ->get()->result_object()   ;   
    }    
    public function GetModulosGrupos($rol) { 
        return  $this->db->select("  * ")->from(" webmodulosgrupos  ") 
                ->where(" webUsuariosRol_id >=",$rol)
                ->get()->result_object()   ;   
    }
}
?>