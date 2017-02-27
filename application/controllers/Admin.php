<?php

/**
 * scription of Admin
 *
 * @author flashery
 */
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // 		Load model
        $this->load->model('users');
        $this->load->model('members');
        $this->load->model('images');
        $this->load->model('accounts');
        $this->load->model('transfers');
        $this->load->model('custom_fields');
        $this->load->model('reports');
        $this->load->model('sms');

        // Load pagination library
        $this->load->library("pagination");
    }

    // 	This function will load the default page of this controller
    public function index() {
        if ($this->permission->check_admin_permission()) {
            // 			Load admin page
            $data['title'] = 'Dashboard';
            $this->view_loader->load($this, 'index', $data);
        }
    }

    // 	This function will logout the current logged in user
    public function logout() {
        $this->session->sess_destroy();
        redirect('index');
    }

    // 	This function will load the accounts configurations
    public function accounts($slug = NULL, $id = NULL) {
        if ($this->permission->check_admin_permission()) {

            // Load admin page
            if ($slug === 'details') {
                $this->load_details($slug, $id);
            } else {
                // Load admin page
                // all users in pagination mode
                $data = $this->accounts_pagination('admin/accounts', 20, 3);
                $data['main_content'] = 'admin/accounts';
                $data['title'] = 'Account';

                $this->load->view('includes/admin/template', $data);
            }
        }
    }

    // 	This function will load the reports
    public function reports($slug = NULL) {
        if ($this->permission->check_admin_permission()) {
            $this->validate_reports_form();
            if ($this->form_validation->run() === FALSE) {
                $data['options'] = $this->custom_fields->custom_fields->get_cf_possible_values(20);
                $data['title'] = ($slug === NULL) ? __FUNCTION__ : $slug;
                $this->view_loader->load($this, 'reports', $data, $slug);
            } else {
                // Get the select field option values, from the database "custom_fields" table
                $data['options'] = $this->custom_fields->custom_fields->get_cf_possible_values(20);
                // Title of the page
                $data['title'] = ($slug === NULL) ? __FUNCTION__ : $slug;
                // Query the report to the database with the data from the form input
                $data['queries'] = $this->generate_reports(
                                $this->input->post('type'), $this->input->post('from'), $this->input->post('to')
                        )->result_array();
                // Create a csv document for download
                $data['csv_url'] = $this->make_csv(
                        $this->generate_reports(
                                $this->input->post('type'), $this->input->post('from'), $this->input->post('to')
                        ), $this->input->post('type')
                );
                // Get the report type name of this current request
                $data['report_type'] = $this->input->post('type');
                // Create a session flash for alert purposes
                $success = 'Data successfully generated';
                $this->session->set_flashdata('success', $success);
                // Load the page with the data created above
                $this->view_loader->load($this, 'reports', $data, $slug);
            }
        }
    }

    // 	This function will load the reports
    public function sub_reports($slug = NULL) {
        if ($this->permission->check_admin_permission()) {
            // Get the select field option values, from the database "custom_fields" table
            $data['options'] = $this->custom_fields->custom_fields->get_cf_possible_values(20);
            // Title of the page
            $data['title'] = ($slug === NULL) ? __FUNCTION__ : $slug;
            // Query the report to the database with the data from the form input
            $data['queries'] = $this->generate_reports(
                            $this->input->post('type2')
                    )->result_array();
            // Create a csv document for download
            $data['csv_url'] = $this->make_csv(
                    $this->generate_reports(
                            $this->input->post('type2')
                    ), $this->input->post('type2')
            );
            // Get the report type name of this current request
            $data['report_type'] = $this->input->post('type2');
            // Create a session flash for alert purposes
            $success = 'Data successfully generated';
            $this->session->set_flashdata('success', $success);
            // Load the page with the data created above
            $this->view_loader->load($this, 'reports', $data, $slug);
        }
    }

    // 	This function will load the settings configurations
    public function settings($slug = NULL) {
        if ($this->permission->check_admin_permission() && $this->permission->check_admin_role()) {
			
            // Load admin page
            if ($slug == 'charges') {
                $transaction_fees = $this->transfers->get_transaction_fees();
                $data = array(
                    'title' => ($slug === NULL) ? ucfirst(__FUNCTION__) : ucfirst($slug),
                    'transaction_fees' => $transaction_fees
                );
            } else if ($slug == 'sms') {
				$this->validate_sms_settings();
				if ($this->form_validation->run() === FALSE) {
					$sms_settings = $this->sms->get_settings();
					$data = array(
						'title' => ($slug === NULL) ? ucfirst(__FUNCTION__) : ucfirst($slug),
						'sms_settings' => $sms_settings
					);
				} else {
					$this->sms_settings_update($slug);
				}
            } else {
                $data = array(
                    'title' => ($slug === NULL) ? ucfirst(__FUNCTION__) : ucfirst($slug)
                );
            }
            $this->view_loader->load($this, 'settings', $data, $slug);
        }
    }

    // 	This function will load the users configurations
    public function users($slug = NULL, $username = NULL) {
        if ($this->permission->check_admin_permission()) {
            // Get the users profile
            switch ($slug) {
                case 'profile':
                    $this->members_profile($slug, $username);
                    break;
                case 'edit_profile':
                    $this->validate_profile_form();
                    if ($this->form_validation->run() === FALSE) {
                        $this->edit_profile($slug, $username);
                    } else {
                        $this->update_profile();
                    }
                    break;
                case 'add_new':
                    if ($this->permission->check_admin_role()) {
                        $this->add_new_member($slug, $username);
                    }
                    break;
                default :
                    // Show all users in pagination mode
                    $data = $this->users_pagination('admin/users', 20, 3);
                    $data['main_content'] = 'admin/users';
                    $data['title'] = 'Users';
                    // Load admin page
                    $this->load->view('includes/admin/template', $data);
            }
        }
    }

    public function sms($slug = NULL) {
        $accounts = $this->accounts->get_accounts(NULL, NULL);
        $data = array(
            'title' => ($slug === NULL) ? ucfirst(__FUNCTION__) : ucfirst($slug),
            'main_content' => 'admin/sms',
            'accounts' => $accounts
        );
        // Load admin page
        $this->load->view('includes/admin/template', $data);
    }

    public function update_member_status($id, $username, $role) {
        if ($this->permission->check_admin_permission()) {
            // Create members array of data
            $members_data['group_id'] = $role;
            if ($role == permission::MEMBER) {
                $members_data['member_activation_date'] = date('Y-m-d h:i:sa');
                $this->create_new_account($username, $members_data);
            }
            // Update members data and check if no errors
            if ($this->members->update_member_status($id, $members_data)) {
                $this->session->set_flashdata('success', 'Successfully updated the status.');
                redirect('admin/users/edit_profile/' . $username);
            } else {
                $this->session->set_flashdata('error', 'Error updating the status.');
                redirect('admin/users/edit_profile/' . $username);
            }
        }
    }

    /*     * *************************************************************** */
    /*     * *********            PRIVATE FUNCTION                ********** */
    /*     * *************************************************************** */

    private function users_pagination($dest, $per_page, $uri_segment) {
        $records = $this->members->record_count();
        $config = array(
            'base_url' => base_url() . $dest,
            'total_rows' => $records,
            'per_page' => $per_page,
            'uri_segment' => $uri_segment,
        );
        $this->pagination->initialize($config);
        // 
        $page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;
        // Query to database
        //$users = $this->users->get_some('username', $config['per_page'], $page);
        //$members = $this->members->get_some('name, email, group_id', $config['per_page'], $page);
        $members = $this->members->get_some($config['per_page'], $page);
        $data = array(
            //'users' => $users,
            'members' => $members,
            'links' => $this->pagination->create_links()
        );
        return $data;
    }

    private function accounts_pagination($dest, $per_page, $uri_segment) {

        $records = $this->members->record_count();
        //$choice = $records / $per_page;
        $config = array(
            'base_url' => base_url() . $dest,
            'total_rows' => $records,
            'per_page' => $per_page,
            'uri_segment' => $uri_segment,
        );

        $this->pagination->initialize($config);
        // 
        $page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;
        // Query to database
        $accounts = $this->accounts->get_accounts($config['per_page'], $page);

        $data = array(
            'accounts' => $accounts,
            'links' => $this->pagination->create_links()
        );
        return $data;
    }

    // Function for updating the profile of the users
    private function update_profile() {
        // Update members data and check if no errors
        if ($this->update_members()) {
            $this->session->set_flashdata('success', 'Successfully updated user');
            redirect('admin/users/add_new');
        } else {
            $this->session->set_flashdata('error', 'Error updating members');
            redirect('admin/users/add_new');
        }
    }

    private function update_members() {
        // Create members array of data
        $members_data = array(
            'email' => $this->input->post('email'),
            'name' => $this->input->post('name')
        );

        // Update members data and check if no errors
        if ($this->members->update_members($members_data)) {
            // Get the users id
            $members_id = $this->members->get_id($members_data['email']);
            // Create a users array of data
            $users_data = array();
            // Add username to users array of data
            $users_data['username'] = $this->input->post('username');
            // If password is not empty
            // Add password to users array of data
            if ($this->input->post('password') !== '') {
                $users_data['password'] = $this->input->post('password');
            }
            $this->update_users($users_data, $members_id);
            $success = TRUE;
        } else {
            $success = FALSE;
        }
        return $success;
    }

    private function update_users($users_data, $members_id) {
        $cf_values_data = array();
        // Update users data and check if no errors
        if ($this->users->update_users($users_data)) {
            // Get the custom fields valid name
            $cf_fields = $this->custom_fields->get_some_cf('id, internal_name');
            $cf_values_data['member_id'] = $members_id['id'];
            $counter = 0;
            foreach ($cf_fields as $internal_name) {
                //echo $this->input->post($internal_name['internal_name']) . '<br/>';
                $data = $this->input->post($internal_name['internal_name']);
                //$cf_values_data[$internal_name['internal_name']] = $data;
                $cf_fields[$counter]['string_value'] = $data;
                $counter++;
            }
            $success = TRUE;
        } else {
            $success = FALSE;
        }

        $success = ($this->custom_fields->update_cf_values($cf_values_data, $cf_fields)) ? TRUE : FALSE;
    }

    private function make_csv($reports, $file_name = 'export') {
        $this->load->dbutil();
        $this->load->helper('file');
        $file_path = './documents/' . $file_name . '.csv';
        $new_report = $this->dbutil->csv_from_result($reports);
        if (!write_file($file_path, $new_report)) {
            $error = array('error' => 'Unable to write file! ' . $file_path);
            $this->session->set_flashdata('error', $error['error']);
        } else {
            return base_url() . 'documents/' . $file_name . '.csv';
        }
    }

    private function generate_reports($param, $start_date = '', $end_date = '') {

        if ($start_date === '' || $end_date === '') {
            $start_date = '1980-01-01';
            $end_date = '2030-12-31';
        }

        $query = NULL;

        switch ($param) {
            case 'Charges':
                $query = $this->reports->get_members_charges($start_date, $end_date);
                break;
            case 'Deposits':
                $query = $this->reports->get_members_deposits($start_date, $end_date);
                break;
            case 'Withdrawal':
                $query = $this->reports->get_members_witdrawal($start_date, $end_date);
                break;
            case 'Investments':
                $query = $this->reports->get_members_investments($start_date, $end_date);
                break;
            case 'Loans':
                $query = $this->reports->get_members_loans($start_date, $end_date);
                break;
            case 'Savings':
                $query = $this->reports->get_members_savings($start_date, $end_date);
                break;
            case 'Cumulative Reports':
                $query = $this->reports->get_cumulative_reports($start_date, $end_date);
                break;
            case 'Issued Loans':
                $query = $this->reports->get_members_loans($start_date, $end_date);
                break;
            case 'Repaid Loans':
                $query = $this->reports->get_repaid_loans($start_date, $end_date);
                break;
            case 'Expired Loans':
                $query = $this->reports->get_expired_loans($start_date, $end_date);
                break;
            case 'Due Loans':
                $query = $this->reports->get_due_loans($start_date, $end_date);
                break;
            default :
                echo 'No selected reports!';
        }
        return $query;
    }

    private function add_new_member($slug, $name = NULL) {
        $this->validate_new_member();
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                'main_content' => 'admin/users/' . $slug,
            );
            $this->load->view('includes/admin/template', $data);
        } else {
            if ($this->input->post('member_type') === 'Customers') {
                $this->new_member_user();
            } else {
                $this->new_admin_user();
            }
        }
    }

    private function new_admin_user() {

        $group_id = ($this->input->post('member_type') === 'Administrator') ? 1 : 2;
        // Insert the data to the database
        $members_data = array(
            'subclass' => 'A',
            'creation_date' => date('Y-m-d'),
            'group_id' => $group_id,
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email')
        );

        $members_query = $this->members->new_member($members_data);
        $members_id = $this->members->get_id($members_data['email']);

        if ($members_query) {

            $users_data = array(
                'id' => $members_id['id'],
                'subclass' => 'A',
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );

            $user_query = $this->users->new_user($users_data);

            if ($user_query) {
                $this->session->set_flashdata('success', 'Admin user successfully added');
                redirect('admin/users/add_new');
            }
        } else {
            $this->session->set_flashdata('error', 'Error adding new admin user');
            redirect('admin/users/add_new');
        }
    }

    private function new_member_user() {

        $group_id = 5;
        // Insert the data to the database
        $members_data = array(
            'subclass' => 'M',
            'creation_date' => date('Y-m-d'),
            'group_id' => $group_id,
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email')
        );

        $members_query = $this->members->new_member($members_data);
        $members_id = $this->members->get_id($members_data['email']);

        if ($members_query) {

            $users_data = array(
                'id' => $members_id['id'],
                'subclass' => 'M',
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );

            $user_query = $this->users->new_user($users_data);

            if ($user_query) {
                $members_data['member_activation_date'] = date('Y-m-d h:i:sa');
                $members_data['mobile_number'] = $this->input->post('mobile_number');
                $this->create_new_account($this->input->post('username'), $members_data);
            }
        } else {
            $this->session->set_flashdata('error', 'Error adding new member.');
            redirect('admin/users/add_new');
        }
    }

    private function members_profile($slug, $name = NULL) {
        $username = ($name === NULL) ? $this->session->userdata('username') : $name;
        $user_profile = $this->users->get_user_profile($username);
        $member_profile = $this->members->get_member_profile($user_profile['id']);
        $profile_image = $this->images->get_profile_image($user_profile['id']);
        $signature_image = $this->images->get_signature_image($user_profile['id']);
        $custom_fields = $this->custom_fields->get_custom_fields();
        $data = array(
            'title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
            'user_profile' => $user_profile,
            'member_profile' => $member_profile,
            'profile_image' => $profile_image,
            'signature_image' => $signature_image,
            'custom_fields' => $custom_fields,
            'main_content' => 'admin/users/' . $slug,
        );
        // Load admin page
        $this->load->view('includes/admin/template', $data);
    }

    private function edit_profile($slug, $name = NULL) {
        $username = ($name === NULL) ? $this->session->userdata('username') : $name;
        $user_profile = $this->users->get_user_profile($username);
        $member_profile = $this->members->get_member_profile($user_profile['id']);
        $profile_image = $this->images->get_profile_image($user_profile['id']);
        $signature_image = $this->images->get_signature_image($user_profile['id']);
        $data = array(
            'title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
            'user_profile' => $user_profile,
            'member_profile' => $member_profile,
            'profile_image' => $profile_image,
            'signature_image' => $signature_image,
            'main_content' => 'admin/users/' . $slug,
        );
        // Load admin page
        $this->load->view('includes/admin/template', $data);
    }

    private function load_details($slug, $id) {
        if ($this->input->post('from') == '' || $this->input->post('to') == '') {
            $start_date = '1980-01-01';
            $end_date = '2030-12-31';
        } else {
            $start_date = $this->input->post('from');
            $end_date = $this->input->post('to');
        }
        // Query to database
        $user_profile = $this->users->get_user_by_id($id);
        $profile_image = $this->images->get_profile_image($id);
        $signature_image = $this->images->get_signature_image($id);
        $accounts = $this->accounts->get_account($id);

        $data = array(
            'id' => $id,
            'title' => 'Details',
            'user_profile' => $user_profile,
            'accounts' => $accounts,
            'profile_image' => $profile_image,
            'signature_image' => $signature_image,
            'main_content' => 'admin/accounts/' . $slug,
            'withdrawals' => $this->reports->get_individual_witdrawal($id, $start_date, $end_date)->result_array(),
            'withdrawals_csv' => $this->make_csv($this->reports->get_individual_witdrawal($id, $start_date, $end_date)),
            'loans' => $this->reports->get_individual_loans($id, $start_date, $end_date)->result_array(),
            'loans_csv' => $this->make_csv($this->reports->get_individual_loans($id, $start_date, $end_date)),
            'repaid_loans' => $this->reports->get_individual_r_loans($id, $start_date, $end_date)->result_array(),
            'repaid_loans_csv' => $this->make_csv($this->reports->get_individual_r_loans($id, $start_date, $end_date)),
            'savings' => $this->reports->get_individual_savings($id, $start_date, $end_date)->result_array(),
            'savings_csv' => $this->make_csv($this->reports->get_individual_savings($id, $start_date, $end_date)),
            'deposits' => $this->reports->get_individual_deposits($id, $start_date, $end_date)->result_array(),
            'deposits_csv' => $this->make_csv($this->reports->get_individual_deposits($id, $start_date, $end_date)),
            'investments' => $this->reports->get_individual_investments($id, $start_date, $end_date)->result_array(),
            'investments_csv' => $this->make_csv($this->reports->get_individual_investments($id, $start_date, $end_date)),
            'charges' => $this->reports->get_individual_charges($id, $start_date, $end_date)->result_array(),
            'charges_csv' => $this->make_csv($this->reports->get_individual_charges($id, $start_date, $end_date)),
            'cumulative_reports' => $this->reports->get_individual_cr($id, $start_date, $end_date)->result_array(),
            'cumulative_reports_csv' => $this->make_csv($this->reports->get_individual_cr($id, $start_date, $end_date)),
        );

        // Load admin page
        $this->load->view('includes/admin/template', $data);
    }

    private function create_new_account($username, $members_data) {
        if ($this->accounts->check_account($username) === NULL) {
            $user_id = $this->users->get_id($username);

            $data = array(
                'subclass' => 'M',
                'creation_date' => $members_data['member_activation_date'],
                'last_closing_date' => '2016-09-04',
                'owner_name' => $username,
                'member_id' => $user_id['id']
            );
            // Create savings account
            $data['type_id'] = 5;
            $this->accounts->create_new_account($data);

            // Create withdrawal account
            $data['type_id'] = 8;
            $this->accounts->create_new_account($data);

            $this->new_cfv($username, $members_data['mobile_number']);
        }
    }

    private function new_cfv($username, $mobile_number) {
        $members_info = $this->members->get_some_members_info('id, name', $username);
        $max_account = $this->accounts->get_max_acct_number();

        $data = array
            (0 => array(
                'subclass' => 'member',
                'field_id' => 13,
                'string_value' => $members_info['name'],
                'member_id' => $members_info['id']
            ),
            1 => array(
                'subclass' => 'member',
                'field_id' => 14,
                'string_value' => $max_account['number'] + 1,
                'member_id' => $members_info['id']
            ),
            2 => array(
                'subclass' => 'member',
                'field_id' => 8,
                'string_value' => $mobile_number,
                'member_id' => $members_info['id']
            )
        );
        if ($this->custom_fields->new_cf_values($data)) {
            $this->session->set_flashdata('success', 'Successfully added new member. Add <a href="#more-info">more info</a> below.');
            redirect('admin/users/edit_profile/' . $username);
        } else {
            $this->session->set_flashdata('error', 'Error adding new member.');
            redirect('admin/users/add_new');
        }
    }

    private function sms_settings_update($slug) {
        $data = array(
			$this->input->post('user'),
			$this->input->post('password'),
			$this->input->post('senderid'),
			$this->input->post('loan_granted'),
			$this->input->post('loan_repaid'),
			$this->input->post('loan_due'),
			$this->input->post('loan_expired'),
			$this->input->post('succesful_withdraw'),
		    $this->input->post('succesful_deposit')
		);
		echo 'WEW';
		 if ($this->sms->update_settings($data)) {
            $this->session->set_flashdata('success', 'Successfully updated sms settings.');
            redirect('admin/settings/sms/');
        } else {
            $this->session->set_flashdata('error', 'Error updating sms settings.');
            redirect('admin/settings/sms/');
        }
    }
    
    /*********************************************************************
     ****************** Form Validation Functions ************************
     *********************************************************************/
	private function validate_sms_settings() {
		$this->form_validation->set_rules('user');
		$this->form_validation->set_rules('password');
		$this->form_validation->set_rules('senderid');
		$this->form_validation->set_rules('loan_granted');
		$this->form_validation->set_rules('loan_repaid');
		$this->form_validation->set_rules('loan_due');
		$this->form_validation->set_rules('loan_expired');
		$this->form_validation->set_rules('succesful_withdraw');
		$this->form_validation->set_rules('succesful_deposit');
	}
	
    private function validate_profile_form() {
        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|matches[password]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
    }

    private function validate_reports_form() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('from', 'From', 'trim');
        $this->form_validation->set_rules('to', 'To', 'trim');
    }

    private function validate_new_member() {
        $pattern = "/^(254)\d{9}$/";
        $data = array(
            'is_unique' => 'This %s already exists.'
        );
        $this->form_validation->set_rules('member_type', 'Member Type', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|is_unique[users.username]', $data);
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|regex_match[' . $pattern . ']');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[members.email]', $data);
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
    }

    /*
      public function update_account_number() {
      $null_accounts = $this->accounts->get_null_accounts();
      $account_number = 21990300700;
      foreach ($null_accounts as $null_accounts) {
      $this->accounts->update_account_number($null_accounts['id'], $account_number);
      $account_number++;
      }
      }
     */
}
