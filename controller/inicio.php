<?php

/**
 * Controlador de la pagina de inicio
 *
 * @author Tiz
 */



class inicio extends fs_controller
{
    //public $lista_aparatos;
    

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Inicio', 'Averias', FALSE, FALSE);
    }
    
    protected function private_core() 
   {
        
    }
}
