<?php

/**
 * Description of listado_tipos
 *
 * @author Tiz
 */

require_model('tablatipos.php');

class listado_tipos_admin extends fs_controller
{
    public $lista_tipos;
    public $tablatipos;
    public $editar;
    public $editar_modal;

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Listado Tipos Admin', 'Aparatos');
    }
    
    protected function private_core() 
   {
        $this->tablatipos = new tablatipos();
        $this->editar = FALSE;
        $this->editar_modal = FALSE;        
                
        if( isset($_POST['tipo']) )//Editar
        {
            $this->editar = $this->tablatipos->get($_POST['tipo']);
            if ($this->editar)
            {
               $this->editar->tipo = $_POST['tipo'];
               $this->editar->descripcion = $_POST['descripcion'];
                              
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
           $this->editar = $this->tablatipos->get($_GET['id']);
           //$this->editar_modal = $edito->get($_GET['id']);
        }
        else if( isset($_GET['borrar']) ) //Eliminar datos
        {
           $borrando = $this->tablatipos->get($_GET['borrar']);
           
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
        
        $this->lista_tipos = $this->tablatipos->all();
    }
}
