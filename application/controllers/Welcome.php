<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller  {

    /**
     * @return void
     */
    public function index()
	{
        $this->_content_output('welcome/welcome_message');
	}
}
