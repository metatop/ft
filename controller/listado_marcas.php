<?php

/**
 * Description of listado_marcas
 *
 * @author Tiz
 */

require_model('tablamarcas.php');

class listado_marcas extends fs_controller
{
    public $lista_marcas;
    public $tablamarcas;
    public $editar;
    public $resultados;
    public $offset;

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Listado Marcas', 'Marcas');
    }
    
    protected function private_core() 
   {
        $this->tablamarcas = new tablamarcas();
        $this->editar = FALSE;
                
                
        if( isset($_POST['marca']) )//Editar
        {
            $this->editar = $this->tablamarcas->get($_POST['marca']);
            if ($this->editar)
            {
               $this->editar->marca = $_POST['marca'];
               $this->editar->descripcion = $_POST['descripcion'];
               $this->editar->web = $_POST['web'];
               $this->editar->email = $_POST['email'];
               
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
            
           
        }
        
        else if( isset($_GET['id']) ) //Mostrar el formulario editar
        {
           $this->editar = $this->tablamarcas->get($_GET['id']);
        }
        else if( isset($_GET['borrar']) ) //Eliminar datos
        {
           $borrando = $this->tablamarcas->get($_GET['borrar']);
           
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
         $this->resultados = $this->tablamarcas->search($this->query, $this->offset);
        }
        else
        {
         $this->resultados = $this->tablamarcas->all($this->offset);
        }
    }    
}
