<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use ReflectionClass;
use ReflectionMethod;

trait RelationsManager
{
    protected static $relationsList = [];

    protected static $relationsInitialized = false;

    protected static $relationClasses = [
        HasOne::class,
        HasMany::class,
        BelongsTo::class,
        BelongsToMany::class,

        HasOneThrough::class,
        HasManyThrough::class,

        MorphTo::class,
        MorphOne::class,
        MorphMany::class,
        MorphToMany::class,

    ];

    public static function getAllRelations($type = null): array
    {
        if (!self::$relationsInitialized) {
            self::initAllRelations();
        }

        return $type ? (self::$relationsList[$type] ?? []) : self::$relationsList;
    }

    protected static function initAllRelations()
    {
        self::$relationsInitialized = true;

        $reflect = new ReflectionClass(static::class);

        foreach ($reflect->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            /** @var ReflectionMethod $method */
            if ($method->hasReturnType() && in_array((string) $method->getReturnType(), self::$relationClasses)) {
                self::$relationsList[(string) $method->getReturnType()][] = $method->getName();
            }
        }
    }

    public static function withAll(): Builder
    {
        $relations = array_flatten(static::getAllRelations());
        return $relations ? self::with($relations) : self::query();
    }
}
