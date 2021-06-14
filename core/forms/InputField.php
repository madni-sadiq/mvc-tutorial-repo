<?php


namespace app\core\forms;


use app\core\Model;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASS = 'password';
    public const TYPE_NUMBER = 'number';
    public string $type;
    public Model $model;
    public string $attribute;

    /**
     * Field constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model,string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model,$attribute);
    }


    public function typePassword(){
        $this->type = self::TYPE_PASS;
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf('
        <input type="%s" name = "%s" value="%s" class="form-control%s">',
        $this->type,
        $this->attribute,
        $this->model->{$this->attribute},
        $this->model->hasError($this->attribute) ? ' is-invalid':'',
        );

    }
}