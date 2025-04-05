Version: V1.0

# Technical Information

## Standard Conventions

### General Convention

The development of IMIS has been carried out by following various different conventions. The conventions are outlined below:

|                       | **Description**                       | **Good**                                | **Bad**                                         | **Remarks**               |
|-----------------------|---------------------------------------|-----------------------------------------|-------------------------------------------------|---------------------------|
| **TABLE Naming**      |                                       |                                         |                                                 |                           |
| Table name            |                                       | treatment_plants                        |                                                 | plural                    |
| Pivot Table           |                                       | build_contains                          |                                                 | plural                    |
| Column Name           | snake_case long name with 3 words max | applicant_name                          | applicants_name                                 | should be singular        |
| ID                    | integer                               | 35, 777777                              |                                                 |                           |
| Code                  | code                                  | B000035, C77777                         |                                                 |                           |
| Primary key           | id                                    | id                                      | containcd                                       |                           |
| Foreign key           | tablename_id                          | containment_id                          | containcd                                       |                           |
| **Controller Naming** |                                       |                                         |                                                 |                           |
| Route                 | singular                              | ArticleController                       | ArticlesController                              |                           |
| View                  | plural                                | articles/1                              | article/1                                       |                           |
| blade name            | kebab-case                            | show-filtered.blade.php                 | showFiltered.blade.php, show_filtered.blade.php |                           |
| Config                |                                       | show_building                           | show-building                                   | use underscore not hyphen |
|                       | kebab-case                            | google-calendar.php                     | googleCalendar.php, google_calendar.php         |                           |
| **Variable Naming**   |                                       |                                         |                                                 |                           |
| Collection            | camelCase                             | \$anyOtherVariable                      | \$any_other_variable                            |                           |
| Object                | descriptive, plural                   | \$activeUsers = User::active()-\>get()  | \$active, \$data                                | make                      |
|                       | descriptive, singular                 | \$activeUser = User::active()-\>first() | \$users, \$obj                                  |                           |

| **FOLDER Naming** |                                                                             |                                                               |
|-------------------|-----------------------------------------------------------------------------|---------------------------------------------------------------|
|                   | **Description**                                                             | **Example**                                                   |
| View              | Singular with kebab-case Module wise Plural with kebab-case sub-module wise | BuildingInfo buildings index.blade.php partial-form.blade.php |
| Controller        | Singular with PascalCase Module wise                                        | BuildingInfo                                                  |
| Model             | Singular with PascalCase Module wise                                        | BuildingInfo                                                  |
| Request           | Singular with PascalCase Module wise                                        | BuildingInfoRequest                                           |

### PostgreSQL Convention

Schema Name

SQL identifiers and keywords must begin with a letter (a-z, but also letters with diacritical marks and non-Latin letters) or an underscore (_). Subsequent characters in an identifier or keyword can be letters, underscores, digits (0-9), or dollar signs (\$).

Indexes

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

Demonstration

-   Create an index

create index my_table_column_a_idx on my_table(column_a);

-   Create a multicolumn index

create index my_table_column_a_column_b_idx on my_table(column_a, column_b);

-   Create a unique index

create unique index my_table_column_a_key on my_table(column_a);

-   Create a multicolumn unique index

create unique index my_table_column_a_column_b_key on my_table(column_a, column_b);

## Basic CRUD

The following is a general over view of how the Basic CRUD operations have been carried out in IMIS. The meaning of Basic CRUD means Create, Read, Update and Delete. The below mentioned CRUD operations are generally of the same pattern and only differ in context so a general over view of the CRUD operation is demonstrated below.

### Models

The corresponding tables have their respective models that are named in Pascal Case in singular form located at app\\Models\\{{schema_name}}

The models contain the connection between the model and the table defined by:

*\$table = ‘schema_name.table_name’*

as well as the primary key defined by:

*primaryKey= ‘primary_key_field’*

For history functionality, the revisionCreationsEnabled is set true and

use RevisionableTrait(use Venturecraft\\Revisionable\\RevisionableTrait;

For soft deletes functionality, we

use SoftDeletes;(use Illuminate\\Database\\Eloquent\\SoftDeletes;)

For Setting belongsTo and HasMany relationships, we:

use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;

belongsToMany('model_relative_path', 'relational_table', primary_key, 'primary_key of relationship')

belongsTo('model_name', 'pk of first table', 'pk of Second table'

use Illuminate\\Database\\Eloquent\\Relations\\HasMany;

The model also contains different types of relationships between the models as well such as a many to many relationships between buildings and containments, one to one relationship with buildings and applications and so on.

### Views

All views used by each module is stored in resources\\views\\{{schema_name}}\\

Each module includes

-   Index: lists all records
-   Create: opens form and calls partial-form contents
-   Edit: opens form and calls partial-form contents
-   Partial-form: creates form content for create or edit
-   History: lists all past edits of the record
-   Show: displays all attributes of particular record

### Controller

The controller’s main function is to provide the connection between the calling route and its subsequent function written in the Service Class.

The basic functions of the controller are:

|  **Function**   | \__construct()                                                         |
|-----------------|------------------------------------------------------------------------|
| **Description** | Initializes authentication, permissions and the service class instance |
| **Parameters**  | Service class instance                                                 |
| **Return**      | null                                                                   |
| **Source**      | app\\Http\\Controllers\\Module\\ExampleController.php                  |

| **Function**    | index()                                                                      |
|-----------------|------------------------------------------------------------------------------|
| **Description** | Returns the index.blade.php page with dropdown values fetched from database. |
| **Parameters**  | null                                                                         |
| **Return**      | \\Illuminate\\View\\View Array [key and value pair]                          |
| **Source**      | app\\Http\\Controllers                                                       |

| **Function**    | create()                                                                            |
|-----------------|-------------------------------------------------------------------------------------|
| **Description** | Returns the form to create new data with dropdown values fetched from the database. |
| **Parameters**  | null                                                                                |
| **Return**      |                                                                                     |
| **Source**      | app\\Http\\Controllers\\Module\\ExampleController.php                               |

| **Function**    | store()                                                          |
|-----------------|------------------------------------------------------------------|
| **Description** | Calls the service class that handles the process of storing data |
| **Parameters**  | Request \$request                                                |
| **Return**      | Success or error message.                                        |
| **Source**      | app\\Http\\Controllers\\Module\\ExampleController.php            |

| **Function**    | show()                                                |
|-----------------|-------------------------------------------------------|
| **Description** | Returns the page displaying individual data           |
| **Parameters**  | \$id                                                  |
| **Return**      | \\Illuminate\\View\\View Array [key and value pair]   |
| **Source**      | app\\Http\\Controllers\\                              |

| **Function**    | edit()                                                                     |
|-----------------|----------------------------------------------------------------------------|
| **Description** | Returns the edit form page displaying pre-existing individual data as well |
| **Parameters**  | \$id                                                                       |
| **Return**      | \\Illuminate\\View\\View Array [key and value pair]                        |
| **Source**      | app\\Http\\Controllers\\Module\\ExampleController.php                      |
|  **Function**   | update()                                                                   |
| **Description** | Calls the service class that handles the process of updating data          |
| **Parameters**  | Request \$request                                                          |
| **Return**      | Success or error message.                                                  |
| **Source**      | app\\Http\\Controllers\\Module\\ExampleController.php                      |
| **Function**    | export()                                                                   |
| **Description** | Calls the service class that handles the process of exporting data         |
| **Parameters**  |                                                                            |
| **Return**      | CSV file containing data                                                   |
| **Source**      | app\\Http\\Controllers\\Module\\ExampleController.php                      |

| **Function**    | history()                                                          |
|-----------------|--------------------------------------------------------------------|
| **Description** | Calls the service class that handles the process of exporting data |
| **Parameters**  |                                                                    |
| **Source**      | app\\Http\\Controllers\\Module\\ExampleController.php              |

Example

Here is an example of basic CRUD in reference to Help Desks

Tables

Help Desks is under FSM module and uses the following table:

-   help_desks

The corresponding tables have their respective models that are named in Pascal Case in singular form. HelpDesk model is located at app\\Models\\Fsm\\.

Views

All views used by this module is stored in resources\\views\\fsm\\help-desks

-   help-desks.index: lists help desks records.
-   help-desks.index: lists help desks records
-   help-desks.create: opens form and calls partial-form for form contents
-   help-desks.partial-form: creates form content
-   help-desks.edit.blade: opens form and calls partial-form for form contents
-   help-desks.edit-partial-form: creates form content for editing
-   help-desks.history: lists all past edits of the record
-   help-desks.show: displays all attributes of particular record

HelpDeskController

app\\Http\\Controllers\\Fsm\\HelpDeskController.php

The controller’s main function is to provide the connection between the calling route and its subsequent function written in the Service Class.

The basic classes of the controller are:

|  **Function**   | \__construct()                                                         |
|-----------------|------------------------------------------------------------------------|
| **Description** | Initializes authentication, permissions and the service class instance |
| **Parameters**  | Service class instance(HelpDeskService)                                |
| **Return**      | null                                                                   |
| **Source**      | app\\Http\\Controllers\\Fsm\\HelpDeskController.php                    |

| **Function**    | index()                                                                      |
|-----------------|------------------------------------------------------------------------------|
| **Description** | Returns the index.blade.php page with dropdown values fetched from database. |
| **Parameters**  | null                                                                         |
| **Return**      | fsm/help-desks.index compact('page_title','service_providers')               |
| **Source**      | app\\Http\\Controllers\\Fsm\\HelpDeskController.php                          |

| **Function**    | create()                                                                                 |
|-----------------|------------------------------------------------------------------------------------------|
| **Description** | Returns the form to create new help desk with dropdown values fetched from the database. |
| **Parameters**  | null                                                                                     |
| **Return**      | fsm/help-desks.create compact('page_title')                                              |
| **Source**      | app\\Http\\Controllers\\Fsm\\HelpDeskController.php                                      |

| **Function**    | store(HelpDeskRequest \$request)                                 |
|-----------------|------------------------------------------------------------------|
| **Description** | Calls the service class that handles the process of storing data |
| **Parameters**  | HelpDeskRequest \$request                                        |
| **Return**      | Success or error message.                                        |
| **Source**      | app\\Http\\Controllers\\Fsm\\HelpDeskController.php              |
| **Remarks**     | storeOrUpdate(\$id = null,\$data) Service Class Function Name    |

| **Function**    | show()                                                        |
|-----------------|---------------------------------------------------------------|
| **Description** | Returns the page displaying individual helpdesk data          |
| **Parameters**  | \$id                                                          |
| **Return**      | fsm/help-desks.show   compact('page_title', 'treatmentPlant') |
| **Source**      | app\\Http\\Controllers\\Fsm\\HelpDeskController.php           |

| **Function**    | history()                                                  |
|-----------------|------------------------------------------------------------|
| **Description** | lists all past edits of the record                         |
| **Parameters**  | \$id                                                       |
| **Return**      | fsm/help-desks.history   compact('page_title', 'helpDesk') |
| **Source**      | app\\Http\\Controllers\\Fsm\\HelpDeskController.php        |

| **Function**    | edit()                                                                              |
|-----------------|-------------------------------------------------------------------------------------|
| **Description** | Returns the edit form page displaying pre-existing individual building data as well |
| **Parameters**  | \$id                                                                                |
| **Return**      | fsm/help-desks.edit    compact('page_title', 'helpDesk')                            |
| **Source**      | app\\Http\\Controllers\\ Fsm\\HelpDeskController.php                                |

| **Function**    | update()                                                                    |
|-----------------|-----------------------------------------------------------------------------|
| **Description** | Calls the service class that handles the process of updating help desk data |
| **Parameters**  | HelpDeskRequest \$request, \$id                                             |
| **Return**      | Success or error message.                                                   |
| **Source**      | app\\Http\\Controllers\\Fsm\\HelpDeskController.php                         |
| **Remarks**     | storeOrUpdate(Request \$request) (service class function)                   |

| **Function**    | export(Request \$request)                                                    |
|-----------------|------------------------------------------------------------------------------|
| **Description** | Calls the service class that handles the process of exporting help desk data |
| **Parameters**  |                                                                              |
| **Return**      | CSV file containing help desks data                                          |
| **Source**      | app\\Http\\Controllers\\Fsm\\HelpDeskController.php                          |
| **Remarks**     | helpDeskService-\>download(\$data);  (service class function)                |

HelpDeskService

Location: app\\Services\\Fsm\\HelpDeskService.php

The Service Class contains all the business logic. It contains all the functions that are being called in the HelpDeskController.

|  **Function**   | storeOrUpdate()                                                                                          |
|-----------------|----------------------------------------------------------------------------------------------------------|
| **Description** | Handles the process of adding/updating new helpdesk.                                                     |
| **Parameters**  | \$id,\$data                                                                                              |
| **Return**      | Success or error message, stores/updates data to help desks                                              |
| **Source**      | app\\Services\\Fsm\\ HelpDeskService.php                                                                 |
| **Logic**       | if \$id is null store new records to database if \$id has some value edit the record of \$id to database |

|  **Function**   | download()                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
|-----------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Description** | Handles the process of exporting building and owner information                                                                                                                                                                                                                                                                                                                                                                                                        |
| **Parameters**  |                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| **Return**      | Returns CSV                                                                                                                                                                                                                                                                                                                                                                                                                                                            |
| **Source**      | app\\Services\\Fsm\\HelpDeskService.php                                                                                                                                                                                                                                                                                                                                                                                                                                |
| **Logic**       | \$help_desk_id = \$data['help_desk_id'] ? \$data['help_desk_id'] : null · Fetching value on the basis of which filter is to be carried out. This value needs to be declared in the script of the index page. · Define columns, and the query fetching the respective columns · Check if any filter values are present, if yes, filter accordingly · Set styles using StyleBuilding provided by Box/Spout\^2 · Run query, store values in the columns · Export the data |

| **Function**    | getAllHelpDesks()                                                                                                                                                                                                                                       |
|-----------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Description** | Handles the process of fetching building and owner data for data tables                                                                                                                                                                                 |
| **Parameters**  | \$request                                                                                                                                                                                                                                               |
| **Return**      | Returns data of building and owner table for datatables                                                                                                                                                                                                 |
| **Source**      | app\\Services\\Fsm\\HelpDeskService.php                                                                                                                                                                                                                 |
| **Logic**       | · Join required tables · Check for filter values if any. Filter accordingly.  · Filter values are defined in the script of the index page · Append actions column that contain buttons such as edit, delete, show, history and so on · Return datatable |

HelpDeskRequest

Location: app\\Http\\Requests\\Fsm\\HelpDeskRequest.php)

HelpDeskRequest handles all validation login. It handles validation logic as well as error messages to be displayed.

|  **Function**   | authorize()                                   |
|-----------------|-----------------------------------------------|
| **Description** | Determines if user is authenticated or not    |
| **Parameters**  |                                               |
| **Return**      | Returns true                                  |
| **Source**      | app\\Http\\Requests\\Fsm\\HelpDeskRequest.php |

| **Function**    | message()                                                                                                                         |
|-----------------|-----------------------------------------------------------------------------------------------------------------------------------|
| **Description** | Message to be displayed in case of validation error                                                                               |
| **Parameters**  |                                                                                                                                   |
| **Return**      | Return validation message                                                                                                         |
| **Source**      | app\\Http\\Requests\\Fsm\\HelpDeskRequest.php                                                                                     |
| **Remarks**     | Need to include errors.list.blade that displays the error message in dashboard Format: ‘Field.validation_rule’ =\>”error_message” |

| **Function**    | rules()                                                                           |
|-----------------|-----------------------------------------------------------------------------------|
| **Description** | Contains the validation rules                                                     |
| **Parameters**  |                                                                                   |
| **Return**      | Return validation logic to calling place                                          |
| **Source**      | app\\Http\\Requests\\Fsm\\HelpDeskRequest.php                                     |
| **Remarks**     | Format for validation rule: ‘field_name’=\>’validation_rule1 \| validation_rule2’ |

Models

The models contain the connection between the model and the table defined by

\$table = ‘fsm.help_desk’ as well as the primary key defined by

primaryKey= ‘id’

## Data Dictionary

The data dictionary for IMIS is attached in Appendix A.

## Environment (.env) File Structure

The .env file for the project is similar to the general .env file structure. The only notable difference is the portion of setting geoserver variables such as workspace, url, auth key and geoserver linkage. An example is provided below:

**Create the .env file:**

| cp .env.example .env |
|----------------------|

**Set up the Database configuration in the .env file:**

| DB_CONNECTION=pgsql DB_HOST=127.0.0.1 DB_PORT=5432 DB_DATABASE=[database_name] DB_USERNAME=[database_username] DB_PASSWORD=[database_password] |
|------------------------------------------------------------------------------------------------------------------------------------------------|

**Set up the Geoserver configuration in the .env file:**

| \#GEOSERVER GEOSERVER_WORKSPACE = [workspace_name] GEOSERVER = http://[server_url]/geoserver/ GEOSERVER_URL = http://[server_url]/geoserver/[workspace_name] AUTH_KEY = [auth_key] |
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|

## File Structure & Storage Management

### File Structure

The breakdown of file structure in Laravel:

1.  app/: This directory contains your application's core code.
    -   Classes/: Contains custom PHP classes.
    -   Console/: Holds artisan console commands.
    -   Enums/: Houses enumeration classes if you're using enums in your application.
    -   Exceptions/: Contains exception handler classes.
    -   Exports/: For classes related to exporting data (e.g., to CSV, Excel).
    -   Helpers/: Houses helper functions or classes used throughout your application.
    -   Http/: Contains controllers, middleware, form requests, etc., related to HTTP requests.
    -   Imports/: For classes related to importing data (e.g., from CSV, Excel).
    -   Models/: Houses the Eloquent models.
    -   Providers/: Contains service providers for the application.
    -   Rules/: For custom validation rule classes if you're using Laravel's validation system.
    -   Services/: Contains classes responsible for specific application services.
2.  bootstrap/: Contains files for bootstrapping the Laravel application.
3.  config/: Contains configuration files for various components of your application.
4.  database/: Contains database-related files.
    -   migrations/: Houses all database migration files.
    -   seeders/: Contains database seeder classes.
5.  public/: The web server's document root. Contains the front controller and assets like CSS, JavaScript, and image files.
6.  resources/: Contains your application's assets like views, language files, and raw asset files.
    -   css/, js/, sass/: Asset directories for CSS, JavaScript, and SASS files.
    -   lang/: Language files.
    -   views/: Blade template files.
7.  routes/: Contains route definitions for your application.
8.  storage/: Contains generated files like logs, cache, and file uploads.
    -   app/: For storing files generated by your application.
    -   framework/cache/: For storing cached files.
    -   logs/: Log files generated by the application.
9.  tests/: Contains test files.
10. vendor/: Contains Composer dependencies.
11. .env: Environment file for setting configuration variables.
12. artisan: Command-line utility for interacting with your Laravel application.

### Storage Management

The files received during form submissions are stored in the application side server into the directory /storage/app/public/.

-   The KML files uploaded via the mobile application during the building survey process are stored in the file path /storage/app/public/building-survey-kml.
-   The images of the building are stored in the file path /storage/app/public/emptyings/houses.
-   The images of the receipts are stored in the file path /storage/app/public/emptyings/ receipts.

Note: The above folder needs to be created during a initial project installation process.

Laravel provides a convenient way to manage file storage through the Storage facade. By default, Laravel uses the local disk driver, which stores files in the storage directory. However, you can configure multiple disks for various storage solutions including cloud storage providers.

For example, to create File storage in Config file: config/filesystems.php

'storagedisk' =\> [

'driver' =\> 'local',

'root' =\> '/home/projects/storagefolder',

],

To store a file:

use Illuminate\\Support\\Facades\\Storage;

\$storage = Storage::disk(' storagedisk ')-\>path('/');

Here, ‘storagedisk’ is the storage name. And ‘root’ has a path where the uploaded excel files are supposed to be stored.

## Laravel Web App Session and Cookies

When a user login normally, session expires in two hours for now. It can be changed via .env file with SESSION_LIFETIME variable.

When a user login with remember me, session will be stored in cookie and it will expire when cookie expires. For now, cookie expiry time is 3 days. It can also be changed via .env file with REMEMBER_ME variable.

## NPM Package Manager

The system uses NPM for package management. All packages that are required is installed using NPM. The installed package is then initialized in the app.js file found in app/resources/js/app.js and app/resources/js/bootstrap.js. Similarly, the css files of the package is initialized in the app.scss file found in app/resources/sass/app.scss. The files to be initialized can be found inside the node_modules.

## Frontend

### JS & CSS

The system uses JavaScript for all of the frontend related dynamic changes and map interface. For simple dynamic form changes according to the selected values of the form, the JavaScript is written in the respective blade file. For example, all scripts for the add building form is written in the create.blade.php file.

All custom javascript functions that are commonly used by different pages have been stored in the path app/public/js. The js files that can be found are:

-   app.js: contains the auto generated js file compiled by npm
-   function.js: contains the custom functions that are shared such as datatables related scripts, prevent accidental deletion script, prevent multiple submits script, export script and so on.
-   Main.js contains the script for the landing page
-   Map_layout.js: contains the script used by the Map interface for its layout explained further in section 11.2
-   Map-function.js: contain the functions used by the map interface explained further in section 11.2

All custom css are stored in /public/css.

All packages that have been used are handled by NPM package manager as explained in Section 9 and are simple called in the script.

### Dashboard Layout

There are three main layouts used in the system, one each for the landing page, dashboard and the map interface.

-   The landing page is stored in views/landingpage.blade.php.
-   The dashboard is stored in views/layouts/dashboard.blade.php. The dashboard layout includes the header, sidebar, toast-message and footer stored in /includes/.
-   The map interface is stored in view/layouts/maps.blade.php.

### General Layout

The different modules are broken down into sub modules and the views are stored at /resources/views/module-name/sub-module-name.

Each sub-module folder can consist of create, edit, history, index, partial-form and show blade files. There may be differences according the module dynamics. The create blade initializes the form and calls the partial-form to display the field attributes. The edit blade also does the same but to edit the record and shares the partial-form to display the field attributes. The history displays all the past changes carried out on the record attributes. Similarly, the index consists of the code and JavaScript to display data using datatables. The partial-form contains the form fields and are dynamically set to be set empty or with data according to the situation. The show file displays the data attributes.
