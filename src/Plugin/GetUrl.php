<?php

namespace Barryvdh\elFinderFlysystemDriver\Plugin;

use League\Flysystem\Filesystem;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Plugin\AbstractPlugin;
use League\Flysystem\Cached\CachedAdapter;

class GetUrl extends AbstractPlugin
{

    /**
     * @var AdapterInterface
     */
    protected $adapter;
    
    /**
     * @var Adapter has method getUrl()
     */
    private $hasMethod;

    /**
     * Set the Filesystem object.
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        parent::setFilesystem($filesystem);

        if ( $filesystem instanceof Filesystem) {
            $this->adapter = $filesystem->getAdapter();

            // For a cached adapter, get the underlying instance
            if ($this->adapter instanceof CachedAdapter) {
                $this->adapter = $this->adapter->getAdapter();
            }
        }

        $this->hasMethod = (method_exists($this->adapter, 'getUrl'));


    }

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'getUrl';
    }

    /**
     * Get the public url
     *
     * @param string $path  path to file
     *
     * @return string|false
     */
    public function handle($path = null)
    {
        if (is_null($path)) {
            return $this->hasMethod;
        }

        if ( ! $this->hasMethod) {
            return false;
        }

        //TODO: Check on actual implementations, not just an existing method
        return $this->adapter->getUrl($path);
    }

}
