<?php

/**
 * @property Ion_auth_model ion_auth_model
 */
class UserList extends MY_Controller
{

    /**
     * UserList constructor.
     */
    public function __construct()
    {

        parent::__construct();

        /** Защита от дурака */
        if (!$this->ion_auth->logged_in()) {
            redirect('/auth/login');
        }

    }

    /**
     * @return void
     */
    public function index()
    {
        $this->data['users'] = $this->ion_auth->get_users_array();

        $this->_content_output('/auth/index', [
            'message' => $this->session->flashdata('message'),
            'users' => $this->ion_auth->get_users_array()
        ]);
    }

    /**
     * @param int $id
     * @return  void
     */
    public function delete($id)
    {
        if (!$id) {
            redirect(site_url('/userList'));
        }

        $this->ion_auth_model->delete_user($id);

        redirect(site_url('/userList'));
    }

    /**
     * @TODO: Сделать обработку на AJAX
     *
     * @param int $id
     * @throws Exception
     */
    public function update($id)
    {
        if (!$id) {
            redirect(site_url('/userList'));
        }

        if ($this->input->post()) {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('user_name', 'username', 'required|trim');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email|trim');

            if ($this->form_validation->run() === false) {

                throw new Exception(validation_errors());
            }

            $data = [
                'username' => $this->input->post('user_name'),
                'email' => $this->input->post('email')
            ];

            if ($this->ion_auth_model->update_user($id, $data)) {

                redirect(site_url('/userList'));
            }
        }

        $this->data = $this->db->get_where('users', array('id' => $id))->row_array();

        $this->_content_output('/auth/edit');
    }

    /**
     * @throws Exception
     * @return void
     */
    public function create_user()
    {

        if ($this->input->post()) {

            $this->load->library('form_validation');

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
                redirect(site_url('/userList'));

            } else {

                throw new Exception($this->ion_auth->errors());
            }
        }

        $this->_content_output('/auth/create_user');
    }
}