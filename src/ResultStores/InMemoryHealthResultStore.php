<?php

namespace Spatie\Health\ResultStores;

use Illuminate\Support\Collection;
use Spatie\Health\Checks\Result;
use Spatie\Health\ResultStores\StoredCheckResults\StoredCheckResult;
use Spatie\Health\ResultStores\StoredCheckResults\StoredCheckResults;

class InMemoryHealthResultStore implements ResultStore
{
    protected StoredCheckResults $storedCheckResults;

    public function save(Collection $checkResults): void
    {
        $this->storedCheckResults = new StoredCheckResults(now());

        $checkResults
            ->map(function (Result $result) {
                return new StoredCheckResult(
                    name: $result->check->getName(),
                    label: $result->check->getLabel(),
                    notificationMessage: $result->getNotificationMessage(),
                    shortSummary: $result->getShortSummary(),
                    status: (string)$result->status->value,
                    meta: $result->meta,
                );
            })
            ->each(function (StoredCheckResult $check) {
                $this->storedCheckResults->addCheck($check);
            });
    }

    public function latestResults(): ?StoredCheckResults
    {
        return $this->storedCheckResults;
    }
}
