<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GoogleDriveController extends Controller
{

    public function put($filename, $fileData)
    {
        Storage::cloud()->put($filename, $fileData);
        return 'File was saved to Google Drive';
    }

    /**
     * @param $filename
     * @param $fileData
     * @return string
     */
    public function putFromLocal($srcPath, $dstPath)
    {

        $fileData = File::get($srcPath);

        Storage::cloud()->put($dstPath, $fileData);
        return 'File was saved to Google Drive';
    }

    public function list($dir = '/', $recursive = false, $fileOrDir = 'file')
    {
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));
        return $contents->where('type', '=', $fileOrDir); // file or dir

    }

    public function listFolderContents($folderName, $root = '/')
    {

        $contents = collect(Storage::cloud()->listContents($root, false));

        // Find the folder you are looking for...
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $folderName)
            ->first(); // There could be duplicate directory names!

        if (!$dir) {
            return 'No such folder!';
        }

        // Get the files inside the folder...
        $files = collect(Storage::cloud()->listContents($dir['path'], false))
            ->where('type', '=', 'file');

        return $files->mapWithKeys(function ($file) {
            $filename = $file['filename'] . '.' . $file['extension'];
            $path = $file['path'];

            // Use the path to download each file via a generated link..
            // Storage::cloud()->get($file['path']);

            return [$filename => $path];
        });

    }

    public function get($fileName = 'test.txt', $dir = '/', $getSubsAlso = false)
    {
        $fileName = explode('/', $dir)[count(explode('/', $dir)) - 1];

        $contents = collect(Storage::cloud()->listContents($dir, $getSubsAlso));
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($fileName, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($fileName, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        //return $file; // array with file info

        $rawData = Storage::cloud()->get($file['path']); //if return $rawData =>open file in browser

        return response($rawData, 200)
            ->header('ContentType', $file['mimetype'])
            ->header('Content-Disposition', "attachment; filename='$fileName'"); //download file
    }

    public function putGetStream($filename, $filePath = '/', $dir = '/', $getSubsAlso = false)
    {
        // Use a stream to upload and download larger files
        // to avoid exceeding PHP's memory limit.

        // Thanks to @Arman8852's comment:
        // https://github.com/ivanvermeyen/laravel-google-drive-demo/issues/4#issuecomment-331625531
        // And this excellent explanation from Freek Van der Herten:
        // https://murze.be/2015/07/upload-large-files-to-s3-using-laravel-5/

        // Assume this is a large file...

        // Upload using a stream...
        Storage::cloud()->put($filename, fopen($filePath, 'r+'));


        $contents = collect(Storage::cloud()->listContents($dir, $getSubsAlso));

        // Get file details...
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        //return $file; // array with file info

        // Store the file locally...
        //$readStream = Storage::cloud()->getDriver()->readStream($file['path']);
        //$targetFile = storage_path("downloaded-{$filename}");
        //file_put_contents($targetFile, stream_get_contents($readStream), FILE_APPEND);

        // Stream the file to the browser...
        $readStream = Storage::cloud()->getDriver()->readStream($file['path']);

        return response()->stream(function () use ($readStream) {
            fpassthru($readStream);
        }, 200, [
            'Content-Type' => $file['mimetype'],
            //'Content-disposition' => 'attachment; filename="'.$filename.'"', // force download?
        ]);
    }

    public function createDir($path)
    {
        Storage::cloud()->makeDirectory($path);
        return 'Directory was created in Google Drive';
    }

    public function createSubDir($parentPath, $subFolderName, $getSubsAlso = false)
    {
        // Create parent dir
        Storage::cloud()->makeDirectory($parentPath);

        // Find parent dir for reference

        // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($parentPath, $getSubsAlso));

        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $subFolderName)
            ->first(); // There could be duplicate directory names!

        if (!$dir) {
            return 'Directory does not exist!';
        }

        // Create sub dir
        Storage::cloud()->makeDirectory($dir['path'] . "/$subFolderName");

        return 'Sub Directory was created in Google Drive';
    }

    public function putInDir($dir, $dirFolderName, $fileName, $fileContent, $getSubsAlso = false)
    {
// dir is dir/dirFolderName
        $contents = collect(Storage::cloud()->listContents($dir, $getSubsAlso));

        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $dirFolderName)
            ->first(); // There could be duplicate directory names!

        if (!$dir) {
            return 'Directory does not exist!';
        }

        Storage::cloud()->put($dir['path'] . "/$fileName", $fileContent);

        return 'File was created in the sub directory in Google Drive';
    }

    public function newest($dir, $filename, $getSubsAlso = false)
    {
        Storage::cloud()->put($filename, \Carbon\Carbon::now()->toDateTimeString());

        $file = collect(Storage::cloud()->listContents($dir, $getSubsAlso))
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->sortBy('timestamp')
            ->last();

        return Storage::cloud()->get($file['path']);
    }

    public function delete($path)
    {

        Storage::cloud()->delete($path);

    }

    public function deleteDir($path)
    {

        Storage::cloud()->deleteDirectory($path);

    }


    public function renameDir($path, $newPath)
    {

        Storage::cloud()->move($path, $newPath);

    }

    public function share($dir, $filename)
    {
        $contents = collect(Storage::cloud()->listContents($dir, false));
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!
        // Change permissions
        // - https://developers.google.com/drive/v3/web/about-permissions
        // - https://developers.google.com/drive/v3/reference/permissions
        $service = Storage::cloud()->getAdapter()->getService();
        $permission = new \Google_Service_Drive_Permission();
        $permission->setRole('reader');
        $permission->setType('anyone');
        $permission->setAllowFileDiscovery(false);
        $permissions = $service->permissions->create($file['basename'], $permission);

        return Storage::cloud()->url($file['path']);
    }

    public function export($dir, $filename, $mimeType)
    {
        $service = Storage::cloud()->getAdapter()->getService();

        $export = $service->files->export($filename, $mimeType);

        return response($export->getBody(), 200, $export->getHeaders());
    }

}