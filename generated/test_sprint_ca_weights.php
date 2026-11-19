<?php

declare(strict_types=1);

require_once __DIR__ . '/SprintCaWeightValidator.php';

function fail(string $message): void
{
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}

function expect_exception(string $label, callable $action, string $needle): void
{
    try {
        $action();
    } catch (InvalidArgumentException $exception) {
        if (!str_contains($exception->getMessage(), $needle)) {
            fail($label . ' message was: ' . $exception->getMessage());
        }

        return;
    }

    fail($label . ' did not throw.');
}

$sum = sum_sprint_ca_weights([10, 40, 50]);
if ($sum !== 100) {
    fail('Valid split sum was ' . $sum . '.');
}

if (validate_sprint_ca_weights(1, [10, 40, 50]) !== true) {
    fail('Valid split did not return true.');
}

if (validate_sprint_ca_weights(1, [0, 100]) !== true) {
    fail('Zeros were rejected.');
}

expect_exception(
    'Sum not 100',
    static function (): void {
        validate_sprint_ca_weights(2, [30, 30]);
    },
    'got 60'
);

expect_exception(
    'Weight out of range',
    static function (): void {
        validate_sprint_ca_weights(1, [101]);
    },
    'between 0 and 100'
);

expect_exception(
    'Non-integer string',
    static function (): void {
        validate_sprint_ca_weights(1, [50, '50']);
    },
    'between 0 and 100'
);

expect_exception(
    'Non-integer float',
    static function (): void {
        validate_sprint_ca_weights(1, [50.5]);
    },
    'between 0 and 100'
);

expect_exception(
    'Empty map',
    static function (): void {
        validate_sprint_ca_weights(1, []);
    },
    'RA 1'
);

echo "OK\n";
exit(0);
