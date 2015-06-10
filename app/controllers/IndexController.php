<?php

namespace Controller;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $isAuth = false;
        if ($this->session->has('oauth_token')) {
            $token = $this->session->get('oauth_token');
            if ($token) {
                $this->vk->setToken($token);
                $isAuth = true;
            }
        }
        $this->view->setVar('isAuth', $isAuth);
    }

    public function listAction()
    {
        if ($this->session->has('oauth_token')) {
            $token = $this->session->get('oauth_token');
            if ($token) {
                $this->vk->setToken($token);
                $list = $this->vk->get('audio.get')->json();
            }
        }
    }

}

