<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Registro de productos <small>Ingrese datos en los campos y presione el botón "registrar"</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Settings 1</a>
                            </li>
                            <li><a href="#">Settings 2</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form class="form-horizontal form-label-left input_mask" name="crearUsuario" id="crearUsuario">

                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="txtNombre" name="txtNombre" placeholder="Nombres">
                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="txtPaterno" name="txtPaterno" placeholder="Apellidos paternos">
                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="txtMaterno" name="txtMaterno" placeholder="Apellidos maternos">
                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="number" class="form-control has-feedback-left" id="credito" name="credito" placeholder="Límite crediticio">
                        <span class="fa fa-credit-card form-control-feedback left" aria-hidden="true"></span>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="email" class="form-control has-feedback-left" id="email" name="email" placeholder="Email">
                        <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="number" class="form-control has-feedback-left" id="telefono" name="telefono" placeholder="Teléfono">
                        <span class="fa fa-commenting form-control-feedback left" aria-hidden="true"></span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="date" class="form-control has-feedback-left" id="nacimiento" name="nacimiento" placeholder="Fecha de nacimiento">
                        <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="number" class="form-control has-feedback-left" id="txtDNI" name="txtDNI" placeholder="DNI">
                        <span class="fa fa-credit-card-alt form-control-feedback left" aria-hidden="true"></span>
                    </div>


                    <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-xs-12     ">
                            <button type="button" class="btn btn-primary" id="btnRegistrar" name="btnRegistrar">Registrar</button>
                        </div>
                    </div>
                <label class="col-xs-6 col-md-3">
                <p id="result_error"></p>
                </label>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Listado de productos</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Settings 1</a>
                            </li>
                            <li><a href="#">Settings 2</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
           
        </div>
    </div>
</div>

<script src="<?= base_url('publico/js_vistas/js/usuarios.js')?>"></script>