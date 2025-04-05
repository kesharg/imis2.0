Version: V1.0

# API Documentation

APIs are logically grouped and stored within multiple controllers to ensure a clear separation of concerns and maintainability. Each controller is responsible for managing a specific set of related endpoints, providing a structured approach to API development.

## AuthController

### App Login

| URL          | {Base_URL}/api/login              |          |                   |
|--------------|-----------------------------------|----------|-------------------|
| Method       | POST                              |          |                   |
| Headers      | Header                            | Type     | Required/Optional |
| Content-Type | application/x-www-form-urlencoded | required |                   |
| Accept       | application/json                  |          |                   |

POST Params

| Parameter | Type   | Required/Optional |
|-----------|--------|-------------------|
| email     | string | required          |
| password  | string | required          |

Success Response

{

"status": true,

"message": "User Logged In Successfully.",

"token": "51\|X5XI5d0vaO1NM7a2q4ROwtvlA9xGbx62sb4wkfMK",

"data": {

"name": "Innovative Solutions",

"gender": "",

"treatment_plant": "Patan Treatment Plant",

"help_desk": "Help Desk 1",

"service_provider": "Shyam Services",

"permissions": {

"building-survey": true,

"save-assessment": true,

"save-emptying-service": true

}

}

}

Error Response

{

"status": false,

"message": "Email & Password does not match with our records."

}

### App Logout

| URL           | {Base_URL}/api/logout             |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | POST                              |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

POST Params

Success Response

{

"success": true,

"message": "Logged out Successfully."

}

## ApiServiceController

### GET application info from application ID

| URL           | {Base_URL}/api/application/{application_id} |          |                   |
|---------------|---------------------------------------------|----------|-------------------|
| Method        | GET                                         |          |                   |
| Headers       | Header                                      | Type     | Required/Optional |
| Authorization | Bearer {access_token}                       | required |                   |
| Content-Type  | application/x-www-form-urlencoded           | required |                   |
| Accept        | application/json                            | required |                   |

GET Params

{application_id}

Success Response

{

"success": true,

"data": {

"id": 1,

"application_date": "2020-12-29T18:15:00.000000Z",

"road_code": "R001035",

"containment_id": "C000047",

"approved_status": true,

"assessment_status": true,

"emptying_status": true,

"feedback_status": true,

"customer_name": "Aditi Tamang",

"ward": 1,

"address": null,

"customer_contact": 9801567798,

"applicant_name": "Aditi Tamang",

"user_id": 17,

"service_provider_id": 1,

"customer_gender": "Female",

"applicant_gender": "Female",

"created_at": "2020-12-29T18:15:00.000000Z",

"updated_at": null,

"deleted_at": null,

"proposed_emptying_date": "2020-12-31T18:15:00.000000Z",

"house_number": "B000113",

"applicant_contact": 9801567798,

"sludge_collection_status": true,

"latitude": null,

"longitude": null,

"emergency_desludging_status": false,

"buildings": {

"bin": "B000113",

"ward": 1,

"road_code": "R001035",

"address": null,

"structure_type_id": 4,

"estimated_area": "105.00",

"floor_count": 2,

"household_served": 1,

"population_served": 5,

"functional_use_id": 1,

"use_category_id": null,

"office_business_name": null,

"building_associated_to": null,

"water_source_id": 5,

"well_presence_status": "Yes",

"toilet_status": "Yes",

"toilet_count": 3,

"sewer_code": null,

"drain_code": null,

"surveyed_status": null,

"surveyed_date": null,

"verification_required": null,

"geom": "0106000020E610000001000000010300000001000000050000006E7A81D87E555540E64719CD49A73B4056EE788E8055554058B859C447A73B400295C50B80555540B705CC5142A73B404920CD557E555540E1638F5A44A73B406E7A81D87E555540E64719CD49A73B40",

"tax_code": "B000113",

"user_id": null,

"created_at": "2022-07-03T18:15:00.000000Z",

"updated_at": "2022-07-03T18:15:00.000000Z",

"deleted_at": null,

"house_number": "B000113",

"desludging_vehicle_accessible": null,

"water_customer_id": null,

"swm_customer_id": null,

"sanitation_system_type_id": 2,

"sanitation_system_technology_id": 7,

"construction_year": 2019,

"distance_from_well": null,

"no_of_ihhl_yes": true,

"no_of_ihhl_no": false,

"mapped_functional_use_id": 1,

"lic_id": null,

"structure_type": {

"id": 4,

"type": "RCC framed",

"created_at": "2022-06-27T03:21:15.000000Z",

"updated_at": "2022-06-27T03:21:15.000000Z",

"deleted_at": null

},

"functional_use": {

"id": 1,

"name": "Residential",

"created_at": "2022-06-27T03:21:14.000000Z",

"updated_at": "2022-06-27T03:21:14.000000Z",

"deleted_at": null

},

"sanitation_system_types": {

"id": 2,

"type": "Onsite Treatment",

"created_at": null,

"updated_at": null,

"deleted_at": null

},

"owners": {

"id": 112,

"bin": "B000113",

"owner_name": "Aditi Tamang",

"owner_gender": "Female",

"owner_contact": 9801567798,

"created_at": null,

"updated_at": null,

"deleted_at": null,

"tax_code": "B000113"

}

},

"service_provider": {

"id": 1,

"company_name": "Clean Desludging Pvt. Ltd",

"ward": 2,

"company_location": "Siddhipur",

"email": "clean@gmail.com",

"contact_person": "Hari Maharjan",

"contact_number": 9860843497,

"geom": null,

"created_at": "2020-05-09T02:23:00.000000Z",

"updated_at": "2023-11-30T18:15:07.000000Z",

"deleted_at": null,

"total_trips": 3,

"contact_gender": "Male",

"status": true

},

"feedback": {

"id": 1,

"application_id": 1,

"comments": "NULL",

"fsm_service_quality": true,

"customer_name": "Aditi Tamang",

"customer_number": 9801567798,

"customer_gender": "F",

"wear_ppe": false,

"user_id": 17,

"created_at": null,

"updated_at": null,

"deleted_at": null,

"service_provider_id": 1

}

},

"message": "Application Details."

}

Error Response

{

"status": false,

"message": "No Application found for ID 9999."

}

### GET containment(s) info from application ID

| URL           | {Base_URL}/api/containment/{application_id} |          |                   |
|---------------|---------------------------------------------|----------|-------------------|
| Method        | GET                                         |          |                   |
| Headers       | Header                                      | Type     | Required/Optional |
| Authorization | Bearer {access_token}                       | required |                   |
| Content-Type  | application/x-www-form-urlencoded           | required |                   |
| Accept        | application/json                            | required |                   |

GET Params

{application_id}

Success Response

{

"success": true,

"data": [

{

"id": "C000047",

"location": "Inside the building footprint",

"size": "2",

"pit_number": null,

"pit_diameter": null,

"tank_length": null,

"tank_width": null,

"depth": null,

"septic_criteria": true,

"construction_date": "2019-12-19",

"buildings_served": 1,

"emptied_status": true,

"last_emptied_date": "2023-01-01",

"next_emptying_date": "2026-01-01",

"no_of_times_emptied": 2,

"geom": "0101000020E61000007D4F23727F5555409B34730F46A73B40",

"surveyed_at": null,

"verification_required": null,

"user_id": null,

"created_at": null,

"updated_at": null,

"deleted_at": null,

"type": "Septic Tank with Soak Away Pit",

"population_served": null,

"household_served": null,

"toilet_count": null,

"distance_closest_well": null

}

],

"message": "Containment details for application 1"

}

Error Response

{

"status": false,

"message": "No containment found or application with ID 9999 doesn't exist."

}

### GET service providers

| URL           | {Base_URL}api/service-providers   |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"data": {

"1": "Clean Desludging Pvt. Ltd",

"2": "Sams Cleaning Service Pvt. Ltd",

"60": "david services",

"63": "Dhiren services",

"66": "madan service",

"67": "avash service",

"69": "michael service"

},

"message": "Service Providers."

}

Error Response

## Assessment Controller

### GET applications without assessment (for assessment)

| URL           | {Base_URL}/api/applications       |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"data": {

"applications": []

},

"message": "Applications"

}

URL

{Base_URL}/api/applications

Method

GET

Headers

| Header        | Type                              | Required/Optional |
|---------------|-----------------------------------|-------------------|
| Authorization | Bearer {access_token}             | required          |
| Content-Type  | application/x-www-form-urlencoded | required          |
| Accept        | application/json                  | required          |

Success Response

{

"success": true,

"data": {

"applications": []

},

"message": "Applications"

}

### Save Assesment

| URL           | {Base_URL}/api/save-assessment    |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

## BuildingSurveyController

### GET WMS layer of buildings

| URL           | {Base_URL}/api/wms/buildings      |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"baseUrl": "http://202.52.0.116:8080/geoserver/imis_base_dev/",

"data": {

"buildings": "wms?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&LAYERS=imis_base_dev:buildings_layer&TILED=true&STYLES=imis_base_dev:buildings_layer_none&FORMAT_OPTIONS=dpi:113&WIDTH={width}&HEIGHT={height}&srs=EPSG:900913&BBOX={minX},{minY},{maxX},{maxY}"

},

"message": "WMS layer for buildings."

}

### GET WMS layer of containments

| URL           |  {Base_URL}/api/wms/containments  |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        |  GET                              |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"baseUrl": "http://202.52.0.116:8080/geoserver/imis_base_dev/",

"data": {

"containments": "wms?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image%2Fpng&TRANSPARENT=true&LAYERS=imis_base%3Acontainments_layer&TILED=true&STYLES=imis_base%3Acontainments_layer_none&CRS=EPSG%3A3857&FORMAT_OPTIONS=dpi%3A113&WIDTH={width}&HEIGHT={height}&BBOX={minX},{minY},{maxX},{maxY}"

},

"message": "WMS layer for containments."

}

### GET WMS layer of wards

| URL           | {Base_URL}/api/wms/wards          |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"baseUrl": "http://202.52.0.116:8080/geoserver/imis_base_dev/",

"data": {

"wards": "wms?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image%2Fpng&TRANSPARENT=true&LAYERS=imis_base_dev%3Awards_layer&TILED=true&STYLES=imis_base_dev%3Awards_layer_none&CRS=EPSG%3A3857&FORMAT_OPTIONS=dpi%3A113&WIDTH={width}&HEIGHT={height}&BBOX={minX},{minY},{maxX},{maxY}"

},

"message": "WMS layer for wards."

}

### GET WMS layer of roads

| **URL**       | {Base_URL}/api/wms/roads          |          |                       |
|---------------|-----------------------------------|----------|-----------------------|
| **Method**    | GET                               |          |                       |
| **Headers**   |  **Header**                       | **Type** | **Required/Optional** |
| Authorization | Bearer {access_token}             | required |                       |
| Content-Type  | application/x-www-form-urlencoded | required |                       |
| Accept        | application/json                  | required |                       |

**Success Response**

{

"success": true,

"baseUrl": "http://202.52.0.116:8080/geoserver/imis_base_dev/",

"data": {

"roads": "wms?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image%2Fpng&TRANSPARENT=true&LAYERS=imis_base_dev%3Aroadlines_layer&TILED=true&STYLES=imis_base_dev%3Aroadlines_layer_width&CRS=EPSG%3A3857&FORMAT_OPTIONS=dpi%3A113&WIDTH={width}&HEIGHT={height}&BBOX={minX},{minY},{maxX},{maxY}"

},

"message": "WMS layer for roads."

}

### POST Save building survey

| URL           | {Base_URL}/api/save-building      |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | POST                              |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

POST Params

| Parameter      | Type        | Required? |
|----------------|-------------|-----------|
| bin            | varchar     | Yes       |
| tax_code       | varchar     | Yes       |
| collected_date | Date(Y-m-d) | Yes       |
| kml            | .kml File   | Yes       |

Success Response

{

"status": true,

"message": "Building survey saved successfully."

}

## EmptyingServiceController

### GET assessed applications (for emptying service)

| URL           | {Base_URL}/api/assessed-applications |          |                   |
|---------------|--------------------------------------|----------|-------------------|
| Method        | GET                                  |          |                   |
| Headers       | Header                               | Type     | Required/Optional |
| Authorization | Bearer {access_token}                | required |                   |
| Content-Type  | application/x-www-form-urlencoded    | required |                   |
| Accept        | application/json                     | required |                   |

Success Response

{

"success": true,

"data": {

"applications": []

},

"message": "Applications"

}

### GET treatment plants (place of disposal)

| URL           | {Base_URL}api/treatment-plants    |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"data": {

"treatment-plants": [

{

"id": 1,

"name": "Lubhu FSTP"

},

{

"id": 2,

"name": "Imadole WWTP"

}

]

},

"message": "Treatment Plants"

}

### GET vacutugs

| URL           | {Base_URL}/api/vacutugs           |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"data": [],

"message": "Vacutugs"

}

### GET drivers

| URL           | {Base_URL}/api/drivers            |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"data": [],

"message": "Drivers."

}

### GET emptiers

| URL           | {Base_URL}/api/emptiers           |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | GET                               |          |                   |
| Headers       | Header                            | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

Success Response

{

"success": true,

"data": [],

"message": "Emptiers."

}

### POST Save emptying service

| URL           | {Base_URL}/api/save-emptying      |          |                   |
|---------------|-----------------------------------|----------|-------------------|
| Method        | POST                              |          |                   |
| Headers       |  Header                           | Type     | Required/Optional |
| Authorization | Bearer {access_token}             | required |                   |
| Content-Type  | application/x-www-form-urlencoded | required |                   |
| Accept        | application/json                  | required |                   |

POST Params

|  Parameter        | Type             | Required? |
|-------------------|------------------|-----------|
| volume_of_sludge  | Double precision | Yes       |
| vacutug_id        | integer          | Yes       |
| place_of_disposal | integer          | Yes       |
| driver            | varchar          | Yes       |
| emptier1          | varchar          | Yes       |
| emptier1          | varchar          | No        |
| start_time        | Time (H:m:s)     | Yes       |
| end_time          | Time (H:m:s)     | Yes       |
| no_of_trips       | Double precision | Yes       |
| receipt_number    | varchar          | Yes       |
| total_cost        | numeric          | Yes       |
| application_id    | integer          | Yes       |
| house_image       | Image file       | Yes       |
| receipt_image     | Image file       | Yes       |

Success Response

{

"success": true,

"message": "Emptying service saved successfully."

}
