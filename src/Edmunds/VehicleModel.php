<?php
namespace CarRived\Edmunds;

/**
 * A vehicle model is specific vehicle brand identified by a name or number
 * (and which is usually further classified by trim or style level).
 */
class VehicleModel extends RemoteObject
{
    /**
     * Gets the make of the model.
     *
     * @return VehicleMake
     */
    public function getMake()
    {
        return new VehicleMake($this->client, $this->make);
    }

    public function getYears($state = null, $submodel = null, $category = null)
    {
        // check if years have been cached
        if (isset($this->years)) {
            return array_map(function ($object) {
                // pass in a reference to this make and model
                $object->make = $this->make;
                $object->model = new \stdClass();
                $object->model->id = $this->id;
                $object->model->name = $this->name;
                $object->model->niceName = $this->niceName;

                return new VehicleModelYear($this->client, $object);
            }, $this->years);
        }

        return $this->client->getModelYears($this->make->niceName, $this->niceName, $state, $submodel, $category);
    }

    public function getYear($year)
    {
        // check if years have been cached
        if (isset($this->years)) {
            $object = $this->findObjectBy($this->years, 'year', (int)$year);

            if ($object === null) {
                throw new ApiException("The model year does not exist.", 404);
            }

            // pass in a reference to this make and model
            $object->make = $this->make;
            $object->model = new \stdClass();
            $object->model->id = $this->id;
            $object->model->name = $this->name;
            $object->model->niceName = $this->niceName;

            return new VehicleModelYear($this->client, $object);
        }

        return $this->client->getModelYear($this->make->niceName, $this->niceName, $year);
    }
}
