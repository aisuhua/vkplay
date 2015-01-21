<?php
namespace Plugin;

use Library\Ras\Loggable;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\View;
use Phalcon\Http\Request as HttpRequest;
use Phalcon\Http\Response as HttpResponse;

class Context extends Plugin
{

    use Loggable;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @param \Phalcon\Http\Request $request
     */
    public function setRequest(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Phalcon\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Phalcon\Http\Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function beforeRender(Event $event, View $view)
    {
        if ($this->request->isAjax()) {
            if ('jsonp' === $this->request->get('format')) {
                $this->_renderJsonp($view);
            } elseif ('json' === $this->request->get('format')) {
                $this->_renderJson($view);
            } else {
                $view->setRenderLevel(View::LEVEL_ACTION_VIEW);
            }
        } elseif ('jsonp' === $this->request->get('format')) {
            $this->_renderJsonp($view);
        }
        return true;
    }

    /**
     * @param View $view
     */
    protected function _renderJsonp(View $view)
    {
        $content = $this->getResponse()
            ->setContentType('application/json')
            ->setJsonContent($view->getParamsToView())
            ->getContent();
        $content = $this->request->get('callback') . '(' . $content . ')';
        $this->response->setContent($content)
            ->send();
        $view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

    protected function _renderJson(View $view)
    {
        $this->getResponse()
            ->setContentType('application/json')
            ->setJsonContent($view->getParamsToView())
            ->send();
        $view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

}