<?php

/**
 * Controlador de listado de aparatos admin
 *
 * @author Tiz
 */

require_model('tablaaparatos.php');
require_model('tablamarcas.php');
require_model('tablatipos.php');
require_model('tablachasis.php');

class detalle_aparato extends fs_controller
{
    public $lista_aparatos;
    public $tablaaparatos;
    public $editar;
    public $offset;
    public $resultados;
    public $todos;
    public $txtbuscar;
    public $documentos;

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Detalle Aparato', 'Aparatos', FALSE, FALSE);
    }
    
    protected function private_core() 
   {
        $this->tablaaparatos = new tablaaparatos();
        $this->tablamarcas = new tablamarcas();
        $this->tablatipos = new tablatipos();
        $this->tablachasis = new tablachasis();
        $this->editar = FALSE;
       
                
        if( isset($_POST['idaparato']) )//Editar
        {
            $this->editar = $this->tablaaparatos->get($_POST['idaparato']);
            if ($this->editar)
            {
               //$this->editar->idaparato = $_POST['idaparato'];
               //$this->editar->marca = $_POST['marca'];
               //$this->editar->modelo = $_POST['modelo'];
               //$this->editar->tipo = $_POST['tipo'];
               //$this->editar->chasis = $_POST['chasis'];
               
               $seguir= "SI";
                               
               
                
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
                           $this->new_message('LA IMAGEN SE HA GUARDADO CORRECTAMENTE');
                          }
                         else
                           $this->new_error_msg ('Error al modificar los datos');  
                          
                         }
                
                                       
                }
                else
                
                {$this->new_error_msg('No has añadido ninguna imagen del APARATO y no se ha guardado nada');}
                
            }
          
           
        }
        
        else if( isset($_GET['id']) ) //Mostrar el formulario editar
        {
          $this->editar = $this->tablaaparatos->get($_GET['id']);
        }
        else if( isset($_GET['borrar']) ) //Eliminar datos
        {
           $borrando = $this->tablaaparatos->get($_GET['borrar']);
           
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
         $this->resultados = $this->tablaaparatos->search($this->query, $this->offset);
        }
        else
        { 
            $this->todos = isset($_POST['check_todos']);
            //$this->new_error_msg ($this->todos);
          if ($this->todos)
            {        
            $this->resultados = $this->tablaaparatos->all($this->offset);
            }
        }
        
        //Subir archivos adjuntos
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
         
         //Borra un documento adjunto
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
         
         // Lista los documentos adjuntos
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
