<?php
namespace ApiOperations;
/**
 * Trait for listable resources. Adds a `all()` static method to the class.
 *
 * This trait should only be applied to classes that derive from extends \Zype\Clients\Api.
 */
trait All
{
    /**
     * @param array $query_params
     *
     * @return all objects for given class
     */
    public static function all($query_params = [], $with_pagination = true)
    {
        $path = self::get_path();
        return self::request('GET', $path, $query_params, false, true);
    }
}
