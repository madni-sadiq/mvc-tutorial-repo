<?php


namespace app\core\forms;


use app\core\Model;

class Forms
{
    public static function begin($action, $method){
        echo sprintf('<form action = "%s" method = "%s">', $action, $method);
        return new Forms();
    }

    public static function end(){
        return '</form>';
    }

    public function field(Model $model, string $attribute)
    {
        return new InputField($model,$attribute);
    }

}