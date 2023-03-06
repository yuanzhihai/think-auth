<?php

namespace yzh52521\auth\provider;

use think\helper\Arr;
use yzh52521\auth\credentials\PasswordCredential;
use yzh52521\auth\interfaces\StatefulProvider;
use yzh52521\auth\model\User;

class ModelUserProvider implements StatefulProvider
{

    protected $model;
    protected array $fields = [
        'username'       => 'username',
        'password'       => 'password',
        'remember_token' => 'remember_token',
    ];

    public function __construct($config)
    {
        $this->model  = Arr::get( $config,'model',User::class );
        $this->fields = array_merge( $this->fields,Arr::get( $config,'fields',[] ) );
    }

    protected function getFieldName($name)
    {
        return Arr::get( $this->fields,$name,$name );
    }

    /**
     * @param \think\Model $user
     * @return mixed
     */
    public function getId($user)
    {
        return $user->getAttr( $user->getPk() );
    }

    /**
     * @param \think\Model $user
     * @return string
     */
    public function getRememberToken($user)
    {
        return $user->getAttr( $this->getFieldName( 'remember_token' ) );
    }

    /**
     * @param \think\Model $user
     * @param string $token
     * @return void
     */
    public function setRememberToken($user,$token)
    {
        $user->setAttr( $this->getFieldName( 'remember_token' ),$token );
        $user->save();
    }

    /**
     * 根据用户ID取得用户
     * @param $id
     * @return mixed
     */
    public function retrieveById($id)
    {
        return $this->createModel()->find( $id );
    }

    /**
     * 根据令牌获取用户
     * @param $id
     * @param $token
     * @return mixed
     */
    public function retrieveByToken($id,$token)
    {
        $model = $this->createModel();

        return $model->where( $model->getPk(),$id )
            ->where( $this->getFieldName( 'remember_token' ),$token )
            ->find();
    }

    /**
     * 根据用户输入的数据获取用户
     * @param PasswordCredential $credentials
     * @return mixed
     */
    public function retrieveByCredentials($credentials)
    {
        if (!$credentials instanceof PasswordCredential) {
            return null;
        }

        $user = $this->createModel()->where( [$this->getFieldName( 'username' ) => $credentials->getUsername()] )->find();

        if ($user && $this->checkPassword( $user,$credentials->getPassword() )) {
            return $user;
        }

        return null;
    }

    /**
     * @param \think\Model $user
     * @param string $password
     * @return bool
     */
    protected function checkPassword($user,$password): bool
    {
        return password_verify( $password,$user->getAttr( $this->getFieldName( 'password' ) ) );
    }

    protected function createModel()
    {
        $class = '\\'.ltrim( $this->model,'\\' );

        return new $class;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the name of the Eloquent user model.
     *
     * @param string $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
