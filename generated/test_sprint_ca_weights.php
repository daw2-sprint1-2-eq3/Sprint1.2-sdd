<?php
declare(strict_types=1);

require_once __DIR__ . '/SprintCaWeightValidator.php';

function fail_test(string $message): never
{
    fwrite(STDERR, 'FAIL: ' . $message . PHP_EOL);
    exit(1);
}

function assert_same(mixed $expected, mixed $actual, string $label): void
{
    if ($expected !== $actual) {
        fail_test(sprintf('%s: expected %s, got %s.', $label, var_export($expected, true), var_export($actual, true)));
    }
}

function assert_invalid_argument(callable $operation, string $messagePart, string $label): void
{
    try {
        $operation();
    } catch (InvalidArgumentException $exception) {
        if (!str_contains($exception->getMessage(), $messagePart)) {
            fail_test(sprintf('%s: exception did not contain "%s".', $label, $messagePart));
        }

        return;
    } catch (Throwable $exception) {
        fail_test(sprintf('%s: expected InvalidArgumentException, got %s.', $label, $exception::class));
    }

    fail_test(sprintf('%s: expected InvalidArgumentException.', $label));
}

assert_same(100, sum_sprint_ca_weights([10, 40, 50]), 'Sum valid split');
assert_same(true, validate_sprint_ca_weights(1, [10, 40, 50]), 'Valid split');

assert_same(100, sum_sprint_ca_weights([0, 100]), 'Sum with zero');
assert_same(true, validate_sprint_ca_weights(1, [0, 100]), 'Valid zeros');

assert_invalid_argument(
    static fn () => validate_sprint_ca_weights(2, [30, 30]),
    'RA 2 must sum to 100; got 60',
    'Incorrect total'
);

assert_invalid_argument(
    static fn () => validate_sprint_ca_weights(1, [101]),
    'between 0 and 100',
    'Weight above range'
);

assert_invalid_argument(
    static fn () => validate_sprint_ca_weights(1, [50, '50']),
    'must be an integer',
    'String weight'
);

assert_invalid_argument(
    static fn () => validate_sprint_ca_weights(1, [50.5]),
    'must be an integer',
    'Float weight'
);

assert_invalid_argument(
    static fn () => validate_sprint_ca_weights(1, []),
    'At least one CA is required',
    'Empty weights'
);

assert_invalid_argument(
    static fn () => sum_sprint_ca_weights([-1]),
    'between 0 and 100',
    'Sum helper range check'
);

assert_invalid_argument(
    static fn () => sum_sprint_ca_weights(['10']),
    'must be an integer',
    'Sum helper type check'
);

echo "OK\n";
exit(0);
