<?php
/**
 * @see Zend_Tool_Project_Context_Filesystem_Directory
 */
require_once 'Zend/Tool/Project/Context/Filesystem/Directory.php';

/**
 * This class is used for creating App directory in Zend-Framework-Skeleton
 *
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Context_ZFS_AppLibraryDirectory extends Zend_Tool_Project_Context_Filesystem_Directory
{

    /**
     * @var string
     */
    protected $_filesystemName = 'App';

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'AppLibraryDirectory';
    }

    /**
     * create()
     *
     */
    public function create()
    {
        parent::create();
        $zfPath = $this->_getZfPath();
        if ($zfPath != false) {
            $zfIterator = new RecursiveDirectoryIterator($zfPath);
            foreach ($rii = new RecursiveIteratorIterator($zfIterator, RecursiveIteratorIterator::SELF_FIRST) as $file) {
                $relativePath = preg_replace('#^'.preg_quote(realpath($zfPath), '#').'#', '', realpath($file->getPath())) . DIRECTORY_SEPARATOR . $file->getFilename();
                if (strpos($relativePath, DIRECTORY_SEPARATOR . '.') !== false) {
                    continue;
                }

                if ($file->isDir()) {
                    mkdir($this->getBaseDirectory() . DIRECTORY_SEPARATOR . $this->getFilesystemName() . $relativePath);
                } else {
                    copy($file->getPathname(), $this->getBaseDirectory() . DIRECTORY_SEPARATOR . $this->getFilesystemName() . $relativePath);
                }

            }
        }
    }

    /**
     * _getZfPath()
     *
     * @return string|false
     */
    protected function _getZfPath()
    {
        require_once 'Zend/Loader.php';
        foreach (Zend_Loader::explodeIncludePath() as $includePath) {
            if (!file_exists($includePath) || $includePath[0] == '.') {
                continue;
            }

            if (realpath($checkedPath = rtrim($includePath, '\\/') . '/Zend/Loader.php') !== false && file_exists($checkedPath)) {
                return dirname($checkedPath);
            }
        }

        return false;
    }

}
