<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * - should auth
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show upload form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('upload.index');
    }

    /**
     * Upload file.
     *
     * Validation rules:
     * - zip file only
     * - zip file contains html, css, js & images only
     *
     * Process
     * step 1 - move zip file to bin
     * step 2 - extract contents
     * step 3 - delete zip file
     * step 4 - validate contents (html, css, js & images only)
     * step 5 - move contents to app
     *
     * @return void
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('inputFile')) {
            $file = $request->file('inputFile');
            $extension = $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();
            $baseName = substr($fileName, 0, strrpos($fileName, '.')) . '_' . time();

            // change file name to avoid duplicate
            $fileName = $baseName . '.' . $extension;

            // TODO: this path may be not work when change to S3
            $binPath = storage_path() . '/app/bin';

            // validation
            if (strtolower($extension) == 'zip') {
                // step 1 - move zip file to bin
                $file->move($binPath, $fileName);

                // step 2 - extract contents
                $zip = new \ZipArchive();
                if ($zip->open($binPath . '/' . $fileName) === TRUE) {
                    $extractToPath = $binPath . '/' . $baseName;

                    $zip->extractTo($extractToPath);
                    $zip->close();

                    // step 3 - delete zip file
                    \Storage::delete('bin/' . $fileName);

                    // step 4 - validate contents (html, css, js & images only)
                    $frogStorage = new \App\Frog\Storage();
                    if ($frogStorage->isContainedInvalidFile('bin/' . $baseName)) {
                        // remove contents from bin
                        File::deleteDirectory($extractToPath);

                        $request->session()->flash('status', 'The zip file should contain html, css, js & images only.');
                        return redirect('upload');
                    } else {
                        // step 5 - move contents to app
                        $success = File::copyDirectory($extractToPath, storage_path() . '/app/' . $baseName);
                        File::deleteDirectory($extractToPath);

                        if ($success) {
                            $request->session()->flash('status', 'Uploaded.');
                            return redirect('/');
                        } else {
                            $request->session()->flash('status', 'Oops. Something went wrong.');
                            return redirect('upload');
                        }
                    }
                } else {
                    // delete file
                    \Storage::delete($fileName);

                    $request->session()->flash('status', 'Oops. Something went wrong.');
                    return redirect('upload');
                }
            } else {
                $request->session()->flash('status', 'Support zip file only.');
                return redirect('upload');
            }
        } else {
            $request->session()->flash('status', 'Please choose file to upload.');
            return redirect('upload');
        }
    }
}
