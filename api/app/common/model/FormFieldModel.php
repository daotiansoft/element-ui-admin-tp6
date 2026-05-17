<?php
namespace app\common\model;

use think\model\relation\HasOne;

class FormFieldModel extends CommonModel
{
    protected $name = 'form_field';

    protected $schema = [
        'id'=>'int',
        'key'=>'string',
        'name'=>'string',
        'field_json'=>'string',
        'create_time'=>'int',
        'update_time'=>'int'
    ];
}
