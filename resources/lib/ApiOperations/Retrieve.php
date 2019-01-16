<?php
namespace ApiOperations;
/**
 * Trait for retrievable resources. Adds a `retrieve()` static method to the class.
 *
 * This trait should only be applied to classes that derive from \Zype\Clients\Api.
 */
trait Retrieve
{
    /**
     * @param string $id The ID of the resource to retrieve
     *
     * @return The object for the given class
     */
    public static function retrieve($id, $format = 'json')
    {
        $path = self::get_path($id) . '.' . $format;
        return self::request("GET", $path, null, false, true);
    }
}
