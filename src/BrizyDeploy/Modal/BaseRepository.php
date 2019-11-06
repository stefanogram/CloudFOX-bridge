<?php

namespace BrizyDeploy\Modal;

class BaseRepository
{
    protected  $db_file_path;

    public function create($object)
    {
        file_put_contents($this->db_file_path, serialize($object));
    }

    public function get()
    {
        return unserialize(file_get_contents($this->db_file_path));
    }

    public function update($object)
    {
        file_put_contents($this->db_file_path, serialize($object));
    }
}