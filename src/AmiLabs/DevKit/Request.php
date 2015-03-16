<?php

namespace AmiLabs\DevKit;

/**
 * Request class.
 */
class Request {
    /**
     * Singleton implementation
     *
     * @var \AmiLabs\DevKit\RequestDriver
     */
    protected static $oDriver = null;
    /**
     * Returns singleton instance.
     *
     * @param string $type  Request type (uri, json, cli)
     * @return \AmiLabs\DevKit\RequestDriver
     */
    public static function getInstance($type = 'uri'){
        if(is_null(self::$oDriver)){
            if(strpos($type, '\\') !== FALSE){
                $className = $type;
            }else{
                $className = '\\AmiLabs\\DevKit\\Request' . strtoupper($type);
            }
            if(class_exists($className)){
                self::$oDriver = new $className();
            }
        }
        return self::$oDriver;
    }
}
/**
 * Request driver interface.
 */
interface IRequestDriver {
    /**
     * Returns scope variable.
     */
    public function get($name, $default = null, $scope = INPUT_GET);
    /**
     * Returns GET Scope.
     */
    public function getScopeGET();
    /**
     * Returns POST Scope.
     */
    public function getScopePOST();
    /**
     * Returns Call Parameters.
     *
     * @param int $index  Parameter index
     */
    public function getCallParameters($index = false);
    /**
     * Returns controller name.
     */
    public function getControllerName();
    /**
     * Returns action name.
     */
    public function getActionName();
}
/**
 * Abstract request driver class.
 */
abstract class RequestDriver {
    /**
     * Controller parsed from uri
     *
     * @var string
     */
    protected $controllerName = 'index';
    /**
     * Action parsed from uri
     *
     * @var string
     */
    protected $actionName = 'index';
    /**
     * Script call parameters parsed from uri
     *
     * @var array
     */
    protected $aData = array();
    /**
     * Returns scope variable.
     *
     * @param string $name    Variable name
     * @param mixed $default  Default variable value if not set in the scope
     * $param int $scope      Scope (INPUT_GET, INPUT_POST)
     * @return mixed
     */
    public function get($name, $default = null, $scope = INPUT_GET){
        $aData = array();
        switch($scope){
            case INPUT_GET:
                $aData = $this->getScopeGET();
                break;
            case INPUT_POST:
                $aData = $this->getScopePOST();
                break;
        }
        return (isset($aData[$name])) ? $aData[$name] : $default;
    }
    /**
     * Returns GET scope.
     *
     * @return array
     */
    public function getScopeGET(){
        return array();
    }
    /**
     * Returns POST scope.
     *
     * @return array
     */
    public function getScopePOST(){
        return array();
    }
    /**
     * Returns script call parameters scope.
     *
     * @param int   $index    Parameter index
     * @param mixed $default  Default parameter value
     * @return array
     */
    public function getCallParameters($index = false, $default = null){
        if($index === false){
            return $this->aData;
        }
        return isset($this->aData[$index]) ? $this->aData[$index] : $default;
    }
    /**
     * Returns controller name.
     *
     * @return string
     */
    public function getControllerName(){
        return $this->controllerName;
    }
    /**
     * Returns action name.
     *
     * @return string
     */
    public function getActionName(){
        return $this->actionName;
    }
}
