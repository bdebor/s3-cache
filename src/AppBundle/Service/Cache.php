<?php

namespace AppBundle\Service;

class Cache extends \Twig_Extension {

    private $folderPath;
    private $cacheName;

    public function __construct() {
        $this->folderPath = __DIR__ . '\cache';
    }

    public function read($fileName, $lifeTime = null) { // $lifeTime in seconds
        $filePath = $this->folderPath.'/'.$fileName.'.txt';
        if (file_exists($filePath)) {
            if($lifeTime){
                if(time() - filemtime($filePath) > $lifeTime) {
                    return false;
                }
            }
            return file_get_contents($filePath);
        } else {
            return false;
        }
    }

    public function write($fileName, $data) {
        file_put_contents($this->folderPath.'/'.$fileName.'.txt', $data);
    }

    public function delete($fileName) {
        $filePath = $this->folderPath . '/' . $fileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function clear() {
        $filePaths = glob($this->folderPath . '/*');
        print_r($filePaths);
        foreach ($filePaths as $filePath) {
            unlink($filePath);
        }
    }

    /**/ // custom Twig filter to include a cached file

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('includeCache', array($this, 'includeCache'), [
                'is_safe' => ['html']
            ])
        ];
    }

    public function includeCache($filePath) {
        $fileName = basename($filePath);

        if ($this->read($fileName)) {
            $data = $this->read($fileName);
        } else {
            ob_start();
            require($filePath);
            $data = ob_get_clean();
            $this->write($fileName, $data);
        }

        return $data;
    }

    /**/ // to cache some code

    public function start($cacheName) {
        $this->cacheName = $cacheName;
        if (!$this->read($cacheName)) {
            ob_start();
            return false;
        }
        return true;
    }

    public function end() {
        if (!$this->read($this->cacheName)) {
            echo $data = ob_get_clean();
            $this->write($this->cacheName, $data);
        } else {
            echo $this->read($this->cacheName);
        }
    }
}
