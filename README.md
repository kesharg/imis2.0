<h2 align="center">IMIS revamp 2.0</h2>


# Installation process

Navigate to the desired installation folder and run following code

```
git clone https://github.com/insol-dev/imis-revamp2.0.git
```

## Install Composer

```
composer install
```
## Install npm
```
npm install && npm run dev
```
## .env file

```
Copy .env.example to .env(create a new file)
```

## Generate app key

```
php artisan key:generate
```

## Setup Database
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=[database_name]
DB_USERNAME=[database_username]
DB_PASSWORD=[database_password]
```
Migrate database tables
```
php artisan migrate
```
Seed the database
```
php artisan db:seed
```
# Maintenance process

Pull code
```
git pull
```
Add code to git
```
git add .
```
Commit code
```
git commit -m "commit_message"
```

Push code
```
git push -u origin master
```

#   i m i s  
 