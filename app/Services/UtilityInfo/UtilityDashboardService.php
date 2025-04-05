<?php
//Last Modified Date: 19-04-2024
//Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024)
namespace App\Services\UtilityInfo;
use App\Models\LayerInfo\Ward;
use DB;

class UtilityDashboardService
{
    public function getRoadsSurfaceTypePerWardChart()
    {
        $chart = array();
        $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
        $surface_types = DB::select("SELECT surface_type
            FROM utility_info.roads
            WHERE surface_type != ''
            AND deleted_at IS NULL
            GROUP BY surface_type");
    
        $types = array();
        foreach ($surface_types as $ctype) {
            $types[$ctype->surface_type] = $ctype->surface_type;
        }
    
        $results = DB::select("SELECT
            w.ward,
            ROUND(CAST(SUM(ST_Length(ST_TRANSFORM(ST_Intersection(r.geom, w.geom), 32645))) AS NUMERIC), 0) AS total_length,
            CASE
                WHEN r.surface_type IS NOT NULL THEN r.surface_type
                ELSE 'Other'
            END AS surface_type,
            ROUND(CAST(SUM(CASE WHEN r.surface_type = s.surface_type THEN ST_Length(ST_TRANSFORM(ST_Intersection(r.geom, w.geom), 32645)) ELSE 0 END) AS NUMERIC), 0) AS surface_type_length
            FROM
                layer_info.wards w
            JOIN
                utility_info.roads r ON ST_Intersects(r.geom, w.geom) AND r.deleted_at IS NULL
            JOIN
                (SELECT DISTINCT surface_type FROM utility_info.roads) AS s ON r.surface_type = s.surface_type
            GROUP BY
            w.ward, r.surface_type
        ");
    
        $values = array();
        $data = array();
    
        foreach ($results as $row) {
            $data[$row->surface_type][$row->ward] = (int)$row->surface_type_length; // Ensure integer values
            $values[$row->surface_type][$row->ward] = (int)$row->surface_type_length; // Ensure integer va2314lues
        }
    
        $labels = array_map(function ($ward) {
            return '"' . $ward . '"';
        }, $wards);
    
        $colors = array(
            '"rgba(201, 119, 119, 1)"', '"rgba(237, 214, 179, 1)"', '"rgba(128, 128, 128, 1)"', '"rgba(169, 169, 169, 1)"', '"rgba(219, 61, 61, 0.65)"',
        );
        $colorsArr = array_slice($colors, 0, count($results), true);
        $datasets = array();
        $count = 0;
        foreach ($types as $key1 => $value1) {
            $dataset = array();
            $dataset['label'] = '"' . $value1 . '"';
            $dataset['color'] = $colors[$count++];
            $dataset['data'] = array();
            $dataset['value'] = array();
            foreach ($wards as $key2 => $value2) {
                $dataset['data'][] = isset($data[$key1][$key2]) ? $data[$key1][$key2] : '0';
                $dataset['value'][] = isset($values[$key1][$key2]) ? $values[$key1][$key2] : '0';
            }
            $datasets[] = $dataset;
        }
    
        $chart = array(
            'labels' => $labels,
            'datasets' => $datasets
        );
    
        return $chart;
    }
    
    public function getRoadsHierarchyPerWardChart()
    {
        $chart = array();
        $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
        $hierarchys = DB::select("SELECT hierarchy
            FROM utility_info.roads
            WHERE hierarchy != ''
            AND deleted_at IS NULL
            GROUP BY hierarchy");
    
        $types = array();
        foreach ($hierarchys as $ctype) {
            $types[$ctype->hierarchy] = $ctype->hierarchy;
        }
    
        $results = DB::select("SELECT
            w.ward,
            ROUND(CAST(SUM(ST_Length(ST_TRANSFORM(ST_Intersection(r.geom, w.geom), 32645))) AS NUMERIC), 0) AS total_length,
            CASE
                WHEN r.hierarchy IS NOT NULL THEN r.hierarchy
                ELSE 'Other'
            END AS hierarchy,
            ROUND(CAST(SUM(CASE WHEN r.hierarchy = s.hierarchy THEN ST_Length(ST_TRANSFORM(ST_Intersection(r.geom, w.geom), 32645)) ELSE 0 END) AS NUMERIC), 0) AS hierarchy_length
            FROM
                layer_info.wards w
            JOIN
                utility_info.roads r ON ST_Intersects(r.geom, w.geom) AND r.deleted_at IS NULL
            JOIN
                (SELECT DISTINCT hierarchy FROM utility_info.roads) AS s ON r.hierarchy = s.hierarchy
            GROUP BY
            w.ward, r.hierarchy
        ");
    
        $values = array();
        $data = array();
    
        foreach ($results as $row) {
            $data[$row->hierarchy][$row->ward] = (int)$row->hierarchy_length; // Ensure integer values
            $values[$row->hierarchy][$row->ward] = (int)$row->hierarchy_length; // Ensure integer values
        }
    
        $labels = array_map(function ($ward) {
            return '"' . $ward . '"';
        }, $wards);
    
        $colors = array(
            '"rgba(247, 153, 153, 1)"', '"rgba(246, 178, 107, 1)"', '"rgba(201, 119, 119, 1)"', '"rgba(255, 179, 3, 0.8)"', '"rgba(219, 61, 61, 0.65)"'
        );
        $colorsArr = array_slice($colors, 0, count($results), true);
        $datasets = array();
        $count = 0;
        foreach ($types as $key1 => $value1) {
            $dataset = array();
            $dataset['label'] = '"' . $value1 . '"';
            $dataset['color'] = $colors[$count++];
            $dataset['data'] = array();
            $dataset['value'] = array();
            foreach ($wards as $key2 => $value2) {
                $dataset['data'][] = isset($data[$key1][$key2]) ? $data[$key1][$key2] : '0';
                $dataset['value'][] = isset($values[$key1][$key2]) ? $values[$key1][$key2] : '0';
            }
            $datasets[] = $dataset;
        }
    
        $chart = array(
            'labels' => $labels,
            'datasets' => $datasets
        );
        return $chart;
    }
    
    public function getDrainsTypePerWardChart()
    {
        $chart = array();
        $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
        $drain_types = DB::select("SELECT cover_type
            FROM utility_info.drains
            WHERE cover_type != ''
            AND deleted_at IS NULL
            GROUP BY cover_type");
    
        $types = array();
        foreach ($drain_types as $ctype) {
            $types[$ctype->cover_type] = $ctype->cover_type;
        }
    
        $results = DB::select("SELECT
            w.ward,
            CASE
                WHEN r.cover_type IS NOT NULL THEN r.cover_type
                ELSE 'Other'
            END AS cover_type,
            ROUND(CAST(SUM(ST_Length(ST_TRANSFORM(ST_Intersection(r.geom, w.geom), 32645))) AS NUMERIC), 0) AS total_length
            FROM
                layer_info.wards w
            JOIN
                utility_info.drains r ON ST_Intersects(r.geom, w.geom) AND r.deleted_at IS NULL
            GROUP BY
                w.ward, r.cover_type
            ORDER BY
                w.ward, total_length DESC;
        ");
    
        $values = array();
        $data = array();
    


        foreach ($results as $row) {
            $data[$row->cover_type][$row->ward] = (int)$row->total_length; // Ensure integer values
            $values[$row->cover_type][$row->ward] = (int)$row->total_length; // Ensure integer values
        }
    
        $labels = array_map(function ($ward) {
            return '"' . $ward . '"';
        }, $wards);
    
        $colors = array(
             '"rgba(247, 153, 153, 1)"','"rgba(159, 226, 191, 0.7)"', '"rgba(255, 229, 0, 0.5)"', '"rgba(255, 179, 3, 0.5)"', '"rgba(219, 61, 61, 0.5)"'
        );
        $colorsArr = array_slice($colors, 0, count($results), true);
        $datasets = array();
        $count = 0;
        foreach ($types as $key1 => $value1) {
            $dataset = array();
            $dataset['label'] = '"' . $value1 . '"';
            $dataset['color'] = $colors[$count++];
            $dataset['data'] = array();
            $dataset['value'] = array();
            foreach ($wards as $key2 => $value2) {
                $dataset['data'][] = isset($data[$key1][$key2]) ? $data[$key1][$key2] : '0';
                $dataset['value'][] = isset($values[$key1][$key2]) ? $values[$key1][$key2] : '0';
            }
            $datasets[] = $dataset;
        }
    
        $chart = array(
            'labels' => $labels,
            'datasets' => $datasets
        );
    
        return $chart;
    }
    
    public function getSewerLengthPerWard()
    {
        $chart = array();
        $query = "SELECT w.ward, ROUND(CAST(SUM(ST_Length(ST_TRANSFORM(ST_Intersection(sewers.geom,w.geom),32645))) AS NUMERIC), 0) AS length
                  FROM layer_info.wards w
                  JOIN utility_info.sewers sewers ON ST_Intersects(sewers.geom, w.geom) AND sewers.deleted_at IS NULL
                  GROUP BY w.ward
                  ORDER BY w.ward";
    
        $results = DB::select($query);
    
        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = (int)$row->length; // Ensure integer values
        }
    
        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );
    
        return $chart;
    }
    
    public function getRoadLengthPerWardChart()
    {
        $chart = array();
        $query = "SELECT w.ward, ROUND(CAST(SUM(ST_Length(ST_TRANSFORM(ST_Intersection(roads.geom,w.geom),32645))) AS NUMERIC), 0) AS length
                  FROM layer_info.wards w
                  JOIN utility_info.roads roads ON ST_Intersects(roads.geom, w.geom) AND roads.deleted_at IS NULL
                  GROUP BY w.ward
                  ORDER BY w.ward";
    
        $results = DB::select($query);
    
        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = (int)$row->length; // Ensure integer values
        }
    
        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );
    
        return $chart;
    }
    
    public function getDrainLengthPerWardChart()
    {
        $chart = array();
        $query = "SELECT w.ward, round(CAST(sum(ST_Length(ST_TRANSFORM(ST_Intersection(drains.geom,w.geom),32645))) as numeric )) as length
        FROM layer_info.wards w, utility_info.drains drains
        WHERE drains.deleted_at IS NULL
        GROUP BY w.ward
        ORDER BY w.ward";
    
        $results = DB::select($query);
    
        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = $row->length;
        }
    
        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );
    
        return $chart;
    }
    
public function getSewerDiameterPerWardChart()
{
    $chart = array();
    $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
    $carrying_width = DB::select("SELECT s.diameter
            FROM (
                SELECT 'unknown' AS diameter
                UNION ALL
                SELECT '<16' AS diameter
                UNION ALL
                SELECT '16-30' AS diameter
                UNION ALL
                SELECT '>30' AS diameter
            ) AS s
            LEFT JOIN (
                SELECT
                    CASE
                        WHEN diameter is null THEN 'unknown'
                        WHEN diameter < 16 THEN '<16'
                        WHEN diameter >= 16 AND diameter < 30 THEN '16-30'
                        WHEN diameter > 30 THEN '>30'
                    END AS diameter
                FROM
                    utility_info.sewers
                WHERE
                    deleted_at IS NULL
                GROUP BY
                    diameter
            ) AS actual_diameters ON s.diameter = actual_diameters.diameter
            ORDER BY
                s.diameter;
            ");

    $types = array();
    foreach ($carrying_width as $ctype) {
        $types[$ctype->diameter] = $ctype->diameter;
    }

    $results = DB::select("SELECT
            w.ward,
            CASE
                WHEN sewers.diameter IS NULL THEN 'unknown'
                WHEN sewers.diameter < 16 THEN '<16'
                WHEN sewers.diameter >= 16 AND sewers.diameter < 30 THEN '16-30'
                WHEN sewers.diameter >= 30 THEN '>30'
            END AS diameter_category,
            ROUND(CAST(SUM(ST_Length(ST_TRANSFORM(ST_Intersection(sewers.geom, w.geom), 32645))) AS NUMERIC), 0) AS total_length
        FROM
            layer_info.wards w
        JOIN
            utility_info.sewers sewers ON ST_Intersects(sewers.geom, w.geom) AND sewers.deleted_at IS NULL
        GROUP BY
            w.ward,
            CASE
                WHEN sewers.diameter IS NULL THEN 'unknown'
                WHEN sewers.diameter < 16 THEN '<16'
                WHEN sewers.diameter >= 16 AND sewers.diameter < 30 THEN '16-30'
                WHEN sewers.diameter >= 30 THEN '>30'
            END
        ORDER BY
            w.ward, total_length;
        ");

    $values = array();
    $data = array();

    foreach ($results as $row) {
        $data[$row->diameter_category][$row->ward] = (int)$row->total_length; // Ensure integer values
        $values[$row->diameter_category][$row->ward] = (int)$row->total_length; // Ensure integer values
    }

    $labels = array_map(function ($ward) {
        return '"' . $ward . '"';
    }, $wards);

    $colors = array(
        '"rgba(19, 42, 19, 0.8)"', '"rgba(79, 121, 66, 0.9)"', '"rgba(79, 119, 45, 0.8)"', '"rgba(169, 169, 169, 1)"', '"rgba(219, 61, 61, 0.65)"'
    );
    $colorsArr = array_slice($colors, 0, count($results), true);
    $datasets = array();
    $count = 0;
    foreach ($types as $key1 => $value1) {
        $dataset = array();
        $dataset['label'] = '"' . $value1 . '"';
        $dataset['color'] = $colors[$count++];
        $dataset['data'] = array();
        $dataset['value'] = array();
        foreach ($wards as $key2 => $value2) {
            $dataset['data'][] = isset($data[$key1][$key2]) ? $data[$key1][$key2] : '0';
            $dataset['value'][] = isset($values[$key1][$key2]) ? $values[$key1][$key2] : '0';
        }
        $datasets[] = $dataset;
    }

    $chart = array(
        'labels' => $labels,
        'datasets' => $datasets
    );
    return $chart;
}

function getDrainDiameterPerWardChart()
{
    $chart = array();
    $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
    $carrying_width = DB::select("SELECT distinct d.size
    FROM (
        SELECT 'unknown' AS size
        UNION ALL
        SELECT '<16' AS size
        UNION ALL
        SELECT '16-30' AS size
        UNION ALL
        SELECT '>30' AS size
    ) AS d
    LEFT JOIN (
        SELECT
            CASE
                WHEN size is null THEN 'unknown'
                WHEN size < 16 THEN '<16'
                WHEN size >= 16 AND size < 30 THEN '16-30'
                WHEN size > 30 THEN '>30'
            END AS size
        FROM
            utility_info.drains
        WHERE
            deleted_at IS NULL
        GROUP BY
            size
    ) AS actual_diameters ON d.size = actual_diameters.size
    ORDER BY
        d.size;
    ");

    $types = array();
    foreach ($carrying_width as $ctype) {
        $types[$ctype->size] = $ctype->size;
    }

    $results = DB::select("SELECT
    w.ward,
    CASE
        WHEN d.size IS NULL THEN 'unknown'
        WHEN d.size < 16 THEN '<16'
        WHEN d.size >= 16 AND d.size < 30 THEN '16-30'
        WHEN d.size >= 30 THEN '>30'
    END AS diameter_category,
    ROUND(CAST(SUM(ST_Length(ST_TRANSFORM(ST_Intersection(d.geom, w.geom), 32645))) AS NUMERIC), 0) AS total_length
    FROM
        layer_info.wards w
    JOIN
        utility_info.drains d ON ST_Intersects(d.geom, w.geom) AND d.deleted_at IS NULL
    GROUP BY
        w.ward, diameter_category
    ORDER BY
        w.ward, total_length DESC;  ");

    $values = array();
    $data = array();

    foreach ($results as $row) {
        $data[$row->diameter_category][$row->ward] = (int)$row->total_length; // Ensure integer values
        $values[$row->diameter_category][$row->ward] = (int)$row->total_length; // Ensure integer values
    }

    $labels = array_map(function ($ward) {
        return '"' . $ward . '"';
    }, $wards);

    $colors = array(
        '"rgba(57, 142, 61, 0.5)"', '"rgba(62, 199, 68, 0.5)"', '"rgba(255, 229, 0, 0.8)"', '"rgba(255, 179, 3, 0.8)"', '"rgba(219, 61, 61, 0.65)"'
    );
    $colorsArr = array_slice($colors, 0, count($results), true);
    $datasets = array();
    $count = 0;
    foreach ($types as $key1 => $value1) {
        $dataset = array();
        $dataset['label'] = '"' . $value1 . '"';
        $dataset['color'] = $colors[$count++];
        $dataset['data'] = array();
        $dataset['value'] = array();
        foreach ($wards as $key2 => $value2) {
            $dataset['data'][] = isset($data[$key1][$key2]) ? $data[$key1][$key2] : '0';
            $dataset['value'][] = isset($values[$key1][$key2]) ? $values[$key1][$key2] : '0';
        }
        $datasets[] = $dataset;
    }

    $chart = array(
        'labels' => $labels,
        'datasets' => $datasets
    );

    return $chart;
}


function getRoadDiameterPerWardChart()
{
    $chart = array();
    $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
    $carrying_width = DB::select("SELECT
    CASE
         WHEN carrying_width < 3 THEN 'Trail'
        WHEN carrying_width >= 3 AND carrying_width <= 5 THEN 'Single Lane'
         WHEN carrying_width > 5 AND carrying_width <= 8 THEN 'Double Lane'
         WHEN carrying_width > 8 THEN 'Highway'
            END AS carrying_width
        FROM
            utility_info.roads
        WHERE
            deleted_at IS NULL
        GROUP BY
            carrying_width
        ORDER BY
            carrying_width;");

    $types = array();
    foreach ($carrying_width as $ctype) {
        $types[$ctype->carrying_width] = $ctype->carrying_width;
    }

    $results = DB::select("SELECT
    w.ward,
    CASE
        WHEN r.carrying_width < 3 THEN 'Trail'
        WHEN r.carrying_width >= 3 AND r.carrying_width <= 5 THEN 'Single Lane'
        WHEN r.carrying_width > 5 AND r.carrying_width <= 8 THEN 'Double Lane'
        WHEN r.carrying_width > 8 THEN 'Highway'
    END AS width_category,
    SUM(ST_Length(ST_TRANSFORM(ST_Intersection(r.geom, w.geom), 32645))) AS total_length,
    SUM(ST_Length(ST_TRANSFORM(ST_Intersection(r.geom, w.geom), 32645)) *
        CASE
            WHEN r.carrying_width < 3 THEN r.carrying_width
            WHEN r.carrying_width >= 3 AND r.carrying_width <= 5 THEN r.carrying_width
            WHEN r.carrying_width > 5 AND r.carrying_width <= 8 THEN r.carrying_width
            WHEN r.carrying_width > 8 THEN r.carrying_width
        END) AS total_width
        FROM
            layer_info.wards w
        JOIN
            utility_info.roads r ON ST_Intersects(r.geom, w.geom) AND r.deleted_at IS NULL
        GROUP BY
            w.ward,
            CASE
                WHEN r.carrying_width < 3 THEN 'Trail'
                WHEN r.carrying_width >= 3 AND r.carrying_width <= 5 THEN 'Single Lane'
                WHEN r.carrying_width > 5 AND r.carrying_width <= 8 THEN 'Double Lane'
                WHEN r.carrying_width > 8 THEN 'Highway'
            END;
        ");

    $values = array();
    $data = array();

    foreach ($results as $row) {
        $formatted_length = number_format($row->total_length, 0, '.', ''); // Remove decimals
        $formatted_width = number_format($row->total_width, 0, '.', ''); // Remove decimals

        $data[$row->width_category][$row->ward] = $formatted_length;
        $values[$row->width_category][$row->ward] = $formatted_width;
    }

    $labels = array_map(function ($ward) {
        return '"' . $ward . '"';
    }, $wards);

    $colors = array(
        '"rgba(57, 142, 61, 0.65)"', '"rgba(62, 199, 68, 0.5)"', '"rgba(251, 236, 93, 0.6)"', '"rgba(246, 178, 107, 0.8)"', '"rgba(219, 61, 61, 0.65)"'
    );
    $colorsArr = array_slice($colors, 0, count($results), true);
    $datasets = array();
    $count = 0;
    foreach ($types as $key1 => $value1) {
        $dataset = array();
        $dataset['label'] = '"' . $value1 . '"';
        $dataset['color'] = $colors[$count++];
        $dataset['data'] = array();
        $dataset['value'] = array();
        foreach ($wards as $key2 => $value2) {
            $dataset['data'][] = isset($data[$key1][$key2]) ? $data[$key1][$key2] : '0';
            $dataset['value'][] = isset($values[$key1][$key2]) ? $values[$key1][$key2] : '0';
        }
        $datasets[] = $dataset;
    }

    $chart = array(
        'labels' => $labels,
        'datasets' => $datasets
    );
    return $chart;
}



public function getWaterSupplyLengthPerWardChart()
{
    $chart = array();
    $query = "SELECT w.ward, CAST(sum(ST_Length(ST_TRANSFORM(ST_Intersection(water_supplys.geom, w.geom), 32645))) AS INTEGER) as length
        FROM layer_info.wards w, utility_info.water_supplys water_supplys
        WHERE water_supplys.deleted_at IS NULL
        GROUP BY w.ward
        ORDER BY w.ward";

    $results = DB::select($query);

    $labels = array();
    $values = array();
    foreach ($results as $row) {
        $labels[] = '"' . $row->ward . '"';
        $values[] = $row->length;
    }

    $chart = array(
        'labels' => $labels,
        'values' => $values,
    );

    return $chart;
}

public function getWaterSupplyDiameterPerWardChart()
{
    $chart = array();
    $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
    $carrying_width = DB::select("SELECT distinct d.diameter
    FROM (
        SELECT 'unknown' AS diameter
        UNION ALL
        SELECT '<16' AS diameter
        UNION ALL
        SELECT '16-30' AS diameter
        UNION ALL
        SELECT '>30' AS diameter
    ) AS d
    LEFT JOIN (
        SELECT
            CASE
                WHEN diameter is null THEN 'unknown'
                WHEN diameter < 16 THEN '<16'
                WHEN diameter >= 16 AND diameter < 30 THEN '16-30'
                WHEN diameter > 30 THEN '>30'
            END AS diameter
        FROM
            utility_info.water_supplys
        WHERE
            deleted_at IS NULL
        GROUP BY
            diameter
    ) AS actual_diameters ON d.diameter = actual_diameters.diameter
    ORDER BY
        d.diameter;
    ");

    $types = array();
    foreach ($carrying_width as $ctype) {
        $types[$ctype->diameter] = $ctype->diameter;
    }

    $results = DB::select("SELECT
    w.ward,
    CASE
        WHEN d.diameter IS NULL THEN 'unknown'
        WHEN d.diameter < 16 THEN '<16'
        WHEN d.diameter >= 16 AND d.diameter < 30 THEN '16-30'
        WHEN d.diameter >= 30 THEN '>30'
    END AS width_category,
    CAST(SUM(ST_Length(ST_TRANSFORM(ST_Intersection(d.geom, w.geom), 32645))) AS INTEGER) AS total_length
FROM
    layer_info.wards w
JOIN
    utility_info.water_supplys d ON ST_Intersects(d.geom, w.geom) AND d.deleted_at IS NULL
GROUP BY
    w.ward, width_category
ORDER BY
    w.ward, total_length DESC;  ");

    $values = array();
    $data = array();

    foreach ($results as $row) {
        $data[$row->width_category][$row->ward] = $row->total_length;
        $values[$row->width_category][$row->ward] = $row->total_length;
    }

    $labels = array_map(function ($ward) {
        return '"' . $ward . '"';
    }, $wards);

    $colors = array(
        '"rgba(57, 142, 61, 0.65)"', '"rgba(62, 199, 68, 0.5)"', '"rgba(251, 236, 93, 0.6)"', '"rgba(246, 178, 107, 0.8)"', '"rgba(219, 61, 61, 0.65)"'
    );
    $colorsArr = array_slice($colors, 0, count($results), true);
    $datasets = array();
    $count = 0;
    foreach ($types as $key1 => $value1) {
        $dataset = array();
        $dataset['label'] = '"' . $value1 . '"';
        $dataset['color'] = $colors[$count++];
        $dataset['data'] = array();
        $dataset['value'] = array();
        foreach ($wards as $key2 => $value2) {
            $dataset['data'][] = isset($data[$key1][$key2]) ? $data[$key1][$key2] : '0';
            $dataset['value'][] = isset($values[$key1][$key2]) ? $values[$key1][$key2] : '0';
        }
        $datasets[] = $dataset;
    }

    $chart = array(
        'labels' => $labels,
        'datasets' => $datasets
    );

    return $chart;
}

}
