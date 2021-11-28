<?php

namespace inRage\Blocks;

use Sober\Controller\Utils;

class Loader
{
    // User
    private $namespace;
    private $path;
    private $directoryClassname;

    // Internal
    private $listOfFiles;
    private $classesToRun = [];

    public function __construct($directoryClassname)
    {
        $this->setDirectoryClassname($directoryClassname);
        $this->setNamespace();

        $this->setPath();

        // Return if there are no Blocks files
        if (!file_exists($this->path)) {
            return;
        }

        // Set the list of files from the Controller files namespace/path
        $this->setListOfFiles();

        // Set the classes to run from the list of files
        $this->setClassesToRun();

        // Set the aliases for static functions from the list of classes to run
        $this->setClassesAlias();
    }

    /**
     * Set Namespace
     *
     * Set the namespace from the filter or use the default
     */
    protected function setNamespace()
    {
        $this->namespace =
            (has_filter('inrage/blocks/namespace')
                ? apply_filters('inrage/blocks/namespace', rtrim($this->namespace))
                : 'App\\' . $this->directoryClassname);
    }

    /**
     * Set Path
     *
     * Set the path assuming PSR4 autoloading from $this->namespace
     */
    protected function setPath()
    {
        $reflection = new \ReflectionClass('App\Controllers\App');
        $blockPath = str_replace('Controllers', $this->directoryClassname, $reflection->getFileName());
        $this->path = dirname($blockPath);
    }

    /**
     * Set File List
     *
     * Recursively get file list and place into array
     */
    protected function setListOfFiles()
    {
        $this->listOfFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->path));
    }

    /**
     * Set Class Instances
     *
     * Load each Class instance and store in $instances[]
     */
    protected function setClassesToRun()
    {
        foreach ($this->listOfFiles as $filename => $file) {
            // Exclude non-PHP files
            if (!Utils::isFilePhp($filename)) {
                continue;
            }

            // Exclude non-Controller classes
            if (!Utils::doesFileContain($filename, 'extends ' . $this->directoryClassname)) {
                continue;
            }

            // Set the classes to run
            $this->classesToRun[] = $this->namespace . '\\' . pathinfo($filename, PATHINFO_FILENAME);
        }
    }

    /**
     * Set Class Alias
     *
     * Remove namespace from static functions
     */
    public function setClassesAlias()
    {
        // Alias each class from $this->classesToRun
        foreach ($this->classesToRun as $class) {
            class_alias($class, (new \ReflectionClass($class))->getShortName());
        }
    }

    /**
     * Get Classes To Run
     *
     * @return array
     */
    public function getClassesToRun()
    {
        return $this->classesToRun;
    }

    private function setDirectoryClassname($directoryClassname)
    {
        $this->directoryClassname = $directoryClassname;
    }
}
