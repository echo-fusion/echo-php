# Simple php framework

## Running the project
For the first time
```
make build
```
and for future running just type this without any build or runnig composer
```
make up
```
now you can navigate to http://localhost:8000 to visit pages.

### Tips
if you want to import database and tables table run this command inside the container
```
make shell
 >> php migraion-cli.php
```

## Test
```
composer test
```