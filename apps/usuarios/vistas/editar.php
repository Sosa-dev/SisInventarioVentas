                            <form class="user" action="index.php?modulo=usuarios&accion=editar" method="POST" >
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Nombre Completo" name="nombre_completo" value="<?php echo htmlspecialchars($_SESSION['usuario_editar']['nombre_completo']); ?>">
                                    </div><br><br>
                                    
                                </div>
                                <!--  -->
                                </div>
                                <div class="col-sm-6">
                                    <select class="form-select " id="floatingSelect" aria-label="Floating label select example" name="id_rol">
                                        <option value="">Seleccionar Rol</option>
                                        
                                        <option value="1" <?php echo ($_SESSION['usuario_editar']['id_rol'] == 1) ? 'selected' : ''; ?>>
                                            Administrador
                                        </option>
                                        
                                        <option value="2" <?php echo ($_SESSION['usuario_editar']['id_rol'] == 2) ? 'selected' : ''; ?>>
                                            Vendedor
                                        </option>
                                           
                                        <option value="3" <?php echo ($_SESSION['usuario_editar']['id_rol'] == 3) ? 'selected' : ''; ?>>
                                            Consultor
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Correo Electrónico" name="correo" value="<?php echo htmlspecialchars($_SESSION['usuario_editar']['correo']); ?>">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="estado">
                                            <option value="">Seleccionar Estado</option>
                                            
                                            <option value="1" <?php echo ($_SESSION['usuario_editar']['estado'] == 1) ? 'selected' : ''; ?>>
                                                Activo
                                            </option>
                                            
                                            <option value="0" <?php echo ($_SESSION['usuario_editar']['estado'] == 0) ? 'selected' : ''; ?>>
                                                Inactivo
                                            </option>
                                        </select>
                                        <!-- <input type="text" class="form-control form-control-user"
                                            id="" placeholder="estado" name="estado" value="<?php //echo htmlspecialchars($_SESSION['usuario_editar']['estado']); ?>"> -->
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-user btn-block">
                                    Guardar Cambios
                                </button>
                            </form><hr><br>
                            <a href="index.php?modulo=usuarios&accion=listar" class="btn btn-secondary btn-user btn-block">
                                 Regresar
                            </a>