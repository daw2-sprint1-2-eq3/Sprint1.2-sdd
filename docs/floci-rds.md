# Floci / RDS check

Sprint 1.2 requires the application to be tested against the course's
Floci/RDS database environment, not only against the local Docker MariaDB.
The application is ready for it: the database connection is configured only
through environment variables, so no code change is needed.

> **Status in this repository:** the procedure below is prepared and the
> application has been verified against MariaDB 11.4 in Docker. The Floci/RDS
> run must be executed with the endpoint and credentials provided by the
> teaching staff, and its output attached to the delivery. Do not write those
> credentials in the repository.

## 1. What the teaching staff provides

Ask for or locate:

- host name / endpoint;
- port (usually `3306`);
- database name;
- username and password;
- whether the user may create tables, or whether the schema must be imported
  by an administrator.

The database engine must be MySQL or MariaDB compatible, because the schema in
`database/schema-mariadb.sql` uses MariaDB syntax.

## 2. Configure the connection

Use shell environment variables. Do not create a committed `.env` with the real
credentials.

```sh
export DB_DRIVER=mysql
export DB_HOST="<floci-host>"
export DB_PORT="3306"
export DB_NAME="<database>"
export DB_USER="<user>"
export DB_PASSWORD="<password>"
```

Check that PHP sees the driver:

```sh
php -m | grep -i pdo_mysql
```

## 3. Create the schema and the demo data

If the database user can create tables:

```sh
php database/seed.php
```

`seed.php` detects `DB_DRIVER=mysql`, loads `database/schema-mariadb.sql`,
creates the tables and inserts the demo rows. It is idempotent.

If the user cannot create tables, import the schema with an administrator
account first:

```sh
mariadb -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p "$DB_NAME" \
  < database/schema-mariadb.sql
php database/seed.php
```

## 4. Smoke test

Start the application with the remote database:

```sh
php -S 127.0.0.1:8000 -t public
```

Then check, in this order:

| Check | Expected result |
| --- | --- |
| `GET /` | login page loads |
| Login `admin` / `admin123` | admin dashboard loads |
| **Projectes** | `P1` and `P2` are listed |
| Open `P1` | sprints, professors, students and instruments are shown |
| **Qualificar** → `P1` | grading matrix loads with individual and team tables |
| Save a grade | success flash message, value is shown again |
| Login `alum1` | student dashboard shows the grade |
| Logout | returns to the login page |

Run the automated tests against the local SQLite database as usual; the test
suite is intentionally isolated and does not touch the remote database:

```sh
php tests/run.php
```

## 5. Evidence for the delivery

Attach or paste:

- the command used to start the app (without the password);
- the output of `php database/seed.php` (it prints the driver and database
  name, not the password);
- a screenshot of the admin dashboard and of the grading matrix;
- the result of `php tests/run.php`;
- any difference between the Floci environment and the local Docker setup.

## 6. Troubleshooting

| Symptom | Check |
| --- | --- |
| `SQLSTATE[HY000] [2002]` | host, port, and whether the Floci security group/firewall allows the connection |
| `Access denied` | username, password and database name |
| `Unknown database` | database name; create it if the provider expects you to |
| `The used table type doesn't support FULLTEXT indexes` | not used by this schema; check that you imported `schema-mariadb.sql`, not the SQLite file |
| `Table already exists` | the schema uses `IF NOT EXISTS`; re-running the seed is safe |
| Charset issues | the connection uses `utf8mb4` in `app/Core/Database.php` |
