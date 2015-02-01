<?php

namespace Controller;

use Library\Phalcon\Mvc\View;

class AuthController extends ControllerBase
{

    public function indexAction()
    {
        $url = $this->vk->authorizationUrl();
        $this->response->redirect($url, true, 302);
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

    public function oauthAction()
    {
        if ($this->request->has('code')) {
            $token = $this->vk->accessToken($this->request->get('code'));
            $this->session->set('oauth_token', $token);
        }
        $this->response->redirect('/', false, 302);
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

}