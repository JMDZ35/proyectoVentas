<?php ?>
<!DOCTYPE html>
<html lang="es"> 
    <?=$this->load->view("plantillas_base/standar/head",(isset($head))? array('headData'=> &$head):'' ,true);?>
    <style>body { padding-right: 0 !important }</style>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container"> 
                <?=$this->load->view("plantillas_base/standar/panel_izq",(isset($panel))?array('panelData'=> &$panel):'',true);?>
                <?=$this->load->view("plantillas_base/standar/top_navbar",(isset($navbar))?array('navbarData'=> &$navbar):'',true);?> 
                <div class="right_col" role="main" style="padding: 30px 50px 0;" >
                    <?=$this->load->view("bodys/".$body ,(isset($bodyData))? array('bodyData'=> &$bodyData):'',true);?> 
                </div>
                <?=$this->load->view("plantillas_base/standar/footer",(isset($footer))?array('footerData'=> &$footer):'',true);?>
            </div>
        </div> 
        <?=$this->load->view("plantillas_base/standar/js",(isset($js))? array('jsData'=> &$js) :'',true);?>
    </body>
</html>    
    
    
    
    
    
