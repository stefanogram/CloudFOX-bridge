<?php

namespace BrizyDeploy\Modal;

class AppRepository extends BaseRepository
{
    public function __construct()
    {
        $this->db_file_path = __DIR__ . '/../../../var/app';
    }
}