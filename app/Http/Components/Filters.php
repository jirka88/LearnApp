<?php
namespace App\Http\Components;
use App\Models\Partition;
use Illuminate\Http\JsonResponse;

abstract class Filters {
    public const DEFAULT_VALUE = 'default';

    /**
     * Slouží pro filtraci dat
     * @param $sort - DEFAULT/ASC/DESC
     */
    public abstract function sorting($sort);
}
