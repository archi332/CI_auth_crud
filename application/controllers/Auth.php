<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * @property CI_Form_validation form_validation
 * @property CI_Session session
 * @property CI_Input input
 */
class Auth extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->database();

        $this->load->helper('path');
    }

    /**
     * @return void
     */
    public function index()
    {
        if (!$this->ion_auth->logged_in()) {

            $this->session->set_flashdata('message', 'Please, login first of all!');

            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {

            redirect($this->config->item('base_url'), 'refresh');
        } else {

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['users'] = $this->ion_auth->get_users_array();

            $this->setContent('auth/index');
            $this->layout();
//			$this->load->view('auth/index', $this->data);
        }
    }

    public function change_password()
    {
        $this->form_validation->set_rules('old', 'Old password', 'required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $user = $this->ion_auth->get_user($this->session->userdata('user_id'));

        if ($this->form_validation->run() == false) { //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['old_password'] = array('name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array('name' => 'new',
                'id' => 'new',
                'type' => 'password',
            );
            $this->data['new_password_confirm'] = array('name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
            );
            $this->data['user_id'] = array('name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );


            $this->setContent('auth/change_password');
            $this->layout();

        } else {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) { //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    //log the user out

    public function logout()
    {
        $this->data['title'] = "Logout";

        //log the user out
        $logout = $this->ion_auth->logout();

        //redirect them back to the page they came from
        redirect('auth', 'refresh');
    }

    //change password

    public function forgot_password()
    {
        //get the identity type from config and send it when you load the view
        $identity = $this->config->item('identity', 'ion_auth');
        $identity_human = ucwords(str_replace('_', ' ', $identity)); //if someone uses underscores to connect words in the column names
        $this->form_validation->set_rules($identity, $identity_human, 'required');

        if ($this->form_validation->run() == false) {
            //setup the input
            $this->data[$identity] = array('name' => $identity,
                'id' => $identity, //changed
            );
            //set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['identity'] = $identity;
            $this->data['identity_human'] = $identity_human;
            $this->load->view('auth/forgot_password', $this->data);
        } else {

            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($this->input->post($identity));

            if ($forgotten) { //if there were no errors

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('auth/login', 'refresh'); //we should display a confirmation page here instead of the login page

            } else {

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/forgot_password', 'refresh');
            }
        }
    }

    /**
     * @param string $code
     */
    public function reset_password($code)
    {
        $reset = $this->ion_auth->forgotten_password_complete($code);

        if ($reset) {  //if the reset worked then send them to the login page

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('auth/login', 'refresh');

        } else { //if the reset didnt work then send them back to the forgot password page

            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect('auth/forgot_password', 'refresh');

        }
    }

    public function register()
    {
        return $this->_content_output('auth/register');
    }

    /**
     * @throws Exception
     * @return void
     */
    public function create_user()
    {
        $this->form_validation->set_rules('user_name', 'First Name', 'required');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {

            throw new Exception(validation_errors());

        }

        $username = strtolower($this->input->post('user_name'));
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $additional_data = ['first_name' => $this->input->post('user_name')];

        if ($this->ion_auth->register($username, $password, $email, $additional_data)) {

            //redirect them back to the admin page
            $this->session->set_flashdata('message', "User Created");

            /** Костыль с либой */
            $this->login();
        } else {

            throw new Exception($this->ion_auth->errors());
        }
    }

    public function login()
    {
        $this->data['title'] = "Login";

        if ($this->ion_auth->logged_in()) {
            redirect(site_url('/'));
        }

        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) { //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember)) {

                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect($this->config->item('base_url'), 'refresh');
            } else { //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
            );

            $this->_content_output('/auth/login');
        }
    }

    /**
     * @return array
     */
    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    /**
     * @return bool
     */
    public function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
