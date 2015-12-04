<?php

class admin_model extends CI_Model {
	public function verify_login(){
		$res = $this->db->query('SELECT * FROM admin_users WHERE 
							email=? AND password=?', array($this->input->post('email',true), $this->input->post('password',true)))->result_array();
		if(!count($res)>0){
			return false;
		}
		
		$this->session->set_userdata('admin_sample', $res[0]['admin_id']);
		$this->session->set_userdata('admin_name_sample', $res[0]['admin_name']);
		
		return true;				
	}

	public function check_admin_available(){
		$res = $this->db->select('*')
						->from('admin_users')
						->where('email',$this->input->post('email',true))
						->get()
						->row_array();
		if(count($res)>0){
			return true;
		}
		return false;
	}
	
	public function getAllAdminUsers(){
		$res = $this->db->select('*')
						->from('admin_users')
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}
	
	public function addAdminUsers(){
		$data = $this->input->post(NULL, TRUE);
		unset($data['add']);
		$this->db->insert('admin_users', $data);
	}
        
        public function updateAdminUsers($post, $id){
		$this->db->update('admin_users', $post, array('admin_id' => $id));
                return true;
	}
	
	public function deleteAdminUser($id){
		$this->db->delete('admin_users', array('admin_id'=>$id));
	}
	
	public function get_admin_user($admin_user_id){
		$res = $this->db->select('*')
						->from('admin_users')
						->where('admin_id ',$admin_user_id)
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}
	
	public function get_user($user_id){
		$res = $this->db->select('*')
						->from('user_profiles')
						->where('user_id',$user_id)
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}	

	public function get_all_users($group_id){
		if($group_id == 2){
			$this->db->join('subscription_users','subscription_users.user_id  = users.id');
			$this->db->join('subscription_levels','subscription_levels.id  = subscription_users.subscription_id');
		}
		$res = $this->db->select('*, users.id as user_id_m')
						->from('users')
						->join('user_profiles','user_profiles.user_id = users.id')
						->join('cities','cities.city_id  = user_profiles.city','left')
						->where('users.group_id',$group_id)
                                                ->group_by('users.id')
						->get()
						->result_array();
		if(count($res)<=0)
			return false;
		return $res;			
	}

	public function get_retail_user($user_id){
		$res = $this->db->select('*, users.id as user_id_m')
						->from('users')
						->join('user_profiles','user_profiles.user_id = users.id')
						->join('cities','cities.city_id  = user_profiles.city')
						->join('subscription_users','subscription_users.user_id  = users.id')
						->join('subscription_levels','subscription_levels.id  = subscription_users.subscription_id')
						->where('users.id',$user_id)
						->get()
						->row_array();
		if(count($res)<=0)
			return false;
		return $res;			
	}

	public function addRetailerUsers(){
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('tank_auth/users');
		$email_activation = $this->config->item('email_activation', 'tank_auth');
		if(!is_null($data = $this->tank_auth->create_user(
						'',
						$this->input->post('email'),
						$this->input->post('password'),
						$email_activation, 2))){
			/// update user profile				
		 	$cond = array('user_id'=>$data['user_id']);					
			$profile 	= array('city' 						=> $this->input->post('city_id'),
							'company_name' 					=> $this->input->post('company_name'),
							'organisation_number' 	=> $this->input->post('organisation_number'),
							'postal_address' 				=> $this->input->post('postal_address'),
							'zipCode' 				=> $this->input->post('zipCode'),
							'contact_person' 				=> $this->input->post('contact_person'),
							'phone_number' 				=> $this->input->post('phone_number'),
							'logo_image' 				=> $this->input->post('logo_image'),
							'logo_ext' 				=> $this->input->post('logo_ext')
			);		
			$this->db->update('user_profiles',$profile,$cond);
			// add retailer subscribed categories
			if($this->input->post('subscribed_cat')){
				$subscribed_cat = $this->input->post('subscribed_cat');
				if(is_array($subscribed_cat) && count($subscribed_cat) > 0){
					$scat = array();
					foreach ($subscribed_cat as $key => $subscribedcat) {
						$scat[] = array('user_id'=>$data['user_id'],'cat_id'=>$subscribedcat);
					}
					if(count($scat) > 0)
						$this->db->insert_batch('subscription_categories', $scat);
				}
			}
			// add subscriptin
			$subscription_detail = $this->users->get_subscription_detail($this->input->post('subsciption_level'));
			$subscription = array(
					'user_id' => $data['user_id'],
					'subscription_id' => 1,
					'totalprice' => $subscription_detail['billing_amount']);
			if($this->input->post('subsciption_level') == 1){
				$subscription['status'] = 'Payment Completed';
			}else{
				$subscription['status'] = 'Payment Pending';
			}
			$subscription['payment_date'] = date('H:i:s M d, Y T');
			$subscription['payer_status'] = 'verified';
			$this->db->insert('subscription_users', $subscription);
			return true;
		}
		return false;
	}

	public function update_retailer_users(){
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('tank_auth/users');
		$data = $this->input->post(NULL, TRUE);
		if($data['password'] != ''){
			$this->tank_auth->update_password($data['password']);
		}
		/// update user profile				
	 	$cond = array('user_id'=>$data['user_id']);					
		$profile 	= array('city' 						=> $this->input->post('city_id'),
						'company_name' 					=> $this->input->post('company_name'),
						'organisation_number' 	=> $this->input->post('organisation_number'),
						'postal_address' 				=> $this->input->post('postal_address'),
						'zipCode' 				=> $this->input->post('zipCode'),
						'contact_person' 				=> $this->input->post('contact_person'),
						'phone_number' 				=> $this->input->post('phone_number'),
						'logo_image' 				=> $this->input->post('logo_image'),
						'logo_ext' 				=> $this->input->post('logo_ext')
		);		
		$this->db->update('user_profiles',$profile,$cond);
		// add retailer subscribed categories
		if($this->input->post('subscribed_cat')){
			$subscribed_cat = $this->input->post('subscribed_cat');
			if(is_array($subscribed_cat) && count($subscribed_cat) > 0){
				$scat = array();
				foreach ($subscribed_cat as $key => $subscribedcat) {
					$scat[] = array('user_id'=>$data['user_id'],'cat_id'=>$subscribedcat);
				}
				if(count($scat) > 0){
					$this->db->delete('subscription_categories', array('user_id'=>$data['user_id']));
					$this->db->insert_batch('subscription_categories', $scat);
				}
			}
		}
                $subscription_detail = $this->users->get_subscription_detail($this->input->post('subsciption_level'));
		// update subscription level
		if($subscription_detail['category_limit'] == 0)
			$this->db->delete('subscription_categories', array('user_id'=>$data['user_id']));
		
		$subscription = array(
				'subscription_id' => $this->input->post('subsciption_level'),
				'totalprice' => $subscription_detail['billing_amount']);
		if($this->input->post('subsciption_level') == 1){
			$subscription['status'] = 'Payment Completed';
		}else{
			$subscription['status'] = 'Payment Pending';
		}
		$subscription['payment_date'] = date('H:i:s M d, Y T');
		$subscription['payer_status'] = 'verified';
		$this->db->update('subscription_users', $subscription, array('user_id' => $data['user_id']));
		return true;
	}

	public function delete_user($id){		
		$query = $this->db->delete('users', array('id' => $id));		
		if($query){
			$query = $this->db->delete('user_profiles', array('user_id' => $id));
		}
		return $query;			
	}
	
}