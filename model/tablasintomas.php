<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tabla familia sintomas
 *
 * @author sm
 */
class tablasintomas extends fs_model
{
    public $fam_sintoma;
    public $descripcion;        
    public $logo;        
            
    public function __construct($t = FALSE) 
    {
        parent::__construct('tablasintomas', 'plugins/fortec/');
        if ($t)
        {
            $this->fam_sintoma = $t['fam_sintoma'];
            $this->descripcion = $t['descripcion'];
            $this->logo = $t['logo'];
        }
        else
        {
            $this->fam_sintoma = NULL;
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
        $data = $this->db->select("SELECT * FROM tablasintomas WHERE fam_sintoma = ".$this->var2str($id).';');
        if($data)
        {
            return new tablasintomas($data[0]);
        }
        else
            return FALSE;
        
    }
    
    public function exists() 
    {
       if ( is_null($this->fam_sintoma) ) 
       {return FALSE;}
       else
           return $this->db->select("SELECT * FROM tablasintomas WHERE fam_sintoma = ".$this->var2str($this->fam_sintoma).';');
       
    }
    
    public function url()
    {
      return 'index.php?page=nueva_familia_sintomas';
    } 
    
    public function save() 
    {
        if( $this->exists() ) // Si existe hacemos un UPDATE (modifica registro) y si no un INSERT (añade nuevo registro)
        {
            if (is_null($this->logo))
            {$el_logo = "plugins/fortec/logos/imgdefecto.jpg";}
            else
                $el_logo = $this->logo;
            
            $sql = "UPDATE tablasintomas SET descripcion = ".$this->var2str($this->descripcion).
                   ",fam_sintoma = " .$this->var2str($this->fam_sintoma).
                   ",logo = ".$this->var2str($el_logo).
                   "WHERE  fam_sintoma = ".$this->var2str($this->fam_sintoma).";";
           return $this->db->exec($sql);
        }
        else 
        {
            $sql = "INSERT INTO tablasintomas (fam_sintoma, descripcion, logo) VALUES
                  (".$this->var2str($this->fam_sintoma).",".$this->var2str($this->descripcion).",".$this->var2str($this->logo).");";
        
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
        return $this->db->exec("DELETE FROM tablasintomas WHERE fam_sintoma = " .$this->var2str($this->fam_sintoma).";");
    }
    
    public function all()
    {
        $lista = array ();
        
        $data = $this->db->select("SELECT * FROM tablasintomas ORDER BY fam_sintoma ASC;");
        if ($data)
        {
            foreach ($data as $d)
                $lista[] = new tablasintomas($d);
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
      $consulta .= " ORDER BY fam_sintoma ASC";
      
      $data = $this->db->select($consulta, $offset);
      if($data)
      {
         foreach($data as $d)
            $prolist[] = new tablasintomas($d);
      }
      
      return $prolist;
   }    
    
    
}
