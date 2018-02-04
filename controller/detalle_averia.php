<?php

/**
 * Controlador de editar averia
 *
 * @author Tiz
 */

require_model('tablaaverias.php');
require_model('tablaaparatos.php');
require_model('tablasintomas.php');
require_model('fs_user.php');

class detalle_averia extends fs_controller
{
    public $lista_aparatos;
    public $tablaaverias;
    public $editar;
   

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Detalle Averia', 'Averias', FALSE, FALSE);
    }
    
    protected function private_core() 
   {
        $this->tablaaparatos = new tablaaparatos();
        $this->tablaaverias = new tablaaverias();
        $this->tablasintomas = new tablasintomas();
        $this->fs_user = new fs_user();
        $this->editar = FALSE;
       
                
        if( isset($_POST['idaveria']) )//Editar
        {
            $this->editar = $this->tablaaverias->get($_POST['idaveria']);
            if ($this->editar)
            {
               $this->editar->idaparato = $_POST['idaparato'];
               $this->editar->idaveria = $_POST['idaveria'];
               $this->editar->familia = $_POST['familia'];
               $this->editar->sintoma = $_POST['sintoma'];
               $this->editar->solucion = $_POST['solucion'];
               $this->editar->enlace = $_POST['enlace'];
               $this->editar->usuario = $_POST['usuario'];
                              
               
                if ( $this->editar->save() )
                   {
                      $this->new_message('Se ha modificado correctamente');
                   }
                else
                    $this->new_error_msg ('Error al modificar los datos');
                
               
            }
        $this->editar = FALSE;    
           
        }
        
        else if( isset($_GET['id']) ) //Mostrar el formulario editar
        {
          $this->editar = $this->tablaaverias->get($_GET['id']);
        }
        
        else if( isset($_GET['borrar']) ) //Eliminar datos
        {
           $borrando = $this->tablaaverias->get($_GET['borrar']);
           
           if ($borrando)
           {
              if ( $borrando->delete() )
              {
                $this->new_message('Se ha eliminado correctamente');
              }
              else 
                $this->new_error_msg ('Error al eliminar los datos');
           }
           else 
              $this->new_error_msg ('No se han encontrado datos para eliminar');
              
           
        }    
   }   
        
}
