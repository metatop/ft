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

class nueva_averia_aparato_admin extends fs_controller
{
    
    public $tablaaverias;
    public $averia_por_aparato;
    public $id_aparato;
    public $ultimoID;


    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Nueva Avería por aparato admin', 'Averias');
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
                $this->id_aparato = $_POST['idaparato'];
                $this->ultimoID = $this->db->lastval();
                $this->new_message('<h3>La avería se ha guardado correctamente,</h3><h4>Ahora puedes:</h4>');
             }
            else
                $this->new_error_msg ('Error al guardar los datos');
        }
        else if( isset($_GET['id']) ) //Muestra el aparato al que se le va añadir una nueva averia.
        {
          $this->averia_por_aparato = $this->tablaaparatos->get($_GET['id']);
        }
    }
}