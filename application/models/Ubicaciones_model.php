<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Ubicaciones_model extends CI_Model{
    
    public function __construct() {
        parent::__construct(); 
    }
    
    private function delimitar(array $arrayPorCheckear,array $coincidencias){ 
        $temp = array();
        foreach ($arrayPorCheckear as $tempKey => $tempVal) {
            if (array_key_exists($tempKey, $coincidencias)) {
                $temp[$tempKey] = $tempVal;
            } else{
                unset($coincidencias[$tempKey]);
            }
        } 
        return $temp;
    }
    
    

    public function GetLocations($id=null   ) { 
        $this->db->select(' * ')->from('cplocation_tbl')->order_by('cplt_name', 'ASC')->limit(1) ; 
        if(!empty($id)){
            $this->db->where('cplt_id', $id); 
        }        
        return $this->db->get()->result()[0]; 
    }
    
    public function GetDivision($id=null   ) { 
        $this->db->select(' * ')->from('cpdivision_tbl')->order_by('cpds_name', 'ASC')->limit(1) ; 
        if(!empty($id)){
            $this->db->where('cpds_id', $id); 
        }        
        return $this->db->get()->result()[0];  
    }
    
    public function GetDepartament($id=null   ) { 
        $this->db->select('  * ')->from('cpdepartment_tbl')->order_by('cpdt_name', 'ASC')->limit(1) ; 
        if(!empty($id)){
            $this->db->where('cpdt_id', $id); 
        }        
        return $this->db->get()->result()[0]; 
    }
    
    public function GetCentroCosto($id   ) { 
        $this->db->select('  * ')->from('WebCpCentroCosto')
                ->order_by('name', 'ASC')->where('id', $id)  ; 
        return $this->db->get()->result()[0]; 
    }
    
    public function GetCentroCostoDiario($CentroCostoId=null ,$user_tbl_id =null,$datetime = null ) { 
        $this->db->select(' WebCpCentroCostoDiario.*, WebCpCentroCosto.name as WebCpCentroCosto_name ')->from('WebCpCentroCostoDiario')
                ->join("WebCpCentroCosto ", 'WebCpCentroCosto.id = WebCpCentroCostoDiario.WebCpCentroCosto_id ', 'left')
                ->order_by('hastaDatetime', 'DESC');  
        if($CentroCostoId!=null){
            $this->db->where('WebCpCentroCosto_id',$CentroCostoId);
        }
        if($user_tbl_id!=null){
            $this->db->where('user_tbl_id',$user_tbl_id);
        }
        if($datetime!=null){
            $this->db->where('desdeDatetime',$datetime)
                    ->where('hastaDatetime',$datetime); 
        } 
        return $this->db->get()->result() ; 
    }
    
    public function desAsignarCentroCostoDiario($user_tbl_id =null,$datetime=null) { 
        $datetime = ($datetime==null)?date('YmdHis'): $datetime;
        $data = array(
            'hastaDatetime' => $datetime
        ); 
        $this->db->where('user_tbl_id', $user_tbl_id);
        $this->db->where('hastaDatetime >', $datetime);
        return $this->db->update('WebCpCentroCostoDiario', $data); 
    }
    
    
    
    
     
    public function GetLocationsList($desde=0,$hasta = 200 ,$soloActivos = true  ) { 
        $this->db->select(' * ')
                ->from('cplocation_tbl')
                ->limit($hasta, $desde)
                ->order_by('cplt_name', 'ASC'); 
        if($soloActivos){
            $this->db->where('cplt_status   ', '1'); 
        }else{
            $temp = array ( 'cplt_status'=>'1'  );
            $this->db->where($temp); 
        }  
        return $this->db->get()->result() ;
    }
    
    public function GetDivisionList($desde=0,$hasta = 200 ,$soloActivos = true   ) { 
        $this->db->select(' * ')
                ->from('cpdivision_tbl')
                ->limit($hasta, $desde)
                ->order_by('cpds_name', 'ASC')  ;
        if($soloActivos){
            $this->db->where('cpds_status >=', '1'); 
        }else{
            $temp = array ( 'cpds_status '=>'1'  );
            $this->db->where($temp); 
        }  
        return $this->db->get()->result() ; 
    }
    
    public function GetDepartamentList($desde=0,$hasta = 200 ,$soloActivos = true   ) { 
        $this->db->select(' * ')
                ->from('cpdepartment_tbl')
                ->limit($hasta, $desde)
                ->order_by('cpdt_name', 'ASC') ; 
        if($soloActivos){
            $this->db->where('cpdt_status  >=', '1'); 
        }else{
            $temp = array ( 'cpdt_status '=>'1'  );
            $this->db->where($temp); 
        }  
        return $this->db->get()->result() ; 
    }
    
    public function GetCentroCostoList($desde=0,$hasta = 200 ,$soloActivos = true   ) { 
        $this->db->select(' * ')
                ->from('webcpcentrocosto')
                ->limit($hasta, $desde)
                ->order_by('name', 'ASC');
        if($soloActivos){
            $this->db->where('status >=', '1'); 
        }else{
            $temp = array ( 'status '=>'1'  );
            $this->db->where($temp); 
        }  
        return $this->db->get()->result() ; 
        return $result->result() ;
    }
    
    public function GetCentroCostoUsersList($campos='*',  $where ,array $delimitador = array(''=>array())  ) { 
        $this->db->select($campos)
                ->from('WebCpCentroCosto')
                ->join('WebCpCentroCostoDiario',  'WebCpCentroCostoDiario.WebCpCentroCosto_id = WebCpCentroCosto.id') 
                ->join('user_tbl',  'user_tbl.user_id = WebCpCentroCostoDiario.user_tbl_id') 
                ->order_by('name', 'ASC') 
                ->where($where);
         
        foreach ($delimitador as $key => $delimitadoTemp) {
            if (count($delimitadoTemp)>0) {
                $this->db->where_in($key , $delimitadoTemp);
            } 
        }
        return $this->db->get()->result_object() ; 
        return $result->result() ;
    }
    ///// FIN SELECTORES
    
    public function getRestriccionUbicaciones($usuarioId ,$area=null,$areaId=null) {
        $this->db->select(' * ')
                ->from('webrestriccionubicaciones')
                ->where('WebUsuario_id', $usuarioId); 
        if($area!=NULL)     { $this->db->where('area',$area); }
        if($areaId!=NULL)   { $this->db->where('areaId',$areaId); } 
        return $this->db->get()->result_object();  
    }
    
    public function setRestriccionUbicaciones(array $datos  = array()) {
        $data = array( 
            'WebUsuario_id'    => null,
            'area'       => null,
            'areaId'   => null,
        ); 
        
        foreach ($datos as $tempKey => $tempVal) {
            if (array_key_exists($tempKey, $data)) {
                $data[$tempKey] = $tempVal;
            } 
        } 
        return $this->db->insert('WebRestriccionUbicaciones', $data); 
    }
    
    public function deleteRestriccionUbicaciones(array $condicion = array()) {
        $data = array( 
            'id' => null,
            'WebUsuario_id'    => null,
            'area'       => null,
            'areaId'   => null,
        ); 
        $parametros = array();
        foreach ($condicion as $tempKey => $tempVal) {
            if (array_key_exists($tempKey, $data)) {
                $parametros[$tempKey] = $tempVal;
            } 
        } 
        return $this->db->delete('WebRestriccionUbicaciones',$parametros); 
    }
    
    ///MANIPULADORES   LOCATION
    public function setLocation(array $datos  ) {
        $datetimeIso = date('YmdHms');
        $id = $this->GetMaxIdFromTable('cplt_id','cplocation_tbl');
        $permitidos = array(
            'cplt_id'     => ++$id,
            'cplt_status' => 1,
            'cplt_created' => $datetimeIso ,
            'cplt_updated' => $datetimeIso, 
            'cplt_name'   => '',
            'cplt_desc'   => '',
            'mime'   => '',
            'nomArchivo'=> ''
        );  
        $enviados = $this->delimitar($datos, $permitidos );  
        $permitidos = array_merge($permitidos ,$enviados);
        return ($this->db->insert('cplocation_tbl',$permitidos))?$id : null ; 
    }
    
    
    public function UpdateLocation(array $datos,$id  ) {
        $datetimeIso = date('YmdHms');
        $permitidos = array(
            'cplt_status' => 0,
            'cplt_updated' => '', 
            'cplt_name'   => '',
            'cplt_desc'   => '',
            'mime'   => '',
            'data'   => ''                  
        );  
        $permitidos = $this->delimitar($datos, $permitidos );  
        $permitidos['cplt_updated'] = $datetimeIso;
        $this->db->where('cplt_id', $id);
        return $this->db->update('cplocation_tbl', $permitidos);
    } 
    
    public function DeleteLocation( $id  ) { 
        $this->db->where('cplt_id', $id);
        return $this->db->delete('cplocation_tbl');
    }
    ///FIN MANIPULADORES   LOCATION
    
    
    ///MANIPULADORES   DIVISION
    public function setDivision(array $datos  ) {
        $datetimeIso = date('YmdHms');
        $id = $this->GetMaxIdFromTable('cpds_id','cpdivision_tbl');
        $permitidos = array(
            'cpds_id'     => ++$id,
            'cpds_status' => 1,
            'cpds_created' => $datetimeIso ,
            'cpds_updated' => $datetimeIso, 
            'cpds_name'   => '',
            'cpds_desc'   => '',
            'mime'   => '',
            'nomArchivo'=> ''
        );  
        $enviados = $this->delimitar($datos, $permitidos );  
        $permitidos = array_merge($permitidos ,$enviados);
        return ($this->db->insert('cpdivision_tbl',$permitidos))?$id : null ; 
    }
    
    
    public function UpdateDivision(array $datos,$id  ) {
        $datetimeIso = date('YmdHms');
        $permitidos = array(
            'cpds_status' => 0,
            'cpds_updated' => '', 
            'cpds_name'   => '',
            'cpds_desc'   => ''
        );  
        $permitidos = $this->delimitar($datos, $permitidos );  
        $permitidos['cpds_updated'] = $datetimeIso;
        $this->db->where('cpds_id', $id);
        return $this->db->update('cpdivision_tbl', $permitidos);
    } 
    
    public function DeleteDivision( $id  ) { 
        $this->db->where('cpds_id', $id);
        return $this->db->delete('cpdivision_tbl');
    }
    ///FIN MANIPULADORES   DIVISION
    
   
    
    ///MANIPULADORES   CENTROCOSTO
    public function setCentroCosto(array $datos  ) {
        $datetimeIso = date('YmdHms');
        $id = $this->GetMaxIdFromTable('id','WebCpCentroCosto');
        $permitidos = array(
            'id'     => ++$id,
            'status' => 1,
            'created' => $datetimeIso ,
            'updated' => $datetimeIso, 
            'name'   => '',
            'desc'   => '',
            'mime'   => '',
            'nomArchivo'=> ''
        );  
        $enviados = $this->delimitar($datos, $permitidos );  
        $permitidos = array_merge($permitidos ,$enviados);
        return ($this->db->insert('WebCpCentroCosto',$permitidos))?$this->db->insert_id() : null ; 
    }
    
    public function setCentroCostoDiario($user_tbl_id , $WebCpCentroCosto_id,$desdeDatetime=null,$hastadatetime=null ) {
        $desdeDatetime = ($desdeDatetime!=null)? new DateTime($desdeDatetime): new DateTime();
        $hastadatetime = ($hastadatetime!=null)? new DateTime($hastadatetime): new DateTime($desdeDatetime->format('YmdHis'));
        
        if($hastadatetime ==$desdeDatetime ){
            $hastadatetime->add(new DateInterval('P20Y'));
        }
        
        $permitidos = array(
           // 'cpdt_id'     => ++$id,
            'WebCpCentroCosto_id' => $WebCpCentroCosto_id,
            'user_tbl_id' => $user_tbl_id ,
            'desdeDatetime' => $desdeDatetime->format('YmdHis'), 
            'hastaDatetime'   => $hastadatetime->format('YmdHis'),  
        );   
        //var_dump($desdeDatetime->format('YmdHis')  , $hastadatetime->format('YmdHis')   ,1,$permitidos); exit(); 
        return ($this->db->insert('WebCpCentroCostoDiario',$permitidos))?$this->db->insert_id() : null ; 
    }
    
    
    public function UpdateCentroCosto(array $datos,$id  ) {
        $datetimeIso = date('YmdHms');
        $permitidos = array(
            'status' => 0,
            'updated' => '', 
            'name'   => '',
            'desc'   => ''
        );  
        $permitidos = $this->delimitar($datos, $permitidos );  
        $permitidos['updated'] = $datetimeIso;
        $this->db->where('id', $id);
        return $this->db->update('WebCpCentroCosto', $permitidos);
    } 
    
    public function DeleteCentroCosto( $id  ) { 
        $this->db->where('id', $id);
        return $this->db->delete('WebCpCentroCosto');
    }
    ///FIN MANIPULADORES   CENTROCOSTO
    
    
}
?>