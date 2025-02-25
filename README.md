<div align="center">

# Simple Task Manager

![Main technologies](https://go-skill-icons.vercel.app/api/icons?i=html,css,ts,nodejs,php,laravel,sail,inertia,vuejs,vite,pnpm,mysql,docker,github,vscode)

A simple system of posts and comments inspired by Orkut, with authentication, registration, permission and public post systems

</div>

## Usage

> Before all, make sure to have installed in your machine the Docker and Docker Compose. Alternatively you could use PHP+Composer+Apache local installation.

This is RBCA Scraps, a simple system of posts and comments inspired by Orkut, with authentication, registration, permission and public post systems (be careful what you post, okay?). As it is still under development, one version may have incompatibilities with another, so feel free to always read the documentation as it will have all the necessary information for setup. Also, feel free to contribute with issues, pull requests and also in the discussions tab.

### Installation

First, use the command below to install the necessary Laravel Sail dependencies (without the need for local installation of Composer or any other tool).

```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

After installation, use the combination of commands below to perform some necessary actions for the application to be fully functional.

```sh
cp .env.example .env # Setup environment variables, you can change if need
./vendor/bin/sail up # This will up the Laravel Sail service containers
./vendor/bin/sail artisan key:generate # This will generate the application key
./vendor/bin/sail artisan migrate --seed # This will run the application migrations to setup database and seed with some dummy data
./vendor/bin/sail pnpm install # This will install Inertia.js Node.js dependencies for frontend
./vendor/bin/sail composer run dev # This will up the local server and required dependencies
```

If all the commands have run without any problems, you can enter the address `http://localhost` (if you have changed anything in the environment variables, take a look at which port the services will be running) where the application will be served.

## Licence

This project is licenced under the [MIT Licence](LICENSE).

## Author

This project is authored by [Gabriel Santos Cardoso](https://gabrielscardoso.com).
