<?php
namespace app\common\model;

class UploadModel extends CommonModel
{
    protected $name = 'upload';
    public $fields=[
        'id',
        'name',
        'size',
        'mime',
        'type',
        'path',
        'time'
    ];
}
