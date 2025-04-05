<?php
return [

    "fnc_create_taxpaymentstatus"=>"
        DROP FUNCTION IF EXISTS taxpayment_info.fnc_taxpaymentstatus();
        CREATE OR REPLACE FUNCTION taxpayment_info.fnc_taxpaymentstatus()
        Returns Boolean
        LANGUAGE plpgsql AS $$
        BEGIN
            DROP TABLE IF EXISTS taxpayment_info.tax_payment_status CASCADE;
                
            CREATE TABLE taxpayment_info.tax_payment_status AS
            SELECT t.tax_code, t.id as tax_payment_id, b.bin as bin, b.ward, b.building_associated_to, (CASE WHEN t.owner_name = 'NULL' THEN '' ELSE t.owner_name END ) AS owner_name, 
			(CASE WHEN t.owner_gender = 'NULL' THEN '' ELSE t.owner_gender END ) AS owner_gender, (CASE WHEN t.owner_contact = NULL THEN NULL ELSE t.owner_contact END ) AS owner_contact, t.last_payment_date, 
                
                CASE 
                    WHEN t.last_payment_date='1970-01-01' THEN 99    
                    WHEN t.last_payment_date is not NULL THEN 
                CASE
                    WHEN date_part('year', AGE(CURRENT_DATE, t.last_payment_date::date))::int > 5 THEN 5
                    ELSE date_part('year', AGE(CURRENT_DATE, t.last_payment_date::date))::int
                END
                END as due_year,  
                Case 
                    WHEN t.tax_code is not NULL AND b.bin is not NULL THEN TRUE
                    WHEN t.tax_code is NULL or b.bin is NULL THEN False
                End as match,
                b.geom, Now() as created_at, Now() as updated_at, b.deleted_at 
            FROM taxpayment_info.tax_payments t LEFT join building_info.buildings b on t.tax_code=b.tax_code;
           
            
            Return True
        ;
        END
        $$;
    ",

    "fnc_insrtupd_taxbuildowner"=>"
        -- DROP FUNCTION IF EXISTS taxpayment_info.fnc_insrtupd_taxbuildowner();    
        CREATE OR REPLACE FUNCTION taxpayment_info.fnc_insrtupd_taxbuildowner()
        Returns Boolean
        LANGUAGE plpgsql AS $$
        BEGIN
            ALTER TABLE building_info.owners DROP CONSTRAINT IF EXISTS owners_tax_code_unique;
            ALTER TABLE building_info.owners ADD CONSTRAINT owners_tax_code_unique UNIQUE (tax_code);

            with tax_data as (
                SELECT t.tax_code, t.owner_name, t.owner_gender, t.owner_contact
                FROM taxpayment_info.tax_payment_status t 
                Left Join building_info.owners o ON o.tax_code = t.tax_code 
                WHERE t.building_associated_to is NULL
            )
            INSERT INTO building_info.owners (tax_code, owner_name, owner_gender, owner_contact, created_at)
                SELECT tax_code, owner_name, owner_gender, owner_contact, NOW() FROM tax_data
                ON CONFLICT ON CONSTRAINT owners_tax_code_unique
                DO 
                UPDATE SET tax_code=excluded.tax_code, owner_name = excluded.owner_name, owner_gender = excluded.owner_gender, owner_contact = excluded.owner_contact, updated_at=NOW();
                
            Return True
        ;
        END
        $$;
    ",

    "fnc_create_wardproportion"=>"DROP FUNCTION IF EXISTS taxpayment_info.fnc_create_wardproportion();    
    CREATE OR REPLACE FUNCTION taxpayment_info.fnc_create_wardproportion()
        Returns Boolean
        LANGUAGE plpgsql AS $$
        BEGIN
            DROP MATERIALIZED VIEW IF EXISTS taxpayment_info.ward_proportion;
                    
            CREATE MATERIALIZED VIEW taxpayment_info.ward_proportion AS 
                SELECT w.ward,  a.count, b.totalcount,
                    ROUND(a.count * 100/b.totalcount::numeric, 2 ) as proportion
                FROM ( 
                    SELECT ward, count(*) as count
                    FROM taxpayment_info.tax_payment_status  
                    WHERE due_year = 0 
                        AND building_associated_to is NULL 
                        AND deleted_at is NULL 
                    GROUP BY ward
                ) a
                JOIN ( 
                    SELECT ward, count(*) as totalcount
                    FROM taxpayment_info.tax_payment_status 
                    WHERE building_associated_to is NULL 
                        AND deleted_at is NULL 
                    GROUP BY ward
                ) b ON b.ward = a.ward
                RIGHT JOIN 
                    layer_info.wards w
                    ON b.ward = w.ward
                ORDER BY b.ward asc;
                
        Return True
    ;
    END
    $$;",

    "fnc_create_gridproportion"=>"DROP FUNCTION IF EXISTS taxpayment_info.fnc_create_gridproportion();    
    CREATE OR REPLACE FUNCTION taxpayment_info.fnc_create_gridproportion()
        Returns Boolean
        LANGUAGE plpgsql AS $$
        BEGIN
            DROP MATERIALIZED VIEW IF EXISTS taxpayment_info.grid_proportion;
                    
            CREATE MATERIALIZED VIEW taxpayment_info.grid_proportion AS 
                SELECT gg.id as grid,  a.count, b.totalcount,
                    ROUND(a.count * 100/b.totalcount::numeric, 2 ) as proportion
                FROM ( 
                    SELECT g.id, count(t.bin) as count
                    FROM taxpayment_info.tax_payment_status t, layer_info.grids g
                    WHERE ST_Contains(ST_Transform(g.geom, 4326), t.geom)
                        AND t.due_year = 0 
                        AND t.building_associated_to is NULL 
                        AND t.deleted_at is NULL 
                    GROUP BY g.id
                ) a
                JOIN ( 
                    SELECT  g.id, count(t.bin) as totalcount
                    FROM taxpayment_info.tax_payment_status t, layer_info.grids g
                    WHERE ST_Contains(ST_Transform(g.geom, 4326), t.geom)
                        AND t.building_associated_to is NULL 
                        AND t.deleted_at is NULL 
                    GROUP BY g.id
                ) b ON b.id = a.id
                RIGHT JOIN 
                        layer_info.grids gg
                        ON b.id = gg.id
                ORDER BY gg.id asc;
                
        Return True
    ;
    END
    $$;",

    "fnc_updonimprt_gridnward_tax"=>"DROP FUNCTION IF EXISTS taxpayment_info.fnc_updonimprt_gridnward_tax();    
    CREATE OR REPLACE FUNCTION taxpayment_info.fnc_updonimprt_gridnward_tax()
        Returns Boolean
        LANGUAGE plpgsql AS $$
        BEGIN
            UPDATE layer_info.wards SET bldgtaxpdprprtn = 0;
            UPDATE layer_info.wards w 
            SET bldgtaxpdprprtn = q.percentage_proportion
            FROM (
                    SELECT a.ward,  a.count, b.totalcount,
                    ROUND(a.count * 100/b.totalcount::numeric, 2 ) as percentage_proportion
                FROM ( 
                    select ward, count(*) as count
                    FROM taxpayment_info.tax_payment_status  
                    WHERE due_year = 0 
                        AND building_associated_to is NULL 
                        AND deleted_at is NULL 
                    GROUP BY ward
                ) a
                JOIN ( 
                    select ward, count(*) as totalcount
                    FROM taxpayment_info.tax_payment_status 
                    WHERE building_associated_to is NULL 
                        AND deleted_at is NULL 
                    GROUP BY ward
                ) b ON b.ward = a.ward
                ORDER BY a.ward asc
            ) as q
            WHERE w.ward = q.ward;
                
            UPDATE layer_info.grids SET bldgtaxpdprprtn = 0;
            UPDATE layer_info.grids g
            SET bldgtaxpdprprtn = q.percentage_proportion
            FROM (
                SELECT a.id,  a.count, b.totalcount,
                    ROUND(a.count * 100/b.totalcount::numeric, 2 ) as percentage_proportion
                FROM ( 
                    SELECT g.id, count(t.bin) as count
                    FROM taxpayment_info.tax_payment_status t, layer_info.grids g
                    WHERE ST_Contains(ST_Transform(g.geom, 4326), t.geom)
                        AND t.due_year = 0 
                        AND t.building_associated_to is NULL 
                        AND t.deleted_at is NULL 
                    GROUP BY g.id
                ) a
                JOIN ( 
                    SELECT  g.id, count(t.bin) as totalcount
                    FROM taxpayment_info.tax_payment_status t, layer_info.grids g
                    WHERE ST_Contains(ST_Transform(g.geom, 4326), t.geom)
                        AND t.building_associated_to is NULL 
                        AND t.deleted_at is NULL 
                    GROUP BY g.id
                ) b ON b.id = a.id
                ORDER BY a.id asc
            )as q
            WHERE g.id = q.id;
                
        Return True
    ;
    END
    $$;"

];