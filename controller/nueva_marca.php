<?php

/**
 * Description of nuevamarca
 *
 * @author sm
 */
require_model('tablamarcas.php');

class nueva_marca extends fs_controller
{
    
    public $tablamarcas;
    public $documentos;


    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Nueva Marca', 'Marcas', FALSE, FALSE);
    }
    
    protected function private_core() 
    {
        $this->tablamarcas = new tablamarcas();
        
        if ( isset($_POST['marca']) )
        {
            $this->tablamarcas->marca = $_POST['marca'];
            $this->tablamarcas->descripcion = $_POST['descripcion'];
            $this->tablamarcas->web = $_POST['web'];
            $this->tablamarcas->email = $_POST['email'];
            
            $imgpordefecto = "plugins/fortec/logos/imgdefecto.jpg";
            $seguir= "SI";
                
               if (is_uploaded_file($_FILES['logo']['tmp_name']) )
                {
                     $imgen= $_FILES['logo']['name'];
                     $ruta = "plugins/fortec/logos/" . $imgen;
                                          
                     if ($_FILES['logo']['size'] > 16000)
                     {
                       $this->new_error_msg('El tamaño de la imagen es superior a 15kb y no se ha guardado');
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
                          $this->tablamarcas->logo = $ruta;
                          } 
                        }
                }
                else 
                    $this->tablamarcas->logo = $imgpordefecto;
                
                
                
            if ( $this->tablamarcas->save() )
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