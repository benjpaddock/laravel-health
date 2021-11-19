<?php

namespace Spatie\Health\ResultStores;

use Illuminate\Support\Collection;

class ResultStores
{
    /** @return Collection<int, \Spatie\Health\ResultStores\ResultStore> */
    public static function createFromConfig(): Collection
    {
        $configValues = config('health.result_stores');

        return collect($configValues)
            ->mapWithKeys(function (mixed $value, mixed $key) {
                $className = is_array($value) ? $key : $value;

                $parameters = is_array($value) ? $value : [];

                return [$className => $parameters];
            })
            ->map(function (array $parameters, string $className): ResultStore {
                return app($className, $parameters);
            });
    }
}
