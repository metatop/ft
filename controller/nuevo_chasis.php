<?php

/**
 * Description of nuevo chasis
 *
 * @author sm
 */
require_model('tablachasis.php');
require_model('tablamarcas.php');

class nuevo_chasis extends fs_controller
{
    
    public $tablachasis;
    public $tablamarcas;


    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Nuevo Chasis', 'Chasis',FALSE,FALSE);
    }
    
    protected function private_core() 
    {
        $this->tablachasis = new tablachasis();
        $this->tablamarcas = new tablamarcas();
        
        if ( isset($_POST['chasis']) )
        {
            $this->tablachasis->chasis = $_POST['chasis'];
            $this->tablachasis->descripcion = $_POST['descripcion'];
            $this->tablachasis->fabricante = $_POST['fabricante'];
            
            
            $imgpordefecto = "plugins/fortec/logos/imgdefecto.jpg";
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
                          $this->tablachasis->logo = $ruta;
                          } 
                        }
                }
                else 
                    $this->tablachasis->logo = $imgpordefecto;
                
                
                
            if ( $this->tablachasis->save() )
             {
                    if ($seguir=="NO")
                    { 
                     $this->new_message('Los demás datos SI se han guardado');
                    }
                    else
                     $this->new_message('Se ha guardado correctamente');
             }
            else
                $this->new_error_msg ('Error al guardar los datos');
        }
        
    }
}