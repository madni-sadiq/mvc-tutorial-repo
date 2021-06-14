<?php


namespace  app\controller;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function home()
    {
        $params = [
            'name' => "Madni Sadiq"
        ];
        return $this->render('Home', $params);
    }
    public function contact(Request $request,Response $response)
    {
        $contact = new ContactForm();
        if ($request->isPost()){
            $contact->loadData($request->getBody());
            if ($contact->validate() && $contact->send()){
                Application::$app->session->setFlash('success', 'Thanks for contacting. We will get to you shortly.');
                return $response->redirect('/contact');
            }
        }
        return $this->render('contact', [
            'model' => $contact
        ]);
    }

}