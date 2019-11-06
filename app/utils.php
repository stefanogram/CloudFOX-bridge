<?php

function deleteFilesByPattern(array $patterns)
{
    foreach ($patterns as $pattern) {
        $files = glob($pattern);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

function copyDirectory($src, $dst)
{
    $dir = opendir($src);
    if ($dir === false) {
        return false;
    }

    @mkdir($dst, 0755);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);

    return true;
}

function recursiveRemoveDir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object))
                    recursiveRemoveDir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        rmdir($dir);
    }
}

function rrmdir($path, array $exclude_paths = [])
{
    if (is_dir($path)) {
        $paths = glob($path . DIRECTORY_SEPARATOR . '{,.[!.]}*', GLOB_BRACE);
        foreach ($paths as $one_path) {
            if (in_array($one_path, $exclude_paths)) {
                continue;
            }
            rrmdir($one_path);
        }

        @rmdir($path);
    } else {
        @unlink($path);
    }
}

function folder_exist($folder)
{
    $path = realpath($folder);

    return ($path !== false && is_dir($path)) ? true : false;
}

function post_deploy_action($params)
{
    if (folder_exist($params['source_latest']) && folder_exist($params['source_backup'])) {
        rrmdir($params['source_current'], $params['exclude_remove_dir']);
        if ($params['success']) {
            copyDirectory($params['source_latest'], $params['dist']);
        } else {
            copyDirectory($params['source_backup'], $params['dist']);
        }

        recursiveRemoveDir($params['source_latest']);
        recursiveRemoveDir($params['source_backup']);
    }
}