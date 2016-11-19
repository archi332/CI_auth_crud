<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 18.11.16
 * Time: 1:00
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class MY_Controller
 *
 * @property Ion_auth ion_auth
 * @property CI_Session session
 * @property CI_Config config
 * @property CI_Input input
 * @property CI_Form_validation form_validation
 */
class MY_Controller extends CI_Controller
{
    /** @var string header of the page */
    private $_header = 'layouts/header';

    /** @var string content of the page */
    private $_content;

    /** @var string footer of the page */
    private $_footer = 'layouts/footer';

    //set the class variable.
    public $template = [];
    public $data = [];

    /**
     * MY_Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('Ion_auth');
        $this->load->library('session');

    }

    /**
     * @return void
     */
    public function layout()
    {

        $this->template['header'] = $this->load->view($this->_header, $this->data, true);

        $this->template['middle'] = $this->load->view($this->_content, $this->data, true);

        $this->template['footer'] = $this->load->view($this->_footer, $this->data, true);

        $this->load->view('layouts/index', $this->template);
    }

    /**
     * @param string $header
     * @return  void
     */
    public function setHeader($header)
    {
        $this->_header = $header;
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * @param string $footer
     * @return void
     */
    public function setFooter($footer)
    {
        $this->_footer = $footer;
    }

    /**
     * @param string $template
     * @return void
     */
    protected function _content_output($template)
    {
        $this->setContent($template);
        $this->layout();
    }
}