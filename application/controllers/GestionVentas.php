<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GestionVentas extends CI_Controller {

    public $htmlData = array();

    public function __construct() {
        parent::__construct();

        $this->htmlData = array(
            "body" => get_class($this)
            , "bodyData" => (object) array()
            , "headData" => (object) array()
            , "footerData" => (object) array()
        );
    }

    public function index() {
        $this->htmlData['body'] .= "/index";
        $this->htmlData['headData']->titulo = "GESTION :: TIENDA";
        $this->load->view('plantillas_base/standar/body', $this->htmlData);
    }

    public function usuarios() {
        $this->htmlData['body'] .= "/usuarios";
        $this->htmlData['headData']->titulo = "GESTION :: TIENDA";
        $this->load->view('plantillas_base/standar/body', $this->htmlData);
    }

    public function cargaDatos() {
        $this->load->model("Usuarios_model", '', TRUE);
        $cargarData = $this->Usuarios_model->cargarData();
        $this->htmlData['bodyData']->cuentas = $cargarData;
        $this->htmlData['headData']->titulo = "GESTION :: TIENDA";
        $this->load->view('vistaDialog/GestionVentas/bandeja', $this->htmlData);
    }

    public function productos() {
        $this->htmlData['body'] .= "/productos";
        $this->htmlData['headData']->titulo = "GESTION :: TIENDA";
        $this->load->view('plantillas_base/standar/body', $this->htmlData);
    }

    public function proveedor() {
        $this->htmlData['body'] .= "/proveedor";
        $this->htmlData['headData']->titulo = "GESTION :: TIENDA";
        $this->load->view('plantillas_base/standar/body', $this->htmlData);
    }

        public function crear() {
        $this->load->model("Usuarios_model", '', TRUE);
        $nombre = $this->input->post("txtNombre");
        $apepat = $this->input->post("txtPaterno");
        $apemat = $this->input->post("txtMaterno");
        $credito = $this->input->post("credito");
        $email = $this->input->post("email");
        $telefono = $this->input->post("telefono");
        $txtDNI = $this->input->post("txtDNI");
        $nacimiento = $this->input->post("nacimiento");
       
        if ($nombre == "" || $apepat == "" || $apemat == "" || $credito == "" || $email=="" || $telefono=="" || $nacimiento=="" || $txtDNI=="" ) {
            echo "<h3>Está prescindiendo uno o más campos obligatorios</h3>";
            $this->load->view("usuarios", $this->htmlData);
        } else {

            $datosPer = array(
                "nom_per" => $nombre,
                "ape_pat_per" => $apepat,
                "ape_mat_per" => $apemat,
                "dni"=>$txtDNI,
                "f_nacimiento"=>$nacimiento,
                "usu_creacion" => $this->session->webCasSession->usuario->USUARIO,
                "fec_creacion" => date("Y-m-d")
            );                                            
            $this->Usuarios_model->registrarPer($datosPer);
            $codigo= $this->Usuarios_model->ultimoId();
            
            $datosCorreo=array(
                "id_persona" => $codigo['id'],
                "des_correo" => $email,
                "usu_creacion" => $this->session->webCasSession->usuario->USUARIO,
                "fec_creacion" => date("Y-m-d")
                );
             $this->Usuarios_model->registrarCor($datosCorreo);
            
            $datosTelefono=array(
                "id_persona" => $codigo['id'],
                "num_tel" => $telefono,
                "usu_creacion" => $this->session->webCasSession->usuario->USUARIO,
                "fec_creacion" => date("Y-m-d")

                );
             $this->Usuarios_model->registrarTel($datosTelefono);

           // $datosUsu=array(              
                //);

             $datosCliente=array(
                "id_persona" => $codigo['id'],
                "l_crediticio" => $credito
                
                );
             $this->Usuarios_model->registrarCliente($datosCliente);
        }

    }

}
?>