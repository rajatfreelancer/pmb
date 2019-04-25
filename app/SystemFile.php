<?php

namespace App\Http\Models;

use App\Http\Controllers\UploadController;
use App\User;
use Aws\S3\S3Client;
use Dompdf\Exception;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Config;
use Psy\Exception\ErrorException;
use Psy\Exception\FatalErrorException;

class SystemFile extends Model
{
   
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disk_name',
        'file_name',
        'file_size',
        'content_type',
        'field_name',
        'model_id',
        'model_type',
        'status',
    ];

    public static function getThumbnailUrl($model, $field = 'image', $h = Setting::THUMBNAIL_HEIGHT, $w = Setting::THUMBNAIL_WIDTH)
    {
        $file = SystemFile::where([
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'field_name' => $field
        ])->orderBy('created_at', 'DESC')->first();
        if ($file != null) {
            $file_path = self::encode($file->disk_name);
            return url('system-image-thumb/' . $file->id . '/' . $h . '/' . $w);
            /*return url('download/' . $file_path);*/
        }
        return "#";
    }

    protected static function getMIMETYPE($base64string)
    {
        preg_match("/^data:(.*);base64/", $base64string, $match);
        if (isset($match[1])) {
            return $match[1];
        }
        return false;
    }

    public static function saveUploadedFile($image_file, $upload_result, $model = null, $uploaded_for = null, $field = 'image')
    {
        if ($image_file != null) {
            $file_path = storage_path().'/app/uploads/';
            $file_path = $image_file->store($file_path);
            $image = new SystemFile();
            $image->disk_name = $file_path;
            $image->file_size = $image_file->getClientOriginalSize();
            $image->file_name = $image_file->getClientOriginalName();
            $image->content_type = $image_file->getMimeType();
            $image->field_name = $field;
            $image->model_id = $model->id;
            $image->model_type = get_class($model);
            $image->save();
            return $image->id;
        }
        return false;
    }

    public static function uploadImage($image){

        $file = $image;
        $destinationPath = public_path(). '/uploads/';
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move($destinationPath, $filename);

        return $filename;
    }

}