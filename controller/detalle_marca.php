<?php

/**
 * Detalle Marcas
 *
 * @author Tiz
 */

require_model('tablamarcas.php');

class  detalle_marca extends fs_controller
{
    public $lista_marcas;
    public $tablamarcas;
    public $editar;
    


    public function __construct() 
    {
        parent::__construct(__CLASS__);
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
               //$this->editar->marca = $_POST['marca'];
               //$this->editar->descripcion = $_POST['descripcion'];
               //$this->editar->web = $_POST['web'];
               //$this->editar->email = $_POST['email'];
               
               $seguir= "SI";
                
               if (is_uploaded_file($_FILES['logo']['tmp_name']) )
                   
               {
                     $imgen= $_FILES['logo']['name'];
                     $ruta = "plugins/fortec/logos/" . $imgen;
                     
                     if ($_FILES['logo']['size'] > 16000)
                     {
                       $this->new_error_msg('ERROR: El tamaño de la imagen es superior a 15kb y NO se ha guardado');
                       $seguir= "NO";
                     }
                     else if (!($_FILES['logo']['type'] == "image/jpeg" OR $_FILES['logo']['type'] == "image/png" OR $_FILES['logo']['type'] == "image/gif"))
                     {
                       $this->new_error_msg('ERROR: Los tipos de imagenes deben de ser JPEG, PNG ó GIF, y NO se ha guardado.');
                       $seguir= "NO";
                     }
                     else
                         
                     
                        if ($seguir=="SI")
                        {
                          if (move_uploaded_file($_FILES['logo']['tmp_name'], $ruta))
                          {      
                           $this->editar->logo = $ruta;
                          }
                          
                          if ( $this->editar->save() )
                          {
                           $this->new_message('EL LOGO SE HA GUARDADO CORRECTAMENTE');
                          }
                         else
                           $this->new_error_msg ('Error al modificar los datos');  
                          
                         }
                
                                       
                }
                else
                
                {$this->new_error_msg('No has añadido ningún logo de la MARCA y no se ha guardado nada');}
                
                    
                
               
            }
            
           
        }
        
        else if( isset($_GET['id']) ) //Mostrar el formulario editar
        {
           $this->editar = $this->tablamarcas->get($_GET['id']);
        }
        
                  
       
    
    }
    
    
    
 
   
   
}
    

