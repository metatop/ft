<?php

/**
 * Modelo de la tabla aparatos
 *
 * @author sm
 */
class tablaaparatos extends fs_model
{
    public $idaparato;
    public $modelo;        
    public $marca;
    public $tipo;
    public $chasis;
    public $logo;        
            
    public function __construct($t = FALSE) 
    {
        parent::__construct('tablaaparatos', 'plugins/fortec/');
        if ($t)
        {
            $this->idaparato = $t['idaparato'];
            $this->modelo = $t['modelo'];
            $this->marca = $t['marca'];
            $this->tipo = $t['tipo'];
            $this->chasis = $t['chasis'];
            $this->logo = $t['logo'];
        }
        else
        {
            $this->idaparato = NULL;
            $this->modelo = NULL;
            $this->marca = NULL;
            $this->tipo = NULL;
            $this->chasis = NULL;
            $this->logo = NULL;
        }
    }
    
    protected function install() 
    {
        // Para meter valores por defecto con una SQL a la tabla
        new tablamarcas();
        new tablatipos();
        new tablachasis();
        return '';
    }
    public function get($id) 
    {
        $data = $this->db->select("SELECT * FROM tablaaparatos WHERE idaparato = ".$this->var2str($id).';');
        if($data)
        {
            return new tablaaparatos($data[0]);
        }
        else
            return FALSE;
        
    }
    
    public function ultimo_id() 
    {
      $data = $this->db->select("SELECT * FROM tablaaparatos WHERE idaparato = (SELECT MAX(idaparato) FROM tablaaparatos)");
        if($data)
        {
            return new tablaaparatos($data[0]);
        }
        else
            return FALSE;  
        
        
    }
    
    public function exists() 
    {
       if ( is_null($this->idaparato) ) 
       {return FALSE;}
       else
           return $this->db->select("SELECT * FROM tablaaparatos WHERE idaparato = ".$this->var2str($this->idaparato).';');
       
    }
    
    public function url()
    {
      return 'index.php?page=nuevo_aparato';
    } 
    
    public function save() 
    {
        if( $this->exists() ) // Si existe hacemos un UPDATE (modifica registro) y si no un INSERT (añade nuevo registro)
        {
            if (is_null($this->logo))
            {$el_logo = "plugins/fortec/logos/imgdefecto.jpg";}
            else
                $el_logo = $this->logo;
            
            $sql = "UPDATE tablaaparatos SET modelo = ".$this->var2str($this->modelo).
                   ",marca = " .$this->var2str($this->marca).
                   ",tipo = ".$this->var2str($this->tipo).
                   ",chasis = ".$this->var2str($this->chasis).
                   ",logo = ".$this->var2str($el_logo).
                   "WHERE  idaparato = ".$this->var2str($this->idaparato).";";
           return $this->db->exec($sql);
        }
        else 
        {
           $sql = "INSERT INTO tablaaparatos (modelo, marca, tipo, chasis, logo) VALUES
                  (".$this->var2str(strtoupper($this->modelo)).",".$this->var2str($this->marca).",".$this->var2str($this->tipo).",".$this->var2str($this->chasis).",".$this->var2str($this->logo).");";
        
           return $this->db->exec ($sql);
           //if ( $this->db->exec ($sql) )
           //{
           //    $this->idaparato = $this->db->lastval();
           //    return TRUE;
           //}
        
           //else
            //   return FALSE;
        }
        
            
    }
    
       
    public function delete() 
    {
        return $this->db->exec("DELETE FROM tablaaparatos WHERE idaparato = " .$this->var2str($this->idaparato).";");
    }
    
    public function all()
    {
        $lista = array ();
        
        $data = $this->db->select("SELECT * FROM tablaaparatos ORDER BY modelo ASC;");
        if ($data)
        {
            foreach ($data as $d)
                $lista[] = new tablaaparatos($d);
        }
        
        return $lista;
    }        

    public function search($query, $offset=0)
   {
      $prolist = array();
      $query = mb_strtoupper( $this->no_html($query) );
      
      $consulta = "SELECT * FROM ".$this->table_name." WHERE ";
      if( is_numeric($query) )
      {
         $consulta .= "modelo LIKE '%".$query."%' OR chasis LIKE '%".$query."%' OR idaparato LIKE '%".$query."%'";
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $consulta .= "modelo LIKE '%".$buscar."%' OR chasis LIKE '%".$buscar."%' OR idaparato LIKE '%".$buscar."%'";
      }
      //$consulta .= " ORDER BY modelo ASC";
      
      $data = $this->db->select($consulta, $offset);
      if($data)
      {
         foreach($data as $d)
            $prolist[] = new tablaaparatos($d);
      }
      
      return $prolist;
   }    
    
    
}
