<?php

namespace Orchid\Screen\Resolvers;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Orchid\Screen\Screen;
use ReflectionClass;
use ReflectionParameter;

class ScreenDependencyResolver
{
    /**
     * @throws \ReflectionException
     */
    public function resolveScreen(Screen $screen, string $method, array $httpQueryArguments = []): array
    {
        $parameters = (new ReflectionClass($screen))->getMethod($method)->getParameters();

        $arguments = collect($httpQueryArguments);

        return collect($parameters)
            ->map(function (ReflectionParameter $parameter) use (&$arguments) {
                return $this->bind($parameter, $arguments);
            })
            ->all();
    }

    /**
     * It takes the serial number of the argument and the required parameter.
     * To convert to object.
     *
     *
     * @throws \Throwable
     *
     * @return mixed
     */
    private function bind(ReflectionParameter $parameter, Collection $httpQueryArguments)
    {
        $class = $parameter->getType() && ! $parameter->getType()->isBuiltin()
            ? $parameter->getType()->getName()
            : null;

        if ($class === null) {
            return $httpQueryArguments->shift();
        }

        $instance = resolve($class);

        if (! is_a($instance, UrlRoutable::class)) {
            return $instance;
        }

        $value = $httpQueryArguments->shift();

        if ($value === null) {
            return $instance;
        }

        $model = is_a($value, $class) ? $value : $instance->resolveRouteBinding($value);

        throw_if(
            $model === null && ! $parameter->isDefaultValueAvailable(),
            (new ModelNotFoundException())->setModel($class, [$value])
        );

        optional(Route::current())->setParameter($parameter->getName(), $model);

        return $model;
    }
}
