<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends CI_Model
{
	private $table_name			= 'users';			// user accounts
	private $profile_table_name	= 'user_profiles';	// user profiles

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->table_name			= $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
		$this->profile_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->profile_table_name;
	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id, $activated=1)
	{
		$this->db->where('id', $user_id);
		$this->db->where('activated', $activated ? 1 : 0);

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login)
	{
		$this->db->where('LOWER(username)=', strtolower($login));
		$this->db->or_where('LOWER(email)=', strtolower($login));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username)
	{
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
		$this->db->where('LOWER(email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
	
	function get_profile_by_user_id($user_id)
	{
		$this->db->select('*');
		$this->db->from($this->profile_table_name);
		$this->db->where('user_id',$user_id);
		$query = $this->db->get()->result_array();
		return $query[0];
	}
	
	function get_user_detail_by_id($user_id, $group_id)
	{
		if($group_id == 2){
			$this->db->join('subscription_users','subscription_users.user_id  = users.id');
			$this->db->join('subscription_levels','subscription_levels.id  = subscription_users.subscription_id');
		}
		$res = $this->db->select('*, users.id as user_id_m')
						->from('users')
						->join('user_profiles','user_profiles.user_id = users.id')
						->join('cities','cities.city_id  = user_profiles.city')
						->where('users.id',$user_id)
						->get()
						->row_array();
		if(count($res)<=0)
			return false;
		return $res;
	} 
	
	function get_address_by_user_id($user_id)
	{
		$this->db->select('*');
		$this->db->from("user_addresses");
		$this->db->where('user_id',$user_id);
		$query = $this->db->get()->result_array();
		return $query;
	}
	
	function get_primary_address_by_user_id($user_id)
	{
		$this->db->select('*');
		$this->db->from("user_addresses");
		$this->db->where('user_id',$user_id);
		$this->db->where('is_primary',1);
		$this->db->limit(1);
		$query = $this->db->get()->row_array();
		if(count($query)<=0)
			return false;				
		return $query;
	}
	
	function add_subscription_level(){
		$data = $this->input->post(NULL, TRUE);
		unset($data['add']);		
		return $this->db->insert('subscription_levels', $data);
	}
	
	public function update_subscription_level($id,$post){
		$query = $this->db->where('id',$id)->update('subscription_levels', $post);	
		return TRUE;						   	
	}
	
	public function delete_subscription_level($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->delete('subscription_levels'); 
		return $query;
	}
	
	function get_subscription_levels()
	{
		$this->db->select('*');
		$this->db->from("subscription_levels");
		$query = $this->db->get()->result_array();
      	if(count($query)<=0)
			return false;				
		return $query;
	}
	
        public function get_subscription_name($id){
            $this->db->select('*');
            $this->db->from("subscription_levels")->where("id",$id);
            $query = $this->db->get()->row_array();
            if(isset($query['description']) && $query['description']){
                return $query['description'];
            }
            if(count($query)<=0)
                    return false;
        }


        function get_subscription_detail($id)
	{
		$this->db->select('*');
		$this->db->from("subscription_levels");
		$this->db->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get()->row_array();
		if(count($query)<=0)
			return false;				
		return $query;
	}
	
        
	function get_user_subscription_by_id($id)
	{
		$this->db->select('*,');
		$this->db->from("subscription_users");
		$this->db->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get()->row_array();
		if(count($query)<=0)
			return false;				
		return $query;
	}
	
	function get_user_subscription_by_userid($userid)
	{

		$this->db->select('*');
		$this->db->from("subscription_users");
		$this->db->where('user_id',$userid);
                $this->db->order_by('id desc');
		$this->db->limit(1);
		$query = $this->db->get()->row_array();
                //echo $this->db->last_query();
		if(count($query)<=0)
			return false;				
		return $query;
	}
	
        function get_all_users_subscription(){
            $sql = "select users.*, 
                                subscription_users.id as suid, 
                                subscription_levels.name as subs_name,
                                billing_amount,
                                totalprice,due_date,is_active,
                                subscription_users.status 
                from subscription_users,users,subscription_levels 
                where subscription_users.user_id = users.id and subscription_levels.id = subscription_users.subscription_id limit 300";
            $result = $this->db->query($sql)->result_array();
            return $result;
        }
        
	function get_user_subscription_with_detail_by_userid($userid)
	{
		$this->db->select('*,subscription_users.id as suid');
		$this->db->from("subscription_users");
		$this->db->join('subscription_levels','subscription_levels.id = subscription_users.subscription_id');
		$this->db->where('user_id',$userid);
		$this->db->where('is_active',1);
		$this->db->order_by('subscription_users.id desc');
		//$this->db->limit(1);
		$query = $this->db->get()->result_array();
		if(count($query)<=0)
			return array();
		return $query;
	}

        function get_subscription_detail_by_id($id){
            $sql = "select users.*, subscription_users.id as suid, subscription_levels.name as subs_name,billing_amount,subscription_users.status,
                    subscription_users.subscription_id,totalprice,subscription_users.user_id,subscription_levels.cycle_period,
                    subscription_levels.cycle_number,subscription_users.created_at,subscription_levels.description,due_date,is_active
                    from subscription_users,users,subscription_levels
                    where subscription_users.user_id = users.id and 
                        subscription_levels.id = subscription_users.subscription_id and subscription_users.id = $id";
            $result = $this->db->query($sql)->row_array();
            return $result;
        }
        
	function add_user_subscription(){
		$subscription_detail = $this->get_subscription_detail($this->input->post('subsciption_level'));
		$data = array(
				'user_id' => $this->session->userdata('user_id'),
				'subscription_id' => $this->input->post('subsciption_level'),
				'is_active' => 0,
				'totalprice' => $subscription_detail['billing_amount']);
		if($this->input->post('subsciption_level') == 1){
			$data['status'] = 'Payment Completed';
		}else{
			$data['status'] = 'Payment Pending';
		}
		$data['payment_date'] = date('H:i:s M d, Y T');
		$data['payer_status'] = 'verified';		
		return $this->db->insert('subscription_users', $data);		
	}
	
	function update_user_subscription(){
		$subscription_detail = $this->get_subscription_detail($this->input->post('subsciption_level'));
		$data = array(
				'subscription_id' => $this->input->post('subsciption_level'),
				'totalprice' => $subscription_detail['billing_amount']);
		if($this->input->post('subsciption_level') == 1){
			$data['status'] = 'Payment Completed';
		}else{
			$data['status'] = 'Payment Pending';
		}
		$data['payment_date'] = date('H:i:s M d, Y T');
		$data['payer_status'] = 'verified';		
		return $this->db->update('subscription_users', $data, array('user_id' => $this->session->userdata('user_id')));		
	}
	
	function get_user_subscribed_cat($user_id){
		$this->db->select('cat_id');
		$this->db->from("subscription_categories");
		$this->db->where('user_id',$user_id);
		$query = $this->db->get()->result_array();
		if(count($query)<=0)
			return false;
		$res = array();
		foreach ($query as $value) {
			$res[] = $value['cat_id'];	
		}
		return $res;
	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->or_where('LOWER(new_email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data, $activated = TRUE)
	{
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;

		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();
			$this->create_profile($user_id);
			return array('user_id' => $user_id);
		}
		return NULL;
	}

	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		if ($activate_by_email) {
			$this->db->where('new_email_key', $activation_key);
		} else {
			$this->db->where('new_password_key', $activation_key);
		}
		$this->db->where('activated', 0);
		$query = $this->db->get($this->table_name);

		if ($query->num_rows() == 1) {

			$this->db->set('activated', 1);
			$this->db->set('new_email_key', NULL);
			$this->db->where('id', $user_id);
			$this->db->update($this->table_name);

			$this->create_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800)
	{
		$this->db->where('activated', 0);
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$this->db->delete($this->table_name);
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete($this->table_name);
		if ($this->db->affected_rows() > 0) {
			$this->delete_profile($user_id);
                        /*
			$this->delete_address($user_id);
			$this->delete_cuisine($user_id);
			$this->delete_user_truck_comments($user_id);
			$this->delete_user_truck_fav($user_id);
                         */
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set_password_key($user_id, $new_pass_key)
	{
		$this->db->set('new_password_key', $new_pass_key);
		$this->db->set('new_password_requested', date('Y-m-d H:i:s'));
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 1;
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
	{
		$this->db->set('password', $new_pass);
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id, $new_pass)
	{
		$this->db->set('password', $new_pass);
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function set_new_email($user_id, $new_email, $new_email_key, $activated)
	{
		$this->db->set($activated ? 'new_email' : 'email', $new_email);
		$this->db->set('new_email_key', $new_email_key);
		$this->db->where('id', $user_id);
		$this->db->where('activated', $activated ? 1 : 0);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		$this->db->set('email', 'new_email', FALSE);
		$this->db->set('new_email', NULL);
		$this->db->set('new_email_key', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_email_key', $new_email_key);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);

		if ($record_ip)		$this->db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}

	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function ban_user($user_id, $reason = NULL)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 1,
			'ban_reason'	=> $reason,
		));
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	function unban_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 0,
			'ban_reason'	=> NULL,
		));
	}

	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_profile($user_id)
	{
		$this->db->set('user_id', $user_id);
		return $this->db->insert($this->profile_table_name);
	}

	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->profile_table_name);
	}
	
	/**
	 * Delete user address
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_address($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_addresses');
	}
	
	/**
	 * Delete user cuisine
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_cuisine($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_cuisines');
	}
	
	/**
	 * Delete user truck_comments
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_user_truck_comments($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_truck_comments');
	}
	
	/**
	 * Delete user truck_favs
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_user_truck_fav($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_truck_fav');
	}
	
	/**
	 * Delete user trucks
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_user_trucks($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_trucks');
	}
        
        public function update_subscription_users($edit_id,$post){
            $post['due_date'] = date('Y-m-d H:i:s',strtotime($post['due_date']));
            return $this->db->update('subscription_users',$post,array('id' => $edit_id));
        }
        
        public function delete_user_subscription_invoice($id){
            $query = $this->db->delete('subscription_users',array('id' => $id));
            //echo $this->db->last_query();
            return $query ;
        }
        
        public function add_user_subscription_invoice($post,$user_id){
            $post['due_date'] = date('Y-m-d H:i:s',strtotime($post['due_date']));
            $post['user_id'] = $user_id;
            return $this->db->insert('subscription_users',$post);
        }
}


/* End of file users.php */
/* Location: ./application/models/auth/users.php */