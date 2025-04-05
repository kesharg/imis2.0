<table>
    <thead>
    <tr>
        <th align="right" width="20"><h1><strong>BIN</strong></h1></th>
        <th align="right" width="20"><h1><strong>House Number</strong></h1></th>
        <th align="right" width="20"><h1><strong>Containment ID</strong></h1></th>
        <th align="right" width="20"><h1><strong>Tax Code</strong></h1></th>
        <th align="right" width="20"><h1><strong>Ward</strong></h1></th>
        <th align="right" width="20"><h1><strong>Road Code</strong></h1></th>
        <th align="right" width="20"><h1><strong>Drain Code</strong></h1></th>
        <th align="right" width="20"><h1><strong>Floor Count</strong></h1></th>
        <th align="right" width="20"><h1><strong>Structure Type</strong></h1></th>
        <th align="right" width="20"><h1><strong>Building Use</strong></h1></th>
        <th align="right" width="20"><h1><strong>Building Area</strong></h1></th>
        <th align="right" width="20"><h1><strong>Building Associated</strong></h1></th>
        <th align="right" width="20"><h1><strong>Sanitation System</strong></h1></th>
        <th align="right" width="20"><h1><strong>Toilet Presence</strong></h1></th>
        <th align="right" width="20"><h1><strong>Total No of Toilets</strong></h1></th>
        <th align="right" width="20"><h1><strong>Water Source</strong></h1></th>
        <th align="right" width="20"><h1><strong>Well Presence Status</strong></h1></th>
        <th align="right" width="20"><h1><strong>Population Shared Toilet</strong></h1></th>
        <th align="right" width="20"><h1><strong>House Hold Served</strong></h1></th>
        <th align="right" width="20"><h1><strong>Population Served</strong></h1></th>
        <th align="right" width="20"><h1><strong>Owner Name</strong></h1></th>
        <th align="right" width="20"><h1><strong>Gender</strong></h1></th>
        <th align="right" width="20"><h1><strong>Contact</strong></h1></th>
    </tr>
    </thead>
    <tbody>
    @foreach($buildingResults as $building)
        <tr>
            <td>{{ $building->bin  }}</td> 
            <td>{{ $building->house_number  }}</td> 
            <td>{{ $building->containment  }}</td>         
            <td>{{ $building->tax_code  }}</td>         
            <td>{{ $building->ward   }}</td>       
            <td>{{ $building->road_code   }}</td>          
            <td>{{ $building->drain_code  }}</td>   
            <td>{{ $building->floor_count   }}</td>       
            <td>{{ $building->structuretype  }}</td>       
            <td>{{ $building->functionaluse   }}</td>      
            <td>{{ $building->estimated_area  }}</td>        
            <td>{{ $building->building_associated_to }}</td>
            <td>{{ $building->sanitationsystem  }}</td>   
            <td>{{ $building->toilet_status }}</td>
            <td>{{ $building->toilet_count }}</td>
            <td>{{ $building->watersource }}</td>
            <td>{{ $building->well_presence_status }}</td>
            <td>{{ $building->population_shared_toilet }}</td>
            <td>{{ $building->household_served }}</td>
            <td>{{ $building->population_served }}</td>
            <td>{{ $building->owner_name }}</td>
            <td>{{ $building->owner_gender }}</td>
            <td>{{ $building->owner_contact}}</td>
        </tr>
    @endforeach
    </tbody>
</table>