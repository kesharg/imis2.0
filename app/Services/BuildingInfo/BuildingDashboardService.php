<?php

namespace App\Services\BuildingInfo;
use DB;
use App\Models\LayerInfo\LandUse;

use App\Models\LayerInfo\Ward;
use App\Models\BuildingInfo\FunctionalUse;
use DateTime;
use Illuminate\Support\Facades\Auth;

class  BuildingDashboardService
{

    public function getCostPaidByContainmentOwnerPerward($year)
    {

        $chart = array();
        $where = " WHERE es.deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from a.created_at) = '$year'";
        }

        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = " AND a.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = " AND 1 = 1";
        }

        $query = "SELECT
            SUM(es.total_cost) AS total_cost,
            w.ward
        FROM
            layer_info.wards w
            LEFT JOIN fsm.applications a ON w.ward = a.ward
            LEFT JOIN fsm.emptyings es ON a.id = es.application_id
                                       AND es.deleted_at IS NULL

            $where $whereRawServiceProvider 
        GROUP BY
            w.ward
        ORDER BY
            w.ward";

        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = $row->total_cost;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }

    public function getEmptyingServicePerWardsAssessmentFeedback($year)
    {
        $where = " WHERE a.deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from a.created_at) = '$year'";
        }
        $query = "
        SELECT t1.ward, t1.emptying_count, t3.feedback_count,
        t4.application_count, t5.sludgecollection_count
        FROM
        (SELECT w.ward, COUNT(e.id) AS emptying_count
        FROM layer_info.wards w
        LEFT JOIN fsm.applications a ON a.ward = w.ward
        LEFT JOIN fsm.emptyings e ON e.application_id = a.id
        $where
        GROUP BY w.ward) AS t1
        JOIN
        (SELECT w.ward, COUNT(f.id) AS feedback_count
        FROM layer_info.wards w
        LEFT JOIN fsm.applications a ON a.ward = w.ward
        LEFT JOIN fsm.feedbacks f ON f.application_id = a.id
        $where
        GROUP BY w.ward) AS t3 ON t1.ward = t3.ward
        JOIN
        (SELECT w.ward, COUNT(a.id) AS application_count
        FROM layer_info.wards w
        LEFT JOIN fsm.applications a ON a.ward = w.ward
        WHERE a.deleted_at IS NULL
        GROUP BY w.ward) AS t4 ON t1.ward = t4.ward
        JOIN
        (SELECT w.ward, COUNT(a.id) AS sludgecollection_count
        FROM layer_info.wards w
        LEFT JOIN fsm.treatment_plants a ON a.ward = w.ward
        WHERE a.deleted_at IS NULL
        GROUP BY w.ward) AS t5 ON t1.ward = t5.ward
        ORDER BY t1.ward";




        $results = DB::select($query);

        $labels = [];


        $emptying_dataset = [];
        $emptying_dataset['stack'] = '"stack 1"';
        $emptying_dataset['label'] = '"Emptying"';
        $emptying_dataset['color'] = '"rgba(62, 199, 68, 0.75)"';
        $emptying_dataset['data'] = [];

        $feedback_dataset = [];
        $feedback_dataset['stack'] = '"stack 2"';
        $feedback_dataset['label'] = '"Feedback"';
        $feedback_dataset['color'] = '"rgba(255, 229, 0, 0.75)"';
        $feedback_dataset['data'] = [];

        $application_dataset = [];
        $application_dataset['stack'] = '"stack 3"';
        $application_dataset['label'] = '"Application"';
        $application_dataset['color'] = '"rgba(255, 179, 3, 0.75)"';
        $application_dataset['data'] = [];

        $sludgecollecion_dataset = [];
        $sludgecollecion_dataset['stack'] = '"stack 4"';
        $sludgecollecion_dataset['label'] = '"Treatment Plant"';
        $sludgecollecion_dataset['color'] = '"rgba(219, 61, 61, 0.75)"';
        $sludgecollecion_dataset['data'] = [];

        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $emptying_dataset['data'][] = $row->emptying_count;
            $feedback_dataset['data'][] = $row->feedback_count;
            $application_dataset['data'][] = $row->application_count;
            $sludgecollecion_dataset['data'][] = $row->sludgecollection_count;
        }

        $datasets = [
            $application_dataset,
            $emptying_dataset,
            $feedback_dataset,
            $sludgecollecion_dataset
        ];

        $chart = array(
            'labels' => $labels,
            'datasets' => $datasets
        );

        return $chart;
    }

    public function getFsmSrvcQltyChart($year)
    {
        $where = " WHERE deleted_at IS NULL";
        if($year) {
            $where .= " AND extract(year from fb.created_at) = '$year'";
        }
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk'))
            {

                $whereRawServiceProvider = " AND fb.service_provider_id = " .Auth::user()->service_provider_id;


            }
            else{
                $whereRawServiceProvider = " AND 1 = 1";

            }
        $query = "SELECT count(CASE WHEN fsm_service_quality THEN 1 END) as yes,count(CASE WHEN NOT fsm_service_quality THEN 1 END) as no"
            . " FROM fsm.feedbacks fb $where $whereRawServiceProvider";

        $results = DB::select($query);
        $labels = array('"Yes"', '"No"');
        $values = array($results[0]->yes, $results[0]->no);

        $colors = ['"rgba(62, 199, 68, 0.35)"', '"rgba(219, 61, 61, 0.35)"'];
        $borderColor = ['"rgba(57, 142, 61, 0.65)"', '"rgba(62, 199, 68, 0.8)"', '"rgba(255, 229, 0, 0.8)"', '"rgba(255, 179, 3, 0.8)"', '"rgba(219, 61, 61, 0.65)"'];
         $hoverBackgroundColor = ['"rgba(62, 199, 68, 0.45)"', '"rgba(219, 61, 61, 0.45)"'];
         $hoverBorderColor = ['"rgba(62, 199, 68, 1)"', '"rgba(219, 61, 61, 1)"'];

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,
            'borderColor' =>  $borderColor,
              'hoverBackgroundColor' => $hoverBackgroundColor,
              'hoverBorderColor' => $hoverBorderColor


        ];
        return $chart;

    }
    public function getSrvcQltyItoPrcChart($year)
        {

            $chart = [
                'labels' => [],
                'values' => [],
                'colors' => [],
                'borderColor' =>  [],
                'hoverBackgroundColor' => [],
                'hoverBorderColor' => [],
            ];

            return $chart;
    }

    public function getSludgeAndTransportationServiceChart($year)
    {
        $where = " WHERE deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from fb.created_at) = '$year'";
        }
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = " AND fb.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = " AND 1 = 1";
        }
        $query = "SELECT ss.scale_value, ss.scale_name, COUNT(fb.id)"
            . " FROM fsm.satisfaction_scales ss LEFT JOIN fsm.feedbacks fb ON ss.scale_value = fb.sldgclln_trptn_srvc_qlty"
            . " $where $whereRawServiceProvider"
            . " GROUP BY ss.scale_value, ss.scale_name"
            . " ORDER BY ss.scale_value DESC";


        $results = DB::select($query);
        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->scale_name . '"';
            $values[] = $row->count;
        }

        $colors = ['"rgba(57, 142, 61, 0.2)"', '"rgba(62, 199, 68, 0.2)"', '"rgba(255, 229, 0, 0.2)"', '"rgba(255, 179, 3, 0.2)"', '"rgba(219, 61, 61, 0.2)"'];
        $borderColor = ['"rgba(57, 142, 61, 0.65)"', '"rgba(62, 199, 68, 0.8)"', '"rgba(255, 229, 0, 0.8)"', '"rgba(255, 179, 3, 0.8)"', '"rgba(219, 61, 61, 0.65)"'];
        $hoverBackgroundColor = ['"rgba(57, 142, 61, 0.45)"', '"rgba(62, 199, 68, 0.45)"', '"rgba(255, 229, 0, 0.45)"', '"rgba(255, 179, 3, 0.45)"', '"rgba(219, 61, 61, 0.45)"'];
        $hoverBorderColor = ['"rgba(57, 142, 61, 1)"', '"rgba(62, 199, 68, 1)"', '"rgba(255, 229, 0, 1)"', '"rgba(255, 179, 3, 1)"', '"rgba(219, 61, 61, 1)"'];

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,
            'borderColor' =>  $borderColor,
            'hoverBackgroundColor' => $hoverBackgroundColor,
            'hoverBorderColor' => $hoverBorderColor
        ];

        return $chart;
    }

    public function getAccelerationServiceDeliveryChart($year)
    {
        $where = " WHERE deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from fb.created_at) = '$year'";
        }
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = " AND fb.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = " AND 1 = 1";
        }
        $query = "SELECT ss.scale_value, ss.scale_name, COUNT(fb.id)"
            . " FROM fsm.satisfaction_scales ss LEFT JOIN fsm.feedbacks fb ON ss.scale_value = fb.accrln_effcnc_srvc_dlvry"
            . " $where $whereRawServiceProvider"
            . " GROUP BY ss.scale_value, ss.scale_name"
            . " ORDER BY ss.scale_value DESC";


        $results = DB::select($query);
        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->scale_name . '"';
            $values[] = $row->count;
        }

        $colors = ['"rgba(57, 142, 61, 0.2)"', '"rgba(62, 199, 68, 0.2)"', '"rgba(255, 229, 0, 0.2)"', '"rgba(255, 179, 3, 0.2)"', '"rgba(219, 61, 61, 0.2)"'];
        $borderColor = ['"rgba(57, 142, 61, 0.65)"', '"rgba(62, 199, 68, 0.8)"', '"rgba(255, 229, 0, 0.8)"', '"rgba(255, 179, 3, 0.8)"', '"rgba(219, 61, 61, 0.65)"'];
        $hoverBackgroundColor = ['"rgba(57, 142, 61, 0.45)"', '"rgba(62, 199, 68, 0.45)"', '"rgba(255, 229, 0, 0.45)"', '"rgba(255, 179, 3, 0.45)"', '"rgba(219, 61, 61, 0.45)"'];
        $hoverBorderColor = ['"rgba(57, 142, 61, 1)"', '"rgba(62, 199, 68, 1)"', '"rgba(255, 229, 0, 1)"', '"rgba(255, 179, 3, 1)"', '"rgba(219, 61, 61, 1)"'];

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,
            'borderColor' =>  $borderColor,
            'hoverBackgroundColor' => $hoverBackgroundColor,
            'hoverBorderColor' => $hoverBorderColor
        ];

        return $chart;
    }

    public function getBehaviorOfTheServiceProviderChart($year)
    {
    }

    public function getImpactCreatingPublicAwarenessChart($year)
    {
        $where = " WHERE deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from fb.created_at) = '$year'";
        }
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = " AND fb.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = " AND 1 = 1";
        }
        $query = "SELECT ss.scale_value, ss.scale_name, COUNT(fb.id)"
            . " FROM fsm.satisfaction_scales ss LEFT JOIN fsm.feedbacks fb ON ss.scale_value = fb.imct_crtng_pblc_awrns"
            . " $where $whereRawServiceProvider"
            . " GROUP BY ss.scale_value, ss.scale_name"
            . " ORDER BY ss.scale_value DESC";


        $results = DB::select($query);
        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->scale_name . '"';
            $values[] = $row->count;
        }

        $colors = ['"rgba(57, 142, 61, 0.2)"', '"rgba(62, 199, 68, 0.2)"', '"rgba(255, 229, 0, 0.2)"', '"rgba(255, 179, 3, 0.2)"', '"rgba(219, 61, 61, 0.2)"'];
        $borderColor = ['"rgba(57, 142, 61, 0.65)"', '"rgba(62, 199, 68, 0.8)"', '"rgba(255, 229, 0, 0.8)"', '"rgba(255, 179, 3, 0.8)"', '"rgba(219, 61, 61, 0.65)"'];
        $hoverBackgroundColor = ['"rgba(57, 142, 61, 0.45)"', '"rgba(62, 199, 68, 0.45)"', '"rgba(255, 229, 0, 0.45)"', '"rgba(255, 179, 3, 0.45)"', '"rgba(219, 61, 61, 0.45)"'];
        $hoverBorderColor = ['"rgba(57, 142, 61, 1)"', '"rgba(62, 199, 68, 1)"', '"rgba(255, 229, 0, 1)"', '"rgba(255, 179, 3, 1)"', '"rgba(219, 61, 61, 1)"'];

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,
            'borderColor' =>  $borderColor,
            'hoverBackgroundColor' => $hoverBackgroundColor,
            'hoverBorderColor' => $hoverBorderColor
        ];

        return $chart;
    }

    public function getppeChart($year)
    {
        $where = " WHERE deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from fb.created_at) = '$year'";
        }
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = " AND fb.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = " AND 1 = 1";
        }
        $query = "SELECT count(CASE WHEN wear_ppe THEN 1 END) as yes,count(CASE WHEN NOT wear_ppe THEN 1 END) as no"
            . " FROM fsm.feedbacks fb $where $whereRawServiceProvider";

        $results = DB::select($query);
        $labels = array('"Yes"', '"No"');
        $values = array($results[0]->yes, $results[0]->no);

        $colors = ['"rgba(62, 199, 68, 0.35)"', '"rgba(219, 61, 61, 0.35)"'];

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,

        ];

        return $chart;
    }

    public function getTotalFeedbackPpeWear()
    {
        $where = " WHERE deleted_at IS NULL";

        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = " AND fb.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = " AND 1 = 1";
        }
        $query = "SELECT count(CASE WHEN wear_ppe THEN 1 END) AS total_count"
            . " FROM fsm.feedbacks fb $where $whereRawServiceProvider";

        $results = DB::select($query);
        return $results[0]->total_count;
    }


    public function getTaxRevenueChart()
    {
        $query = "SELECT dy.value, dy.name, COUNT(build.bin) as c
        FROM taxpayment_info.due_years dy
        LEFT JOIN (SELECT b.bin, case when bt.due_year IS NOT NULL then bt.due_year else 99 end
         as due_year from building_info.buildings b
        LEFT JOIN ( SELECT DISTINCT tax_code, bin, due_year
        FROM taxpayment_info.tax_payment_status
        WHERE deleted_at IS NULL AND match IS TRUE ) AS bt
        ON bt.bin = b.bin  WHERE b.deleted_at IS NULL AND b.building_associated_to IS NULL) AS build
        ON dy.value = build.due_year
        GROUP BY dy.value, dy.name
        ORDER BY dy.value ASC";

        $results = DB::select($query);
        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->name . '"';
            $values[] = $row->c;
        }

        $background_colors = ['"rgba(32, 139, 58, 0.25)"', '"rgba(153, 202, 60, 0.25)"', '"rgba(203, 219, 71, 0.25)"', '"rgba(252, 236, 82, 0.25)"', '"rgba(251, 176, 64, 0.25)"', '"rgba(247, 142, 49, 0.25)"', '"rgba(242, 107, 33.25)"'];
        $colors = ['"rgba(32, 139, 58, 0.5)"', '"rgba(153, 202, 60, 0.5)"', '"rgba(203, 219, 71, 0.5)"', '"rgba(252, 236, 82, 0.5)"', '"rgba(251, 176, 64, 0.5)"', '"rgba(247, 142, 49, 0.5)"', '"rgba(242, 107, 33, 0.5)"'];

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,
            'background_colors' => $background_colors
        ];

        return $chart;
    }

    public function getWaterSupplyPaymentChart()
    {
        $query = "SELECT dy.value, dy.name, COUNT(build.bin) as c
        FROM watersupply_info.due_years dy
        LEFT JOIN (SELECT b.bin, case when bt.due_year IS NOT NULL then bt.due_year else 99 end
         as due_year from building_info.buildings b
        LEFT JOIN ( SELECT DISTINCT tax_code, bin, due_year
        FROM watersupply_info.watersupply_payment_status
        WHERE deleted_at IS NULL AND match IS TRUE ) AS bt
        ON bt.bin = b.bin  WHERE b.deleted_at IS NULL AND b.building_associated_to IS NULL) AS build
        ON dy.value = build.due_year
        GROUP BY dy.value, dy.name
        ORDER BY dy.value ASC";

        $results = DB::select($query);
        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->name . '"';
            $values[] = $row->c;
        }

        $background_colors = ['"rgba(175, 175, 175, 0.25)"', '"rgba(66, 134, 244, 0.25)"', '"rgba(0, 255, 255, 0.25)"', '"rgba(61, 229, 45, 0.25)"', '"rgba(158, 38, 244, 0.25)"', '"rgba(30, 0, 132, 0.25)"', '"rgba(255, 0, 0, 0.25)"'];
        $colors = ['"rgba(175, 175, 175, 0.5)"', '"rgba(66, 134, 244, 0.5)"', '"rgba(0, 255, 255, 0.5)"', '"rgba(61, 229, 45, 0.5)"', '"rgba(158, 38, 244, 0.5)"', '"rgba(30, 0, 132, 0.5)"', '"rgba(255, 0, 0, 0.5)"'];

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,
            'background_colors' => $background_colors
        ];

        return $chart;
    }

    public function getSewerLengthPerWard()
    {
        $chart = array();
        $query = "SELECT w.ward, round(CAST(sum(ST_Length(ST_TRANSFORM(ST_Intersection(sewers.geom,w.geom),32645))/1000) as numeric ),2) as length
        FROM layer_info.wards w, utility_info.sewers sewers
        WHERE sewers.deleted_at IS NULL
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

    public function getHotspotsPerWard($year = null)
    {
        $chart = array();
        $where = " WHERE h.deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from h.date) = '$year'";
        }
        $query = "select count(id) as num_of_hotspots, w.ward
        from public_health.waterborne_hotspots h
        right join layer_info.wards w ON h.ward = w.ward $where group by w.ward order by w.ward";

        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = $row->num_of_hotspots;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }
    public function getFsmCampaignsPerWard($year = null)
    {
        $chart = array();
        $where = " WHERE fsm.deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from fsm.start_date) = '$year'";
        }
        $query = "select count(id) as num_of_fsm_campaigns, w.ward
        from fsm.fsm_campaigns fsm
        right join layer_info.wards w ON fsm.ward = w.ward $where group by w.ward order by w.ward";

        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = $row->num_of_fsm_campaigns;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }

    public function getFsmCampaignsSupportedBy($year = null)
    {
        $chart = array();
        $where = " WHERE fsm.deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from fsm.start_date) = '$year'";
        }
        $query = "select count(id) as num_of_fsm_campaigns,supported_by
        from fsm.fsm_campaigns fsm $where group by supported_by order by supported_by";

        $results = DB::select($query);
        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . ($row->supported_by == null ? "Unknown" : $row->supported_by) . '"';
            $values[] = $row->num_of_fsm_campaigns;
        }
        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }

    public function getBuildingsPerWardChart()
    {
        $chart = array();

        $query = 'SELECT w.ward, COUNT(b.bin) AS count'
            . ' FROM layer_info.wards w'
            . ' LEFT JOIN building_info.buildings b'
            . ' ON b.ward = w.ward'
            . ' WHERE b.deleted_at IS NULL'
            . ' GROUP BY w.ward'
            . ' ORDER BY w.ward';

        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = $row->count;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }
    public function getEmptyingRequestsPerStructureTypeChart()
    {

        $chart = array();
        $query = 'SELECT st.type, COUNT(e.id) AS count
        FROM fsm.emptyings e
        LEFT JOIN fsm.applications a
        ON a.id = e.application_id
        LEFT JOIN building_info.buildings b
        ON b.bin = a.house_number
        LEFT JOIN building_info.structure_types st
        ON st.id = b.structure_type_id
        WHERE e.deleted_at IS NULL
        GROUP BY st.id
        ORDER BY st.id';

        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->type . '"';
            $values[] = $row->count;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }


   public function getBuildingUseChart($ward = null)
    {
        
        $query = "SELECT
            functional_use_name,
            building_count
          FROM (
            SELECT
              CASE
                WHEN fu.name = 'Residential' THEN 'Residential'
                WHEN fu.name = 'Mixed (Residential + Other Uses)' THEN 'Mixed (Residential + Other Uses)'
                WHEN fu.name = 'Commercial' THEN 'Commercial'
                ELSE 'Others'
              END AS functional_use_name,
              COUNT(b.bin) AS building_count
            FROM
              building_info.buildings b
              LEFT JOIN building_info.functional_uses fu ON fu.id = b.functional_use_id
            WHERE
              b.deleted_at IS NULL
            GROUP BY
              functional_use_name
          ) AS subquery
          ORDER BY
            CASE
              WHEN functional_use_name = 'Residential' THEN 1
              WHEN functional_use_name = 'Mixed (Residential + Other Uses)' THEN 2
              WHEN functional_use_name = 'Commercial' THEN 3
              ELSE 4
            END";
  
      
        
        $results = DB::select($query);
        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->functional_use_name . '"';
            $values[] = $row->building_count;
        }

        $colors = [
            '"#8ECAE6"',
            '"#219EBC"',
            '"#023047"',
            '"#ffb964"',
        ];
        

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors
        ];

        return $chart;
    }

    public function getCompostSalesByTreatmentPlantChart($year = null)
    {
        $where = " WHERE c.deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from c.created_at) = '$year'";
        }
        $chart = array();

        $query = "SELECT c.name, sum(s.weight) as sum
        FROM fsm.treatment_plants c
        JOIN fsm.compost_sales s
        ON s.treatment_plant_id = c.id
        $where
        GROUP BY c.id, c.name ORDER BY c.id";
        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->name . '"';
            $values[] = $row->sum;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );
        return $chart;
    }

    public function getSludgeCollectionByTreatmentPlantChart($year = null)
    {
        $where = " WHERE c.deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from c.created_at) = '$year'";
        }
        if (Auth::user()->hasRole('Treatment Plant')) {
            $treatment_plant_id = " AND s.treatment_plant_id  = " . Auth::user()->treatment_plant_id;
        } else {
            $treatment_plant_id = " AND 1 = 1";
        }
        $chart = array();

        $query = "SELECT c.name, sum(s.volume_of_sludge) as sum
        FROM fsm.treatment_plants c
        JOIN fsm.sludge_collections s
        ON s.treatment_plant_id = c.id
        $where $treatment_plant_id
        GROUP BY c.id, c.name ORDER BY c.id";
        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->name . '"';
            $values[] = $row->sum;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }

    public function getEmptyingServiceByTypeYear()
    {

        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = " AND e.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = " AND 1 = 1";
        }
        $query = "SELECT TO_CHAR(i, 'YYYY') AS year, c.type, COUNT(C.*) AS count
        FROM GENERATE_SERIES(NOW() + '-4 years', NOW(), '1 year') AS i
        LEFT JOIN fsm.emptyings e
        ON TO_CHAR(e.emptied_date, 'YYYY') = TO_CHAR(i, 'YYYY')
        LEFT JOIN " . 'fsm.applications' . " a
        ON e.application_id = a.id
        LEFT JOIN " . 'fsm.containments' . " c
        ON a.containment_id = c.id
        WHERE e.deleted_at IS NULL $whereRawServiceProvider
        GROUP BY year, c.type
        ORDER BY year";

        $results = DB::select($query);
        $containment_types = DB::select("SELECT type
            FROM fsm.containments
            WHERE type != 'No Containment'
            AND deleted_at IS NULL
            GROUP BY type");
        $types = array();
        foreach ($containment_types as $ctype) {
            $types[$ctype->type] = $ctype->type;
        }
        $data = array();
        foreach ($results as $row) {
            $data[$row->year][$row->type] = $row->count;
        }

        $years = array_keys($data);
        $labels = array_map(function ($year) {
            return '"' . $year . '"';
        }, $years);
        $colors = array(
            '"rgb(100, 130, 252, 0.7)"',
            '"rgb(60, 116, 47, 0.7)"',
            '"rgb(176, 109, 28, 0.7)"',
            '"rgb(248, 79, 101, 0.7)"',
            '"rgb(0, 0, 128, 0.7)"',
            '"rgb(205, 71, 7, 0.7)"',
            '"rgb(184, 0, 0, 0.7)"',
            '"rgb(37, 198, 198, 0.7)"',
            '"rgb(151, 92, 173, 0.7)"',
            '"rgb(243, 162, 63, 0.7)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
        );
        $colorsArr = array_slice($colors, 0, count($results), true);
        $datasets = array();
        $count = 0;
        foreach ($types as $key1 => $value1) {
            $dataset = array();
            $dataset['label'] = '"' . $value1 . '"';
            $dataset['color'] = $colors[$count++];
            $dataset['data'] = array();
            foreach ($years as $year) {
                $dataset['data'][] = isset($data[$year][$key1]) ? $data[$year][$key1] : '0';
            }
            $datasets[] = $dataset;
        }

        $chart = array(
            'labels' => $labels,
            'datasets' => $datasets
        );

        return $chart;
    }

    public function getContainmentTypesByLanduse()
    {

        $chart = array();

        $landuses = Landuse::orderBy('class')->pluck('class', 'class')->toArray();
        $containment_types = DB::select("SELECT type
            FROM fsm.containments
            WHERE type != 'No Containment'
            AND deleted_at IS NULL
            GROUP BY type");
        $types = array();
        foreach ($containment_types as $ctype) {
            $types[$ctype->type] = $ctype->type;
        }



        $query = "SELECT class, type, count, totalclass, percentage_proportion FROM public.landuse_summaryforchart";
        //from materialized view

        $results = DB::select($query);

        $data = array();
        $values = array();

        foreach ($results as $row) {
            $data[$row->type][$row->class] = $row->percentage_proportion;
            $values[$row->type][$row->class] = $row->count;
        }

        $labels = array_map(function ($landuse) {
            return '"' . $landuse . '"';
        }, $landuses);
        // $colors = array('"#B938C7"', '"#528aad"','"#5AA59C"');
        $colors = array(
            '"rgb(100, 130, 252, 0.7)"',
            '"rgb(60, 116, 47, 0.7)"',
            '"rgb(176, 109, 28, 0.7)"',
            '"rgb(248, 79, 101, 0.7)"',
            '"rgb(0, 0, 128, 0.7)"',
            '"rgb(205, 71, 7, 0.7)"',
            '"rgb(184, 0, 0, 0.7)"',
            '"rgb(37, 198, 198, 0.7)"',
            '"rgb(151, 92, 173, 0.7)"',
            '"rgb(243, 162, 63, 0.7)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
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

            foreach ($landuses as $key2 => $value2) {
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

    public function getContainmentTypesByBldgUseResidentials()
    {

        $chart = array();

        $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
        $containment_types = DB::select("SELECT type
            FROM fsm.containments
            WHERE type != 'No Containment'
            AND deleted_at IS NULL
            GROUP BY type");

        $types = array();
        foreach ($containment_types as $ctype) {
            $types[$ctype->type] = $ctype->type;
        }

        $query = 'SELECT a.ward, a.type, a.count, b.totalward,
		ROUND(a.count * 100/b.totalward) as percentage_proportion
                FROM ( select c.type, count(c.*), b.ward
                from building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
                join fsm.containments c on bc.containment_id = c.id
                where b.functional_use_id = 1 AND b.deleted_at IS NULL group by c.type, b.ward
                     ) a
                JOIN ( select count(c.*) as totalward, b.ward
                     from building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
                join fsm.containments c on bc.containment_id = c.id
                where b.functional_use_id = 1 AND b.deleted_at IS NULL group by b.ward
                     ) b ON b.ward = a.ward

               ORDER BY a.ward asc';

        $results = DB::select($query);

        $data = array();
        $values = array();

        foreach ($results as $row) {
            $data[$row->type][$row->ward] = $row->percentage_proportion;
            $values[$row->type][$row->ward] = $row->count;
        }


        $labels = array_map(function ($ward) {
            return '"' . $ward . '"';
        }, $wards);

        // $colors = array('"#B938C7"', '"#528aad"','"#5AA59C"');
        $colors = array(
            '"rgb(100, 130, 252, 0.7)"',
            '"rgb(60, 116, 47, 0.7)"',
            '"rgb(176, 109, 28, 0.7)"',
            '"rgb(248, 79, 101, 0.7)"',
            '"rgb(0, 0, 128, 0.7)"',
            '"rgb(205, 71, 7, 0.7)"',
            '"rgb(184, 0, 0, 0.7)"',
            '"rgb(37, 198, 198, 0.7)"',
            '"rgb(151, 92, 173, 0.7)"',
            '"rgb(243, 162, 63, 0.7)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
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

    public function getContainmentTypesByBldgUse()
    {

        $chart = array();

        $bldguses = FunctionalUse::orderBy('name')->pluck('name', 'name')->toArray();
        $containment_types = DB::select("SELECT type
            FROM fsm.containments
            WHERE type != 'No Containment'
            AND deleted_at IS NULL
            GROUP BY type");
        $types = array();
        foreach ($containment_types as $ctype) {
            $types[$ctype->type] = $ctype->type;
        }

        $query = 'SELECT a.bldg_name, a.type, a.count, b.total_bldguse,
		ROUND(a.count * 100/b.total_bldguse::numeric, 2 ) as percentage_proportion
                FROM ( select c.type, count(c.*), bldg.name as bldg_name
                from building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
                join fsm.containments c on bc.containment_id = c.id
                join building_info.functional_uses bldg on bldg.id = b.functional_use_id
                where b.functional_use_id is not null AND b.deleted_at IS NULL group by c.type, b.functional_use_id, bldg.name
                     ) a
                JOIN ( select count(c.*) as total_bldguse, bldg.name as bldg_name
               from building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
                join fsm.containments c on bc.containment_id = c.id
                join building_info.functional_uses bldg on bldg.id = b.functional_use_id
                where b.functional_use_id is not null AND b.deleted_at IS NULL group by b.functional_use_id, bldg.name
                     ) b ON b.bldg_name = a.bldg_name

               ORDER BY a.bldg_name asc';

        $results = DB::select($query);

        $data = array();
        $values = array();
        foreach ($results as $row) {
            $data[$row->type][$row->bldg_name] = $row->percentage_proportion;
            $values[$row->type][$row->bldg_name] = $row->count;
        }


        $labels = array_map(function ($bldguse) {
            return '"' . $bldguse . '"';
        }, $bldguses);
        $colors = array(
            '"rgb(100, 130, 252, 0.7)"',
            '"rgb(60, 116, 47, 0.7)"',
            '"rgb(176, 109, 28, 0.7)"',
            '"rgb(248, 79, 101, 0.7)"',
            '"rgb(0, 0, 128, 0.7)"',
            '"rgb(205, 71, 7, 0.7)"',
            '"rgb(184, 0, 0, 0.7)"',
            '"rgb(37, 198, 198, 0.7)"',
            '"rgb(151, 92, 173, 0.7)"',
            '"rgb(243, 162, 63, 0.7)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
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
            foreach ($bldguses as $key2 => $value2) {
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

    public function getContainmentTypesByStructypes()
    {

        $chart = array();

        $results_obj = DB::select('select st.type as st_type,structure_type_id
                from building_info.buildings b
                LEFT JOIN building_info.structure_types st ON b.structure_type_id =  st.id
                where b.structure_type_id is not null AND b.deleted_at IS NULL
                group by b.structure_type_id, st.type');

        foreach ($results_obj as $value) {
            $structtypes[$value->structure_type_id] = $value->st_type;
        }

        $containment_types = DB::select("SELECT type
            FROM fsm.containments
            WHERE type != 'No Containment'
            AND deleted_at IS NULL
            GROUP BY type");
        $types = array();
        foreach ($containment_types as $ctype) {
            $types[$ctype->type] = $ctype->type;
        }

        $query = 'SELECT st.type as st_type, a.structure_type_id, a.type, a.count, b.totalstructure_type,
		ROUND(a.count * 100/b.totalstructure_type::numeric, 2 ) as percentage_proportion
                FROM ( select b.structure_type_id, c.type, count(c.*)
                 from building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
                join fsm.containments c on bc.containment_id = c.id
                where structure_type_id is not null group by c.type, b.structure_type_id
                     ) a
                  JOIN ( select count(c.*) as totalstructure_type, b.structure_type_id
                   from building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
                join fsm.containments c on bc.containment_id = c.id
                where structure_type_id is not null group by b.structure_type_id
                     ) b ON b.structure_type_id = a.structure_type_id
                JOIN building_info.structure_types st ON a.structure_type_id =  st.id
               ORDER BY a.structure_type_id asc';

        $results = DB::select($query);

        $data = array();
        $values = array();
        foreach ($results as $row) {

            $data[$row->type][$row->structure_type_id] = $row->percentage_proportion;
            $values[$row->type][$row->structure_type_id] = $row->count;
        }

        $labels = array_map(function ($structtype) {
            return '"' . $structtype . '"';
        }, $structtypes);

        $colors = array(
            '"rgb(100, 130, 252, 0.7)"',
            '"rgb(60, 116, 47, 0.7)"',
            '"rgb(176, 109, 28, 0.7)"',
            '"rgb(248, 79, 101, 0.7)"',
            '"rgb(0, 0, 128, 0.7)"',
            '"rgb(205, 71, 7, 0.7)"',
            '"rgb(184, 0, 0, 0.7)"',
            '"rgb(37, 198, 198, 0.7)"',
            '"rgb(151, 92, 173, 0.7)"',
            '"rgb(243, 162, 63, 0.7)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
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
            foreach ($structtypes as $key2 => $value2) {
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

    public function getContainmentTypesPerWard()
    {


        $chart = array(
            // 'labels' => $labels,
            // 'datasets' => $datasets,

            'labels' => [],
            'datasets' => [],
        );

        return $chart;
    }

    public function getContainmentsPerWard()
    {
        $query = "SELECT b.ward, COUNT(*) total,
        SUM(CASE WHEN type = 'Pit' THEN 1 ELSE 0 END) pitcount,
        SUM(CASE WHEN type = 'Septic Tank' THEN 1 ELSE 0 END) septicount
        from building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
        join fsm.containments c on bc.containment_id = c.id
        WHERE b.ward <> 0 AND b.deleted_at IS NULL GROUP BY ward ORDER BY b.ward";

        $results = DB::select($query);

        $totalResultsCount = count($results);

        $resultsPerTable = ceil($totalResultsCount / 2);

        $wardNo = array();
        $noOfPit = array();
        $noOfSeptic = array();
        $noOfContainments = array();

        foreach ($results as $row) {
            $wardNo[] = $row->ward;
            $noOfPit[] = $row->pitcount;
            $noOfSeptic[] = $row->septicount;
            $noOfContainments[] = $row->total;
        }

        $containPerWard = [
            'wardNo' => $wardNo,
            'noOfPit' => $noOfPit,
            'noOfSeptic' => $noOfSeptic,
            'noOfContainments' => $noOfContainments,
            'totalResultsCount' => $totalResultsCount,
            'resultsPerTable' => $resultsPerTable
        ];

        return $containPerWard;
    }

    public function getContainTypeChart($ward = null)
    {

        $query = "SELECT type, COUNT(*)"
            . " FROM " . 'fsm.containments' . " "
            . " WHERE type != 'No Containment'"
            . " AND deleted_at IS NULL"
            . ($ward ? " AND ward = " . $ward : "")
            . " GROUP BY type";
        $results = DB::select($query);

        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->type . '"';
            $values[] = $row->count;
        }

        // $colors = array('"#B938C7"', '"#528aad"','"#5AA59C"');

        $colors = array(
            '"rgb(100, 130, 252, 0.7)"',
            '"rgb(60, 116, 47, 0.7)"',
            '"rgb(176, 109, 28, 0.7)"',
            '"rgb(248, 79, 101, 0.7)"',
            '"rgb(0, 0, 128, 0.7)"',
            '"rgb(205, 71, 7, 0.7)"',
            '"rgb(184, 0, 0, 0.7)"',
            '"rgb(37, 198, 198, 0.7)"',
            '"rgb(151, 92, 173, 0.7)"',
            '"rgb(243, 162, 63, 0.7)"',
            '"rgb(0, 171, 102, 0.7)"',
            '"rgb(45, 85, 98, 0.7)"',
            '"rgba(251,0, 0, 0.7)"',
            '"rgb(173, 255, 47, 0.7)"',
            '"rgb(246, 255, 0)"',
        );
        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors
        ];

        return $chart;
    }

    public function getPovertyInSettlementChart($ward = null)
    {
        $query = 'SELECT DISTINCT settpovert, COUNT(*) FROM settarea ' . ($ward ? 'WHERE ward = ' . $ward : '') . ' GROUP BY settpovert';

        $results = DB::select($query);

        $labels = ['"Not Poor"', '"Poor"', '"Very Poor"', '"Extreme Poor"'];
        $values = array();

        foreach ($results as $row) {
            $values[] = $row->count;
        }

        $colors = ['"#fff5f0"', '"#fca487"', '"#eb362a"', '"#67000d"'];

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors
        ];

        return $chart;
    }

    public function getNextEmptyingContainmentsChart($ward = null)
    {
        $query = "SELECT TO_CHAR(i, 'YYYY-MM') AS month, COUNT(c.id) AS count"
            . " FROM GENERATE_SERIES(NOW() + '1 month', NOW() + '12 months', '1 month') AS i"
            . " LEFT JOIN " . 'fsm.containments' . " c"
            . " ON TO_CHAR(i, 'YYYY-MM') = TO_CHAR(c.next_emptying_date, 'YYYY-MM')"
            . " WHERE emptied_status = true AND c.deleted_at IS NULL"
            . " GROUP BY month"
            . " ORDER BY month";
        $results = DB::select($query);

        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->month . '"';
            $values[] = $row->count;
        }

        $chart = [
            'labels' => $labels,
            'values' => $values
        ];

        return $chart;
    }

    public function getcontainmentEmptiedByWard()
    {
        $chart = array();
        $dateTime = new DateTime();
        $startDate = $dateTime->format('Y-m-d');
        $dateTime->modify('+29 days');
        $endDate = $dateTime->format('Y-m-d');

        $query = "SELECT
        w.ward, COUNT(a.id) AS count
        FROM layer_info.wards w
        LEFT JOIN ( building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
        join fsm.containments c on bc.containment_id = c.id )
        ON b.ward = w.ward
        LEFT JOIN fsm.applications a
        ON a.containment_id = c.id
        AND a.id IN(SELECT application_id FROM fsm.emptyings WHERE next_emptying_date >= '$startDate' AND next_emptying_date <= '$endDate')
        WHERE a.deleted_at IS NULL
        GROUP BY w.ward
        ORDER BY w.ward";

        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = $row->count;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }

    public function getproposedEmptyingDateContainmentsChart()
    {
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = "WHERE a.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = "WHERE 1 = 1";
        }
        $query = "SELECT i.week, COUNT(a.id) AS count"
            . " FROM (SELECT CONCAT('Week ', w + 1) AS week, NOW()::DATE + w * 7 + 1 AS date FROM GENERATE_SERIES(0, 3) w) AS i"
            . " LEFT JOIN fsm.applications a"
            . " ON a.proposed_emptying_date BETWEEN i.date AND i.date + 6"
            . " $whereRawServiceProvider"
            . " GROUP BY i.week"
            . " ORDER BY i.week";

        $results = DB::select($query);

        $labels = array();
        $values = array();

        foreach ($results as $row) {
            $labels[] = '"' . $row->week . '"';
            $values[] = $row->count;
        }

        $chart = [
            'labels' => $labels,
            'values' => $values
        ];

        return $chart;
    }

    public function getProposedEmptiedDateContainmentsByWard()
    {
        $chart = array();
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {

            $whereRawServiceProvider = "WHERE a.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawServiceProvider = "WHERE 1 = 1";
        }

        $dateTime = new DateTime();
        $startDate = $dateTime->format('Y-m-d');

        $dateTime->modify('+29 days');
        $endDate = $dateTime->format('Y-m-d');
        $query = "SELECT
        w.ward, COUNT(a.id) AS count
        FROM layer_info.wards w
        LEFT JOIN ( building_info.buildings b join building_info.build_contains bc on b.bin = bc.bin
                join fsm.containments c on bc.containment_id = c.id )
        ON b.ward = w.ward
        LEFT JOIN fsm.applications a
        ON a.containment_id = c.id
        AND a.id IN(SELECT id FROM fsm.applications WHERE proposed_emptying_date >= '$startDate' AND proposed_emptying_date <= '$endDate')
        $whereRawServiceProvider AND a.deleted_at IS NULL
        GROUP BY w.ward
        ORDER BY w.ward";

        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach ($results as $row) {
            $labels[] = '"' . $row->ward . '"';
            $values[] = $row->count;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }

    public function getMonthlyAppRequestByoperators($year)
    {




        $chart = array(
            //'labels' => $labels,
            //'datasets' => $datasets
            'labels' => [],
            'datasets' => [],
        );

        return $chart;
    }

    public function getNumberOfEmptyingbyMonths($year)
    {

        $label = array(0 => "Jan", 1 => "Feb", 2 => "Mar", 3 => "Apr", 4 => "May", 5 => "Jun", 6 => "Jul", 7 => "Aug", 8 => "Sep", 9 => "Oct", 10 => "Nov", 11 => "Dec");
        $where = " WHERE a.deleted_at IS NULL";
        if ($year) {
            $where .= " AND extract(year from a.created_at) = '$year'";
        }
        $query =  "SELECT COALESCE(SUM(CASE WHEN w.geom IS NOT NULL THEN 1 ELSE 0 END), 0) AS low_income_count,
        COALESCE(SUM(CASE WHEN w.geom IS NOT NULL THEN 1 ELSE 0 END), 0) AS other_count,
        months.month_val AS month
        FROM (SELECT generate_series(1,12) AS month_val) AS months
        LEFT JOIN fsm.applications AS a ON months.month_val = extract(month FROM a.created_at)
        LEFT JOIN building_info.buildings AS b ON a.house_number = b.bin
        LEFT JOIN layer_info.low_income_communities AS w ON ST_Intersects(ST_Transform(w.geom, 4326), b.geom)
       $where OR a.created_at IS NULL
        GROUP BY months.month_val
        ORDER BY months.month_val;";


        $results = DB::select($query);
        $labels = [];
        foreach ($label as $month) {
            $labels[] = '"' . $month . '"';
        }

        $low_income_communities_dataset = [];
        $low_income_communities_dataset['stack'] = '"stack 1"';
        $low_income_communities_dataset['label'] = '"Low Income communities"';
        $low_income_communities_dataset['color'] = '"rgba(62, 199, 68, 0.75)"';
        $low_income_communities_dataset['data'] = [];

        $other_dataset = [];
        $other_dataset['stack'] = '"stack 2"';
        $other_dataset['label'] = '"Other communities"';
        $other_dataset['color'] = '"rgba(255, 229, 0, 0.75)"';
        $other_dataset['data'] = [];

        foreach ($results as $row) {

            $low_income_communities_dataset['data'][] = $row->low_income_count;
            $other_dataset['data'][] = $row->other_count;
        }

        $datasets = [
            $low_income_communities_dataset,
            $other_dataset
        ];

        $chart = array(
            'labels' => $labels,
            'datasets' => $datasets
        );
        return $chart;
    }

    public function getBuildingFloorCountPerWard()
{
            $chart = array();

            // Assuming you have a Ward model and 'wards' table
            $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();

            $query = "SELECT
                w.ward,
                SUM(CASE WHEN floor_count = 1 THEN 1 ELSE 0 END) AS floor_1,
                SUM(CASE WHEN floor_count = 2 THEN 1 ELSE 0 END) AS floor_2,
                SUM(CASE WHEN floor_count = 3 THEN 1 ELSE 0 END) AS floor_3,
                SUM(CASE WHEN floor_count = 4 THEN 1 ELSE 0 END) AS floor_4,
                SUM(CASE WHEN floor_count >= 5 THEN 1 ELSE 0 END) AS floor_5
            FROM
                building_info.buildings b
            LEFT JOIN
                layer_info.wards w ON b.ward = w.ward
            GROUP BY
                w.ward
            ORDER BY
                w.ward";

            $results = DB::select($query);
            $chartData = [];
            $labels = [];
            $floorCounts = [1, 2, 3, 4, '5+']; // Define the categories

            foreach ($results as $row) {
                $ward = $row->ward;
                $data = [
                    $row->floor_1,
                    $row->floor_2,
                    $row->floor_3,
                    $row->floor_4,
                    $row->floor_5
                ];

                $labels[] = $ward;
                $chartData[$ward] = $data;
            }

            $datasets = [];
            $colors = [
            'rgba(32, 139, 58, 0.8)',
            'rgba(153, 202, 60, 0.8)',
            'rgba(252, 236, 82, 0.8)',
            'rgba(251, 176, 64, 0.8)',
            'rgba(247, 142, 49, 0.8)',
            'rgba(242, 107, 33, 0.8)'];
            foreach ($floorCounts as $key => $floorCount) {
                $dataset = [
                    'label' => 'Floor ' . $floorCount,
                    'backgroundColor' => $colors[$key], // Set your colors accordingly
                    'data' => [],
                ];

                foreach ($labels as $ward) {
                    $dataset['data'][] = isset($chartData[$ward][$key]) ? $chartData[$ward][$key] : 0;
                    $dataset['value'][] = isset($chartData[$ward][$key]) ? $chartData[$ward][$key] : 0;
                }

                $datasets[] = $dataset;
            }

            $chart = [
                'labels' => $labels,
                'datasets' => $datasets,
            ];

            return $chart;
    }



 public function getBuildingStructureTypePerWard()
        {
            $chart = array();
        
           
            $query = "SELECT
                        w.ward,
                        st.type,
                        COUNT(*) AS count
                    FROM
                        building_info.buildings b
                    INNER JOIN
                        layer_info.wards w ON b.ward = w.ward
                    INNER JOIN
                        building_info.structure_types st ON b.structure_type_id = st.id
                    GROUP BY
                        w.ward, st.type
                    ORDER BY
                        w.ward, st.type;";
        
            $results = DB::select($query);
        
            $chartData = [];
            $labels = [];
            $structureTypes = [];
        
            foreach ($results as $row) {
                $ward = $row->ward;
                $structureType = $row->type;
                $count = $row->count;
        
                if (!in_array($ward, $labels)) {
                    $labels[] = $ward;
                }
        
                if (!in_array($structureType, $structureTypes)) {
                    $structureTypes[] = $structureType;
                }
        
                $chartData[$ward][$structureType] = $count;
            }
            $datasets = [];
            $colors = [
            'rgba(32, 139, 58, 0.8)',
            'rgba(153, 202, 60, 0.8)',
            'rgba(252, 236, 82, 0.8)',
            'rgba(251, 176, 64, 0.8)',
            ];
        
            foreach ($structureTypes as $key1 => $value1) {
                $dataset = [
                    'label' => $value1,
                    'color' => $colors[$key1],
                    'data' => [],
                    'value' => []
                ];
        
                foreach ($labels as $key2 => $ward) {
                    $dataset['data'][] = isset($chartData[$ward][$value1]) ? $chartData[$ward][$value1] : 0;
                    $dataset['value'][] = isset($chartData[$ward][$value1]) ? $chartData[$ward][$value1] : 0;
                }
        
                $datasets[] = $dataset;
            }
        
            $chart = [
                'labels' => $labels,
                'datasets' => $datasets,
            ];
            return $chart;
        }
    public function getBuildingSanitationSystem(){
        return DB::table('building_info.sanitation_systems as s')
        ->leftJoin('building_info.buildings as b', 's.id', '=', 'b.sanitation_system_id')
        ->select('s.sanitation_system','s.icon_name', DB::raw('COUNT(b.bin) as bin_count'))
        ->where('s.dashboard_display', true)
        ->where('b.deleted_at', null)
        ->groupBy('s.sanitation_system', 's.id')
        ->orderBy('s.id', 'asc')
        ->get();
    }
    
    public function getBuildingSanitationSystemOthers(){
        
        $results = DB::table('building_info.sanitation_systems as s')
        ->leftJoin('building_info.buildings as b', function($join) {
            $join->on('s.id', '=', 'b.sanitation_system_id')
                 ->whereNull('b.deleted_at');
        })
        ->select(DB::raw('COUNT(b.bin) as bin_count, s.sanitation_system as sanitation_system_name'))
        ->where('s.dashboard_display', false)
        ->whereNotIn('s.id', [11])
        ->groupBy('s.sanitation_system', 's.id')
        ->get();
        
        $sanitation_systems_arr = [];
        foreach($results as $result){
            $sanitation_systems_arr[] = $result->sanitation_system_name;
        }
            $sanitation_systems = implode(",\n", $sanitation_systems_arr);

        return ['total' => $results->sum('bin_count'), 'sanitation_system_names' => $sanitation_systems];
        
    }
}