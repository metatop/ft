<?php

/**
 * Modelo de la tabla averias
 *
 * @author sm
 */
class tablaaverias extends fs_model
{
    public $idaveria; //clave primaria de la tabla
    public $idaparato; //enlaza a tabla aparatos       
    public $familia; //enlaza a tabla familia de sintomas
    public $sintoma;
    public $solucion;
    public $enlace; //para enlazar al origen de la averia (foro, web..)
    public $usuario; //enlaza a la tabla usuarios
    public $marca;
    public $modelo;
    public $chasis;  
    
    public function __construct($t = FALSE) 
    {
        parent::__construct('tablaaverias', 'plugins/fortec/');
        if ($t)
        {
            $this->idaveria = $t['idaveria'];
            $this->idaparato = $t['idaparato'];
            $this->familia = $t['familia'];
            $this->sintoma = $t['sintoma'];
            $this->solucion = $t['solucion'];
            $this->enlace = $t['enlace'];
            $this->usuario = $t['usuario'];
            $this->modelo = $t['modelo'];
            $this->chasis = $t['chasis'];
            $this->marca = $t['marca'];
        }
        else
        {
            $this->idaveria = NULL;
            $this->idaparato = NULL;
            $this->familia = NULL;
            $this->sintoma = NULL;
            $this->solucion = NULL;
            $this->enlace = NULL;
            $this->usuario = NULL;
            //$this->modelo = NULL;
            //$this->chasis = NULL;
        }
    }
    
    protected function install() 
    {
        // Para meter valores por defecto con una SQL a la tabla
        new tablaaparatos();
        new tablasintomas();
        return '';
    }
    public function get($id) 
    {
        //$data = $this->db->select("SELECT * FROM tablaaverias WHERE idaveria = ".$this->var2str($id).';');
        $data = $this->db->select("SELECT * FROM tablaaverias JOIN tablaaparatos on tablaaparatos.idaparato = tablaaverias.idaparato WHERE idaveria = ".$this->var2str($id).';');
        if($data)
        {
            return new tablaaverias($data[0]);
        }
        else
            return FALSE;
        
    }
    
    public function exists() 
    {
       if ( is_null($this->idaveria) ) 
       {return FALSE;}
       else
           return $this->db->select("SELECT * FROM tablaaverias WHERE idaveria = ".$this->var2str($this->idaveria).';');
       
    }
    // Si hay documentos relacionados es TRUE, en caso contrario la funcion da FALSE
    // Se pone en el HTML con rainTPL
    public function haydoc($id){
        
     $datos = $this->db->select("SELECT * FROM tablaaverias JOIN tabladocus on tabladocus.idaveria = tablaaverias.idaveria  WHERE tablaaverias.idaveria = ".$this->var2str($id).';');   
     if($datos)
        {
            return TRUE;
        }
        else
            return FALSE;
     
    }


    public function url()
    {
      return 'index.php?page=nueva_averia';
    } 
    
    public function save() 
    {
        if( $this->exists() ) // Si existe hacemos un UPDATE (modifica registro) y si no un INSERT (añade nuevo registro)
        {
                    
            $sql = "UPDATE tablaaverias SET sintoma = ".$this->var2str($this->sintoma).
                   ",familia = " .$this->var2str($this->familia).
                   ",idaparato = ".$this->var2str($this->idaparato).
                   ",solucion = ".$this->var2str($this->solucion).
                   ",enlace = ".$this->var2str($this->enlace).
                   ",usuario = ".$this->var2str($this->usuario).
                   "WHERE  idaveria = ".$this->var2str($this->idaveria).";";
           return $this->db->exec($sql);
        }
        else 
        {
           $sql = "INSERT INTO tablaaverias (idaparato, familia, sintoma, solucion, enlace, usuario) VALUES
                  (".$this->var2str($this->idaparato).
                   ",".$this->var2str($this->familia).
                   ",".$this->var2str($this->sintoma).
                   ",".$this->var2str($this->solucion).
                   ",".$this->var2str($this->enlace).
                   ",".$this->var2str($this->usuario).");";
        
           return $this->db->exec ($sql);
           //if ( $this->db->exec ($sql) )
           //{
           //    $this->idaverias = $this->db->lastval();
           //    return TRUE;
           //}
        
           //else
            //   return FALSE;
        }
        
            
    }
    
       
    public function delete() 
    {
        return $this->db->exec("DELETE FROM tablaaverias WHERE idaveria = " .$this->var2str($this->idaveria).";");
    }
    
    public function all()
    {
        $lista = array ();
        
        $data = $this->db->select("SELECT * FROM tablaaverias ORDER BY idaveria ASC;");
        if ($data)
        {
            foreach ($data as $d)
                $lista[] = new tablaaverias($d);
        }
        
        return $lista;
    }        

    public function averiayaparato()
    {
        $lista = array ();
        
        $data = $this->db->select("SELECT * FROM tablaaverias JOIN tablaaparatos on tablaaparatos.idaparato = tablaaverias.idaparato  ORDER BY modelo ASC;");
        if ($data)
        {
            foreach ($data as $d)
                $lista[] = new tablaaverias($d);
        }
        
        return $lista;
    }        
    
    public function search($query, $offset=0)
   {
      $prolist = array();
      $query = mb_strtoupper( $this->no_html($query) );
      
      $consulta = "SELECT * FROM tablaaverias JOIN tablaaparatos on tablaaparatos.idaparato = tablaaverias.idaparato WHERE ";
      if( is_numeric($query) )
      {
         $consulta .= "modelo LIKE '%".$query."%' OR chasis LIKE '%".$query."%'";
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $consulta .= "modelo LIKE '%".$buscar."%' OR chasis LIKE '%".$buscar."%'";
      }
      $consulta .= " ORDER BY modelo ASC";
      
      $data = $this->db->select($consulta, $offset);
      if($data)
      {
         foreach($data as $d)
            $prolist[] = new tablaaverias($d);
      }
      
      return $prolist;
   }    
    
    public function busca_por_idaparato($query, $offset=0)
   {
      $prolist = array();
      $query = mb_strtoupper( $this->no_html($query) );
      
      $consulta = "SELECT * FROM tablaaverias JOIN tablaaparatos on tablaaparatos.idaparato = tablaaverias.idaparato WHERE ";
      if( is_numeric($query) )
      {
         $consulta .= "tablaaverias.idaparato = ".$query.';';
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $consulta .= "tablaaverias.idaparato = ".$buscar.";";
      }
      //$consulta .= " ORDER BY idaveria ASC";
      
      $data = $this->db->select($consulta, $offset);
      if($data)
      {
         foreach($data as $d)
            $prolist[] = new tablaaverias($d);
      }
      
      return $prolist;
   }    

     public function busca_por_chasis($query, $offset=0)
   {
      $prolist = array();
      $query = mb_strtoupper( $this->no_html($query) );
      
      $consulta = "SELECT * FROM tablaaverias JOIN tablaaparatos on tablaaparatos.idaparato = tablaaverias.idaparato WHERE ";
      if( is_numeric($query) )
      {
         $consulta .= "chasis = ".$query.';';
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $consulta .= " chasis = '".$buscar."'";
      }
      //$consulta .= " ORDER BY idaveria ASC";
      
      $data = $this->db->select($consulta, $offset);
      if($data)
      {
         foreach($data as $d)
            $prolist[] = new tablaaverias($d);
      }
      
      return $prolist;
   }    
   
   
}
