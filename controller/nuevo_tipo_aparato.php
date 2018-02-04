<?php

/**
 * Description of nuevatipo
 *
 * @author sm
 */
require_model('tablatipos.php');

class nuevo_tipo_aparato extends fs_controller
{
    public $nuevo_tipo;
    public $tablatipos;


    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Nuevo Tipo de Aparato', 'Aparatos');
    }
    
    protected function private_core() 
    {
        $this->tablatipos = new tablatipos();
        
        if ( isset($_POST['tipo']) )
        {
            $this->tablatipos->tipo = $_POST['tipo'];
            $this->tablatipos->descripcion = $_POST['descripcion'];
            
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
                          $this->tablatipos->logo = $ruta;
                          }
                                                  
                        }
                }
                else 
                    $this->tablatipos->logo = $imgpordefecto;
                
                
            if ( $this->tablatipos->save() )
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