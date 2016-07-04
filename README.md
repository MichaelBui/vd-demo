# VD-Demo

* Author: [Michael Bui](http://about.me/michaelbui) (mf.michaelbui@gmail.com)
* Technical overview:
    * Environment
        * CoreOS
        * [Docker](https://www.docker.com/)
    * Backend
        * Stack: LEMP (Linux / Nginx / MySQL / PHP)
        * Framework: [Lumen](https://lumen.laravel.com)
    * Frontend:
        * Framework: AngularJS
        * UX/UI: Angular Material
        * Client: Restangular (to consume backend APIs)
        * Builders: npm, gulp, bower,...

## Requirements
For your convenience, only Docker is required.
All project-related libraries is installed inside docker images/containers

## Instruction
* All `/run_*.sh` files are wrapper of `docker-compose` commands

    If you are familiar with Docker, you can use any Docker-related tools to do your own orchestration

* For your convenience, I've added a postman collection (`postman.json`).
    Just import the collection into your postman and update environment for postman variable `{{host}}`

### Run (Dev)
* Run `./run_dev.sh up -d`
* Test all APIs at endpoint: `http://localhost:30101/`
* To destroy all dev containers, run `./run_dev.sh down`
* Client UI for your convenient: `http://localhost:30104/`

    **NOTE:** Do update the API URL in the Client UI if necessary

### Test
* Run `./run_test.sh`
* All tests should run and passed
* You can also run test on postman against sample data in `postman.data.json`

### Run (Prod)
* Run `./run_prod.sh up -d`
* Test all APIs at endpoint: `http://localhost:30100/`
* To destroy all dev containers, run `./run_prod.sh down`
* Client UI for your convenient: `http://localhost:30103/`

    **NOTE:** Do update the API URL in the Client UI if necessary

# Thank you!
