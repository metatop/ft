<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tablatipos
 *
 * @author sm
 */
class tablatipos extends fs_model
{
    public $tipo;
    public $descripcion;        
    public $logo;        
            
    public function __construct($t = FALSE) 
    {
        parent::__construct('tablatipos', 'plugins/fortec/');
        if ($t)
        {
            $this->tipo = $t['tipo'];
            $this->descripcion = $t['descripcion'];
            $this->logo = $t['logo'];
        }
        else
        {
            $this->tipo = NULL;
            $this->descripcion = NULL;
            $this->logo = NULL;
        }
    }
    
    protected function install() 
    {
        // Para meter valores por defecto con una SQL a la tabla
        return '';
    }
    public function get($id) 
    {
        $data = $this->db->select("SELECT * FROM tablatipos WHERE tipo = ".$this->var2str($id).';');
        if($data)
        {
            return new tablatipos($data[0]);
        }
        else
            return FALSE;
        
    }
    
    public function exists() 
    {
       if ( is_null($this->tipo) ) 
       {return FALSE;}
       else
           return $this->db->select("SELECT * FROM tablatipos WHERE tipo = ".$this->var2str($this->tipo).';');
       
    }
    
    public function url()
    {
      return 'index.php?page=nuevo_tipo_aparato';
    }  
    
    public function save() 
    {
        if( $this->exists() ) // Si existe hacemos un UPDATE (modifica registro) y si no un INSERT (añade nuevo registro)
        {
            if (is_null($this->logo))
            {$el_logo = "plugins/fortec/logos/imgdefecto.jpg";}
            else
                $el_logo = $this->logo;
            
            $sql = "UPDATE tablatipos SET descripcion = ".$this->var2str($this->descripcion).
                   ",tipo = " .$this->var2str($this->tipo).
                   ",logo = ".$this->var2str($el_logo).
                   "WHERE  tipo = ".$this->var2str($this->tipo).";";
           return $this->db->exec($sql);
        }
        else 
        {
           $sql = "INSERT INTO tablatipos (tipo, descripcion, logo) VALUES
                  (".$this->var2str($this->tipo).",".$this->var2str($this->descripcion).",".$this->var2str($this->logo).");";
        
           return $this->db->exec ($sql);
           //if ( $this->db->exec ($sql) )
           //{
           //    $this->marca = $this->db->lastval();
           //    return TRUE;
           //}
        
           //else
            //   return FALSE;
        }
        
            
    }
    
    
    public function delete() 
    {
        return $this->db->exec("DELETE FROM tablatipos WHERE tipo = " .$this->var2str($this->tipo).";");
    }
    
    public function all()
    {
        $lista = array ();
        
        $data = $this->db->select("SELECT * FROM tablatipos ORDER BY tipo ASC;");
        if ($data)
        {
            foreach ($data as $d)
                $lista[] = new tablatipos($d);
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
         $consulta .= "descripcion LIKE '%".$query."%'";
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $consulta .= "descripcion LIKE '%".$buscar."%'";
      }
      $consulta .= " ORDER BY tipo ASC";
      
      $data = $this->db->select($consulta, $offset);
      if($data)
      {
         foreach($data as $d)
            $prolist[] = new tablatipos($d);
      }
      
      return $prolist;
   }    
    
    
}
