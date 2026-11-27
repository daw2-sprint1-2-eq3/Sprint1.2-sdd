# PHP and PDO

**Read when** you write PHP, a model, SQL, a schema change, or a transaction.

Style reference: `app/Models/Module.php` and `app/Models/Instrument.php`. Score rules and means: `app/Models/Grade.php`, checked by `tests/test-grade-avg.php`.

## PHP

- First line of every PHP file: `declare(strict_types=1);`.
- App classes use namespaces `App\Models`, `App\Controllers`, `App\Core`.
- Models are the only place for SQL. Controllers pass already-cast ids and raw form strings; models validate.
- Return shape used by writes: `['ok' => bool, 'errors' => array, …]` (see `Module::create`, `Grade::store`). Do not throw for a user validation error.
- User-facing validation text is Catalan. Identifiers stay English.

## PDO

- Connection: `App\Core\Database::getInstance()` (`ERRMODE_EXCEPTION`, `FETCH_ASSOC`).
- Every value from the request is a bound parameter (`:id`). Never concatenate it into SQL.
- `query()` is only for SQL with no outside input (fixed `ORDER BY`). If the statement needs a filter, use `prepare` + `execute`.
- Prefer explicit column lists on new queries. `SELECT *` in older models is debt; do not copy it (`docs/agents/known-debt.md`).
- Cast ids in PHP (`(int)`) before they reach SQL. Still bind them.

## Schema

Two files must describe the same tables and columns:

- `database/schema.sql` — SQLite (dev + tests)
- `database/schema-mariadb.sql` — MariaDB (Docker)

Also update `database/seed.php` and the test that covers the new rule. Do not invent a third schema.

SQLite and MariaDB differ (`AUTOINCREMENT` vs `AUTO_INCREMENT`, `CHECK`). Keep behaviour aligned; do not use a MySQL-only function in the SQLite file.

## Transactions

Use a transaction when one user action writes more than one table and a partial write would be wrong (example: instrument + `instrument_criterion` rows).

```php
$pdo = Database::getInstance();
$pdo->beginTransaction();
try {
    // prepared writes
    $pdo->commit();
} catch (\Throwable $e) {
    $pdo->rollBack();
    throw $e;
}
```

Validation failures return `errors` **before** `beginTransaction`. Do not leave a transaction open across `view()` or `redirect()`.

How to run tests and seed: `tests/README.md` and `AGENTS.md` checks. Do not duplicate those commands here.
