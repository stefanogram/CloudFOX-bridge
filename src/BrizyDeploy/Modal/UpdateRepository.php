<?php

namespace BrizyDeploy\Modal;

class UpdateRepository extends BaseRepository
{
    public function __construct()
    {
        $this->db_file_path = __DIR__ . '/../../../var/update';
    }
}