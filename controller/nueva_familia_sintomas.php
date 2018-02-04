<?php

/**
 * Description of Nueva Familia Sintomas
 *
 * @author sm
 */
require_model('tablasintomas.php');

class nueva_familia_sintomas extends fs_controller
{
    
    public $tablasintomas;


    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Nueva Sección', 'Secciones');
    }
    
    protected function private_core() 
    {
        $this->tablasintomas = new tablasintomas();
        
        if ( isset($_POST['fam_sintoma']) )
        {
            $this->tablasintomas->fam_sintoma = $_POST['fam_sintoma'];
            $this->tablasintomas->descripcion = $_POST['descripcion'];
                        
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
                          $this->tablasintomas->logo = $ruta;
                          } 
                        }
                }
                else 
                    $this->tablasintomas->logo = $imgpordefecto;
                
                
                
            if ( $this->tablasintomas->save() )
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