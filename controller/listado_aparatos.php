<?php

/**
 * Controlador de listado de aparatos
 *
 * @author Tiz
 */

require_model('tablaaparatos.php');
require_model('tablamarcas.php');
require_model('tablatipos.php');
require_model('tablachasis.php');

class listado_aparatos extends fs_controller
{
    public $lista_aparatos;
    public $tablaaparatos;
    public $editar;
    public $offset;
    public $resultados;
    public $todos;
    public $txtbuscar;
   

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Buscar y añadir averías', 'Averías');
    }
    
    protected function private_core() 
   {
        $this->tablaaparatos = new tablaaparatos();
        $this->tablamarcas = new tablamarcas();
        $this->tablatipos = new tablatipos();
        $this->tablachasis = new tablachasis();
        $this->editar = FALSE;
       
                
        if( isset($_POST['idaparato']) )//Editar
        {
            $this->editar = $this->tablaaparatos->get($_POST['idaparato']);
            if ($this->editar)
            {
               $this->editar->idaparato = $_POST['idaparato'];
               $this->editar->marca = $_POST['marca'];
               $this->editar->modelo = $_POST['modelo'];
               $this->editar->tipo = $_POST['tipo'];
               $this->editar->chasis = $_POST['chasis'];
               
               $seguir= "SI";
                               
               
                
               if (is_uploaded_file($_FILES['logo']['tmp_name']) )
                   
               {
                     $imgen= $_FILES['logo']['name'];
                     $ruta = "plugins/fortec/logos/" . $imgen;
                     
                     if ($_FILES['logo']['size'] > 1024000)
                     {
                       $this->new_error_msg('El tamaño de la imagen es superior a 1Mb y no se ha guardado');
                       $seguir= "NO";
                     }
                     else if (!($_FILES['logo']['type'] == "image/jpeg" OR $_FILES['logo']['type'] == "image/png" OR $_FILES['logo']['type'] == "image/gif"))
                     {
                       $this->new_error_msg('Los tipos de imagenes deben de ser JPEG, PNG ó GIF, y NO se ha guardado.');
                       $seguir= "NO";
                     }
                     else
                         
                     
                        if ($seguir=="SI")
                        {
                          if (move_uploaded_file($_FILES['logo']['tmp_name'], $ruta))
                          {      
                          $this->editar->logo = $ruta;
                          } 
                        }
                }
               
                if ( $this->editar->save() )
                   {
                      if ($seguir=="NO")
                      { 
                       $this->new_message('Los demás datos SI se han guardado');
                      }
                      else
                      $this->new_message('Se ha modificado correctamente');
                   }
                else
                    $this->new_error_msg ('Error al modificar los datos');
                
               
            }
        $this->editar = FALSE;    
           
        }
        
        else if( isset($_GET['id']) ) //Mostrar el formulario editar
        {
          $this->editar = $this->tablaaparatos->get($_GET['id']);
        }
        else if( isset($_GET['borrar']) ) //Eliminar datos
        {
           $borrando = $this->tablaaparatos->get($_GET['borrar']);
           
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
        
       if($this->query != '')
        {
         $this->resultados = $this->tablaaparatos->search($this->query, $this->offset);
        }
        else
        { 
            $this->todos = isset($_POST['check_todos']);
            
          if ($this->todos)
            {        
            $this->resultados = $this->tablaaparatos->all($this->offset);
            }
        }       
    }
}
