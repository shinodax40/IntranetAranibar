<?php

/*
 * ProductosImpl
 */

class ProductosMapper {

    const findCount = "SELECT "
            . "    COUNT(p.id) AS total "
            . "FROM "
            . "    productos p "
            . "INNER JOIN tbl_tipo_pagina tp ON "
            . "    tp.id = p.id_tipo_pagina "
            . "INNER JOIN tbl_sub_tipo_pagina sp ON "
            . "    sp.id = p.id_sub_pagina "
            . "WHERE 1 =1 AND p.activo = 1 ";
    
    const findAll = "SELECT "
            . "    p.id, "
            . "    p.nombreProd, "           
            . "    p.precioPart AS precio, "
            . "    tp.nombre AS nombreTipo, "
            . "    sp.nombre AS nombreSubTipo, "
            . "    p.id_tipo_pagina AS idCategorias, "
            . "    p.id_sub_pagina AS idSubCategorias  "
            . "FROM "
            . "    productos p "
            . "INNER JOIN tbl_tipo_pagina tp ON "
            . "    tp.id = p.id_tipo_pagina "
            . "INNER JOIN tbl_sub_tipo_pagina sp ON "
            . "    sp.id = p.id_sub_pagina  "
            . "WHERE 1=1 AND p.activo = 1 "; 
    
    
    
            
    
    const findAllFilter = "SELECT "
            . "    p.id, "
            . "    p.nombreProd, "
            . "    ( "
            . "        IFNULL( "
            . "            ( "
            . "            SELECT "
            . "                SUM(d.cantidad) "
            . "            FROM "
            . "                tbl_ingresos pe "
            . "            INNER JOIN tbl_ingresos_deta d ON "
            . "                d.id_ingresos = pe.id "
            . "            WHERE "
            . "                d.id_prod = p.id AND pe.activo = 1 "
            . "        ), "
            . "        0 "
            . "        ) - IFNULL( "
            . "            ( "
            . "            SELECT "
            . "                SUM(d.cantidad) "
            . "            FROM "
            . "                tbl_pedido pe "
            . "            INNER JOIN tbl_deta_pedido d ON "
            . "                d.id_pedido = pe.id_pedido "
            . "            WHERE "
            . "                d.id_prod = p.id AND pe.anulada = 'N' "
            . "        ), "
            . "        0 "
            . "        ) "
            . "    ) AS stock, "
            . "    p.precioPart AS precio, "
            . "    tp.nombre AS nombreTipo, "
            . "    sp.nombre AS nombreSubTipo, "
            . "    p.id_tipo_pagina AS idCategorias, "
            . "    p.id_sub_pagina AS idSubCategorias ,     aa.salidadProd "
            . "FROM "
            . "    productos p "
            . "INNER JOIN tbl_tipo_pagina tp ON "
            . "    tp.id = p.id_tipo_pagina "
            . "INNER JOIN tbl_sub_tipo_pagina sp ON "
            . "    sp.id = p.id_sub_pagina 
           
           LEFT OUTER JOIN (            
                   SELECT
                        d.id_prod,
                        SUM(d.cantidad) as salidadProd
                        FROM 
                        tbl_pedido pe 
                        INNER JOIN tbl_deta_pedido d ON 
                        d.id_pedido = pe.id_pedido 
                        INNER JOIN tbl_cliente cl 
                        ON cl.id_cliente = pe.id_cliente
                        WHERE 
                         pe.anulada = 'N' 
                        AND cl.tipo_comprador = 'Particular'
                  GROUP BY d.id_prod
            )    aa 
            ON aa.id_prod  = p.id
            
            
              "
            . "WHERE 1=1 AND p.activo = 1 ";
    
    const findId = "SELECT "
            . "    p.id, "
            . "    p.nombreProd, "
            . "    ( "
            . "        IFNULL( "
            . "            ( "
            . "            SELECT "
            . "                SUM(d.cantidad) "
            . "            FROM "
            . "                tbl_ingresos pe "
            . "            INNER JOIN tbl_ingresos_deta d ON "
            . "                d.id_ingresos = pe.id "
            . "            WHERE "
            . "                d.id_prod = p.id AND pe.activo = 1 "
            . "        ), "
            . "        0 "
            . "        ) - IFNULL( "
            . "            ( "
            . "            SELECT "
            . "                SUM(d.cantidad) "
            . "            FROM "
            . "                tbl_pedido pe "
            . "            INNER JOIN tbl_deta_pedido d ON "
            . "                d.id_pedido = pe.id_pedido "
            . "            WHERE "
            . "                d.id_prod = p.id AND pe.anulada = 'N' "
            . "        ), "
            . "        0 "
            . "        ) "
            . "    ) AS stock, "
            . "    p.precioPart AS precio, "
            . "    tp.nombre AS nombreTipo, "
            . "    sp.nombre AS nombreSubTipo, "
            . "    p.id_tipo_pagina AS idCategorias, "
            . "    p.id_sub_pagina AS idSubCategorias, "
            . "       aa.salidadProd "
            . "FROM "
            . "    productos p "
            . "INNER JOIN tbl_tipo_pagina tp ON "
            . "    tp.id = p.id_tipo_pagina "
            . "INNER JOIN tbl_sub_tipo_pagina sp ON "
            . "    sp.id = p.id_sub_pagina  
            
             LEFT OUTER JOIN (            
                   SELECT
                        d.id_prod,
                        SUM(d.cantidad) as salidadProd
                        FROM 
                        tbl_pedido pe 
                        INNER JOIN tbl_deta_pedido d ON 
                        d.id_pedido = pe.id_pedido 
                        INNER JOIN tbl_cliente cl 
                        ON cl.id_cliente = pe.id_cliente
                        WHERE 
                         pe.anulada = 'N' 
                        AND cl.tipo_comprador = 'Particular'
                  GROUP BY d.id_prod
            )    aa 
            ON aa.id_prod  = p.id
            
            
            
            "
            . "WHERE 1 =1 AND p.id = :id AND p.activo = 1 ";

}

?>