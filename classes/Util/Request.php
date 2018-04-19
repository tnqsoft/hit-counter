<?php

namespace Util;

class Request
{
    private $query;

    private $request;

    private $session;

    public function __construct()
    {
        $this->getQuery();
        $this->getRequest();
        $this->getSession();
    }

    public function isRequestGet()
    {
        return $this->getCurrentRequestMethod() == 'GET';
    }

    public function isRequestPost()
    {
        return $this->getCurrentRequestMethod() == 'POST';
    }

    public function isRequestPut()
    {
        return $this->getCurrentRequestMethod() == 'PUT';
    }

    public function isRequestDelete()
    {
        return $this->getCurrentRequestMethod() == 'DELETE';
    }

    public function isXMLHttpRequest()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    public function getCurrentRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getQuery()
    {
        $this->query = $_GET;

        return $this->query;
    }

    public function getRequest()
    {
        $this->request = $_POST;
        $raw = json_decode(file_get_contents("php://input"), true);
        if ($raw !== null) {
            $this->request = array_merge($this->request, $raw);
        }

        return $this->request;
    }

    public function getQueryParam($name, $default = null)
    {
        return Utility::getArrayValue($name, $this->query, $default);
    }

    public function getRequestParam($name, $default = null)
    {
        return Utility::getArrayValue($name, $this->request, $default);
    }

    public function getSessionId()
    {
        return session_id();
    }

    public function getSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->session = $_SESSION;

        return $this->session;
    }

    public function getSessionParam($name, $default = null)
    {
        return Utility::getArrayValue($name, $this->session, $default);
    }
}
