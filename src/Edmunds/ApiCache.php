<?php
namespace CarRived\Edmunds;

/**
 * Stores records as temporary files.
 */
class ApiCache
{
    protected $path;
    private $memoryCache;

    public function __construct($path)
    {
        $this->path = realpath($path);
        $this->memoryCache = [];
    }

    public function has($name)
    {
        $id = md5($name);
        return array_key_exists($id, $this->memoryCache) || file_exists($this->getFileName($id));
    }

    public function fetch($name)
    {
        $id = md5($name);
        if (array_key_exists($id, $this->memoryCache)) {
            return $this->memoryCache[$id];
        } else {
            $filename = $this->getFileName($id);
            $data = file_get_contents($filename);

            $this->memoryCache[$id] = $data;
            return $data;
        }
    }

    public function store($name, $data)
    {
        $id = md5($name);
        $this->memoryCache[$id] = $data;

        $filename = $this->getFileName($id);
        file_put_contents($filename, $data);
    }

    public function delete($name)
    {
        $id = md5($name);
        unset($this->memoryCache[$id]);

        $filename = $this->getFileName($id);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    protected function getFileName($id)
    {
        return $this->path . DIRECTORY_SEPARATOR . md5($id) . '.tmp';
    }
}
