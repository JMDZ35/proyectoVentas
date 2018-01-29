<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Rol_model extends CI_Model{
    
    public function __construct() {
        parent::__construct(); 
    }
     
    public function getList($id = null ,$rolMaximo = null ) { 
        $this->db->select(" * ")->from("webusuariosrolhijo"); 
       
       // die($sql);
        if($id!=null)        
            $this->db->where('id',$id);
        if($rolMaximo!=null) 
            $this->db->where( 'id >=' ,$rolMaximo ) ;
        return $this->db->get()->result_object() ;  
    }
    public function getbusqueda($codigo){
        $this->db->select('id')
                ->from('webmodulos')                
                ->where('WebUsuariosRol_id=',$codigo);
        
        return $this->db->get()->result_object();        
    }
    public function getCursos() { 
        $this->db->select(" id,nom_cursos ")->from("maecursos")->order_by('nom_cursos','desc');               
        return $this->db->get()->result_object() ;  
    }    
    public function getSeccion() { 
        $this->db->select(" id,nom_seccion ")->from("maeseccion")->order_by('nom_seccion','desc');               
        return $this->db->get()->result_object() ;  
    }    
    public function getGrados() { 
        $this->db->select(" id,nom_grado ")->from("maegrados")->order_by('nom_grado','desc') ;               
        return $this->db->get()->result_object();  
    }        
    public function getProfesores() { 
        $this->db->distinct()
                ->select('mp.ape_pat_per,mp.ape_mat_per,mp.nom_per,mu.role_usuario')
                ->from('maepersona mp')
                ->join('maeusuarios mu','mp.id=mu.id_persona' )
                ->where('mu.role_usuario',4);
        
        return $this->db->get()->result_object();
    }       
    public function busquedaRol($rolhijo) { 
        $this->db->select(" webusuariosrol_id,nombre ")->from("webusuariosrolhijo");  
        $this->db->where( 'id' ,$rolhijo ) ;
        return $this->db->get()->result_object() ;  
    }    
    
    public function getPermisosList($usuarioId,$moduloId = null) {
        $codigo=1;
        $this->db->select('webusuariopermisos.*,webmodulos.titulo,webmodulos.descripcion')
                ->from('webusuariopermisos')
                ->join('webmodulos','webmodulos.id = webusuariopermisos.WebModulos_id' )
                ->where(array('webusuariopermisos.WebUsuarios_id'=>$usuarioId,'webmodulos.isVisible'=>$codigo,'webusuariopermisos.permiso'=>$codigo))
                ->order_by('webmodulos.titulo', 'ASC');
        if($moduloId!=null) 
            $this->db->where(array('webusuariopermisos.WebUsuarios_id'=>$usuarioId,'webmodulos.isVisible'=>$codigo,'webusuariopermisos.permiso'=>$codigo));        
        return $this->db->get()->result_object();
    }
    public function getPermisosDisponible($usuarioId) {
        $this->db->select(' 
                wu.id,
                wu.webusuarios_id,
                wu.WebModulos_id,
                wb.id,
                wb.descripcion,
                wb.titulo             
                
                ')
                ->from('webmodulos wb')
                ->join('webusuariopermisos wu','wb.id=wu.webmodulos_id ' )
                ->where('wb.isVisible=1 and wu.permiso=0   and wu.WebUsuarios_id='.$usuarioId);

        return $this->db->get()->result_object();
    }
    public function setPermiso($usuarioId,$moduloId) {
        $data = array(
            'WebUsuarios_id' => $usuarioId,
            'WebModulos_id' => $moduloId,
            'permiso' => 1
        ); 
        return $this->db->insert('webusuariopermisos', $data); 
    }
    public function setPermiso1($usuarioId,$moduloId,$data) {
        $this->db->set($data);
        $this->db->where(array('WebUsuarios_id'=>$usuarioId,'WebModulos_id'=>$moduloId));        
        return $this->db->update('webusuariopermisos');
        //return $this->db->insert('WebUsuarioPermisos', $data); 
    }    
    public function deletePermiso($usuarioId,$moduloId,$data) {
        $this->db->set($data);
        $this->db->where(array('WebUsuarios_id'=>$usuarioId,'WebModulos_id'=>$moduloId));        
        return $this->db->update('webusuariopermisos');
        //return $this->db->insert('WebUsuarioPermisos', $data); 
    }
}
?>