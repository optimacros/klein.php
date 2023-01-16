<?php
/**
 * Klein (klein.php) - A fast & flexible router for PHP
 *
 * @author      Chris O'Hara <cohara87@gmail.com>
 * @author      Trevor Suarez (Rican7) (contributor and v2 refactorer)
 * @copyright   (c) Chris O'Hara
 * @link        https://github.com/klein/klein.php
 * @license     MIT
 */

namespace Klein\DataCollection;

use Klein\Route;

/**
 * RouteCollection
 *
 * A DataCollection for Routes
 */
class RouteCollection extends DataCollection
{

    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @override (doesn't call our parent)
     * @param array $routes The routes of this collection
     */
    public function __construct(array $routes = array())
    {
        parent::__construct($routes);
        foreach ($routes as $value) {
            $this->add($value);
        }
    }

    /**
     * Set a route
     *
     * {@inheritdoc}
     *
     * A value may either be a callable or a Route instance
     * Callable values will be converted into a Route with
     * the "name" of the route being set from the "key"
     *
     * A developer may add a named route to the collection
     * by passing the name of the route as the "$key" and an
     * instance of a Route as the "$value"
     *
     * @param string $key The name of the route to set
     * @param mixed $value The value of the route to set
     * @return RouteCollection
     * @see DataCollection::set()
     */
    public function set(string $key, mixed $value): DataCollection
    {
        if (!$value instanceof Route) {
            $value = new Route($value);
        }

        return parent::set($key, $value);
    }

    /**
     * Add a route instance to the collection
     *
     * This will auto-generate a name
     *
     * @param Route $route
     * @return RouteCollection|DataCollection
     */
    public function addRoute(Route $route): RouteCollection|DataCollection
    {
        /**
         * Auto-generate a name from the object's hash
         * This makes it so that we can autogenerate names
         * that ensure duplicate route instances are overridden
         */
        $name = spl_object_hash($route);

        return $this->set($name, $route);
    }

    /**
     * Add a route to the collection
     *
     * This allows a more generic form that
     * will take a Route instance, string callable
     * or any other Route class compatible callback
     *
     * @param callable|Route $route
     * @return RouteCollection|DataCollection
     */
    public function add(callable|Route $route): RouteCollection|DataCollection
    {
        if (!$route instanceof Route) {
            $route = new Route($route);
        }

        return $this->addRoute($route);
    }

    /**
     * Prepare the named routes in the collection
     *
     * This loops through every route to set the collection's
     * key name for that route to equal the routes name, if
     * its changed
     *
     * Thankfully, because routes are all objects, this doesn't
     * take much memory as its simply moving references around
     *
     * @return RouteCollection
     */
    public function prepareNamed(): static
    {
        // Create a new collection so we can keep our order
        $prepared = new static();

        foreach ($this as $route) {
            $route_name = $route->getName();

            if (null !== $route_name) {
                // Add the route to the new set with the new name
                $prepared->set($route_name, $route);
            } else {
                $prepared->add($route);
            }
        }

        // Replace our collection's items with our newly prepared collection's items
        $this->replace($prepared->all());

        return $this;
    }
}
