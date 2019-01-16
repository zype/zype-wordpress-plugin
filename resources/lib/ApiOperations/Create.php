<?php
namespace ApiOperations;
/**
 * Trait for creatable resources. Adds a `create()` static method to the class.
 *
 * This trait should only be applied to classes that derive from extends \Zype\Clients\Api.
 */
trait Create
{
    /**
     * @param array $body
     *
     * @return The object created for the given class
     */
    public static function create($body = [])
    {
        $path = self::get_path();
        return self::request("POST", $path, $body);
    }
}
