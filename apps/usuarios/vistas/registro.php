<div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Agregar nuevo usuario</h1>
                            </div>
                            <form class="user" method="POST" action="index.php?modulo=usuarios&accion=registro" autocomplete="off">
                                 <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Nombre Completo" name="nombre_completo">
                                    </div>
                                    
                                </div>
                                <div class="col-sm-6">
                                    <select class="form-select " id="floatingSelect" aria-label="Floating label select example" name="id_rol">
                                        
                                        <option value="1">
                                            Administrador
                                        </option>
                                        
                                        <option value="2">
                                            Vendedor
                                        </option>
                                        
                                        <option value="3">
                                            Consultor
                                        </option>
                                    </select>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Correo Electrónico" name="correo">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Contraseña" name="contrasena">
                                    </div>
                                </div>
                                
                                <hr>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Registrar Usuario
                                </button>
                            </form>
                   
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>