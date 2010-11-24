<?php

class Membership_model extends Model {

        // get user from database by id
        // 
        function getUser($id = '') {
                $user = array('id' => '', 'username' => '', 'first_name' => '', 'last_name' => '', 'avatar' => '', 'email_address' => '');
                if ($id) {
                        $this->db->select('*');
                        $this->db->where('id', $id);
                        $query = $this->db->get('fs_membership');
                        foreach ($query->result() as $c) {
                                $user['id'] = $c->id;
                                $user['username'] = $c->username;
                                $user['first_name'] = $c->first_name;
                                $user['last_name'] = $c->last_name;
                                $user['avatar'] = $c->avatar;
                                $user['email_address'] = $c->email_address;
                                break;
                        }
                }
                return $user;
        }

        // validate password 
        //
        function validate() {

                $this->db->where('username', $this->input->post('username'));
                $this->db->where('password', md5($this->input->post('password')));
                $query = $this->db->get('fs_membership');

                foreach ($query->result() as $user) {
                        return $user->id;
                }
		
                return 0;
        }
	
        // check to see if this username exists
        //
        function username_exists($str) {

                $this->db->where('username', $str);
                $query = $this->db->get('fs_membership');

                if ($query->num_rows > 0)
                        return true;
        }
        
        // create a new user and save to the database
        //
        function create_user() {
		
                $member_data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'email_address' => $this->input->post('email_address'),			
                        'username' => $this->input->post('username'),
                );

                if ($this->input->post('avatar'))
                        $member_data['avatar'] = $this->input->post('avatar');

                if ($_POST['password']) {
                        $member_data['password'] = md5($this->input->post('password'));						
                }

                if ($this->input->post('id')) {
                        $this->db->where('id', $this->input->post('id'));
                        $user = $this->db->update('fs_membership', $member_data); 
                }
                else {
                        $user = $this->db->insert('fs_membership', $member_data);
                }

                return $user;
        }
}