<?php

/**
 * Controlador de editar averia
 *
 * @author Tiz
 */

require_model('tablaaverias.php');
require_model('tablaaparatos.php');
require_model('tablasintomas.php');
require_model('fs_user.php');

class editar_averia extends fs_controller
{
    public $lista_aparatos;
    public $tablaaverias;
    public $editar;
   

    public function __construct() 
    {
        parent::__construct(__CLASS__, 'Editar Averia', 'Averias', FALSE, FALSE);
    }
    
    protected function private_core() 
   {
        $this->tablaaparatos = new tablaaparatos();
        $this->tablaaverias = new tablaaverias();
        $this->tablasintomas = new tablasintomas();
        $this->fs_user = new fs_user();
        $this->editar = FALSE;
       
                
        if( isset($_POST['idaveria']) )//Editar
        {
            $this->editar = $this->tablaaverias->get($_POST['idaveria']);
            if ($this->editar)
            {
               $this->editar->idaparato = $_POST['idaparato'];
               $this->editar->idaveria = $_POST['idaveria'];
               $this->editar->familia = $_POST['familia'];
               $this->editar->sintoma = $_POST['sintoma'];
               $this->editar->solucion = $_POST['solucion'];
               $this->editar->enlace = $_POST['enlace'];
               $this->editar->usuario = $_POST['usuario'];
                              
               
                if ( $this->editar->save() )
                   {
                      $this->new_message('Se ha modificado correctamente');
                   }
                else
                    $this->new_error_msg ('Error al modificar los datos');
                
               
            }
        $this->editar = FALSE;    
           
        }
        
        else if( isset($_GET['id']) ) //Mostrar el formulario editar
        {
          $this->editar = $this->tablaaverias->get($_GET['id']);
        }
        
        else if( isset($_GET['borrar']) ) //Eliminar datos
        {
           $borrando = $this->tablaaverias->get($_GET['borrar']);
           
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
   }   
        /*
             
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
   } */
}
