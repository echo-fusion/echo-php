![echoPHP Logo](./logo.png)


## Running the project
For the first time:
```
make build
```
and for future running containers just type:
```
make up
```
now you can navigate to http://localhost:8000 to visit pages.

### Tips
if you want to import database and tables table run this command inside the container
```
make shell
 >> ./vendor/bin/doctrine-migrations migrate
```

## Test
```
composer test
```