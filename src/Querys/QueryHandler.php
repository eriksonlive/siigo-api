<?php

namespace App\Querys;

use App\Connection\Connection;
use PDO;

class QueryHandler
{
    public $conn;

    public function __construct()
    {
        $connection = new Connection();
        $this->conn = $connection->getConnect();
    }

    public function getMethodsPay($id_factura)
    {
        $sql = "select tipo as codigo_medio_pago,ch.description as desc_medio_pago
            from acc_trans a
            inner join chart ch on a.chart_id=ch.id AND link='AR_paid:AP_paid'
            where trans_id=:id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_factura]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getInvoice($id_factura)
    {
        $sql = "
            SELECT 
                ar.department_id,
                ar.invnumber as num_factura,
                ar.transdate as fecha_factura,
                CASE
                    WHEN cu.idtype = 51 THEN 'Juridica'
                    WHEN cu.idtype = 1 THEN 'Natural'
                END as tipo_persona,
                CASE
                    WHEN cu.idtype = 1 THEN cu.customernumber
                    ELSE
                        CASE 
                            WHEN substr(cu.taxnumber, 0, strpos(cu.taxnumber, '-')) = '' THEN cu.taxnumber
                            ELSE substr(cu.taxnumber, 0, strpos(cu.taxnumber, '-'))
                        END
                END as nit_sin_df,
                CASE 
                    WHEN cu.idtype = 1 THEN '' 
                    ELSE cast(nv_nit as character varying(1)) 
                END as digitoverificacion,
                CASE 
                    WHEN (name2 IS NULL OR name2 = '') THEN cu.name 
                    ELSE name2 
                END razonsocial,
                CASE  
                    WHEN cu.idtype = 51 THEN cu.address1 
                    ELSE pa.direccion 
                END direccion,
                COALESCE(ap.cod_iso, 'Co') as codigo_pais,
                ap.nombre as nombre_pais,
                dp.codigo codigo_departamento,
                dp.nombre nombre_departamento,
                mp.codigo codigo_ciudad,
                mp.nombre nombre_ciudad,
                codigo_postal codpostal,
                '57'::text as indicativo,
                CASE
                    WHEN cu.idtype = 51 AND cont_ac_telefono IS NOT NULL THEN 
                        COALESCE(cont_ac_telefono, cu.phone, '')
                    WHEN cu.idtype = 1 AND pa.telefono IS NOT NULL THEN pa.telefono
                END telefonodestinatario,
                CASE
                    WHEN cu.idtype = 51 AND cont_ac_correo IS NOT NULL AND cont_ac_correo != '' THEN 
                        COALESCE(cont_ac_correo, cu.email)
                    WHEN cu.idtype = 1 AND pa.email IS NOT NULL AND pa.email != '' THEN pa.email
                    ELSE ''
                END correodestinatario,
                e.employeenumber as vendedor, -- también puede ser e.ssn
                ar.notes as notas,
                -- Detalle
                p.partnumber codigo_producto,
                p.description as producto,
                qty cantidad,
                i.sellprice as precio,
                i.discount as descuento,
                cliente_declara_iva(cu.id) as declara_iva,  -- 1 para sí, 0 para no
                '01' as id_medio_pago,
                ar.amount as valor_pago,
                ar.transdate as fecha_pago
            FROM 
                defaults df, 
                ar
                INNER JOIN customer cu ON cu.id = ar.customer_id
                INNER JOIN invoice i ON i.trans_id = ar.id
                INNER JOIN parts p ON i.parts_id = p.id
                LEFT JOIN seccion s ON p.seccion = s.seccion
                INNER JOIN as_municipio mp ON mp.id = cu.city
                INNER JOIN as_departamento dp ON dp.id = mp.id_departamento
                INNER JOIN as_pais ap ON ap.id = dp.id_pais
                LEFT JOIN (
                    SELECT 
                        rate, 
                        parts_id, 
                        ch.description, 
                        accno
                    FROM 
                        partstax pt
                        INNER JOIN tax t ON pt.chart_id = t.chart_id
                        INNER JOIN chart ch ON t.chart_id = ch.id AND ch.description ILIKE 'IVA%' AND accno ILIKE '24%'
                ) cta ON cta.parts_id = i.parts_id
                LEFT JOIN as_pacientes pa ON 
                    pa.cedula = TRIM(cu.customernumber) OR 
                    'OD' || pa.cedula = TRIM(cu.customernumber) -- para posibles pacientes odontología
                LEFT JOIN fe_homologa_tipo_identificacion ti ON 
                    cu.idtype = ti.idtype AND 
                    (CASE 
                        WHEN cu.idtype = 1 THEN pa.tipo_documento = ti.tipo_documento 
                        ELSE 1 = 1 
                    END)
                INNER JOIN department d ON ar.department_id = d.id
                INNER JOIN (
                    SELECT 
                        *, 
                        CASE 
                            WHEN department_id = '-1' THEN 
                                (SELECT department_id FROM ar WHERE id = :id) 
                            ELSE department_id 
                        END department_esperado
                    FROM fe_emisor
                    WHERE  
                        (department_id IN (SELECT department_id FROM ar WHERE id = :id) OR department_id = '-1')
                    ORDER BY department_id DESC 
                    LIMIT 1
                ) fe_emisor ON fe_emisor.department_esperado = d.id
                INNER JOIN employee e ON e.id = ar.employee_id
            WHERE
                ar.id = :id 
            AND
                 i.sellprice > 0;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_factura]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}
