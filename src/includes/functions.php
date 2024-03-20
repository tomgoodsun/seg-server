<?php

use App\Data\SuperGlobalVariable;

function snakeToCamel(string $input): string
{
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $input)));
}

function camelToSnake(string $input): string
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}

function isCamel(string $input): bool
{
    return preg_match('/^[a-z]+(?:[A-Z][a-z]+)*$/', $input) === 1;
}

function isPascal(string $input): bool
{
    return preg_match('/^[A-Z][a-z]+(?:[A-Z][a-z]+)*$/', $input) === 1;
}

function sgv(): SuperGlobalVariable
{
    return SuperGlobalVariable::getInstance();
}
