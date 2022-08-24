<?php
/**
 * Created by PhpStorm.
 * User: mahmood
 * Date: 8/24/22
 * Time: 1:35 PM
 */

namespace App\Services;


use App\Models\File;
use App\Traits\ServiceResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class FileService
{
    use ServiceResponseTrait;

    public function create()
    {
        return view("fileUpload");
    }

    public function uploadFile(Collection $inputs)
    {
        $file = $inputs->get('file');
        if (!$file) {
            return $this->error(Response::HTTP_UNPROCESSABLE_ENTITY, "file not found");
        }
        $filePath = storage_path('app/public/upload/testFile');

        if (!file_exists($filePath)) {

            if (!mkdir($filePath, 0777, true)) {
                return response()->json(["ok" => 0, "info" => "Failed to create $filePath"]);
            }
        }
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
        $filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;

        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");

        if ($out) {
            $in = fopen($_FILES['file']['tmp_name'], "rb");

            if ($in) {
                while ($buff = fread($in, 10240)) {
                    fwrite($out, $buff);
                }
            } else {
                return $this->error(Response::HTTP_UNPROCESSABLE_ENTITY, "Failed to open input stream");
            }

            fclose($in);
            fclose($out);
            unlink($_FILES['file']['tmp_name']);
        }

        if (!$chunks || $chunk == $chunks - 1) {
            rename("{$filePath}.part", $filePath);
            $array = ['file' => $fileName];
            File::create($array);
        }

        $info = "Upload OK";
        $ok = 1;

        return $this->success(['ok' => $ok, 'info' => $info]);
    }
}
