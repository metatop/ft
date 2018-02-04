<?php

/**
 * Description of listado_marcas
 *
 * @author Tiz
 */

require_model('tablachasis.php');
require_model('tablamarcas.php');

class  editar_chasis extends fs_controller
{
    public $lista_chasis;
    public $tablachasis;
    public $tablamarcas;
    public $editar;
    public $documentos;


    public function __construct() 
    {
        parent::__construct(__CLASS__);
    }
    
    protected function private_core() 
   {
        $this->tablachasis = new tablachasis();
        $this->tablamarcas = new tablamarcas();
        $this->editar = FALSE;
        $this->documentos = array();
        
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
                          $this->editar->logo = $ruta;
                          } 
                        }
                }
               
                if ( $this->editar->save() )
                   {
                      if ($seguir=="NO")
                      { 
                       $this->new_message('Los demás datos quer hayas modificado SI se han guardado');
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
           $this->editar = $this->tablachasis->get($_GET['id']);
        }
        
        if( isset($_GET['borrar']) ) //Eliminar datos
        {
           $borrando = $this->tablachasis->get($_GET['borrar']);
           
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
        
        
        if( isset($_GET['folder']) AND isset($_GET['id']) )
      {
         if( !file_exists('tmp/'.FS_TMP_NAME.'docus') )
         {
            mkdir('tmp/'.FS_TMP_NAME.'docus');
         }
         
         if( !file_exists('tmp/'.FS_TMP_NAME.'docus/'.$_GET['folder']) )
         {
            mkdir('tmp/'.FS_TMP_NAME.'docus/'.$_GET['folder']);
         }
         
         if( isset($_POST['upload']) )
         {
            if( is_uploaded_file($_FILES['fdocumento']['tmp_name']) )
            {
               if( !file_exists('tmp/'.FS_TMP_NAME.'docus/'.$_GET['folder'].'/'.$_GET['id']) )
               {
                  mkdir('tmp/'.FS_TMP_NAME.'docus/'.$_GET['folder'].'/'.$_GET['id']);
               }
               
               copy($_FILES['fdocumento']['tmp_name'], "tmp/".FS_TMP_NAME."docus/".$_GET['folder'].'/'.$_GET['id'].'/'.$_FILES['fdocumento']['name']);
               $this->new_message('Documentos añadido correctamente.');
            }
         }
         else if( isset($_GET['delete']) )
         {
            if( file_exists('tmp/'.FS_TMP_NAME.'docus/'.$_GET['folder'].'/'.$_GET['id'].'/'.$_GET['delete']) )
            {
               if( unlink('tmp/'.FS_TMP_NAME.'docus/'.$_GET['folder'].'/'.$_GET['id'].'/'.$_GET['delete']) )
               {
                  $this->new_message('Archivo '.$_GET['delete'].' eliminado correctamente.');
               }
               else
                  $this->new_error_msg('Error al eliminar el archivo '.$_GET['delete'].'.');
            }
            else
               $this->new_error_msg('Archivo no encontrado.');
         }
         
         $this->documentos = $this->get_documentos();
        
        
        
                
           
        
        
       }
    
    
    
    }
    
    
    
 
   
   public function url()
   {
      if( isset($_GET['folder']) AND isset($_GET['id']) )
      {
         return 'index.php?page='.__CLASS__.'&folder='.$_GET['folder'].'&id='.$_GET['id'];
      }
      else
         return parent::url();
   }
   
   private function get_documentos() // Listamos los archivos cuyo carpeta (folder) sea igual a la del form 
   {
      $doclist = array();
      $folder = 'tmp/'.FS_TMP_NAME.'docus/'.$_GET['folder'].'/'.$_GET['id'];
      
      if( file_exists($folder) )
      {
         foreach( scandir($folder) as $f )
         {
            if($f != '.' AND $f != '..')
            {
               $doclist[] = array(
                   'name' => (string)$f,
                   'fullname' => $folder.'/'.$f,
                   'filesize' => $this->human_filesize( filesize(getcwd().'/'.$folder.'/'.$f) ),
                   'date' => date ("d-m-Y H:i:s", filemtime(getcwd().'/'.$folder.'/'.$f) )
               );
            }
         }
      }
      
      return $doclist;
   }
   
   private function human_filesize($bytes, $decimals = 2)
   {
      $sz = 'BKMGTP';
      $factor = floor((strlen($bytes) - 1) / 3);
      return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
   }
}
    

