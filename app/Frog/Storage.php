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
     * This is a name of the directory which store 'pending' contents
     */
    private $bin = 'bin';

    /*
     * MimeType pattern
     *
     * html             text/html
     * js               text/plain
     * css              text/plain
     * .jpg or .jpeg    image/jpeg
     * .png             image/png
     * .gif             image/gif
     * .DS_Store        application/octet-stream
     */
    private $pattern = '/text\/(html|plain)|image\/(jpeg|png|gif)|application\/octet-stream/';

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
        foreach (\Storage::directories($path) as $dir) {
            $name = str_replace_first($path . '/', '', $dir);

            // don't get bin directory
            if ($name == $this->bin) {
                continue;
            }

            $result[] = [
                'id' => encrypt($dir),
                'name' => $name,
                'path' => $path,
                'size' => 0,
                'isFile' => false,
            ];
        }

        // get files
        foreach (\Storage::files($path) as $file) {
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
     * is contained invalid files
     *
     * @param string $path
     * @return boolean
     */
    public function isContainedInvalidFile($path) {
        $isInvalid = false;
        foreach (\Storage::files($path, true) as $file) {
            if (!preg_match($this->pattern, \Storage::mimeType($file))) {
                $isInvalid = true;
                break;
            }
        }

        return $isInvalid;
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