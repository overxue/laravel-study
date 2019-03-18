<?php
namespace App\Handlers;

use Image;

class ImageUploadHandler
{
    // 只允许以下后缀的图片文件上传
    protected $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        $folder_name = "uploads/images/$folder/" . date('Ym/d', time());
        $upload_path = public_path() . '/' . $folder_name;
        // 获取文件后缀名称
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 如果上传的不是图片终止操作
        if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }
        // 将图片移动到目标路径存储
        $file->move($upload_path, $filename);

        // 如果限制了图片的宽度，就进行剪裁
        if ($max_width && $extension != 'gif') {
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        $image = Image::make($file_path);

        $image->resize($max_width, null, function ($constraint) {
            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}
