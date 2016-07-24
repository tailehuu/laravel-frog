<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * - must log in
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
     *
     * Process
     * - move file to Storage
     * - extract it
     * - then delete zip file
     *
     * @return void
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('inputFile')) {
            $file = $request->file('inputFile');
            $extension = $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();
            $baseName = substr($fileName, 0, strrpos($fileName, '.'));
            $destinationPath = storage_path() . '/app';

            // validation
            if(strtolower($extension) == 'zip') {
                // file exists ?
                if(\Storage::exists($fileName)) {
                    $message = 'Error. File already exists.';
                    return redirect('upload');
                } else {
                    // move to Storage
                    $file->move($destinationPath, $fileName);

                    // extract it
                    $zip = new \ZipArchive();
                    if ($zip->open($destinationPath . '/' . $fileName) === TRUE) {
                        $extractToPath = $destinationPath . '/' . $baseName;

                        // check folder exist
                        if (is_dir($extractToPath)) {
                            $extractToPath .= '_' . time();
                        }

                        $zip->extractTo($extractToPath);
                        $zip->close();

                        // delete file
                        \Storage::delete($fileName);

                        $message = 'Uploaded.';
                        return redirect('/');
                    } else {
                        // delete file
                        \Storage::delete($fileName);

                        $message = 'Oops. Something went wrong.';
                        return redirect('upload');
                    }
                }
            } else {
                $message = 'Error. Support zip file only.';
                return redirect('upload');
            }
        } else {
            $message = 'Error. Please choose file to upload.';
            return redirect('upload');
        }
    }
}
