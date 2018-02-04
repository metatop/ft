<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tablamarcas
 *
 * @author sm
 */
class tablamarcas extends fs_model
{
    public $marca;
    public $descripcion;        
    public $web;
    public $email;
    public $logo;        
            
    public function __construct($t = FALSE) 
    {
        parent::__construct('tablamarcas', 'plugins/fortec/');
        if ($t)
        {
            $this->marca = $t['marca'];
            $this->descripcion = $t['descripcion'];
            $this->web = $t['web'];
            $this->email = $t['email'];
            $this->logo = $t['logo'];
        }
        else
        {
            $this->marca = NULL;
            $this->descripcion = NULL;
            $this->web = NULL;
            $this->email = NULL;
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
        $data = $this->db->select("SELECT * FROM tablamarcas WHERE marca = ".$this->var2str($id).';');
        if($data)
        {
            return new tablamarcas($data[0]);
        }
        else
            return FALSE;
        
    }
    
    public function exists() 
    {
       if ( is_null($this->marca) ) 
       {return FALSE;}
       else
           return $this->db->select("SELECT * FROM tablamarcas WHERE marca = ".$this->var2str($this->marca).';');
       
    }
    
    public function save() 
    {
        if( $this->exists() ) // Si existe hacemos un UPDATE (modifica registro) y si no un INSERT (añade nuevo registro)
        {
            //if (is_null($this->logo))
            //{$el_logo = "plugins/fortec/logos/imgdefecto.jpg";}
            //else
                $el_logo = $this->logo;
            
            $sql = "UPDATE tablamarcas SET descripcion = ".$this->var2str($this->descripcion).
                   ",marca = " .$this->var2str($this->marca).
                   ",web = " .$this->var2str($this->web).
                   ",email = ".$this->var2str($this->email).
                   ",logo = ".$this->var2str($el_logo).
                   "WHERE  marca = ".$this->var2str($this->marca).";";
           return $this->db->exec($sql);
        }
        else 
        {
           $sql = "INSERT INTO tablamarcas (marca, descripcion, web, email, logo) VALUES
                  (".$this->var2str(strtoupper($this->marca)).",".$this->var2str($this->descripcion).",".$this->var2str($this->web).",".$this->var2str($this->email).",".$this->var2str($this->logo).");";
        
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
    
    public function url()
    {
      return 'index.php?page=nueva_marca';
    }
    
    public function delete() 
    {
        return $this->db->exec("DELETE FROM tablamarcas WHERE marca = " .$this->var2str($this->marca).";");
    }
    
    public function all()
    {
        $lista = array ();
        
        $data = $this->db->select("SELECT * FROM tablamarcas ORDER BY marca ASC;");
        if ($data)
        {
            foreach ($data as $d)
                $lista[] = new tablamarcas($d);
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
         $consulta .= "marca LIKE '%".$query."%'";
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $consulta .= "marca LIKE '%".$buscar."%'";
      }
      $consulta .= " ORDER BY marca ASC";
      
      $data = $this->db->select($consulta, $offset);
      if($data)
      {
         foreach($data as $d)
            $prolist[] = new tablamarcas($d);
      }
      
      return $prolist;
   }    
    
    
}
