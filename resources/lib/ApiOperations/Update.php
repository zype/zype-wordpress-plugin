<?php
namespace ApiOperations;
/**
 * Trait for updatable resources. Adds a `update()` static method to the class.
 *
 * This trait should only be applied to classes that derive from extends \Zype\Clients\Api.
 */
trait Update
{
    /**
     * @param string $id The ID of the resource to update
     * @param array $body
     *
     * @return The object updated for the given class
     */
    public static function update($id, $body = [])
    {
        $path = self::get_path($id) . '.' . $format;
        return self::request("PUT", $path, $body);
    }
}
