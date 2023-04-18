<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploadController extends AdminController
{

    public function uploadProfileImage($file)
    {
        $fileName = $file->getClientOriginalName();
        $fileNamePhp = $file->getFilename();
        $year = Carbon::now()->year;
        $imagePath = "/upload/images/{$year}/profile/{$fileNamePhp}/";
        if (!file_exists($imagePath)) {
            if (!mkdir($imagePath) && !is_dir($imagePath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $imagePath));
            }
        }
        Image::make($file->getRealPath())->save($imagePath . $fileName, 80, 'jpg');
//        $fileUrl = $file->storeAs($imagePath, $fileName, 'public');
//        $fileUrl = Storage::disk('public')->putAs($imagePath, $file);
//        $file = $file->move(public_path($imagePath), $fileName);
        $sizes = ["100x200"];
        $url['images'] = $this->resize($file->getRealPath(), $sizes, $imagePath, $fileName, 20, 'jpg');
        $url['thumb'] = $url['images']['original'];
        return $url;
    }

    public function uploadImage($file)
    {
        $fileName = $file->getClientOriginalName();
        $fileNamePhp = $file->getFilename();
        $year = Carbon::now()->year;
//        $document_file = $file->storeAs( env('FTP_PREFIX_DIRECTION') . '/' . env('FTP_PREFIX_FOLDER') . "/images/{$year}/{$fileName}/", $fileName ,  'ftp');
//        $document_file_url = "http://". env('FTP_DOMAIN') . '/' . explode('www/' , $document_file)[1];
        $imagePath = "/upload/images/{$year}/{$fileNamePhp}/";

        if (!file_exists(public_path($imagePath))) {
            if (!mkdir(public_path($imagePath), 0777, true) && !is_dir(public_path($imagePath))) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', public_path($imagePath)));
            }
        }
        Image::make($file->getRealPath())->save(public_path($imagePath . $fileName), 80, 'jpg');
//        $fileUrl = $file->storeAs($imagePath, $fileName , 'public');
//        $fileUrl = Storage::disk('public')->putAs($imagePath, $file);
//        $file = $file->move(public_path($imagePath), $fileName);
        $sizes = ["243x200", "235x190", "75x75"];
        $url['images'] = $this->resize($file->getRealPath(), $sizes, $imagePath, $fileName, 20, 'jpg');
        $url['thumb'] = $url['images']['original'];
        return $url;
    }

    public function uploadImageWithDimensions($file, $dimensions)
    {
        $fileName = $file->getClientOriginalName();
        $fileNamePhp = $file->getFilename();
        $year = Carbon::now()->year;
//        $document_file = $file->storeAs( env('FTP_PREFIX_DIRECTION') . '/' . env('FTP_PREFIX_FOLDER') . "/images/{$year}/{$fileName}/", $fileName ,  'ftp');
//        $document_file_url = "http://". env('FTP_DOMAIN') . '/' . explode('www/' , $document_file)[1];
        $imagePath = "/upload/images/{$year}/{$fileNamePhp}/";

        if (!file_exists(public_path($imagePath))) {
            if (!mkdir(public_path($imagePath)) && !is_dir(public_path($imagePath))) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', public_path($imagePath)));
            }
        }
        Image::make($file->getRealPath())->save(public_path($imagePath . $fileName), 80, 'jpg');
//        $fileUrl = $file->storeAs($imagePath, $fileName , 'public');
//        $fileUrl = Storage::disk('public')->putAs($imagePath, $file);
//        $file = $file->move(public_path($imagePath), $fileName);
        $sizes = [$dimensions];
        $url['images'] = $this->resize($file->getRealPath(), $sizes, $imagePath, $fileName, 80, 'jpg');
        $url['thumb'] = $url['images']['original'];
        return $url;
    }

    public function saveFile(UploadedFile $file, $driver)
    {
        $file_extension = $file->getClientOriginalExtension();
        $fileName = $this->createFilename($file, $driver);
        $parentFilePath = $this->createParentFilePath($file_extension, $driver);
        $filePath = $parentFilePath . $fileName;


        $isFileExists = Storage::disk($driver)->exists($filePath);
        if (!$isFileExists) {
            if ($driver === "local") {
                $fileUrl = $file->storeAs($parentFilePath, $fileName, $driver);

            } else {
                $disk = Storage::disk($driver);
                $fileUrl = $disk->putFileAs($parentFilePath, $file, $fileName);
//                unlink($file->getPathname());
            }

            return response([
            'data' => [
                'fileUrl' => $driver === "local" ? $filePath : env('STORAGE_URL') . "/" . $fileUrl,
                'filePath' => $filePath,
                'fileExtension' => $file_extension,
                'driver' => $driver
            ],
            'message' => 'فایل با موفقیت آپلود شد.',
        ], 200);

        } else {
            return false;
        }
    }

//    public function uploadVideo($file, $driver)
//    {
//        $saved_file_response = $this->saveFile($file, 'local');
//        $parentFilePath = $this->createParentFilePath($saved_file_response['fileExtension'], $driver);
//        $video_encoder = new VideoEncoderController();
//        $saved_file_in_driver_via_ffmpeg_response = $video_encoder->saveVideoFileViaFFMpeg($saved_file_response['fileUrl'], $parentFilePath, $driver);
//        Storage::disk('local')->delete($saved_file_response['filePath']);
//        switch ($driver) {
//            case "public"  :
//                $videoUrl = asset("storage" . $saved_file_in_driver_via_ffmpeg_response['file_path']);
//                break;
//            case "sftp":
//            default :
//                $videoUrl = env('STORAGE_URL') . $saved_file_in_driver_via_ffmpeg_response['file_path'];
//                break;
//        }
//        return response([
//            'data' => [
//                'videoUrl' => $videoUrl,
//                'videoTime' => $saved_file_in_driver_via_ffmpeg_response['video_duration'],
//            ],
//            'message' => 'فایل ویدیویی با موفقیت آپلود شد.',
//        ], 200);
//    }


    public function streamUploadFile(FileReceiver $receiver)
    {
        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need
            return $this->saveFile($save->getFile(), 'local');
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();
        return response()->json([
            "done" => $handler->getPercentageDone()
        ]);
    }



}
