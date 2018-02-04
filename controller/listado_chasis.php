
<?php

/**
 * Description of listado_chasis
 *
 * @author Tiz
 */

require_model('tablachasis.php');
require_model('tablamarcas.php');

class listado_chasis extends fs_controller
{
    public $lista_chasis;
    public $tablachasis;
    public $tablamarcas;
    public $editar;
    public $todos;
    public $resultados;
    public $offset;
    

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Listado Chasis', 'Chasis');
    }
    
    protected function private_core() 
   {
        $this->tablachasis = new tablachasis();
        $this->editar = FALSE;
        $this->tablamarcas = new tablamarcas();        
                
        if( isset($_POST['chasis']) )//Editar
        {
            $this->editar = $this->tablachasis->get($_POST['chasis']);
            if ($this->editar)
            {
               $this->editar->chasis = $_POST['chasis'];
               $this->editar->descripcion = $_POST['descripcion'];
               $this->editar->fabricante = $_POST['fabricante'];
               
               
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
          $this->editar = $this->tablachasis->get($_GET['id']);
        }
          
        
        //$this->lista_chasis = $this->tablachasis->all();
        if($this->query != '')
        {
         $this->resultados = $this->tablachasis->search($this->query, $this->offset);
        }
        else
        { 
            $this->todos = isset($_POST['check_todos']);
            //$this->new_error_msg ($this->todos);
          if ($this->todos)
            {        
            $this->resultados = $this->tablachasis->all($this->offset);
            }
        }       
    }
}
