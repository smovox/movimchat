<?php

/**
 * @file AjaxController.php
 * This file is part of Movim.
 * 
 * @brief Description
 *
 * @author Timothée jaussoin
 *
 * @date  14 November 2013
 */

class AjaxController extends BaseController
{
    protected $funclist = array();
    protected static $instance;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        if(!is_object(self::$instance)) {
            self::$instance = new AjaxController();
        }
        return self::$instance;
    }

    /**
     * Generates the javascript part of the ajax.
     */
    public function genJs()
    {
        if(empty($this->funclist)) {
            return '';
        }
        
        $buffer = '<script type="text/javascript">';
        foreach($this->funclist as $funcdef) {
            $parlist = implode(', ', $funcdef['params']);

            $buffer .= "function " . $funcdef['object'] . '_'
                . $funcdef['funcname'] . "(${parlist}) {";
            $buffer .= "movim_ajaxSend('".$funcdef['object']."', '".$funcdef['funcname']."', [${parlist}]);}\n";

        }
        return $buffer . "</script>\n";
    }

    /**
     * Defines a new function.
     */
    public function defun($widget, $funcname, array $params)
    {
        $this->funclist[] = array(
            'object' => $widget,
            'funcname' => $funcname,
            'params' => $params,
            );
    }
}

?>
