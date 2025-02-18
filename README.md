# CalStatePays

CalStatePays is a visualization application for discovering, exploring, and analyzing the potential student financial earnings after graduation from 1 of 6 different Cal State Universities. California State employment records associated with alumni from these CSU campuses are used as the bases for the information that is presented.

* Our production website for CalStatePays is located at: https://calstatepays.org

## Table of Contents
<!-- TOC -->
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
     - [Production Installation](#production-installation)
     - [Development Installation](#development-installation)
  - [Developement Cycle](#development-cycle)
     - [Front end](#front-end)
     - [Database](#database)
  - [Bugs and issues:](#bugs-and-issues)
  - [License](#license)

<!-- /TOC -->

## Prerequisites

- Install [Git](https://git-scm.com/downloads) to access the software repository
- Install [Docker](https://docs.docker.com/install/) desktop to run containers on your development machine
- Install [Docker-Compose](https://docs.docker.com/compose/install/) to manage a set of containers
- Select and Install you Favorite Text Editor or IDE
- Ensure you meet the [Laravel 5.4 requirements](https://laravel.com/docs/5.4) to perform development work

## Installation

There are several different installation options for the CalStatePays software.  The only option that is provided by these instructions is the development option.

### Production Installation
To be revised!


### Development Installation
As a developer, you will find it useful to install the application, in total, on your local machine.  The development installation creates four containers used to setup a working environment. This environment contains a web server, a database, and two supporting containers.  The web server mounts the home directory of your cloned project. This allows the developer to use their favorite development tools outside of the containers, with updates to software being made directly.

The steps you need to perform to install this sofware are as follows:
  ```
  git clone https://github.com/CalStatePays/calstatepays.git
  cd calstatepays
  cp .env.dev .env
  docker-compose up --detach
  ```

  ```
  # you may have to wait a bit before executing the following commands to allow the containers to stablize
  docker-compose exec web php artisan migrate --seed
  ```

⚠️ This process is driven by the `.env.dev` file.  Container names, etc, are derived from the `COMPOSE_PROJECT_NAME` which has been set to "calstatepays". You may want to review the contents of this file prior to running the `docker-compose` command referenced above, and make appropriate changes.  E.g., you might want to change the default password for the database.

You may launch your favorite web browser and access your version of the calstatepays application:
  * The application is reachable at: http://localhost:8080/    # The port number can be changed via the WEB_PORT environment variable
  * The database GUI is reachable at: http://localhost:8081/   # The port number can be changed via the ADMINER_PORT environment variable

You can reset your docker environment via the following command:
```
docker-compose down
docker volume rm calstatepays_volume           # Assuming $COMPOSE_PROJECT_NAME == calstatepays
docker-compose up --detach --force-recreate
```

## Development cycle

During development, there are two major components that involve
additional processing to ensure the code/data base is setup correctly.
One component is related to the front-end, where Yarn is used to
pre-process the javascript code.  The other componet is related to the
database, were raw CSV files need to be converted to appropriate .json
data for the Laravel database component.

A developer needs to take the following additional steps, during the
development cycle if they make any changes to the javascript (which
includes the Vue components) components or the database seed values.

### Front End

The Yarn Package manager is used to compile all of the front-end resources. Execute the following command to bring the compiled resources
up-to-date.

```
$ docker-compose exec web yarn run dev
```

Alternatively, you can setup a 'watch' to perform incrementaly compile front-end resources as you develop. Open a secondary terminal, and execute the following command:

```
$ docker-compose exec web yarn run watch
```

**Note:** When you run this command on the terminal it starts a process that will continuously watch for front end resource changes. If you want to stop the process then simply issue `Ctrl+C`.

⚠️ **Important:** Make sure you terminate the watch process before you start switching into different branches!

Prior any pull requests to merge in new front-end changes, make sure you run one of the following commands to prepare your environment correctly.

```
$ docker-compose exec web yarn run prod
```
or
```
$ docker-compose exec web npm run prod
```
⚠️ **Important:** If the above step is not followed debug flags and other secrets might get left in for people to see through their browsers console.


### Database

The raw CSV files are provided by the CalStatePays data team, which differs from the development team.
These CSV files need to be converted to appropriate .json files so that the a seed operation can load the database. 
When we received update CSV files, which is very infrequent, the following steps need to be perform.

```
$ git submodule add https://github.com/csuntechlab/calstatepays_data.git csv_data
$ # Place the input files from the data team into csv_data/input
$ docker-compose exec web csv_data/build_json
$ docker-compose exec web cp -r csv_data/output database/data
$ docker-compose exec web php artisan migrate:refresh --seed
```

If all works as it purports to, you can commit (etc) the changes to the database/data subdirectory.

```
$ git commit -m 'Updated CSP data from the data team' database/data
```

You may also want to consider committing the new, raw CSV files as part of the calstatepays_data.git project

```
$ cd csv_data/input
$ git commit -m 'Updated CSP data from the data team' .
```


For additional information on the conversion process, etc., see the [calstatepays_data](https://github.com/csuntechlab/calstatepays_data) project.


## Bugs and issues:

If you discover a bug and or issue within the application, please create a GitHub issue with the bug label. In addition, please list the necessary steps to reproduce the bug in the description.

## License
CalStatePays is open-sourced software licensed under the GNU General Public License v3+. A copy can be found in the `COPYING` file.

