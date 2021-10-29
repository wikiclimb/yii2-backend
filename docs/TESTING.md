# Testing with Docker containers

`docker-compose up -d` can be used to start the database and application containers.

The environments need to be configured to run against the correct IP and port exposed by the container.

The code that runs the migrations runs on the host, it can access the test database service on localhost using the 
following configuration strings:

```php
['dsn' => 'mysql:host=127.0.0.1:33006;dbname=wikiclimb_test',],
```
When we send a request to the server, then the database service is accessed from the apache service, that service
needs to access the test database using the host name and port available inside the Docker network:

```php
['dsn' => 'mysql:host=wkcdbtest:3306;dbname=wikiclimb_test',],
```
