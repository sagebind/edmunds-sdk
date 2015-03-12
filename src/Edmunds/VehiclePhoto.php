<?php
namespace CarRived\Edmunds;

/**
 * Represents a particular photo of a specific vehicle style.
 */
class VehiclePhoto extends RemoteObject
{
    public function getUrl($size)
    {
        foreach ($this->photoSrcs as $url) {
            $photoSize = intval(substr($url, strrpos($url, '_') + 1, -4));
            if ($this->getSizeFromUrl($url) === $size) {
                return 'https://media.ed.edmunds-media.com' . $url;
            }
        }

        return null;
    }

    /**
     * Gets the URL of the best quality image.
     *
     * @return string
     */
    public function getBestQualityUrl()
    {
        $bestUrl = null;
        $bestSize = 0;

        foreach ($this->photoSrcs as $url) {
            if ($this->getSizeFromUrl($url) > $bestSize) {
                $bestUrl = $url;
                $bestSize = $this->getSizeFromUrl($url);
            }
        }

        return 'https://media.ed.edmunds-media.com' . $bestUrl;
    }

    protected function getSizeFromUrl($url)
    {
        return intval(substr($url, strrpos($url, '_') + 1, -4));
    }
}
