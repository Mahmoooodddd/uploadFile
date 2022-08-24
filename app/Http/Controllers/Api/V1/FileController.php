<?php
/**
 * Created by PhpStorm.
 * User: mahmood
 * Date: 8/24/22
 * Time: 1:31 PM
 */

namespace App\Http\Controllers\Api\V1;


use App\Services\FileService;
use App\Http\Controllers\CoreController;
use Illuminate\Http\Request;
class FileController extends CoreController
{
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function create()
    {
        return $this->fileService->create();
    }


    public function uploadFile(Request $request)
    {
        $formData = $request->request->all();
        $data = [];
        if (isset($formData['data'])) {
            $data = json_decode($formData['data'], true);
        }
        $file = $request->file('file');
        $data['file'] =$file;
        $rules = [
            'file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf,mp4,rar,zip',
        ];
        $errors = $this->validateParams($data, $rules);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }
        $inputs = $this->getParamsCollection($data);
        $result = $this->fileService->uploadFile($inputs);
        return $this->response($result);
    }
}
