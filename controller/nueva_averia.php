<?php

/**
 * Controlador de nueva averia
 *
 * @author sm
 */
require_model('tablaaparatos.php');
require_model('tablamarcas.php');
require_model('tablatipos.php');
require_model('tablachasis.php');
require_model('tablaaverias.php');
require_model('tablasintomas.php');
require_model('fs_user.php');

class nueva_averia extends fs_controller
{
    
    public $tablaaverias;
  


    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Nueva Avería', 'Averias', FALSE,FALSE);
    }
    
    protected function private_core() 
    {
        $this->tablaaverias = new tablaaverias();
        $this->tablaaparatos = new tablaaparatos();
        $this->tablamarcas = new tablamarcas();
        $this->tablatipos = new tablatipos();
        $this->tablachasis = new tablachasis();
        $this->tablasintomas = new tablasintomas();
        $this->fs_user = new fs_user();
        
        if ( isset($_POST['sintoma']) )
        {
            $this->tablaaverias->idaparato = $_POST['idaparato'];
            $this->tablaaverias->familia = $_POST['familia'];
            $this->tablaaverias->sintoma = $_POST['sintoma'];
            $this->tablaaverias->solucion = $_POST['solucion'];
            $this->tablaaverias->enlace = $_POST['enlace'];
            $this->tablaaverias->usuario = $_POST['usuario'];
            
                       
                
                
            if ( $this->tablaaverias->save() )
             {
                $this->new_message('Se ha guardado correctamente');
             }
            else
                $this->new_error_msg ('Error al guardar los datos');
        }
        
    }
}