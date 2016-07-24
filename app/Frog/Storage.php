<?php

namespace App\Frog;
use Illuminate\Support\Facades\Crypt;

/**
 * A helper class to manipulate files, directories or content
 *
 * @author Tai LE <lhtai181@gmail.com>
 */

class Storage {

    /*
     * Get files & directories in a path
     *
     * @param string $encryptPath
     * @return Array
     */
    public function files($encryptedPath) {
        $path = decrypt($encryptedPath);
        $result = [];

        // get directories, no recursive
        $dirs = \Storage::directories($path);
        foreach ($dirs as $dir) {
            $name = str_replace_first($path . '/', '', $dir);
            $result[] = [
                'id' => encrypt($dir),
                'name' => $name,
                'path' => $path,
                'size' => 0,
                'isFile' => false,
            ];
        }

        // get files
        $files = \Storage::files($path);
        foreach ($files as $file) {
            $name = str_replace_first($path . '/', '', $file);
            $result[] = [
                'id' => encrypt($file),
                'name' => $name,
                'path' => $path,
                'size' => \Storage::size($file),
                'isFile' => true,
            ];
        }

        return $result;
    }

    /*
     * Encrypt a path
     *
     * @param string $path
     * @return string
     */
    private function encrypt($path) {
        return Crypt::encrypt($path);
    }

    /*
     * Decrypt a path
     *
     * @param string $encryptedPath
     * @return string
     */
    private function decrypt($encryptedPath) {
        try {
            $decrypted = Crypt::decrypt($encryptedPath);
        } catch (DecryptException $e) {
            $decrypted = '';
        }
        return $decrypted;
    }
}