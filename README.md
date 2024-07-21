# weatherForecastServer

## Guide to run project
### 1. Install XAMPP
- Download and install XAMPP (Version 8.1) from [this link](https://www.apachefriends.org/download.html)

### 2. Install Composer
- Download and install Composer from [this link](https://getcomposer.org/)

### 3. Git clone project 
- Open command line, move to folder want to storage project. Run command to git clone project:
```html
git clone https://github.com/dosidat1503/weatherForecastServer
```

### 4. Install dependencies
- Move to folder contain project and run command:
```html
composer install
```

### 5. Add file .env
- At project folder, add file .env and copy code from file .env.example into file .env by command:
```html
cp .env.example .env
```

### 6. Create app key:
- Run command to create app key:
```html
php artisan key:generate
```


- You can access it in your browser at http://localhost:8000
 