<?php
include_once 'user_model.php';
/**
 * Retailer class inherited by user model
 *
 * @author hafiz
 */
class Retailer_model extends user_model
{
    public static $table;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * get all retailers
     * 
     * @param array $conditions Conditions to be included in where array
     * @return array $retailers all retailers according to condition
     * 
     */
    public function get_all_retailers($conditions = array())
    {
	   $retailers = $this->db->select('*,users.id as uid')
						->from('users')
						->join('user_profiles','user_profiles.user_id = users.id')
						->where('users.group_id',RETAILER_USER)
                                                ->group_by('user_profiles.company_name')
						->get()
						->result_array();
								//$this->output->enable_profiler(1);
		
      return $retailers;        
    
	}
	
    

}

?>
