<?php
declare(strict_types=1);

/**
 * Add assessment-criterion weights after validating their values.
 * PHP associative array keys are unique; callers must not rely on duplicate IDs.
 *
 * @param array<int|string, mixed> $weights
 */
function sum_sprint_ca_weights(array $weights): int
{
    $sum = 0;

    foreach ($weights as $criterionId => $weight) {
        if (!is_int($weight)) {
            throw new InvalidArgumentException(
                sprintf('Weight for criterion "%s" must be an integer.', (string) $criterionId)
            );
        }

        if ($weight < 0 || $weight > 100) {
            throw new InvalidArgumentException(
                sprintf(
                    'Weight for criterion "%s" must be between 0 and 100 inclusive; got %d.',
                    (string) $criterionId,
                    $weight
                )
            );
        }

        $sum += $weight;
    }

    return $sum;
}

/**
 * Validate that a learning outcome's criterion weights total exactly 100.
 *
 * @param array<int|string, mixed> $weights
 */
function validate_sprint_ca_weights(int|string $raId, array $weights): true
{
    if ($weights === []) {
        throw new InvalidArgumentException(
            sprintf('At least one CA is required when configuring weights for RA %s.', (string) $raId)
        );
    }

    $sum = sum_sprint_ca_weights($weights);

    if ($sum !== 100) {
        throw new InvalidArgumentException(
            sprintf('Weights for RA %s must sum to 100; got %d.', (string) $raId, $sum)
        );
    }

    return true;
}
