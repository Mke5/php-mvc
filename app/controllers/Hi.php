<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Hi class
 */
class Hi extends Controller
{

    public function index()
    {
        $this->view('hi');
    }

}