<?php

namespace App\Querys;

use App\Connection\Connection;
use Exception;
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

    public function getArIntegrationErp()
    {
        $sql = "SELECT COUNT(*) as total FROM public.ar_integration_erp";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data[0];
    }

    public function setArIntegrationErp($mode, $value1, $value2 = null)
    {
        // Construcción dinámica del SQL según el modo seleccionado
        $sql = "";

        if ($mode === 'single') {
            // Inserción con un ID específico
            $sql = "
            INSERT INTO public.ar_integration_erp (ar_id, integration_invnumber, integration_status)
            SELECT id, invnumber, 'pendiente'
            FROM public.ar
            WHERE id = :value1
            ON CONFLICT (ar_id) DO NOTHING;
        ";
        } elseif ($mode === 'range') {
            // Inserción dentro de un rango de IDs
            $sql = "
            INSERT INTO public.ar_integration_erp (ar_id, integration_invnumber, integration_status)
            SELECT id, invnumber, 'pendiente'
            FROM public.ar
            WHERE id BETWEEN :value1 AND :value2
            ON CONFLICT (ar_id) DO NOTHING;
        ";
        } elseif ($mode === 'from') {
            // Inserción a partir de un número de ID en adelante
            $sql = "
            INSERT INTO public.ar_integration_erp (ar_id, integration_invnumber, integration_status)
            SELECT id, invnumber, 'pendiente'
            FROM public.ar
            WHERE id >= :value1
            ON CONFLICT (ar_id) DO NOTHING;
        ";
        } elseif ($mode === 'list' && is_array($value1) && !empty($value1)) {
            // Inserción con una lista de IDs específicos
            $placeholders = implode(',', array_fill(0, count($value1), '?')); // Genera ?,?,? dinámicamente
            $sql = "
            INSERT INTO public.ar_integration_erp (ar_id, integration_invnumber, integration_status)
            SELECT id, invnumber, 'pendiente'
            FROM public.ar
            WHERE id IN ($placeholders)
            ON CONFLICT (ar_id) DO NOTHING;
        ";
        } else {
            throw new Exception("Modo de inserción no válido.");
        }

        // Preparar la consulta
        $stmt = $this->conn->prepare($sql);

        // Bind de los valores según el modo
        if ($mode === 'list') {
            $stmt->execute($value1); // Ejecuta con array directamente
        } else {
            $stmt->bindParam(':value1', $value1, PDO::PARAM_INT);
            if ($mode === 'range') {
                $stmt->bindParam(':value2', $value2, PDO::PARAM_INT);
            }
            $stmt->execute();
        }

        // Retornar la cantidad de registros insertados
        return $stmt->rowCount();
    }

    public function proccessErp($limit)
    {
        $sql = "
            SELECT * FROM public.ar_integration_erp 
            WHERE (Integration_status = 'pendiente' OR Integration_status = 'procesando' OR (Integration_status = 'error' AND reintentos < 3))
            ORDER BY integration_date ASC 
            LIMIT :limite;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function setStatusErpProccess($idRegistro)
    {
        $stmtUpdate = $this->conn->prepare("
            UPDATE public.ar_integration_erp 
            SET Integration_status = 'procesando', integration_date = NOW() 
            WHERE id = :id
        ");
        $stmtUpdate->execute([':id' => $idRegistro]);
    }

    public function setStatusErpOk($response, $idRegistro)
    {
        $sql = "
            UPDATE public.ar_integration_erp 
            SET integration_response = :response,
                Integration_status = 'procesado',
                integration_date = NOW()
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':response' => json_encode($response),
            ':id'       => $idRegistro
        ]);
    }

    public function getErrorInvoices()
    {
        $sql = "SELECT * FROM ar_integration_erp 
                WHERE integration_status = 'error' 
                  AND reintentos < 3";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createArIntegrationErp()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS public.ar_integration_erp
            (
                id serial,
                ar_id bigint,
                integration_invnumber bigint,
                integration_date timestamp with time zone DEFAULT ('now'),
                integration_request text,
                integration_response text,
                Integration_status character varying(10),
                reintentos integer DEFAULT 0,
                CONSTRAINT id_pki PRIMARY KEY (id),
                CONSTRAINT ar_id FOREIGN KEY (ar_id)
                    REFERENCES public.ar (id) MATCH SIMPLE
                    ON UPDATE NO ACTION
                    ON DELETE NO ACTION
            );
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function setStatusErpError($error, $idRegistro)
    {
        // Obtiene el número actual de reintentos para esta factura
        $sqlSelect = "SELECT reintentos FROM public.ar_integration_erp WHERE id = :id";
        $stmtSelect = $this->conn->prepare($sqlSelect);
        $stmtSelect->execute([':id' => $idRegistro]);
        $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);
        $reintentos = $result ? (int)$result['reintentos'] : 0;

        // Incrementa el contador
        $reintentos++;

        // Define el nuevo estado: si se han intentado menos de 3 veces, lo dejamos en 'error';
        // si se llega a 3, se marca como 'failed'
        $nuevoEstado = ($reintentos < 3) ? 'error' : 'failed';

        // Actualiza el registro con el mensaje de error, el nuevo estado y el contador de reintentos
        $sqlUpdate = "UPDATE public.ar_integration_erp 
                  SET integration_response = :error,
                      integration_status = :estado,
                      reintentos = :reintentos,
                      integration_date = NOW()
                  WHERE id = :id";
        $stmtUpdate = $this->conn->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':error'      => $error,
            ':estado'     => $nuevoEstado,
            ':reintentos' => $reintentos,
            ':id'         => $idRegistro
        ]);

        return $stmtUpdate->rowCount();
    }
}
