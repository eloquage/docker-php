<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Support;

/**
 * Wrapper around net_get_interfaces() to retrieve available host network interfaces.
 *
 * @see https://www.php.net/manual/en/function.net-get-interfaces.php
 */
final class NetworkInterfaces
{
    /**
     * Get list of available host network interface names.
     *
     * @param  bool  $upOnly  When true, only return interfaces that are up. Default true.
     * @return array<int, string> Sorted list of interface names.
     */
    public static function getInterfaceNames(bool $upOnly = true): array
    {
        if (! function_exists('net_get_interfaces')) {
            return [];
        }

        $interfaces = net_get_interfaces();
        if ($interfaces === false) {
            return [];
        }

        $names = [];
        foreach ($interfaces as $name => $attrs) {
            if ($upOnly && isset($attrs['up']) && $attrs['up'] !== true) {
                continue;
            }
            $names[] = $name;
        }

        sort($names, SORT_NATURAL);

        return array_values($names);
    }
}
