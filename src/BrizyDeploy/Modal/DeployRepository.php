<?php

namespace BrizyDeploy\Modal;

class DeployRepository extends BaseRepository
{
    public function __construct()
    {
        $this->db_file_path = __DIR__ . '/../../../var/deploy';
    }
}