<?php

/**
 * Description of listado_familia_sintomas
 *
 * @author Tiz
 */

require_model('tablasintomas.php');

class listado_familia_sintomas extends fs_controller
{
    
    public $tablasintomas;
    public $mostrar;
    public $offset;
    public $resultados;

    public function __construct() {
        parent::__construct(__CLASS__, 'Listado Secciones', 'Secciones');
    }
    
    protected function private_core() {
        $this->tablasintomas = new tablasintomas();
        
      if($this->query != '')
      {
         $this->resultados = $this->tablasintomas->search($this->query, $this->offset);
      }
      else
      {
         $this->resultados = $this->tablasintomas->all($this->offset);
      }        




        
    }
}
