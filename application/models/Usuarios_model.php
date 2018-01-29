<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Usuarios_model extends CI_Model{
    
    public function __construct() {
        parent::__construct(); 
    }
    
    public function login($usuario ,$pass ) { 
        return
                $this->db->select('  
        ma.id           as CODIGO,    
	mu.nom_usuario	as USUARIO      ,
        ma.documento   as DOCUMENTO    ,
        mt.num_tel	as TELEFONO     ,
        mu.clav_usuario as CLAVE        ,
        mu.role_usuario as ROLES        ,
        mc.des_correo	as CORREO	                                       
                      ')
                ->from('maepersona  ma')
                ->join('maecorreos  mc','ma.id=mc.id_persona' )
                ->join('maetelefono mt','ma.id=mt.id_persona')
                ->join('maeusuarios mu','ma.id=mu.id_persona')
                ->where(array('mu.nom_usuario'=>$usuario,'mu.clav_usuario'=>$pass))     
                ->get()->result_object()  ;  
        
    }
    
    public function cargarData(){
        $this->db->distinct()
                ->select('  
        ma.id           as CODIGO,    
        CONCAT(ma.ape_pat_per,\' \',ma.ape_mat_per,\' \',ma.nom_per) as PERSONA ,
	mu.nom_usuario	as USUARIO      ,
        mt.num_tel	as TELEFONO     ,
        mc.des_correo	as CORREO	,
        mu.role_usuario	as ROLES	,
           case   mu.role_usuario	
           when  2 then \'DIRECTOR\'
           when  3 then \'SUB DIRECTOR\'
           when  4 then \'VENDEDOR\'
           when  5 then \'AUXILIAR\'
           when  6 then \'CLIENTE\'  END AS CARGO                                             
                      ')
                ->from('maepersona  ma' ,'left')
                ->join('maecorreos  mc','ma.id=mc.id_persona' ,'left')
                ->join('maetelefono mt','ma.id=mt.id_persona','left')
                ->join('maeusuarios mu','ma.id=mu.id_persona','left')
                ->where('mu.role_usuario<>','1')
                ->order_by('1','desc');             
        
        return $this->db->get()->result_object() ;          
    }
    public function CheckList($datos) { 
        return $this->db->select(" * ")->from("maepersona ")
                ->where($datos)->get()->result_object();
    }
    
    public function registrarPer($datos){
        return $this->db->insert("maepersona",$datos);
    }
    public function ultimoId(){
        $this->db->select("id")->from("maepersona")->order_by("1","desc")->limit(1);
        return $this->db->get()->row_array();
    }
    public function registrarCor($datos){
        return $this->db->insert("maecorreos",$datos);
    }
    public function registrarTel($datos){
        return $this->db->insert("maetelefono",$datos);
    }
    public function registrarUsu($datos){
        return $this->db->insert("maeusuarios",$datos);
    }
    public function registrarCliente($datos){
        return $this->db->insert("maecliente",$datos);
    }    
}
    ?>