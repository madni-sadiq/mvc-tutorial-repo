<?php
namespace app\core;

use app\core\Db\Database;
use app\core\Db\DBModel;

class Application
{
    public string $layout = 'main';
    public static string $RootDir;
    public string $userClass;
    public Router $router;
    public Request $request;
    public Database $db;
    public Response $response;
    public static Application $app;
    public ?Controller $controller = null;
    public Session $session;
    public ?UserModel $user;
    public View $view;

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }
    public function __construct($Dir, array $config)
    {
        $this->userClass = $config['userClass'];
        self::$RootDir = $Dir;
        self::$app = $this;
        $this->db = new Database();
        $this->session = new Session();
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $primaryValue = $this->session->get('user');
        if($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
            }
        else{
            $this->user = null;
        }
    }
    public static function isGuest(){
        return !self::$app->user;
    }
    public function run(){
        try {
            echo $this->router->resolve();
        }catch (\Exception $e){
            $this->response->set_statusCode($e->getCode());
            echo $this->view->renderView('_error',[
                'exception' => $e
            ]);
        }
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user',$primaryValue);

        return true;
    }

    public function logout()
    {
        $this->user = null;

        $this->session->remove('user');

    }
}