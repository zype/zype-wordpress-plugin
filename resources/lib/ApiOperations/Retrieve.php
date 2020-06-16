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
     * @param array $options, possible options
     *  $format: 'json', 'xml', 'csv. 'json' by default
     *  $cache: true or false. true by default
     *
     * @return The object for the given class
     */
    public static function retrieve($id, $options = ['format' => 'json', 'cache' => true])
    {
        $options['format'] = $options['format']?: 'json';
        $options['cache'] = isset($options['cache']) ? $options['cache'] : true;
        $path = self::get_path($id) . '.' . $options['format'];
        return self::request("GET", $path, null, false, $options['cache']);
    }
}
