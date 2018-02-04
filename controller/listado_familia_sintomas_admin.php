<?php

/**
 * Description of listado_fam_sintomas
 *
 * @author Tiz
 */

require_model('tablasintomas.php');

class listado_familia_sintomas_admin extends fs_controller
{
    public $lista_fam_sintomas;
    public $tablasintomas;
    public $editar;
    public $editar_modal;

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Listado Secciones Admin', 'Secciones');
    }
    
    protected function private_core() 
   {
        $this->tablasintomas = new tablasintomas();
        $this->editar = FALSE;
        $this->editar_modal = FALSE;        
                
        if( isset($_POST['fam_sintoma']) )//Editar
        {
            $this->editar = $this->tablasintomas->get($_POST['fam_sintoma']);
            if ($this->editar)
            {
               $this->editar->fam_sintoma = $_POST['fam_sintoma'];
               $this->editar->descripcion = $_POST['descripcion'];
                              
               $seguir= "SI";
                
               if (is_uploaded_file($_FILES['logo']['tmp_name']) )
                   
               {
                     $imgen= $_FILES['logo']['name'];
                     $ruta = "plugins/fortec/logos/" . $imgen;
                     
                     if ($_FILES['logo']['size'] > 16000)
                     {
                       $this->new_error_msg('El tamaño de la imagen es superior a 15Kb y no se ha guardado');
                       $seguir= "NO";
                     }
                     else if (!($_FILES['logo']['type'] == "image/jpeg" OR $_FILES['logo']['type'] == "image/png" OR $_FILES['logo']['type'] == "image/gif"))
                     {
                       $this->new_error_msg('Los fam_sintomas de imagenes deben de ser JPEG, PNG ó GIF, y NO se ha guardado.');
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
           $this->editar = $this->tablasintomas->get($_GET['id']);
           //$this->editar_modal = $edito->get($_GET['id']);
        }
        else if( isset($_GET['borrar']) ) //Eliminar datos
        {
           $borrando = $this->tablasintomas->get($_GET['borrar']);
           
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
        
        $this->lista_fam_sintomas = $this->tablasintomas->all();
    }
}
