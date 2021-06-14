<?php


namespace app\models;


use app\core\Db\DBModel;
use app\core\UserModel;

class Users extends UserModel
{
    const  STATUS_INACTIVE = 0;
    const  STATUS_ACTIVE = 1;
    const  STATUS_DELETED = 2;

    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public int $status = 0;
    public string $password = '';
    public string $ConfirmPassword = '';

    public function tableName(): string
    {
        return 'users';
    }
    public function primaryKey(): string
    {
       return 'id';
    }

    public function save(){
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password,PASSWORD_DEFAULT);
        return parent::save();
     //   return print 'Completing Registration';
    }
    public function rules(): array
    {
        return [
            'firstname' => self::RULE_REQUIRED,
            'lastname' => self::RULE_REQUIRED,
            'email' => [[self::RULE_REQUIRED], [self::RULE_EMAIL], [self::RULE_EXIST, 'class'=>self::class]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN,'min'=>'8'], [self::RULE_MAX, 'max'=>'24']],
            'ConfirmPassword'=>[self::RULE_REQUIRED, [self::RULE_MATCH, 'match'=>'password']]
        ];
    }

    public function attributes(): array
    {
        return [
            'firstname',
            'lastname',
            'email',
            'password',
            'status'
    ];
    }

    public function labels(): array
    {
        return [
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'ConfirmPassword' => 'Confirm Password'
        ];
    }
    public function getDisplayName(): string
    {
        return $this->firstname.' '.$this->lastname;
    }
}