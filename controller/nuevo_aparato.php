<?php

/**
 * Controlador de nuevo aparato
 *
 * @author sm
 */
require_model('tablaaparatos.php');
require_model('tablamarcas.php');
require_model('tablatipos.php');
require_model('tablachasis.php');

class nuevo_aparato extends fs_controller
{
    
    public $tablaaparatos;
    public $ultimoID;

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Nuevo Aparato', 'Aparatos', FALSE, FALSE);
    }
    
    protected function private_core() 
    {
        $this->tablaaparatos = new tablaaparatos();
        $this->tablamarcas = new tablamarcas();
        $this->tablatipos = new tablatipos();
        $this->tablachasis = new tablachasis();
        
        if ( isset($_POST['modelo']) )
        {
            $this->tablaaparatos->marca = $_POST['marca'];
            $this->tablaaparatos->modelo = $_POST['modelo'];
            $this->tablaaparatos->chasis = $_POST['chasis'];
            $this->tablaaparatos->tipo = $_POST['tipo'];
            
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
                          $this->tablaaparatos->logo = $ruta;
                          } 
                        }
                }
                else 
                    $this->tablaaparatos->logo = $imgpordefecto;
                
            if  (is_null($this->tablaaparatos->marca)) 
            {
               $this->new_error_msg('El campo marca es obligatorio'); 
            }
            else    
                
                
               if ( $this->tablaaparatos->save() )
               {
                    if ($seguir=="NO")
                    { 
                     $this->new_message('Los demás datos SI se han guardado');
                    }
                    else
                     $this->ultimoID = $this->db->lastval();   
                     $this->new_message('<h3>Se ha guardado correctamente</h3> <h4>Ahora puedes:</h4>');
                }
                else
                    $this->new_error_msg ('Error al guardar los datos');
        }
        
    }
}