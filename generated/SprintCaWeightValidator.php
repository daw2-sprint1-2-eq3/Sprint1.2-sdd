<?php

declare(strict_types=1);

/**
 * Sprint CA weight checks for one learning outcome (RA).
 *
 * Callers must not rely on duplicate criterion ids. A PHP associative
 * array keeps only the last value for a repeated key.
 */

function sum_sprint_ca_weights(array $weights): int
{
    $sum = 0;

    foreach ($weights as $weight) {
        if (!is_int($weight) || $weight < 0 || $weight > 100) {
            throw new InvalidArgumentException(
                'Weight must be an integer between 0 and 100.'
            );
        }

        $sum += $weight;
    }

    return $sum;
}

function validate_sprint_ca_weights(int|string $raId, array $weights): true
{
    if ($weights === []) {
        throw new InvalidArgumentException(
            'RA ' . $raId . ' requires at least one assessment criterion.'
        );
    }

    $sum = sum_sprint_ca_weights($weights);

    if ($sum !== 100) {
        throw new InvalidArgumentException(
            'RA ' . $raId . ' weights must sum to 100, got ' . $sum . '.'
        );
    }

    return true;
}
