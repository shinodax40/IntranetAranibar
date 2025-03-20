<?php

/*
 * CategoriaImpl
 */

class SubCategoriaMapper {
    
        const findAll = "SELECT p.id, p.nombre FROM tbl_sub_tipo_pagina p WHERE 1=1 AND p.activo = 1  ";
    
        const findById = "SELECT p.id, p.nombre FROM tbl_sub_tipo_pagina p WHERE 1=1 AND p.id = :id AND p.activo = 1 ORDER BY p.orden ASC ";
    
      
}


?>