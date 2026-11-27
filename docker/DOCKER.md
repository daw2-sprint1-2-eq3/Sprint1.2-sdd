# Docker — application and database

The stack has two containers:

| Service | Image | Purpose | Host port |
| --- | --- | --- | --- |
| `app` | `php:8.3-apache` built from `docker/Dockerfile` | PHP 8.3 + Apache, document root `public/` | `8080` |
| `db` | `mariadb:11.4` | MariaDB database | `3307` |

The application and the database run in separate containers, which satisfies
the "separate app and DB servers" requirement.

## First start

```sh
cp .env.example .env      # optional: the defaults work for the classroom
docker compose -f docker/docker-compose.yml up -d --build
```

Open <http://localhost:8080>.

The first time the `mariadb_data` volume is created, MariaDB runs
`database/schema-mariadb.sql` automatically and creates the tables.

## Load the demo data

The schema is created automatically, but the sample rows are not. Run the
seed once the database is healthy:

```sh
docker compose -f docker/docker-compose.yml exec app php database/seed.php
```

Demo accounts:

| Username | Password | Role |
| --- | --- | --- |
| `admin` | `admin123` | admin |
| `prof1` | `prof123` | professor |
| `alum1` | `alum123` | student |

`prof2` and `alum2`…`alum4` also exist.

## Daily commands

```sh
# Follow the logs
docker compose -f docker/docker-compose.yml logs -f

# Stop the containers (data is kept in the volume)
docker compose -f docker/docker-compose.yml down

# Run the test suite inside the app container
docker compose -f docker/docker-compose.yml exec app php tests/run.php

# Open a MariaDB shell
docker compose -f docker/docker-compose.yml exec db \
  mariadb -u daw2 -pdaw2 daw2_grades
```

## Reset the database

`down` keeps the volume, so the schema and seed survive restarts. To start
from scratch:

```sh
docker compose -f docker/docker-compose.yml down -v
docker compose -f docker/docker-compose.yml up -d --build
docker compose -f docker/docker-compose.yml exec app php database/seed.php
```

`down -v` deletes the `mariadb_data` volume. Use it only when you accept losing
all data.

## Configuration

The container reads the same constants as local PHP (`config/config.php`):

| Variable | Default | Meaning |
| --- | --- | --- |
| `DB_DRIVER` | `mysql` in Compose | `mysql` or `sqlite` |
| `DB_HOST` | `db` | MariaDB service name |
| `DB_PORT` | `3306` | port **inside** the Compose network |
| `DB_NAME` | `daw2_grades` | database name |
| `DB_USER` | `daw2` | application user |
| `DB_PASSWORD` | `daw2` | application password |

Do not commit `.env`. `.env.example` documents the available values.

## Troubleshooting

- **Port already in use** — change `APP_PORT` or `DB_PORT_HOST` in `.env`.
- **Tables missing** — the schema only runs on a fresh volume. Run
  `down -v` and start again, or import the schema manually.
- **"Access denied" from PHP** — check that `DB_USER`, `DB_PASSWORD` and
  `DB_NAME` match the values used by the MariaDB container.
- **Changes do not appear** — the Compose file bind-mounts the repository over
  `/var/www/html`; check `docker compose ... logs app` for PHP errors.
