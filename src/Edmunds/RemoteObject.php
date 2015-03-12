<?php
namespace CarRived\Edmunds;

/**
 * A local object that represents remote data.
 */
abstract class RemoteObject
{
    protected $client;
    protected $objectData;

    public function __construct(ApiClient $client, \stdClass $objectData)
    {
        $this->client = $client;
        $this->objectData = $objectData;
    }

    public function getData()
    {
        return $this->objectData;
    }

    public function __get($name)
    {
        return $this->objectData->{$name};
    }

    public function __isset($name)
    {
        return isset($this->objectData->{$name});
    }

    protected function findObjectBy(array $array, $property, $value)
    {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]->{$property} === $value) {
                return $array[$i];
            }
        }

        return null;
    }
}
