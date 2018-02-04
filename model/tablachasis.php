<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description de la tabla chasis
 *
 * @author sm
 */
class tablachasis extends fs_model
{
    public $chasis;
    public $descripcion;        
    public $fabricante;
    public $logo;        
            
    public function __construct($t = FALSE) 
    {
        parent::__construct('tablachasis', 'plugins/fortec/');
        if ($t)
        {
            $this->chasis = $t['chasis'];
            $this->descripcion = $t['descripcion'];
            $this->fabricante = $t['fabricante'];
            $this->logo = $t['logo'];
        }
        else
        {
            $this->chasis = NULL;
            $this->descripcion = NULL;
            $this->fabricante = NULL;
            $this->logo = NULL;
        }
    }
    
    protected function install() 
    {
        // Para meter valores por defecto con una SQL a la tabla
        new tablamarcas();
        return '';
    }
    public function get($id) 
    {
        $data = $this->db->select("SELECT * FROM tablachasis WHERE chasis = ".$this->var2str($id).';');
        if($data)
        {
            return new tablachasis($data[0]);
        }
        else
            return FALSE;
        
    }
    
    public function exists() 
    {
       if ( is_null($this->chasis) ) 
       {return FALSE;}
       else
           return $this->db->select("SELECT * FROM tablachasis WHERE chasis = ".$this->var2str($this->chasis).';');
       
    }
    
    public function url()
    {
      return 'index.php?page=nuevo_chasis';
    } 
    
    public function save() 
    {
        if( $this->exists() ) // Si existe hacemos un UPDATE (modifica registro) y si no un INSERT (añade nuevo registro)
        {
            if (is_null($this->logo))
            {$el_logo = "plugins/fortec/logos/imgdefecto.jpg";}
            else
                $el_logo = $this->logo;
            
            $sql = "UPDATE tablachasis SET descripcion = ".$this->var2str($this->descripcion).
                   ",chasis = " .$this->var2str($this->chasis).
                   ",fabricante = " .$this->var2str($this->fabricante).
                   ",logo = ".$this->var2str($el_logo).
                   "WHERE  chasis = ".$this->var2str($this->chasis).";";
           return $this->db->exec($sql);
        }
        else 
        {
           $sql = "INSERT INTO tablachasis (chasis, descripcion, fabricante, logo) VALUES
                  (".$this->var2str(strtoupper($this->chasis)).",".$this->var2str($this->descripcion).",".$this->var2str($this->fabricante).",".$this->var2str($this->logo).");";
        
           return $this->db->exec ($sql);
           //if ( $this->db->exec ($sql) )
           //{
           //    $this->chasis = $this->db->lastval();
           //    return TRUE;
           //}
        
           //else
            //   return FALSE;
        }
        
            
    }
    
    
    public function delete() 
    {
        return $this->db->exec("DELETE FROM tablachasis WHERE chasis = " .$this->var2str($this->chasis).";");
    }
    
    public function all()
    {
        $lista = array ();
        
        $data = $this->db->select("SELECT * FROM tablachasis ORDER BY chasis ASC;");
        if ($data)
        {
            foreach ($data as $d)
                $lista[] = new tablachasis($d);
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
         $consulta .= "chasis LIKE '%".$query."%'";
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $consulta .= "chasis LIKE '%".$buscar."%'";
      }
      $consulta .= " ORDER BY chasis ASC";
      
      $data = $this->db->select($consulta, $offset);
      if($data)
      {
         foreach($data as $d)
            $prolist[] = new tablachasis($d);
      }
      
      return $prolist;
   }    
    
    
}
