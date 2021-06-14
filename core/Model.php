<?php


namespace app\core;


use app\core\Application;
use http\Params;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MAX = 'min';
    public const RULE_MIN = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_EXIST = 'exist';

    public array $error=[];
    public function loadData($data){
        foreach ($data as $key => $value){
            if(property_exists($this, $key))
            $this->{$key} = $value;
        }
    }
    abstract function rules(): array;

    public function labels():array
    {
        return [];
    }
    public function validate(){
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
           // var_dump($rules);
            if (!is_string($rules)) { // bug caught here in the tutorial
                foreach ($rules as $rule) {
                    $ruleName = $rule[0];


                    if ($ruleName === self::RULE_REQUIRED && !$value) {
                        $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                    }
                    if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                        $this->addErrorForRule($attribute, self::RULE_EMAIL);
                    }

                    if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']){
                        $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                    }
                    if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
                        $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                    }
                    if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}){
                        $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                    }
                    if ($ruleName === self::RULE_EXIST){
                        $className = $rule['class'];
                        $uniqueAttr = $rule['attribute'] ?? $attribute;
                        $tableName = $className::tableName();
                        $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr ");
                        $statement->bindValue(":attr", $value);
                        $statement->execute();
                        $record = $statement->fetchObject();
                        if ($record) {
                            $this->addErrorForRule($attribute, self::RULE_EXIST, ['exist'=>$attribute]);
                        }
                        }
                }
            } else {
                $ruleName = $rules;
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }

            }
        }

        return empty($this->error);
    }
    private function addErrorForRule(string $attribute, string $rule, $params = [] ){
        $message = $this->errorMessages()[$rule]??'';
        foreach ($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message );
        }
        $this->error[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message){

        $this->error[$attribute][] = $message;
    }
    public function errorMessages(){
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_MATCH=> 'The value must match {match}',
            self::RULE_MIN => 'The value must be {min} characters long',
            self::RULE_MAX => 'The value must be less than {max} characters',
            self::RULE_EMAIL => 'The value must be a valid email',
            self::RULE_EXIST => 'A user with this {exist} already exist'
        ];
    }
    public function hasError($attribute){
        return $this->error[$attribute]??false;
    }

    public function getFirstError($attribute)
    {
        return $this->error[$attribute][0]?? false;
    }

}
