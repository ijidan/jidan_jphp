<?php

/*
 * Copyright 2012 Funplus Game, All Rights Reserved.
 * package_name : sso.class.php
 * ------------------
 * AuthenGate Single Sign On system
 * 
 * PHP versions > 5
 *
 * @Author   : Fang Li <fang.li@funplusgame.com>
 * @Version  : 2012-11-02
 * -------------------------------------------------------------------------
 */

// ////////////////////////////////////////
// ///////////////Examples/////////////////
// ////////////////////////////////////////
// ////////////////////////////////////////
// ////////////////////////////////////////
/* --

include('sso.class.php');
$sso = new Sso('THIS IS YOUR APP TOKEN');
$sso->getuser();
if ($sso->islogin()) {
    if ($sso->ispermit()) {
        echo "you have permisison!";
    } else {
        echo "you don't have permission";
        exit;
    }
} else {
    $sso->redirect();
}

--*/
// ////////////////////////////////////////
// ////////////////////////////////////////
/* --
public function login()
{
    import("@.ORG.Sso");
    $sso = new Sso(C('SSO_APP_TOKEN'));
    $sso->getuser();
    if ($sso->islogin()){
        if ($sso->ispermit()) {
            $params = session('SSO_PARAMS');
            foreach ($params as $param) {
                if ($param['param_name'] == "admin"){
                    $isadmin = $param['param_value'];
                }
            }
            session('Readonly', true);
            if ($isadmin == "1"){
                session('Administrator', true);
            } else {
                session('Administrator', null);
            }
            session('operator', session('SSO_USERNAME'));
            $this->redirect('pool', 'Index');
        } else {
            $this->error('You are not allow to access this application');
        }
    } else {
        $sso->redirect();
        exit;
    }
    // if (session('Readonly') != true)
    // {
        // $this->display();
    // } else
    // {
        
    // }
}
--*/
// ////////////////////////////////////////
// ////////////////////////////////////////
// ////////////////////////////////////////

class Sso {
    
    public $SSO_API;
    public $APP_TOKEN;
    public $RETURN_URL;
    
    function __construct($app_token = "", $return_url = null, $sso_api = null)
    {
        session_start();
        $this->APP_TOKEN = $app_token;
        $this->RETURN_URL = (is_null($return_url)) ? self::getCurrentUrl() : $return_url;
        $this->SSO_API = (is_null($sso_api)) ? 'https://login.socialgamenet.com/Index/' : $sso_api;
        // $this->SSO_API = (is_null($sso_api))?'http://localhost/ops-sso-authengate/Index/':$sso_api;
    }
    
    // Get User information if access_token privided
    public function getuser()
    {
        if (isset($_GET['access_token'])) {
            $access_token = $_GET['access_token'];
            $handle = fopen($this->SSO_API . 'getuser?access_token=' . $access_token . '&app_token=' . $this->APP_TOKEN, "rb");
            $raw_user_info = stream_get_contents($handle);
            fclose($handle);
            if ($raw_user_info) {
                $user_info = json_decode($raw_user_info, true);
                
                $_SESSION['SSO_UID'] = $user_info['uid'];
                $_SESSION['SSO_USERNAME'] = $user_info['uname'];
                $_SESSION['SSO_EMAIL'] = $user_info['email'];
                $_SESSION['SSO_PARAMS'] = $user_info['app_parameters'];
                $_SESSION['SSO_IS_LOGIN'] = true;
                
                if ($user_info['isallow'] == 1) {
                    $_SESSION['SSO_PERMISSION'] = true;
                } else {
                    $_SESSION['SSO_PERMISSION'] = false;
                }
                
            }
        }
    }
    
    // Redirect to SSO login if not logged in
    public function redirect()
    {
        $handle = fopen($this->SSO_API . 'gettoken?app_token=' . $this->APP_TOKEN, "rb");
        $raw_client_token = stream_get_contents($handle);
        fclose($handle);
        if ($raw_client_token) {
            $client_token = json_decode($raw_client_token);
            header('Location: ' . $this->SSO_API . 'api?client_token=' . $client_token . '&return_url=' . $this->RETURN_URL);
            exit;
        } else {
            echo "app_token not accepted.";
            exit;
        }
    }
    
    //check islogin
    public function islogin()
    {
        if ((isset($_SESSION['SSO_IS_LOGIN'])) && ($_SESSION['SSO_IS_LOGIN'] == true)) {
            return true;
        } else {
            return false;
        }
    }
    
    //check has permission
    public function ispermit()
    {
        if ((isset($_SESSION['SSO_PERMISSION'])) && ($_SESSION['SSO_PERMISSION'] == true)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function logout($return_url = null)
    {
        $return_url = (is_null($return_url)) ? $this->RETURN_URL : $return_url;
        $_SESSION['SSO_UID'] = null;
        $_SESSION['SSO_USERNAME'] = null;
        $_SESSION['SSO_EMAIL'] = null;
        $_SESSION['SSO_PARAMS'] = null;
        $_SESSION['SSO_IS_LOGIN'] = null;
        $_SESSION['SSO_PERMISSION'] = null;
        header('Location: ' . $this->SSO_API . 'signout?return_url=' . $return_url);
        exit;
    }
    
    // get return url
    private function getCurrentUrl()
    {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER['PHP_SELF'];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER['PHP_SELF'];
        }
        return $pageURL;
    }
    
}
