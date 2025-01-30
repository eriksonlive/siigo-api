SELECT ar.department_id,
                ar.invnumber as num_factura,
                ar.transdate as fecha_factura,
                CASE
                WHEN cu.idtype =51  then 'Juridica'
                WHEN cu.idtype =1 then 'Natural'
                END as  tipo_persona,
                CASE
                    WHEN cu.idtype=1   then cu.customernumber
                    ELSE
                    CASE WHEN substr(cu.taxnumber,0,strpos(cu.taxnumber,'-'))=''  then cu.taxnumber
                    else substr(cu.taxnumber,0,strpos(cu.taxnumber,'-'))
                    END
                END  as nit_sin_df,
                CASE WHEN cu.idtype=1  then '' else cast (nv_nit as character varying(1)) END as digitoverificacion,
                CASE WHEN (name2 is null or name2='') then cu.name else name2 END  razonsocial,
                CASE  WHEN cu.idtype=51 then cu.address1 else pa.direccion end direccion,
                COALESCE(ap.cod_iso,'Co') as codigo_pais,
                ap.nombre as nombre_pais,
                dp.codigo codigo_departamento,
                dp.nombre nombre_departamento,
                mp.codigo codigo_ciudad,
                mp.nombre nombre_ciudad,
                codigo_postal codpostal,
                '57'::text as indicativo,
                CASE
                WHEN cu.idtype=51 and cont_ac_telefono is not null  THEN coalesce(cont_ac_telefono,cu.phone,'')
                WHEN cu.idtype=1  and pa.telefono is not null THEN pa.telefono
                END telefonodestinatario,
                CASE
                WHEN cu.idtype=51 and cont_ac_correo is not null and cont_ac_correo!=''  THEN coalesce(cont_ac_correo,cu.email)  WHEN cu.idtype=1  and pa.email is not null and  pa.email !='' THEN pa.email
                ELSE ''
                END correodestinatario,
            e.employeenumber as vendedor, --también puede ser e.ssn
            ar.notes as notas,
                --det
                p.partnumber codigo_producto ,
                p.description as producto,
                qty cantidad,
                i.sellprice as precio,
                i.discount as descuento,
                cliente_declara_iva(cu.id) as declara_iva,  --1 para si  0 para no ---- indica si declara IVA para enviar el código de homologación SIIGO
               -- ch.tipo as id_medio_pago,
                '01' as id_medio_pago,
               -- abs(ac.amount) as valor_pago,
               ar.amount as valor_pago,
               -- ac.transdate as fecha_pago
               ar.transdate as fecha_pago
                    FROM defaults df, ar
                    INNER JOIN customer cu on cu.id=ar.customer_id
                    INNER JOIN invoice i on i.trans_id=ar.id
                    INNER JOIN parts p on i.parts_id=p.id
                    LEFT JOIN seccion s on p.seccion=s.seccion
                    INNER JOIN as_municipio mp on mp.id=cu.city
                    INNER JOIN as_departamento dp on dp.id=mp.id_departamento
                    INNER JOIN as_pais ap on ap.id=dp.id_pais
                    LEFT JOIN  --garantiza que salga solo una cuenta de imp asociada al IVA
                    (   SELECT rate,parts_id,ch.description,accno
                        FROM partstax pt
                        INNER JOIN tax t on pt.chart_id=t.chart_id
                        INNER JOIN chart ch on t.chart_id=ch.id  and ch.description ilike 'IVA%' and accno ilike '24%'
                    )cta on cta.parts_id=i.parts_id

                    --requerido para FEparticulares
                    LEFT JOIN as_pacientes pa on
                    pa.cedula=trim(cu.customernumber) OR 'OD'||pa.cedula=trim(cu.customernumber)--para posibles pacientes odontología
                   
                    LEFT JOIN fe_homologa_tipo_identificacion ti  on cu.idtype=ti.idtype and (case when cu.idtype=1 then pa.tipo_documento=ti.tipo_documento else 1=1 end)
                    INNER JOIN department d ON ar.department_id=d.id
                    INNER JOIN ( --encuentra unico reg por resolu en fe_emisor.. sino existe configurac para la sede, trae el reg con dep_id=-1
                        SELECT *,case when department_id='-1' then (SELECT department_id FROM ar WHERE id=6472) else department_id end  department_esperado
                        FROM fe_emisor
                        WHERE  ( department_id in(SELECT department_id FROM ar WHERE id=6472) or department_id='-1')
                        order by department_id desc limit 1
                    )fe_emisor on fe_emisor.department_esperado=d.id
                    INNER JOIN employee e ON e.id=ar.employee_id

                -- LEFT JOIN acc_trans ac on ac.trans_id=ar.id and id_nc is null
                -- LEFT JOIN chart ch on ac.chart_id=ch.id  and  ch.link  ='AR_paid:AP_paid'

                WHERE
                ar.id = 6219