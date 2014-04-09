<?php

class AgroLogistics_Cache_Backend_File extends Zend_Cache_Backend_File
{
    protected $_options = array(
        'cache_dir' => 'gs://cache',
        'file_locking' => true,
        'read_control' => true,
        'read_control_type' => 'crc32',
        'hashed_directory_level' => 0,
        'hashed_directory_umask' => 0700,
        'file_name_prefix' => 'zend_cache',
        'cache_file_umask' => 0600,
        'metadatas_array_max_size' => 100
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
    }
    
    public function getTmpDir()
    {
        return 'gs://cache';
    }
}
