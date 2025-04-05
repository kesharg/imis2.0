Version: V1.0

Contents

[1 Data Type Standard for IMIS](#data-type-standard-for-imis)

[2 Postgres Naming Convention](#postgres-naming-convention)

[2.1 Conventions](#conventions)

[2.2 Demonstration](#demonstration)

[3 Building IMS](#building-ims)

[3.1 Buildings](#buildings)

[3.2 Building Survey](#building-survey)

[3.3 Low Income Communities](#low-income-communities)

[4 Fecal Sludge IMS](#fecal-sludge-ims)

[4.1 Containment IMS](#containment-ims)

[4.1.1 Containments](#containments)

[4.2 Service Provider IMS](#service-provider-ims)

[4.2.1 Service Providers](#service-providers)

[4.2.2 Employee Information](#employee-information)

[4.2.3 Desludging Vehicles Information](#desludging-vehicles-information)

[4.3 Treatment Plant IMS](#treatment-plant-ims)

[4.3.1 Treatment Plants](#treatment-plants)

[4.3.2 Performance Efficiency Test](#performance-efficiency-test)

[4.4 Emptying Service IMS](#emptying-service-ims)

[4.4.1 Application](#application)

[4.4.2 Emptying](#emptying)

[4.4.3 Sludge Collection](#sludge-collection)

[4.4.4 Feedback](#feedback)

[4.4.5 Help Desk](#help-desk)

[5 PT/CT IMS](#ptct-ims)

[5.1  Public / Community Toilets](#public--community-toilets)

[5.2 PT Users Log](#pt-users-log)

[6 CWIS IMS](#cwis-ims)

[6.1 CWIS Generator](#cwis-generator)

[6.2 KPI Target](#kpi-target)

[7 Utility IMS](#utility-ims)

[7.1 Road Network Information](#road-network-information)

[7.2 Sewer Network Information](#sewer-network-information)

[7.3 Water Supply Network Information](#water-supply-network-information)

[7.4 Drain Network Information](#drain-network-information)

[8 Property Tax Collection IMS](#property-tax-collection-ims)

[9 Water Supply ISS](#water-supply-iss)

[10 Urban Management DSS](#urban-management-dss)

[10.1 Map Feature](#map-feature)

[10.2 Point of interests](#point-of-interests)

[10.3 Sewerareas](#sewerareas)

[11 Public Health ISS](#_Toc165367231)

[11.1 Waterborne Hotspot](#waterborne-hotspot)

[11.2 Yearly Waterborne Cases Information](#yearly-waterborne-cases-information)

[11.3 Water Sample Information](#water-sample-information)

[12 Settings](#settings)

[12.1 Performance Efficiency Standards](#performance-efficiency-standards)

[12.2 CWIS Setting](#cwis-setting)

[12.3 User Information Management](#user-information-management)

[12.3.1 User](#user)

[12.3.2 Roles](#roles)

[12.3.3 Permissions](#permissions)

[13 Other tables & views](#other-tables--views)

[13.1 Wards](#wards)

[13.2 Grids](#grids)

[14 Sewer Connection](#Sewer-Connection)

# Data Type Standard for IMIS

The following table outlines the data types used for IMIS and the standard conventions set that should be followed during future additional developments and maintenance of IMIS to ensure best practices. (reference: [PostgreSQL: Documentation: 15: Chapter 8. Data Types](https://www.postgresql.org/docs/current/datatype.html)):

| Data Type                   | Data Description                                                                                                                                                                                                | Limitations                                                                     |
|-----------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------|
| INTEGER                     | Values that describe whole numbers are assigned this data type such as counts, foreign keys, primary keys, ward numbers, year, etc. Note: This data type should be used when decimal values need not be stored. | stores range of data:  -2147483648 to +2147483647                               |
| BIG INTEGER                 | If integer data type is not sufficient for the use case, then BIGINTEGER should be used, for example: mobile numbers, contact numbers, etc.                                                                     | stores range of data: -9223372036854775808 to +9223372036854775807              |
| NUMERIC                     | Values that describe decimal values and require exact precision are assigned this data type such as size, length, width, capacity.                                                                              | up to 6 digits before the decimal point; up to 5 digits after the decimal point |
| DOUBLE PRECISION            | If numeric data type is not sufficient for the use case, then double precision should be used                                                                                                                   | 15 decimal digits precision                                                     |
| CHARACTER VARYING           | Values containing characters such as alphabets, special characters, etc.                                                                                                                                        |                                                                                 |
| Timestamp without time zone | Stores a timestamp value without a timezone. Values that describe date & time of creating, editing and deleting.                                                                                                | Example: 2022-11-27 23:03:31 Format: YYYY-MM-DD Hr-Min-Sec                      |
| Time                        | Values that describe time only.                                                                                                                                                                                 | Format: Hr-Min-Sec                                                              |
| Date                        | Values that describe date only.                                                                                                                                                                                 | Format: YYYY-MM-DD                                                              |
| Boolean                     | Values that describe Yes/No values such as status.                                                                                                                                                              |                                                                                 |
| GEOMETRY                    | Values that store geometric data types such as geom.                                                                                                                                                            |                                                                                 |

# Postgres Naming Convention

## Conventions

Schema Name:

SQL identifiers and keywords must begin with a letter (a-z, but also letters with diacritical marks and non-Latin letters) or an underscore (_). Subsequent characters in an identifier or keyword can be letters, underscores, digits (0-9), or dollar signs (\$).

Indexes:

The standard names for indexes in PostgreSQL are:

{tablename}_{columnname(s)}_{suffix}

where the suffix is one of the following:

-   pkey for a Primary Key constraint

-   key for a Unique constraint

-   excl for an Exclusion constraint

-   idx for any other kind of index

-   fkey for a foreign key

-   check for a Check constraint

Standard suffix for sequences is

-   seq for all sequences

## Demonstration

Create an index

create index my_table_column_a_idx on my_table(column_a);

Create a multicolumn index

create index my_table_column_a_column_b_idx on my_table(column_a, column_b);

Create a unique index

create unique index my_table_column_a_key on my_table(column_a);

Create a multicolumn unique index

create unique index my_table_column_a_column_b_key on my_table(column_a, column_b);

|                  | **Description**                       | **Good**           | **Bad**         | **Remarks**        |
|------------------|---------------------------------------|--------------------|-----------------|--------------------|
| **TABLE Naming** |                                       |                    |                 |                    |
| **Schema name**  |                                       | building_info, fsm | buildingInfos   | singular           |
| **Table name**   |                                       | treatment_plants   |                 | plural             |
| **Pivot Table**  |                                       | build_contains     |                 | plural             |
| **Column Name**  | snake_case long name with 3 words max | applicant_name     | applicants_name | should be singular |
| **ID**           | integer                               | 35, 777777         |                 |                    |
| **Code**         | code                                  | B000035, C77777    |                 |                    |
| **Primary key**  | id                                    | id                 | containcd       |                    |
| **Foreign key**  | tablename_id                          | containment_id     | containcd       |                    |

# Building IMS

**Schema Name: building_info**

The Building Info Module uses the following tables:

**Data Tables**

-   building_surveys: stores survey information received from the mobile application.

-   buildings: stores primary information of the building

-   owners: relational database that connects buildings and their owner. Foreign_key: bin

**Lookup Tables**

-   functional_uses: stores functional use attributes used as dropdowns

-   sanitation_system_types: stores sanitation system type attributes used as dropdowns.

-   sanitation_system_technologies: stores sanitation system technology attributes used as dynamic dropdown options that change according to sanitation_system_types

-   structure_types: stores type of building structure types.

-   use_categorys: stores Use categorys attributes used as dropdowns.

-   water_sources: stores Water sources attributes used as dropdowns.

-   wms_links: stores links for WMS (geoserver)

**Relational Tables**

-   build_contains: relational database that connects buildings and containments. Foreign Key: bin and containment_id.

## Buildings

**Data Tables**

Table Name: **buildings**

| **Field Name**                  | **Label**                                     | **Description**                                                                                                  | **Data Type**                       |
|---------------------------------|-----------------------------------------------|------------------------------------------------------------------------------------------------------------------|-------------------------------------|
| bin                             |                                               | Unique identifier for the building (auto generated)                                                              | character varying pk                |
| house_number                    | House Address                                 | Unique address code of the building assigned by the city (e.g., house number, holding number)                    | character varying                   |
| building_associated_to          | BIN of Main Building                          | BIN of the main building associated with the auxiliary building                                                  | character varying                   |
| tax_code                        | Tax Code / Holding ID                         | Identifier for the building’s tax code/ holding ID                                                               | character varying                   |
| ward                            | Ward No                                       | Identifier for the local administrative unit                                                                     | integer                             |
| road_code                       | Road Code                                     | Identifier for the road that the building is connected                                                           | character varying fk:roads          |
| structure_type_id               | Structure Type                                | Type of the building structure                                                                                   | integer fk:structure_types          |
| floor_count                     | Number of Floors                           | Number of floors of the building                                                                                 | integer                             |
| construction_year               | Year of Construction                          | Year when the building was constructed                                                                           | integer                             |
| low_income_hh                   | Low Income Household                          | Boolean indicating if building is low income                                                                     | boolean                             |
| lic_id                          | Low Income Community Name                     | Unique identified of low income community                                                                        | integer fk: low_income_community    |
| functional_use_id               | Functional Use of Building                    | Functionality of the building                                                                                    | integer fk: functional_uses         |
| use_category_id                 | Use Categories of Buildings                   | Category of the building use, depending on functional use                                                        | integer fk: use_catergorys          |
| office_business_name            | Office or Business Name                       | Name of the business or office in the building, if not residential                                               | character varying                   |
| household_served                | Number of Households                          | Number of households served by the building                                                                      | integer                             |
| population_served               | Population of Building                        | Number of people served by the building                                                                          | integer                             |
| male_population                 | Male Population                               | Number of male population                                                                                        | integer                             |
| female_population               | Female Population                             | Number of female population                                                                                      | integer                             |
| other_population                | Other Population                              | Number of other population                                                                                       | integer                             |
| diff_abled_male _pop           | Differnetly Abled Male Population             | Number of differently abled male population                                                                      | integer                             |
| diff_abled_female \_pop         | Differnetly Abled female Population           | Number of differently abled female population                                                                    | integer                             |
| diff_abled_others \_pop         | Differently Abled Other Population            | Number of differently abled other population                                                                     | integer                             |
| surveyed_date                   | Surveyed Date                                 | Date when the building was surveyed, auto generated if building added from building surveys                      | date                                |
| verification_status             |                                               | Status that indicates the need of geometry verification.                                                         | boolean                             |
| toilet_status                   | Toilet Presence                               | Boolean indicating whether the toilets are present on the building premises                                      | boolean                             |
| toilet_count                    | Number of Toilets                             | Number of toilet facilities in the building if toilet_status is true                                             | integer                             |
| no_hh_shared_toilet             | Households with Shared Toilet               | No of households with access to shared toilets                                                                   | integer                             |
| sanitation_system_id            | Sanitation System                             | Sanitation system (technology) of the building                                                                   | integer fk:sanitation_systems       |
| sewer_code                      | Sewer Code                                    | Identifier for the sewer system, if applicable                                                                   | character varying fk: sewers        |
| drain_code                      | Drain Code                                    | Identifier for the drain system, if applicable                                                                   | character varying fk: drains        |
| desludging_vehicle \_accessible | Building Accessible to Desludging Vehicle     | Whether the building is accessible to the desludging vehicle                                                     | boolean                             |
| water_source_id                 | Main Drinking Water Source                    | Source of water supply to the building                                                                           | integer fk: water_sources           |
| watersupply_pipe_code           | Water Supply Pipeline Code                    | Water supply pipeline code (if water source is Municipal/Public Water Supply)                                    | character varying fk: water_supplys |
| water_customer_id               | Water Supply Customer ID                      | Unique identifier for the water customer record, if available (if water source is Municipal/Public Water Supply) | character varying                   |
| well_presence_status            | Well Presence                                 | a boolean indicating whethe a well is present on the building premises                                           | character varying                   |
| distance_from_well              | Distance of Well from Closest Containment (m) | Distance from the building to the nearest well, if applicable                                                    | numeric                             |
| swm_customer_id                 | SWM Customer ID                               | Unique identifier for the solid waste management customer record, if available                                   | character varying                   |
| estimated_area                  |                                               | Estimated area of the building in square meters (unit m2) (Auto calculated from geom)                            | numeric                             |
| geom                            | Building Footprint (KML File)                 | Geospatial coordinates of the building (represented as a polygon)                                                | geometry                            |
| user_id                         |                                               | Identifier for the user who created the record (Auto Fill, Hidden)                                               | integer fk: users                   |
| created_at                      |                                               | Timestamp when the record was created (Auto Fill, Hidden)                                                        | timestamp                           |
| updated_at                      |                                               | Timestamp when the record was last updated (Auto Fssssill, Hidden)                                               | timestamp                           |
| deleted_at                      |                                               | Timestamp when the record was deleted (Auto Fill, Hidden)                                                        | timestamp                           |

Table Name: **owners**

| **Field Name** | **Label**            | **Description**                                                | **Data Type**                                 |
|----------------|----------------------|----------------------------------------------------------------|-----------------------------------------------|
| id             |                      | Unique identifier for the record (auto generated)              | integer pk                                    |
| bin            |                      | Unique identifier for the building                             | character varying fk: building_info.buildings |
| owner_name     | Owner Name           | Name of the building owner                                     | character varying                             |
| owner_gender   | Owner Gender         | Gender of the building owner                                   | character varying                             |
| owner_contact  | Owner Contact Number | Contact Number of the building owner                           | big integer                                   |
| tax_id         |                      | Tax Idenfitication of the Owner                                | character varying                             |
| created_at     |                      | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp                                     |
| updated_at     |                      | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp                                     |
| deleted_at     |                      | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp                                     |


**Relational Tables**

Table Name: **build_contains**

| **Field Name** | **Label**         | **Description**                                                   | **Data Type**                                 |
|----------------|-------------------|-------------------------------------------------------------------|-----------------------------------------------|
| id             |                   | Unique identifier for the record (auto generated)                 | integer pk                                    |
| bin            |                   | Unique identifier for the building                                | character varying fk: building_info.buildings |
| containment_id |                   | Identifier for the containment that the building is connected to  | character varying fk: fsm.containments        |
| main_building  | Is Main Building? | Boolean indicating if main building or auxiliary building         | boolean                                       |
| created_at     |                   | Timestamp when the record was created (Auto Fill, Hidden)         | timestamp                                     |
| updated_at     |                   | Timestamp when the record was last updated (Auto Fill, Hidden)    | timestamp                                     |
| deleted_at     |                   | Timestamp when the record was deleted (Auto Fill, Hidden)         | timestamp                                     |

**Lookup Tables**

Table Name: **functional_uses**

| **Field Name** | **Description**                                                | **Data Type**     |
|----------------|----------------------------------------------------------------|-------------------|
| id             | Unique identifier for the record (auto generated)              | integer pk        |
| name           | Name of the Functional Use                                     | character varying |
| created_at     | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp         |
| updated_at     | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp         |
| deleted_at     | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp         |

Table Name: **sanitaiton_systems**

| **Field Name**    | **Description**                                                | **Data Type** |
|-------------------|----------------------------------------------------------------|---------------|
| id                | Unique identifier for the record (auto generated)              | integer pk    |
| sanitation_system | Type of sanitation system of the building                      | integer       |
| created_at        | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp     |
| updated_at        | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp     |
| deleted_at        | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp     |

Table Name: **structure_types**

| **Field Name** | **Label**      | **Description**                                                | **Data Type**     |
|----------------|----------------|----------------------------------------------------------------|-------------------|
| id             |                | Unique identifier for the record (auto generated)              | integer pk        |
| type           | Structure Type | The name of the construction technique of the building         | character varying |
| created_at     |                | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp         |
| updated_at     |                | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp         |
| deleted_at     |                | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp         |

Table Name: **use_categorys**

| **Field Name**    | **Label**                  | **Description**                                                | **Data Type**                              |
|-------------------|----------------------------|----------------------------------------------------------------|--------------------------------------------|
| id                |                            | Unique identifier for the record (auto generated)              | integer pk                                 |
| name              | Use Categories of Building | The name of the use category                                   | character varying                          |
| functional_use_id | Functional Use of Building | Identifier for the functional use this use category belongs to | integer fk: building_info. functional_uses |
| created_at        |                            | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp                                  |
| updated_at        |                            | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp                                  |
| deleted_at        |                            | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp                                  |

Table Name: **water_sources**

| **Field Name** | **Label**                  | **Description**                                                | **Data Type**     |
|----------------|----------------------------|----------------------------------------------------------------|-------------------|
| id             |                            | Unique identifier for the record (auto generated)              | integer pk        |
| source         | Main Drinking Water Source | The source of water resource                                   | character varying |
| created_at     |                            | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp         |
| updated_at     |                            | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp         |
| deleted_at     |                            | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp         |

Table Name: **wms_links**

| **Field Name** | **Description**               | **Data Type**     |
|----------------|-------------------------------|-------------------|
| name           | The name of the wms link used | character varying |
| link           | The link of the wms           | character varying |

## Building Survey

Table Name: **building_surveys**

| **Field Name** | **Label**                     | **Description**                                                                                           | **Data Type**     |
|----------------|-------------------------------|-----------------------------------------------------------------------------------------------------------|-------------------|
| id             |                               | Unique identifier for surveyed data                                                                       | Integer pk        |
| bin            |                               | Temporary Unique identifier for the building assigned by user                                             | character varying |
| kml            | Building Footprint (KML File) | KML file name                                                                                             | character varying |
| collected_date | Surveyed Date                 | Date when the KML file was collected                                                                      | date              |
| user_id        |                               | Identifier for the user who created the record (Auto Fill, Hidden)                                        | integer           |
| is_enabled     |                               | Boolean indicating whether the record is visible or not, is disabled after the building has been approved | boolean           |
| tax_code       | Tax Code                      | Identifier for the building tax record                                                                    | character varying |
| created_at     |                               | Timestamp when the record was created (Auto Fill, Hidden)                                                 | timestamp         |
| updated_at     |                               | Timestamp when the record was last updated (Auto Fill, Hidden)                                            | timestamp         |
| deleted_at     |                               | Timestamp when the record was deleted (Auto Fill, Hidden)                                                 | timestamp         |

## Low Income Communities

Table Name: **low_income_communities**

| **Field Name**         | **Label**                 | **Description**                                                               | **Data Type**        |
|------------------------|---------------------------|-------------------------------------------------------------------------------|----------------------|
| id                     |                           | Unique identifier for the record (auto generated)                             | integer pk           |
| geom                   | Area                      | Geospatial coordinates of the low income community (represented as a polygon) | geometry             |
| population_total       | Population                | Total population of LIC community                                             | integer              |
| number_of_households   | No. of Households         | Number of Households                                                          | integer              |
| population_male        | Male Population           | Total male population                                                         | integer              |
| population_female      | Female Population         | Total female population                                                       | integer              |
| population_others      | Other Population          | Total other population                                                        | integer              |
| no_of_buildings        | No. of Buildings          | Total building count                                                          | integer              |
| no_of_septic_tank      | No. of Septic Tanks       | Total number of septic tanks                                                  | integer              |
| no_of_holding_tank     | No. of Holding Tanks      | Total number of holding tanks                                                 | integer              |
| no_of_pit              | No. of Pits               | Total number of pits                                                          | integer              |
| no_of_sewer_connection | No. of Sewer Connections  | Total number of sewer connections                                             | integer              |
| community_name         | Community Name            | Name of Community                                                             | Character varying    |
| deleted_at             |                           | Timestamp when the record was deleted (Auto Fill, Hidden)                     | timestamp            |
| created_at             |                           | Timestamp when the record was created (Auto Fill, Hidden)                     | timestamp            |
| updated_at             |                           | Timestamp when the record was last updated (Auto Fill, Hidden)                | timestamp            |


# Fecal Sludge & Septage IMS

Schema Name: **fsm**

The FSM Module uses the following tables:

Data Tables:

-   Containments: stores the containment information.

-   service_providers: stores information about entities offering desludging services.

-   employees: stores employee data associated with the system.

-   desludging_vehicles: contains details about vehicles used for desludging operations.

-   treatment_plants: holds information about treatment plants.

-   treatmentplant_tests: stores records of tests conducted at treatment plants.

-   applications: contains information regarding applications submitted.

-   emptyings: stores data related to emptying operations carried out.

-   sludge_collections: holds details about collections of sludge.

-   feedbacks: contains feedback provided by the users.

-   help_desks: stores information related to help desks available.

## Containment IMS

### Containments

**Data Tables**

Table Name: **containments**

| **Field Name**        | **Label**                       | **Description**                                                                                | **Data Type**                 |
|-----------------------|---------------------------------|------------------------------------------------------------------------------------------------|-------------------------------|
| id                    |                                 | containment                                                                                    | character varying pk          |
| type_id               | Containment Type                | Type of the containment with outlet details                                                    | integer fk: containment_types |
| location              | Containment Location            | Location of the containment (Inside/ Outside building)                                         | character varying             |
| size                  | Containment Volume (m³)         | Volume of the containment in cubic meter                                                       | numeric                       |
| pit_diameter          | Pit Diameter (m)                | Diameter of the pit in meter                                                                   | numeric                       |
| tank_length           | Tank Length (m)                 | Length of the tank in meter                                                                    | numeric                       |
| tank_width            | Tank Width (m)                  | Width of the tank in meter                                                                     | numeric                       |
| depth                 | Tank Depth (m)                  | Depth of the containment in meter                                                              | numeric                       |
| septic_criteria       | Septic Tank Standard Compliance | Boolean value indicating the compliance of septic tank                                         | boolean                       |
| construction_date     | Construction Date               | Containment construction date                                                                  | date                          |
| buildings_served      | Buildings Served                | Number of buildings served by the septic system                                                | integer                       |
| population_served     | Population Served               | Number of populations served by the septic system                                              | integer                       |
| household_served      | Household Served                | Number of households served by the septic system                                               | integer                       |
| emptied_status        | Emptied Status                  | Boolean value indicating the emptying status of the septic system, if emptied or not           | boolean                       |
| last_emptied_date     | Last Emptied Date               | Date of the last time the septic system was emptied                                            | date                          |
| next_emptying_date    | Next Emptying Date              | Date of the next scheduled emptying of the septic system                                       | date                          |
| no_of_times_emptied   | Number of Times Emptied         | Number of times the septic system has been emptied                                             | integer                       |
| geom                  |                                 | Geospatial coordinates of the septic system geometry (represented as a point)                  | geometry                      |
| surveyed_at           |                                 | Date when the septic system was surveyed                                                       | date                          |
| verification_required |                                 | Indicates whether the containment requires further verification after addition into the system | boolean                       |
| user_id               |                                 | Identifier for the user who created the record (Auto Fill, Hidden)                             | integer                       |
| created_at            |                                 | Timestamp when the record was created (Auto Fill, Hidden)                                      | timestamp                     |
| updated_at            |                                 | Timestamp when the record was last updated (Auto Fill, Hidden)                                 | timestamp                     |
| deleted_at            |                                 | Timestamp when the record was deleted (Auto Fill, Hidden)                                      | timestamp                     |

**Lookup Tables**

Table Name: **containment_types**

| **Field Name** | **Description**                                                | **Data Type**     |
|----------------|----------------------------------------------------------------|-------------------|
| id             | Unique identifier for the record (auto generated)              | integer pk        |
| type           | Type of containment with outlet                                | character varying |
| created_at     | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp         |
| updated_at     | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp         |
| deleted_at     | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp         |

## Service Provider IMS

### Service Providers

Table Name: serivce_providers

| **Field Name**   | **Label**                  | **Description**                                                                         | **Data Type**      |
|------------------|----------------------------|-----------------------------------------------------------------------------------------|--------------------|
| id               |                            | Unique identifier for each Service Provider                                             | Integer pk         |
| company_name     | Company Name               | Company Name of the Service Provider                                                    | character varying  |
| ward             | Ward Number                | Ward number where service provider                                                      | Integer            |
| company_location | House Number & Street Name | House address (house number and street name) of the service provider office             | character varying  |
| service_area     | Service Ward(s)            | Wards served by service provider                                                        | character varying  |
| email            | Email                      | Email address of the service provider                                                   | character varying  |
| contact_person   | Contact Person Name        | Name of the company head/ proprietor of the service provider                            | character varying  |
| contact_number   | Contact Person Number      | Contact number of the contact person/ service provider office                           | big integer        |
| contact_gender   | Contact Person Gender      | Gender of contact person                                                                | character varying  |
| geom             |                            | Geospatial coordinates of the location of the service provider (represented as a point) | geometry           |
| status           | Status                     | boolean value that defined the operational Status of Service Provider                   | boolean            |
| created_at       |                            | timestamp when the record was created (Auto Fill, Hidden)                               | Timestamp          |
| updated_at       |                            | Timestamp when the record was last updated (Auto Fill, Hidden)                          | Timestamp          |
| deleted_at       |                            | Timestamp when the record was deleted (Auto Fill, Hidden)                               | Timestamp          |

### Employee Information

Table Name: **employees**

| **Field Name**      | **Label**                        | **Description**                                                             | **Data Type**                |
|---------------------|----------------------------------|-----------------------------------------------------------------------------|------------------------------|
| id                  |                                  | Unique identifier for each employee                                         | integer pk                   |
| service_provider_id | Service Provider Name            | Unique identifier for the service provider the employee works for           | integer fk:service_providers |
| name                | Employee Name                    | Name of the employee                                                        | character varying            |
| gender              | Employee Gender                  | Sex of the employee                                                         | character varying            |
| contact_number      | Employee Contact Number          | Contact Number of the employee                                              | big integer                  |
| dob                 | Date Of Birth                    | Date of birth of the employee                                               | date                         |
| address             | Address                          | Address of the employee                                                     | character varying            |
| social_security_id  | Social Security ID               | Social security ID of employee (if available)                               | character varying            |
| employee_type       | Designation                      | Type of employee                                                            | character varying            |
| year_of_experience  | Working Experience (years)       | Working experience of the employee                                          | integer                      |
| wage                | Monthly Remuneration Of Employee | Monthly wage of the employee                                                | integer                      |
| license_number      | Driving License Number           | License number of employee (if driver)                                      | character varying            |
| license_issue_date  | License Issue Date               | License issued date of employee (if driver)                                 | date                         |
| training_status     | Training Received                | Indicates what training the employee has completed                          | character varying            |
| employment_start    | Job Start Date                   | Start date of the employee's employment                                     | date                         |
| status              | Status                           | Boolean value that indicates if the employee is currently active or not     | boolean                      |
| employment_end      |                                  | End date of the employee's employment (if status is set as not operational) | date                         |
| user_id             |                                  | Identifier for the user who created the record (Auto Fill, Hidden)          | Integer                      |
| created_at          |                                  | Timestamp when the record was created (Auto Fill, Hidden)                   | timestamp                    |
| updated_at          |                                  | Timestamp when the record was last updated (Auto Fill, Hidden)              | timestamp                    |
| deleted_at          |                                  | Timestamp when the record was deleted (Auto Fill, Hidden)                   | timestamp                    |

### Desludging Vehicles Information

Table Name: **desludging_vehicles**

| **Field Name**       | **Label**                     | **Description**                                                                                              | **Data Type**                 |
|----------------------|-------------------------------|--------------------------------------------------------------------------------------------------------------|-------------------------------|
| id                   |                               | A unique identifier for each desludging vehicle.                                                             | integer pk                    |
| license_plate_number | Vehicle License Plate Number  | The license plate number of the desludging vehicle.                                                          | character varying             |
| capacity             | Capacity (m3)                 | The size of the desludging vehicle in m3.                                                                    | numeric                       |
| width                | Width (m)                     | The width of the desludging vehicle in meters.                                                               | numeric                       |
| service_provider_id  | Service Provider              | Unique identifier for the service provider the desludging vehicle is owned by                                | integer fk: service_providers |
| status               | Status                        | Boolean value indicating the status of the desludging vehicle, whether it is operational or non operational. | boolean                       |
| description          | Description                   | A description of the desludging vehicle.                                                                     | character varying             |
| comply_with_maintainance_standards| Comply with Maintenance Standards  | Maintains Status whether desludging vehicle complies with maintenance standards or not.    | boolean                       |
| created_at           |                               | Timestamp when the record was created (Auto Fill, Hidden)                                                    | timestamp                     |
| updated_at           |                               | Timestamp when the record was last updated (Auto Fill, Hidden)                                               | timestamp                     |
| deleted_at           |                               | Timestamp when the record was deleted (Auto Fill, Hidden)                                                    | timestamp                     |

## Treatment Plant IMS

### Treatment Plants

Table Name: treatment_plants

Table Structure

| **Field Name**     | **Label**                           | **Description**                                                      | **Data Type**     |
|--------------------|-------------------------------------|----------------------------------------------------------------------|-------------------|
| id                 |                                     | A unique identifier for each treatment plant                         | integer pk        |
| name               | Name                                | The name of the treatment plant.                                     | character varying |
| location           | Location                            | The location of the treatment plant.                                 | character varying |
| type               | Treatment Plant Type                | The type of Treatment Plant (FSTP, Centralized/ Decentralized WWTP)  | integer           |
| capacity_per_day   | Capacity Per Day (m³)               | The capacity of the treatment plant (m3 per day).                    | numeric           |
| caretaker_name     | Caretaker Name                      | The name of the caretaker of the facility.                           | character varying |
| caretaker \_gender | Caretaker Gender                    | The gender of the caretaker for the facility.                        | character varying |
| caretaker_number   | Caretaker Number                    | The phone number of the caretaker for the facility.                  | big integer       |
| status             | Status                              | The operational facility of the treatment plant                      | boolean           |
| geom               | Click To Set Latitude And Longitude | The geographic coordinates of the facility (represented as a point). | geometry          |
| created_at         |                                     | Timestamp when the record was created (Auto Fill, Hidden)            | timestamp         |
| updated_at         |                                     | Timestamp when the record was last updated (Auto Fill, Hidden)       | timestamp         |
| deleted_at         |                                     | Timestamp when the record was deleted (Auto Fill, Hidden)            | timestamp         |

### Treatment Plant Performance Efficiency Test

Table Name: treatmentplant_tests

| **Field Name**     | **Label**       | **Description**                                                    | **Data Type**                |
|--------------------|-----------------|--------------------------------------------------------------------|------------------------------|
| id                 |                 | Unique identifier for each row.                                    | integer pk                   |
| date               | Sample Date     | Date on which the sample was taken                                 | timestamp without time zone  |
| temperature        | Temperature °C  | Temperature of the sample in degrees Celsius                       | double precision             |
| ph                 | PH              | pH level of the sample                                             | double precision             |
| cod                | COD (mg/l)      | Concentration of Chemical Oxygen Demand in milligrams per liter    | double precision             |
| bod                | BOD (mg/l)      | Concentration of Biochemical Oxygen Demand in milligrams per liter | double precision             |
| tss                | TSS (mg/l)      | Concentration of Total Suspended Solids in milligrams per liter    | double precision             |
| ecoli              | Ecoli           | Quantity of Escherichia coli bacteria in the sample                | integer                      |
| sample_location    | Sample Location | Sample Location ( Influent, Effluent )                             | character varying            |
| remarks            | Remark          | Remarks                                                            | character varying            |
| treatment_plant_id | Treatment Plant | Identifier for the treatment plant.                                | integer fk: treatment_plants |
| user_id            |                 | Identifier for the user who created the record (Auto Fill, Hidden) | integer fk:users             |
| created_at         |                 | Timestamp when the record was created (Auto Fill, Hidden)          | time without time zone       |
| updated_at         |                 | Timestamp when the record was last updated (Auto Fill, Hidden)     | timestamp without time zone  |
| deleted_at         |                 | Timestamp when the record was deleted (Auto Fill, Hidden)          | timestamp without time zone  |

## Emptying Service IMS

### Application

Table Name: applications

| Field name                  | Label                    | description                                                                  | data_type                         |
|-----------------------------|--------------------------|------------------------------------------------------------------------------|-----------------------------------|
| id                          |                          | Unique identifier for each application lodged.                               | integer pk                        |
| application_date            | Application Date         | Date of application lodged                                                   | date                              |
| road_code                   | Road                     | Identifier for the road that the building is connected                       | character varying fk:roadlines    |
| containment_id              |                          | Identifier for the containment connected to the building                     | character varying fk:containments |
| house_number                | House Number             | Unique identifier of the building where the septic tank is located           | character varying  fk: buildings  |
| approved_status             |                          | Boolean indicating application approval status                               | boolean                           |
| emptying_status             | Emptying Status          | Boolean indicating the emptying status                                       | boolean                           |
| feedback_status             | Feedback Status          | Boolean indicating feedback collection status                                | boolean                           |
| sludge_collection_status    | Sludge Collection Status | Boolean indicating sludge collection status                                  | boolean                           |
| customer_name               | Owner Name               | Name of the owner of the building                                            | character varying                 |
| customer_gender             | Owner Gender             | Gender of the owner                                                          | character varying                 |
| customer_contact            | Owner Contact (Phone)    | Contact number of the owner                                                  | big integer                       |
| applicant_name              | Applicant Name           | Name of the applicant, can be same as owner                                  | character varying                 |
| applicant_gender            | Applicant Gender         | Gender of the applicant                                                      | character varying                 |
| applicant_contact           | Applicant Contact Number | Contact number of the applicant                                              | big integer                       |
| ward                        | Ward Number              | Ward number of the building                                                  | integer                           |
| address                     | House Number             | Address of the building                                                      | character varying                 |
| user_id                     |                          | Identifier for the user who created the record (Auto Fill, Hidden)           | integer  fk:users                 |
| service_provider_id         | Service Provider Name    | Identifier for the service provider assigned to provide the emptying service | integer fk:service_providers      |
| proposed_emptying_date      | Proposed Emptying Date   | Date when the septic tank is proposed to be emptied                          | date                              |
| emergency_desludging_status | Emergency Desludging     | Boolean indicating whether the desludging is of high priority                | boolean                           |
| created_at                  |                          | Timestamp when the record was created (Auto Fill, Hidden)                    | timestamp                         |
| updated_at                  |                          | Timestamp when the record was last updated (Auto Fill, Hidden)               | timestamp                         |
| deleted_at                  |                          | Timestamp when the record was deleted (Auto Fill, Hidden)                    | timestamp                         |

### Emptying

Table Name: emptyings

| **Field Name**           | **Label**                       | **Description**                                                              | **Data Type**                    |
|--------------------------|---------------------------------|------------------------------------------------------------------------------|----------------------------------|
| id                       |                                 | Unique identifier for each emptied application                               | integer pk                       |
| application_id           | Application ID                  | Identifier for the application                                               | integer  fk: applications        |
| service_receiver_name    | Service Receiver Name           | Name of person who was present at time of emptying                           | character varying                |
| service_receiver_gender  | Service Receiver Gender         | Gender of service receiver                                                   | character varying                |
| service_receiver_contact | Service Receiver Contact Number | Contact Number of service receiver                                           | big integer                      |
| volume_of_sludge         | Sludge Volume (m3)              | The volume of sludge (m3) emptied                                            | numeric                          |
| emptied_date             | Date                            | Containment emptying date                                                    | date                             |
| emptying_reason          | Reason for Emptying             | The reason for which the containment was emptied                             | character varying                |
| driver                   | Driver Name                     | Identifier for the driver of the desludging vehicle                          | integer  fk: employees           |
| emptier1                 | Emptier 1 Name                  | Identifier for the first person involved in the emptying process             | integer  fk: employees           |
| emptier2                 |  Emptier 2 Name                 | Identifier for the second person involved in the emptying process            | integer  fk: employees           |
| start_time               | Start time                      | The start time of the emptying process                                       | time                             |
| end_time                 | End time                        | The end time of the emptying process                                         | time                             |
| no_of_trips              | No. of trips                    | The number of trips required to empty the sludge                             | integer                          |
| receipt_number           | Receipt number                  | Identifier for the receipt generated after the emptying process              | character varying                |
| treatment_plant_id       |  Disposal Place                 | The place where the sludge was disposed                                      | integer  fk: treatment_plants    |
| house_image              | House Image                     | Identifier for the image of the building from where the sludge was emptied   | character varying                |
| receipt_image            | Receipt Image                   | Identifier for the image of the receipt generated after the emptying process | character varying                |
| total_cost               | Total cost                      | The total cost of the emptying process                                       | numeric                          |
| desludging_vehicle_id    | Desludging Vehicle Number Plate | Identifier for the desludging vehicle used for emptying                      | integer  fk: desludging_vehicles |
| service_provider_id      |                                 | Identifier for the service provider who provided the emptying service        | integer  fk: service_providers   |
| comments                 | Comments (if any)               | Any additional comments that were noted during service delivery              | text                             |
| user_id                  |                                 | Identifier for the user who created the record (Auto Fill, Hidden)           | integer  fk:users                |
| created_at               |                                 | Timestamp when the record was created (Auto Fill, Hidden)                    | timestamp                        |
| updated_at               |                                 | Timestamp when the record was last updated (Auto Fill, Hidden)               | timestamp                        |
| deleted_at               |                                 | Timestamp when the record was deleted (Auto Fill, Hidden)                    | timestamp                        |

### Sludge Collection

Table Name: **sludge_collections**

| Field Name            | Label          | Description                                                         | Data type                       |
|-----------------------|----------------|---------------------------------------------------------------------|---------------------------------|
| id                    |                |  unique identifier for each sludge collection                       | integer pk                      |
| application_id        | Application ID |  ID of the application used to initiate the waste disposal service. | integer fk: applications        |
| treatment_plant_id    |                |  ID of the treatment plant where the sludge was disposed            | integer fk: treatment_plants    |
| service_provider_id   |                |  ID of the service provider who provided the emptying service       | integer fk: service_providers   |
| desludging_vehicle_id |                |  ID of the desludging vehicle used for the sludge disposal          | integer fk: desludging_vehicles |
| volume_of_sludge      | Sludge Volume  |  volume of sludge (in cubic meters) disposed                        | numeric                         |
| entry_time            | Entry Time     |  Entry time of vehicle into treatment plant                         | time                            |
| exit_time             | Exit Time      |  Exit time of vehicle into treatment plant                          | time                            |
| date                  | Date           |  date of the sludge disposal                                        | date                            |
| user_id               |                |  Identifier for the user who created the record (Auto Fill, Hidden) | integer fk: users               |
| created_at            |                | Timestamp when the record was created (Auto Fill, Hidden)           | timestamp                       |
| updated_at            |                | Timestamp when the record was last updated (Auto Fill, Hidden)      | timestamp                       |
| deleted_at            |                | Timestamp when the record was deleted (Auto Fill, Hidden)           | timestamp                       |

### Feedback

Table Name: fsm.feedbacks

| **Field Name**      | **La bel**                                             | **Description**                                                                                         | **Data Type**                 |
|---------------------|--------------------------------------------------------|---------------------------------------------------------------------------------------------------------|-------------------------------|
| id                  |                                                        | A unique identifier for each feedback.                                                                  | integer pk                    |
| application_id      | Application ID                                         | A unique identifier for the application submitted by the customer.                                      | integer fk: applications      |
| fsm_service_quality | Are you satisfied with the Service Quality?            | A boolean value indicating whether the customer is satisfied with the Service Quality.                  | boolean                       |
| wear_ppe            | Did the sanitation workers wear PPE during desludging? | A boolean value indicating whether the service provider is wearing Personal Protective Equipment (PPE). | boolean                       |
| comments            | Comments                                               | Comments provided by the customer.                                                                      | character varying             |
| customer_name       | Applicant Name                                         | The name of the customer who submitted the feedback.                                                    | character varying             |
| customer_gender     | Applicant Gender                                       | The email address of the customer who submitted the feedback.                                           | character varying             |
| customer_number     | Applicant Contact Number                               | The phone number of the customer who submitted the feedback.                                            | big int                       |
| user_id             |                                                        | Identifier for the user who created the record (Auto Fill, Hidden)                                      | integer fk: users             |
| service_provider_id |                                                        |  ID of the service provider who provided the emptying service                                           | integer fk: service_providers |
| created_at          |                                                        | Timestamp when the record was created (Auto Fill, Hidden)                                               | timestamp                     |
| updated_at          |                                                        | Timestamp when the record was last updated (Auto Fill, Hidden)                                          | timestamp                     |
| deleted_at          |                                                        | Timestamp when the record was deleted (Auto Fill, Hidden)                                               | timestamp                     |

### Help Desk

Table Name: **help_desks**

| Field Name          | Label          | Description                                                                     | Data type                     |
|---------------------|----------------|---------------------------------------------------------------------------------|-------------------------------|
| id                  |                | Unique identifier for each help desk                                            | integer pk                    |
| name                | Help Desk Name | Name of the help desk                                                           | character varying             |
| description         | Description    | Additional information about the help desk                                      | character varying             |
| contact_number      | Contact Number | Contact number of the help desk                                                 | big integer                   |
| email               | Email Address  | Email address of the help desk                                                  | character varying             |
| service_provider_id |                | Unique identifier for the service provider associated with the help desk if any | integer fk: service_providers |
| created_at          |                | Timestamp when the record was created (Auto Fill, Hidden)                       | timestamp                     |
| updated_at          |                | Timestamp when the record was last updated (Auto Fill, Hidden)                  | timestamp                     |
| deleted_at          |                | Timestamp when the record was deleted (Auto Fill, Hidden)                       | timestamp                     |

# PT/CT IMS

Schema Name: fsm

The FSM Module uses the following tables:

Data Tables:

-   Toilets: stores the community toilets and public toilets information.

-   ctpt_users: stores the community toilets and public toilet users information.

Relational Tables:

-   build_toilets: relational database that connects buildings and toilets. Foreign Key: bin and toilet_id.

##  Public / Community Toilets

Table name: toilets

| Field Name                          | Label                                         | Description                                                                        | Data type         |
|-------------------------------------|-----------------------------------------------|------------------------------------------------------------------------------------|-------------------|
| id                                  |                                               | Unique identifier for the toilet.                                                  | integer pk        |
| name                                | Toilet Name                                   | Name of the toilet.                                                                | character varying |
| bin                                 | House Number                                  | Bin number associated with the toilet.                                             | character varying |
| type                                | Toilet Type                                   | Type of the toilet, such as "Public Toilet" or "Community Toilet".                     | character varying |
| access_frm_nearest_road             | Distance From Nearest Road (m)                | Distance from the nearest road.                                                    | integer           |
| male_seats                          | No. of Seats For Male Users                   | Number of male seats available in the toilet.                                      | integer           |
| female_seats                        | No. of Seats For Female Users                 | Number of female seats available in the toilet.                                    | integer           |
| male_or_female_facility             | Separate Facility for Male and Female         | Indicates whether the toilet has separate facilities for males and females or not. | boolean           |
| handicap_facility                   | No. of Seats for People with Disability Users | Indicates whether the toilet has facilities for the disabled or not.               | boolean           |
| children_facility                   | No. of Seats for  Children                    | Indicates whether the toilet has facilities for children or not.                   | boolean           |
| sanitary_supplies_disposal_facility | Sanitary Supplies And Disposal Facilities     | Indicates whether the toilet has sanitary supplies and disposal facilities or not. | boolean           |
| owner                               | Owning Institution                            | Owning institution of the toilet.                                                  | character varying |
| operator_or_maintainer              | Operate And Maintained By                     | Operator or maintainer of the toilet.                                              | character varying |
| status                              | Status                                        | Boolean indicating operational status of toilet                                    | boolean           |
| indicative_sign                     | Presence of Indicative Sign                   | Indicates whether the toilet has an indicative sign or not.                        | boolean           |
| fee_collected                       | Uses Fee Collection                           | Indicates whether the fee is collected for using the toilet.                       | boolean           |
| caretaker_name                      | Caretaker Name                                | Caretaker of the toilet.                                                           | character varying |
| caretaker_contact_number            | Caretaker Contact                             | Contact number of the Caretaker of the toilet.                                     | bigint            |
| ward                                | Ward                                          | Ward number where the toilet is located.                                           | integer           |
| geom                                |                                               | Geometric location of the toilet (represented as a point).                         | geometry          |
| no_of_hh_connected                  | No. of Households Served                      | No. of household served                                                            | integer           |
| no_of_male_users                    | No. of Male Users                             | No. of male users                                                                  | integer           |
| no_of_female_users                  | No. of Female Users                           | No. of female users                                                                | integer           |
| no_of_children_users                | No. of Children Users                         | No. of children users                                                              | integer           |
| no_of_pwd_users                     | No. of People With Disability Users           | No. of People With Disability Users                                                | integer           |
| total_no_of_toilets                 | Total Number of Seats                         | Total number of seats                                                              | integer           |
| amount_of_fee_collected             | Uses Fee Rate                                 | Uses Fee Rate                                                                      | numeric           |
| frequency_of_fee_collected          | Frequency of Fee Collection                   | Frequency of Fee Collection                                                        | character varying |
| pwd_seats                           | No. of Seats for People with Disability Users | No. of Seats for People with Disability Users                                      | integer           |
| caretaker_gender                    | Caretaker Gender                              | Gender of Caretaker                                                                | character varying |
| location_name                       | Location Name                                 | Location Name                                                                      | character varying |
| total_no_of_urinals                 | Total Number of Urinals                       | Total Number of Urinals                                                            | integer           |
| separate_facility_with_universal_design| Adherence with Universal Design Principles | Adherence with Universal Design Principles                                         | boolean           |
| owning_institution_name             | Name of Owning Institution                    | Name of Owning Institution                                                         | character varying |
| operator_or_maintainer_name         | Name of Operate and Maintained by             | Name of Operate and Maintained by                                                  | character varying |
| created_at                          |                                               | Timestamp when the record was created (Auto Fill, Hidden)                          | timestamp         |
| updated_at                          |                                               | Timestamp when the record was last updated (Auto Fill, Hidden)                     | timestamp         |
| deleted_at                          |                                               | Timestamp when the record was deleted (Auto Fill, Hidden)                          | timestamp         |

## PT Users Log

Table name: **ctpt_users**

| **Field Name** | **Label**                  | **Description**                                                    | **Data type**       |
|----------------|----------------------------|--------------------------------------------------------------------|---------------------|
| id             |                            | Unique identifier for each CTPT data                               | Integer pk          |
| date           | Date                       | The date when the data was collected.                              | Integer             |
| toilet_id      | Toilet Name                | Unique identifier for the toilet of which the data was collected.  | Integer fk: toilets |
| no_male_user   | No. of Male Users (daily)  | Number of male users who used the toilet daily.                   | Integer             |
| no_female_user | No. of Female Users (daily)| Number of female users who used the toilet daily.                 | Integer             |
| created_at     |                            | Timestamp when the record was created (Auto Fill, Hidden)          | timestamp           |
| updated_at     |                            | Timestamp when the record was last updated (Auto Fill, Hidden)     | timestamp           |
| deleted_at     |                            | Timestamp when the record was deleted (Auto Fill, Hidden)          | timestamp           |

**Relational Tables**

Table Name: **build_toilets**

| **Field Name** | **Description**                                                | **Data Type**                                 |
|----------------|----------------------------------------------------------------|-----------------------------------------------|
| id             | Unique identifier for the record (auto generated)              | integer pk                                    |
| bin            | Unique identifier for the building                             | character varying fk: building_info.buildings |
| toilet_id      | Identifier for the toilet that the building is connected to    | character varying fk: fsm.toilets             |
| created_at     | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp                                     |
| updated_at     | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp                                     |
| deleted_at     | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp                                     |

# CWIS IMS

## CWIS Generator

Schema Name: **cwis**

The CWIS IMS Module uses the following tables:

**Data Tables:**

-   data_sources: stores with information related indicators, its data types and more.

-   data_cwis: stores yearly information related indicators and its value generated from the system or user input.

Table name: data_sources

| **Field Name**      | **Description**                                                           | **Data type**     |
|---------------------|---------------------------------------------------------------------------|-------------------|
| id                  | Unique identifier for the record (auto generated)                         | Integer pk        |
| category_id         | Unique Identifier for category.                                           | integer           |
| sub_category_id     | Unique Identifier for subcategory.                                        | integer           |
| parameter_id        | Unique Identifier for parameter.                                          | integer           |
| assmntmtrc_dtpnt    | Description of each identifier,                                           | text              |
| unit                | Unit of the CWIS data generated.                                          | character varying |
| sym_no              | Sequence no based on category, sub category and parameter id.             | integer           |
| category_title      | A title to be displayed for the indicator category.                       | character varying |
| sub_category_title  | A title to be displayed for the indicator subcategory.                    | character varying |
| parameter_title     | A title to be displayed for the indicator parameter.                      | character varying |
| co_cf               | CWIS outcome or CWIS function                                             | character varying |
| data_type           | Type of data validation                                                   | ARRAY             |
| heading             | A heading to be displayed for the indicator.                              | character varying |
| label               | A label to be displayed for the indicator.                                | character varying |
| indicator_code      | A unique identifier for the indicator generated.                          | character varying |
| parent_id           | A key that denotes that the indicator is sub indicator for the parent id. | integer           |
| remark              | Remarks                                                                   | character varying |
| is_system_generated | Boolean value for either it is generated by the system or not.            | character varying |
| data_periodicity    | Periodicity of Data generation ( Yearwise \| Aggregate)                   | character varying |
| formula             | Formula used for auto generation                                          | text              |
| answer_type         | Answer Type ( Quantitative \| Qualitative)                                | character varying |

Table name: data_cwis

| **Field Name**      | **Description**                                                           | **Data type**                  |
|---------------------|---------------------------------------------------------------------------|--------------------------------|
| id                  | Unique identifier for the record (auto generated)                         | Integer pk                     |
| sub_category_id     | Unique Identifier for subcategory.                                        | integer                        |
| parameter_id        | Unique Identifier for parameter.                                          | integer                        |
| assmntmtrc_dtpnt    | Description of each identifier,                                           | text                           |
| unit                | Unit of the CWIS data generated.                                          | character varying              |
| co_cf               | CWIS outcome or CWIS function                                             | character varying              |
| data_value          | Actual value of the cwis Indicator of each year.                          | text                           |
| data_type           | Type of data validation                                                   | ARRAY                          |
| sym_no              | Sequence no based on category, sub category and parameter id.             | Integer                        |
| year                | Year of data CWIS data generated.                                         | integer                        |
| source_id           | A unique identifier referenced to cwis.data_sources.                      | Integer fk: cwis. data_sources |
| heading             | A heading to be displayed for the indicator.                              | character varying              |
| label               | A label to be displayed for the indicator.                                | character varying              |
| indicator_code      | A unique identifier for the indicator generated.                          | character varying              |
| parent_id           | A key that denotes that the indicator is sub indicator for the parent id. | Integer fk: cwis. data_sources |
| remark              | Remarks                                                                   | character varying              |
| is_system_generated | Boolean value for either it is generated by the system or not.            | character varying              |
| data_periodicity    | Periodicity of Data generation ( Yearwise \| Aggregate)                   | character varying              |
| formula             | Formula used for auto generation                                          | text                           |
| answer_type         | Answer Type ( Quantitative \| Qualitative)                                | character varying              |
| created_at          | Timestamp when the record was created (Auto Fill, Hidden)                 | timestamp with time zone       |
| updated_at          | Timestamp when the record was last updated (Auto Fill, Hidden)            | timestamp with time zone       |
| deleted_at          | Timestamp when the record was deleted (Auto Fill, Hidden)                 | timestamp with time zone       |

## KPI Target

Schema Name: **fsm**

The Utility IMS Module uses the following tables:

Data Tables

-   kpi_targets: stores with information related to KPI targets.

Table name: kpi_targets

| **Field Name** | **Description**                                                | **Data type**            |
|----------------|----------------------------------------------------------------|--------------------------|
| id             | A unique identifier for each record.                           | integer pk               |
| Indicator_id   | A unique identifier for each indicator.                        | integer                  |
| year           | Year for which KPI target is fixed.                            | integer                  |
| target         | Target value of KPI for the year.                              | integer                  |
| created_at     | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp with time zone |
| updated_at     | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp with time zone |
| deleted_at     | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp with time zone |

# Utility IMS

Schema Name: **utility_info**

The Utility IMS Module uses the following tables:

Data Tables

-   Roads: stores with information related to road infrastructure.

-   Sewers: manages data associated with sewer systems.

-   water_supplys: contains details about water supply networks.

-   Drains: stores information regarding drainage systems.

## Road Network Information

Table name: **roads**

| **Field Name** | **Label**                     | **Description**                                                            | **Data Type**        |
|----------------|-------------------------------|----------------------------------------------------------------------------|----------------------|
| code           | Code                          | A unique identifier for each road. (City endorsed road code, if available) | character varying pk |
| name           | Road Name                     | The name of the road.                                                      | character varying    |
| right_of_way   | Right of Way                  | The width of the road (right of way) in meters.                            | numeric              |
| hierarchy      | Hierarchy                     | The type of road based on its network.                                     | character varying    |
| surface_type   | Surface Type                  | The type of surface on the road.                                           | character varying    |
| length         | Road Length                   | The length of the road in meters.                                          | numeric              |
| carrying_width | Carriageway Width of the Road | The carriageway width of the road that can be used for traffic in meters.  | numeric              |
| geom           |                               | The geometric shape or location of the road (represented as a linestring). | geometry             |
| created_at     |                               | Timestamp when the record was created (Auto Fill, Hidden)                  | timestamp            |
| updated_at     |                               | Timestamp when the record was last updated (Auto Fill, Hidden)             | timestamp            |
| deleted_at     |                               | Timestamp when the record was deleted (Auto Fill, Hidden)                  | timestamp            |

## Sewer Network Information

Table name: **sewers**

| **Field Name**    | **Label**                          | **Description**                                                                   | **Type**                   |
|-------------------|------------------------------------|-----------------------------------------------------------------------------------|----------------------------|
| code              | Code                               | Unique identifier for the sewer section (City endorsed sewer code, if available). | Character varying pk       |
| road_code         | Corresponding Road Code            | Corresponding road code.                                                          | character varying fk:roads |
| length            | Length                             | Length of the sewer section in meters                                             | float                      |
| location          | Location                           | Location of the sewer section (Middle, Left or Right side of the road)            | character varying          |
| diameter          | Diameter                           | Diameter of the sewer section in mm.                                              | numeric                    |
| tp_id             | Treatment Plant ID                 | Corresponding treatment plant.                                                    |    integer                 |
| geom              |                                    | Geometric information for the sewer section (represented as a linestring).        | geometry                   |
| created_at        |                                    | Timestamp when the record was created                                             | timestamp                  |
| updated_at        |                                    | Timestamp when the record was last updated                                        | timestamp                  |
| deleted_at        |                                    | Timestamp when the record was deleted (if applicable)                             | timestamp                  |

## Water Supply Network Information

Table name: **water_supplys**

| **Field Name** | **Label**               | **Description**                                                                             | **Data Type**              |
|----------------|-------------------------|---------------------------------------------------------------------------------------------|----------------------------|
| code           | Code                    | A unique identifier for each water supply pipeline (City endorsed sewer code, if available) | character varying pk       |
| road_code      | Corresponding Road Code | Corresponding road code.                                                                    | character varying fk:roads |
| diameter       | Diameter                | The diameter of the water supply pipe in mm                                                 | numeric                    |
| length         | Length                  | The length of the water supply pipe (in meters)                                             | integer                    |
| geom           |                         | The geometry of the water supply pipe (represented as a linestring).                        | geometry                   |
| project_name   | City Water Supply       | Name of the Project                                                                         | character varying          |
| type           | Type                    | Type of the pipeline (Main \| Secondary \| Distribution)                                    | character varying          |
| material_type  | Material Type           | Type of the pipe material ( HDPE \| GI \| Others)                                           | character varying          |
| created_at     |                         | Timestamp when the record was created (Auto Fill, Hidden)                                   | timestamp                  |
| updated_at     |                         | Timestamp when the record was last updated (Auto Fill, Hidden)                              | timestamp                  |
| deleted_at     |                         | Timestamp when the record was deleted (Auto Fill, Hidden)                                   | timestamp                  |

## Drain Network Information

Table name: **drains**

| **Field Name** | **Label**               | **Description**                                                | **Data Type**              |
|----------------|-------------------------|----------------------------------------------------------------|----------------------------|
| code           | Code                    | A unique identifier for each drain                             | character varying pk       |
| road_code      | Corresponding Road Code | Corresponding road code.                                       | character varying fk:roads |
| cover_type     | Cover Type              | The type of the drain cover (Open, Closed, Unknown)            | character varying          |
| surface_type   | Surface Type            | The type of the surface lining (Lined, Unlined, Unknown)       | character varying          |
| size           | Size                    | The size of the drain in mm                                    |  numeric                   |
| length         | Length                  | The length of the drain (in meters)                            | numeric  
| tp_id          | Treatment Plant ID      | Corresponding treatment plant.                                 | integer
| geom           |                         | The geometry of the drain (represented as a linestring).       | geometry                   |
| created_at     |                         | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp                  |
| updated_at     |                         | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp                  |
| deleted_at     |                         | Timestamp when the record was deleted (Auto Fill, Hidden)         timestamp                  |

# Property Tax Collection IMS

Schema Name: **taxpayment_info**

The Property Tax Collection IMS Module uses the following tables:

Data Tables

-   tax_payment_status: Stores Tax Payment records after computation with match status.

-   tax_payments: Stores Tax Payment records temporarily during import.

Lookup Tables

-   due_years: Reference table for due year classification.

Table name: **tax_payment_status**

| **Field Name**         | **Label**         | **Description**                                                                | **Data Type**        |
|------------------------|-------------------|--------------------------------------------------------------------------------|----------------------|
| tax_code               | Tax Code          | Unique identifier for each tax record.                                         | Character varying pk |
| tax_payment_id         |                   | Tax payment identification number                                              | Integer              |
| bin                    |                   | building identification number                                                 | Character varying    |
| ward                   | Ward              | Ward number where the property is located                                      | Integer              |
| building_associated_to |                   | Auxiliary Building number or ID associated to the property                     | Character varying    |
| owner_name             | Owner Name        | Name of the property owner                                                     | Character varying    |
| owner_gender           | Owner Gender      | Gender of the property owner                                                   | Character varying    |
| owner_contact          | Owner Contact     | Phone number of the property owner                                             | Big Integer          |
| last_payment_date      | Last Payment Date | Date when the last payment was made                                            | Date                 |
| due_year               | Due Year          | Year for which the payment is due                                              | Integer              |
| match                  |                   | Boolean value indicating if building match with existing building data or not. | Boolean              |
| geom                   |                   | Geospatial data of the building (represented as a polygon).                    | Geometry             |
| created_at             |                   | Timestamp when the record was created (Auto Fill, Hidden)                      | timestamp            |
| updated_at             |                   | Timestamp when the record was last updated (Auto Fill, Hidden)                 | timestamp            |
| deleted_at             |                   | Timestamp when the record was deleted (Auto Fill, Hidden)                      | timestamp            |

# Water Supply ISS

Schema Name: watersupply_info

The Water Supply Support System Module uses the following tables:

Data Tables

-   watersupply_payment_status: Stores Watersupply Payment records after computation with match status.

-   watersupply_payments: Stores Watersupply Payment records temporarily during import.

Lookup Tables

-   due_years: Reference table for due year classification.

Table name: **watersupply_payment_status**

| **Field Name**         | **Label**         | **Description**                                                             | **Data Type**        |
|------------------------|-------------------|-----------------------------------------------------------------------------|----------------------|
| tax_code               | Tax Code          | Unique identifier for each tax record.                                      | Character varying pk |
| watersupply_payment_id |                   | Unique identifier for the water supply payment record                       | Big Integer          |
| bin                    |                   | Unique identifier for the building                                          | Character varying    |
| ward                   | Ward              | Ward number of the building                                                 | Integer              |
| building_associated_to |                   | Description of the building                                                 | Character varying    |
| owner_name             | Owner Name        | Name of the owner of the building                                           | Character varying    |
| owner_gender           | Owner Gender      | Gender of the owner of the building                                         | Character varying    |
| owner_contact          | Owner Contact     | Contact number of the owner of the building                                 | Big Integer          |
| last_payment_date      | Last Payment Date | Date of last payment made by the owner for taxes or water supply            | Date                 |
| due_year               | Years Due         | Year for which the tax or water supply payment is due                       | Integer              |
| match                  |                   | Status of match for building that match with existing building data or not. | Boolean              |
| geom                   |                   | Geometric information of the building (represented as a polygon).           | Geometry             |
| created_at             |                   | Timestamp when the record was created (Auto Fill, Hidden)                   | Timestamp            |
| updated_at             |                   | Timestamp when the record was last updated (Auto Fill, Hidden)              | Timestamp            |
| deleted_at             |                   | Timestamp when the record was deleted (Auto Fill, Hidden)                   | Timestamp            |

# Urban Management DSS

## Map Feature

Schema Name: utility_info

## Point of interests

| **column_name** | **Description**                                                                         | **data_type**               |
|-----------------|-----------------------------------------------------------------------------------------|-----------------------------|
| id              | Unique identifier for each point of interest                                            | bigint                      |
| geom            | Geometric information of the location of the point of interest (represented as a point) | geometry                    |
| name            | Name of Point of Interest                                                               | character varying           |
| ward            | Ward the point of interest is located                                                   | integer                     |
| created_at      | Timestamp when the record was created (Auto Fill, Hidden)                               | timestamp without time zone |
| updated_at      | Timestamp when the record was last updated (Auto Fill, Hidden)                          | timestamp without time zone |
| deleted_at      | Timestamp when the record was deleted (Auto Fill, Hidden)                               | timestamp without time zone |
| gid             | Unique identifier for each point of interest                                            | integer                     |

## Sewerareas

| column_name | Description                                                                        | data_type                   |
|-------------|------------------------------------------------------------------------------------|-----------------------------|
| id          | Unique identifier for each point of interest                                       | integer                     |
| area        | Total Area covered in m2                                                           | numeric                     |
| type        | Type of sewer area ( if it is sewer area or non-sewer area)                        | character varying           |
| geom        | Geometric information of the location of the sewerareas (represented as a polygon) | geometry                    |
| created_at  | Timestamp when the record was created (Auto Fill, Hidden)                          | timestamp without time zone |
| updated_at  | Timestamp when the record was last updated (Auto Fill, Hidden)                     | timestamp without time zone |
| deleted_at  | Timestamp when the record was deleted (Auto Fill, Hidden)                          | timestamp without time zone |

# Public Health ISS

Schema Name: **public_health**

The Public Health ISS Module uses the following tables:

Data Tables

-   waterborne_hotspots: Stores information about waterborne hotspots.

-   yearly_waterborne_cases: Stores information about yearly waterborne cases. (No of cases on a yearly basis)

## Waterborne Hotspot

Table name: **waterborne_hotspots**

| Field Name        | Label            | Description                                                                           | Data Type         |
|-------------------|------------------|---------------------------------------------------------------------------------------|-------------------|
| id                |                  | Unique identifier for each waterborne hotspot                                         | integer pk        |
| disease           | Infected Disease | Hotspot disease name (enum used) Cholera Diarrhea Dysentery Hepatitis A Typhoid Polio | integer           |
| hotspot_location  | Hotspot Location | Hotspot location of the disease                                                       | character varying |
| date              | Date             | Date when the hotspot information was collected                                       | date              |
| ward              |                  | Ward number where the cases occurred                                                  | integer           |
| no_of_cases       | No of Cases      | Number of cases reported                                                              | integer           |
| male_cases        | Male             | Number of male cases reported                                                         | integer           |
| female_cases      | Female           | Number of female cases reported                                                       | integer           |
| other_cases       | Other            | Number of other cases reported                                                        | integer           |
| no_of_fatalities  | No of Fatalities | Number of fatalities reported                                                         | integer           |
| female_fatalities | Female           | Number of female fatalities reported                                                  | integer           |
| male_fatalities   | Male             | Number of male fatalities reported                                                    | integer           |
| other_fatalities  | Other            | Number of other fatalities reported                                                   | integer           |
| notes             | Notes            | Additional notes or information                                                       | character varying |
| geom              | Hotspot Area     | Geometric information of the location of the cases (represented as a polygon)         | geometry          |
| created_at        |                  | Timestamp when the record was created (Auto Fill, Hidden)                             | timestamp         |
| updated_at        |                  | Timestamp when the record was last updated (Auto Fill, Hidden)                        | timestamp         |
| deleted_at        |                  | Timestamp when the record was deleted (Auto Fill, Hidden)                             | timestamp         |

## Yearly Waterborne Cases Information

Table name: **yearly_waterborne_cases**

| Field Name              | Label            | Description                                                                           | Data Type         |
|-------------------------|------------------|---------------------------------------------------------------------------------------|-------------------|
| id                      |                  | Unique identifier for each yearly waterborne case                                     | integer pk        |
| infected_disease        | Infected Disease | Hotspot disease name (enum used) Cholera Diarrhea Dysentery Hepatitis A Typhoid Polio | integer           |
| year                    | Year             | The year when the disease cases were identified                                       | integer           |
| ward                    |                  | Ward number where the cases occurred                                                  | integer           |
| total_no_of_cases       | No of Cases      | Number of cases reported in that year                                                 | integer           |
| male_cases              | Male             | Number of male cases reported in that year                                            | integer           |
| female_cases            | Female           | Number of female cases reported in that year                                          | integer           |
| other_cases             | Other            | Number of other cases reported in that year                                           | integer           |
| total_no_of_fatalities  | No of Fatalities | Number of fatalities reported in that year                                            | integer           |
| female_fatalities       | Female           | Number of female fatalities reported in that year                                     | integer           |
| male_fatalities         | Male             | Number of male fatalities reported in that year                                       | integer           |
| other_fatalities        | Other            | Number of other fatalities reported in that year                                      | integer           |
| notes                   | Notes            | Additional notes or information                                                       | character varying |
| created_at              |                  | Timestamp when the record was created (Auto Fill, Hidden)                             | timestamp         |
| updated_at              |                  | Timestamp when the record was last updated (Auto Fill, Hidden)                        | timestamp         |
| deleted_at              |                  | Timestamp when the record was deleted (Auto Fill, Hidden)                             | timestamp         |

## Water Sample Information

Table name: **water_samples**

| Field Name              | Label            | Description                                                                           | Data Type         |
|-------------------------|------------------|---------------------------------------------------------------------------------------|-------------------|
| id                      |                  | Unique identifier                                                                     | integer           |
| date                    | Sample Date      | Date on which the water sample was taken                                              | Date              |
| sample_location         | Sample Location  | Water Sample Location                                                                 | character varying |
| no_of_samples_taken     |  No of Water Samples Taken                | Date on which the water sample was taken                     | integer           |
| water_coliform_test_result | Water Coliform Test Result   | The result received after the Coliform Test                            | character varying (8) with Check Constraint (only accepts values 'positive' or 'negative')           |
| geom               |                       | The geographic coordinates of the facility (represented as a point).                  | geometry          |
| created_at         |                                     | Timestamp when the record was created (Auto Fill, Hidden)               | timestamp         |
| updated_at         |                                     | Timestamp when the record was last updated (Auto Fill, Hidden)          | timestamp         |
| deleted_at         |                                     | Timestamp when the record was deleted (Auto Fill, Hidden)               | timestamp         |

# Settings

## Performance Efficiency Standards

Schema Name: **public**

Table name: treatment_plant_performance_efficiency_test_settings

| **Field Name** | **Label**                   | **Description**                                                | **Data Type**               |
|----------------|-----------------------------|----------------------------------------------------------------|-----------------------------|
| id             |                             | Unique identifier                                              | integer                     |
| tss_standard   | TSS Standard (mg/I)         | Total suspended solids (TSS) Standard Value (mg/l)             | integer                     |
| ecoli_standard | ECOLI Standard (CFU/100 mL) | Ecoli Standard (CFU/100 mL)                                    | integer                     |
| ph_min         | PH Minimum                  | Minimum Ph value                                               | integer                     |
| ph_max         | PH Maximum                  | Maximum Ph value                                               | integer                     |
| bod_standard   | BOD Standard (mg/l)         | Biochemical oxygen demand (BOD) standard (mg/l)                | integer                     |
| created_at     |                             | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp without time zone |
| updated_at     |                             | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp without time zone |
| deleted_at     |                             | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp without time zone |

## CWIS Setting

Schema Name: **public**

Table name: **site_settings**

This table holds the value that is input from the editable form and is used in CWIS models for CWIS dashboard.

| **Field Name** | **Description**                                                | **Data Type**               |
|----------------|----------------------------------------------------------------|-----------------------------|
| id             | Unique identifier                                              | integer                     |
| name           | Variable name                                                  | character varying           |
| value          | Value input from the editable form                             | character varying           |
| category       | Category of site settings (cwis_setting)                       | character varying           |
| created_at     | Timestamp when the record was last updated (Auto Fill, Hidden) | timestamp without time zone |
| updated_at     | Timestamp when the record was deleted (Auto Fill, Hidden)      | timestamp without time zone |
| deleted_at     | Timestamp when the record was created (Auto Fill, Hidden)      | timestamp without time zone |

## User Information Management

Schema Name: **auth**

The User Information Management Module uses the following tables:

Data Tables

-   failed_jobs: Stores information about background job failures.

-   password_resets: Stores data for password reset tokens, facilitating the process of resetting user passwords.

-   personal_access_tokens: Manages personal access tokens used for authentication in API requests.

-   users: A data table that stores information about users, including usernames, passwords, and other relevant details.

Lookup Tables

-   roles: Acts as a lookup table, storing different roles that can be assigned to users.

-   permissions: Acts as a lookup table, storing different permissions that can be assigned to users or roles.

Relational Tables

-   model_has_permissions: Represents the association between models (e.g., users) and permissions in a many-to-many relationship.

-   model_has_roles: Represents the association between models (e.g., users) and roles in a many-to-many relationship.

-   role_has_permissions: Represents the association between roles and permissions in a many-to-many relationship.

### User

Table name: **users**

| **Field Name**      | **Label**        | **Description**                                                | **Data Type**                 |
|---------------------|------------------|----------------------------------------------------------------|-------------------------------|
| id                  |                  | Unique identifier for each user                                | Integer pk                    |
| name                | Full Name        | Name of the user                                               | Character varying             |
| gender              | Gender           | Gender of the user                                             | Character varying             |
| username            | Username         | Username of the user                                           | Character varying             |
| email               | Email            | Email address of the user                                      | Character varying             |
| password            | Password         | Encrypted Password of the user                                 | Character varying             |
| remember_token      |                  | Token used to remember the user (optional)                     | Character varying             |
| treatment_plant_id  | Treatment Plant  | Identifier for the user's treatment plant (optional)           | Integer fk: treatment_plants  |
| help_desk_id        | Help Desk        | Identifier for the user's help desk (optional)                 | Integer fk: help_desks        |
| service_provider_id | Service Provider | Identifier for the user's service provider (optional)          | Integer fk: service_providers |
| transfer_station_id |                  | Identifier for the user's transfer station (optional)          | Big Integer                   |
| landfill_site_id    |                  | Identifier for the user's landfill site (optional)             | Big Integer                   |
| user_type           | User Type        | Type of user, such as "Municipality" or "Service Provider"     | character varying             |
| status              | Status           | Status of the user, such as "Active" or "Inactive"             | Integer                       |
| created_at          |                  | Timestamp when the record was last updated (Auto Fill, Hidden) | Timestamp                     |
| updated_at          |                  | Timestamp when the record was deleted (Auto Fill, Hidden)      | Timestamp                     |
| deleted_at          |                  | Timestamp when the record was created (Auto Fill, Hidden)      | Timestamp                     |

### Roles

Table name: **roles**

| **Field Name** | **Label** | **Description**                                                                      | **Data Type**               |
|----------------|-----------|--------------------------------------------------------------------------------------|-----------------------------|
| id             |           | Unique identifier for each role.                                                     | bigint                      |
| name           | Name      | Descriptive name for the role                                                        | character varying           |
| guard_name     |           | Represents the authentication guard (e.g., "web" or "api") associated with the role. | character varying           |
| created_at     |           | Timestamp when the record was last updated (Auto Fill, Hidden)                       | timestamp without time zone |
| updated_at     |           | Timestamp when the record was deleted (Auto Fill, Hidden)                            | timestamp without time zone |

### Permissions

Table name: **permissions**

| **Field Name** | **Description**                                                                      | **Data Type**               |
|----------------|--------------------------------------------------------------------------------------|-----------------------------|
| id             | Unique identifier for each role.                                                     | bigint                      |
| name           | Descriptive name for the role                                                        | character varying           |
| group          | Group of Permissions based on Modules of IMIS.                                       | character varying           |
| type           | Type of permission (eg Add View Delete etc)                                          | character varying           |
| guard_name     | Represents the authentication guard (e.g., "web" or "api") associated with the role. | character varying           |
| created_at     | Timestamp when the record was last updated (Auto Fill, Hidden)                       | timestamp without time zone |
| updated_at     | Timestamp when the record was deleted (Auto Fill, Hidden)                            | timestamp without time zone |

Data Tables

Table name: failed_jobs

| **Field Name** | **Description**                  | **Data Type**               |
|----------------|----------------------------------|-----------------------------|
| id             | Unique identifier                | Bigint pk                   |
| uuid           |                                  | character varying           |
| connection     | Connection name                  | text                        |
| queue          | Queue name                       | text                        |
| payload        | Serialized job payload           | text                        |
| exception      | Serialized exception information | text                        |
| failed_at      | Timestamp of failure             | timestamp without time zone |

Table name: **password_resets**

| **Field Name** | **Description**       | **Data Type**               |
|----------------|-----------------------|-----------------------------|
| email          | User's email address  | character varying pk        |
| token          | Password reset token  | character varying           |
| created_at     | Timestamp of creation | timestamp without time zone |

Table name: **personal_access_tokens**

| **Field Name** | **Description**                     | **Data Type**               |
|----------------|-------------------------------------|-----------------------------|
| id             | Unique identifier                   | Bigint pk                   |
| tokenable_type | Polymorphic type of tokenable       | character varying           |
| tokenable_id   | Polymorphic identifier of tokenable | bigint                      |
| name           | Name of the personal access token   | character varying           |
| token          | Personal access token               | character varying           |
| abilities      | Serialized token abilities          | text                        |
| last_used_at   | Timestamp of last usage             | timestamp without time zone |
| created_at     | Timestamp of creation               | timestamp without time zone |
| updated_at     | Timestamp of last update            | timestamp without time zone |

Relational Tables

Table name: model_has_permissions

| **Field Name** | **Description**                           | **Data Type**               |
|----------------|-------------------------------------------|-----------------------------|
| permission_id  | Foreign key referencing permissions table | Bigint fk: auth.permissions |
| model_type     | Type of model / users                     | character varying           |
| model_id       | Foreign key referencing users table       | Bigint fk: auth.users       |

Table name: **model_has_roles**

| **Field Name** | **Description**                     | **Data Type**         |
|----------------|-------------------------------------|-----------------------|
| role_id        | Foreign key referencing roles table | Bigint fk: auth.roles |
| model_type     | Type of model / users               | character varying     |
| model_id       | Foreign key referencing users table | Bigint fk: auth.users |

Table name: **role_has_permissions**

| **Field Name** | **Description**                           | **Data Type**               |
|----------------|-------------------------------------------|-----------------------------|
| permission_id  | Foreign key referencing permissions table | Bigint fk: auth.permissions |
| role_id        | Foreign key referencing roles table       | Bigint fk: auth.roles       |

# Other tables & views

## Wards

| **Field Name**                               | **Description**                                                                               | **Data Type**               |
|----------------------------------------------|-----------------------------------------------------------------------------------------------|-----------------------------|
| ward                                         | Unique identifier for each Ward.                                                              | integer                     |
| geom                                         | The geometry of the ward (represented as a polygon).                                          | USER-DEFINED                |
| area                                         | Area of the ward polygon in m2                                                                | double precision            |
| no_build                                     | Summary count of no of buildings within ward geom                                             | bigint                      |
| no_rcc_framed                                | Summary count of no of rcc framed buildings within ward geom                                  | bigint                      |
| no_wooden_mud                                | Summary count of no of wooden buildings within ward geom                                      | bigint                      |
| no_load_bearing                              | Summary count of no of load bearing buildings within ward geom                                | bigint                      |
| no_cgi_sheet                                 | Summary count of no of cgi sheet buildings within ward geom                                   | bigint                      |
| no_build_directly_to_sewerage_network        | Summary count of no of building directly connected to sewer network within ward geom          | integer                     |
| no_contain                                   | Summary count of no of containments within ward geom                                          | bigint                      |
| no_pit_holding_tank                          | Summary count of no of pit/holding tanks within ward geom                                     | bigint                      |
| no_septic_tank                               | Summary count of no of septic tanks within ward geom                                          | bigint                      |
| total_rdlen                                  | Total length of roads within ward geom                                                        | double precision            |
| bldgtaxpdprprtn                              | Building to tax payment proportion within ward geom                                           | double precision            |
| wtrpmntprprtn                                | Building to water supply payment proportion within ward geom                                  | double precision            |
| no_popsrv                                    | Total no of population served within ward geom                                                | bigint                      |
| no_hhsrv                                     | Total no of household served within ward geom                                                 | bigint                      |
| no_emptying                                  | Total count of no of emptying of containments within ward geom                                | bigint                      |
| created_at                                   | Timestamp when the record was last created (Auto Fill, Hidden)                                | timestamp without time zone |
| deleted_at                                   | Timestamp when the record was deleted (Auto Fill, Hidden)                                     | timestamp without time zone |
| modified_at                                  | Timestamp when the record was modified (Auto Fill, Hidden)                                    | timestamp without time zone |
| updated_at                                   | Timestamp when the record was last updated (Auto Fill, Hidden)                                | timestamp without time zone |


## Grids

|**Field Name**                                | **Description**                                                                               | **Data Type**               |
|----------------------------------------------|-----------------------------------------------------------------------------------------------|-----------------------------|
| id                                           | Unique identifier for each Grid.                                                              | integer                     |
| geom                                         | The geometry of the grid (represented as a polygon).                                          | USER-DEFINED                |
| no_build                                     | Summary count of no of buildings within grid geom                                             | bigint                      |
| no_contain                                   | Summary count of no of containments within grid geom                                          | bigint                      |
| no_rcc_framed                                | Summary count of no of rcc framed buildings within grid geom                                  | bigint                      |
| no_wooden_mud                                | Summary count of no of wooden buildings within grid geom                                      | bigint                      |
| no_load_bearing                              | Summary count of no of load bearing buildings within grid geom                                | bigint                      |
| no_cgi_sheet                                 | Summary count of no of cgi sheet buildings within grid geom                                   | bigint                      |
| no_build_directly_to_sewerage_network        | Summary count of no of building directly connected to sewer network within grid geom          | integer                     |
| no_pit_holding_tank                          | Summary count of no of pit/holding tanks within grid geom                                     | bigint                      |
| no_septic_tank                               | Summary count of no of septic tanks within grid geom                                          | bigint                      |
| total_rdlen                                  | Total length of roads within grid geom                                                        | double precision            |
| bldgtaxpdprprtn                              | Building to tax payment proportion within grid geom                                           | double precision            |
| wtrpmntprprtn                                | Building to water supply payment proportion within grid geom                                  | double precision            |
| no_popsrv                                    | Total no of population served within grid geom                                                | bigint                      |
| no_hhsrv                                     | Total no of household served within grid geom                                                 | bigint                      |
| no_emptying                                  | Total count of no of emptying of containments within grid geom                                | bigint                      |
| created_at                                   | Timestamp when the record was last created (Auto Fill, Hidden)                                | timestamp without time zone |
| updated_at                                   | Timestamp when the record was last updated (Auto Fill, Hidden)                                | timestamp without time zone |
| deleted_at                                   | Timestamp when the record was deleted (Auto Fill, Hidden)                                     | timestamp without time zone |


# Sewer Connection

Schema Name: **sewer-connection**

Table name: sewer-connections

| **Field Name** | **Label**                   | **Description**                                                | **Data Type**               |
|----------------|-----------------------------|----------------------------------------------------------------|-----------------------------|
| id             |                             | Unique identifier                                              | integer                     |
| sewer_code     | Sewer Code                  | Unique code for each sewer record                              | character varying           |
| bin            | Building Code               | Unique code for each building record                           | character varying           |
| created_at     |                             | Timestamp when the record was last updated (Auto Fill, Hidden) | Timestamp                   |
| updated_at     |                             | Timestamp when the record was deleted (Auto Fill, Hidden)      | Timestamp                   |
| deleted_at     |                             | Timestamp when the record was created (Auto Fill, Hidden)      | Timestamp                   |

