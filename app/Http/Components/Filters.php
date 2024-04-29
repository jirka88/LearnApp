<?php

namespace App\Http\Components;

abstract class Filters {
    public const DEFAULT_VALUE = 'default';

    /**
     * Slouží pro filtraci dat
     *
     * @param  $sort  - DEFAULT/ASC/DESC
     */
    abstract public function sorting($sort);
}
