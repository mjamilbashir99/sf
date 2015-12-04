<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package        CodeIgniter
 * @author        ExpressionEngine Dev Team
 * @copyright    Copyright (c) 2006, EllisLab, Inc.
 * @license        http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since        Version 1.0
 * @filesource
 */



// ------------------------------------------------------------------------

/**
 * Pagination Class
 *
 * @package        CodeIgniter
 * @subpackage    Libraries
 * @category    Pagination
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/libraries/pagination.html
 */
class MY_Pagination extends CI_Pagination
{
    // --------------------------------------------------------------------
    
    /**
     * Generate the pagination links
     *
     * @access    public
     * @return    string
     */    
    function create_links()
    {
        // If our item count or per-page total is zero there is no need to continue.
        if ($this->total_rows == 0 OR $this->per_page == 0)
        {
           return '';
        }

        // Calculate the total number of pages
        $num_pages = ceil($this->total_rows / $this->per_page);

        // Is there only one page? Hm... nothing more to do here then.
        if ($num_pages == 1)
        {
            return '';
        }

        // Determine the current page number.        
        $CI =& get_instance();    
        $get_page = array('page');
        
        $page_num['page'] = intval($this->uri_segment);
        if ($page_num['page'] != FALSE)
        {
            $this->cur_page = $page_num['page'];
            
            // Prep the current page - no funny business!
            //$this->cur_page = $this->cur_page;
            
        }

        $this->num_links = (int)$this->num_links;
        
        if ($this->num_links < 1)
        {
            show_error('Your number of links must be a positive number.');
        }
                
        if ( ! is_numeric($this->cur_page))
        {
            $this->cur_page = 0;
        }
        
        // Is the page number beyond the result range?
        // If so we show the last page
        if ($this->cur_page > $this->total_rows)
        {
            $this->cur_page = ($num_pages - 1) * $this->per_page;
        }
        
        $uri_page_number = $this->cur_page;
        $this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with
        $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
        $end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

        // Add a trailing slash to the base URL if needed
        $this->base_url = rtrim($this->base_url, '/') ;

          // And here we go...
        $output = '';

       //  Render the "First" link (Optional)
        if  ($this->cur_page > $this->num_links)
        {
            $output .= $this->first_tag_open.'<a href="'.$this->base_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
        }
        
        
        // Render the "previous" link
        if  ($this->cur_page != 1)
        {
            $i = $uri_page_number - $this->per_page;
            if ($i == 0) $i = '';
            $output .= $this->prev_tag_open.'<a href="'.$this->base_url."/".$i.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
        } 
        else
        {
            $output .= '<span class="disabled">'.$this->prev_link.'</span>';
        }
    
        
        //not enough pages to bother breaking it up
        if ($num_pages < 7 + ($this->num_links * 2))   
        {
            $output .= $this->pages_loop($start-1,$end);
        }
        //enough pages to hide some
        elseif($num_pages > 5 + ($this->num_links * 2))    
        {
            //Prepare First pages string
            $first_pages = $this->num_tag_open.'<a href="'.$this->base_url.'">1</a>'.$this->num_tag_close;
            $first_pages .= $this->num_tag_open.'<a href="'.$this->base_url.'"/'.$this->per_page.'">2</a>'.$this->num_tag_close;
            $first_pages .= "...";
            
            //Prepare Second pages string
            $num_pages_minus = $num_pages-1;
            $last_page_url = (($num_pages * $this->per_page) - $this->per_page);
            $secondlast_page_url = ($last_page_url - $this->per_page);
            $second_pages = "...";
            $second_pages .= $this->num_tag_open.'<a href="'.$this->base_url.'"/'.$secondlast_page_url.'">'.$num_pages_minus.'</a>'.$this->num_tag_close.'<span> | </span>';
            $second_pages .= $this->num_tag_open.'<a href="'.$this->base_url.'"/'.$last_page_url.'">'.$num_pages.'</a>'.$this->num_tag_close.'<span> | </span>';
        
            //close to beginning; only hide later pages
            if($this->cur_page < 1 + ($this->num_links * 2))
            {
                $output .= $this->pages_loop(1,3 + ($this->num_links * 2));
                
                $output .= $second_pages;   
            }
            //in middle; hide some front and some back
            elseif($num_pages - ($this->num_links * 2) > $this->cur_page && $this->cur_page > ($this->num_links * 2))
            {
                $output .= $first_pages;
                
                $output .= $this->pages_loop($this->cur_page - $this->num_links,$this->cur_page + $this->num_links);
                
                $output .= $second_pages;
            }
            //close to end; only hide early pages
            else
            {
                $output .= $first_pages;
                
                $output .= $this->pages_loop($num_pages - (2 + ($this->num_links * 2)),$num_pages);
            }
        }

        // Render the "next" link
        if ($this->cur_page < $num_pages)
        {
            $output .= $this->next_tag_open.'<a href="'.$this->base_url."/".($this->cur_page * $this->per_page).'">'.$this->next_link.'</a>'.$this->next_tag_close;
        } 
        else
        {
            $output .= '<span class="disabled">'.$this->next_link.'</span>';
        }

        /* Render the "Last" link (Optional)
        if (($this->cur_page + $this->num_links) < $num_pages)
        {
            $i = (($num_pages * $this->per_page) - $this->per_page);
            $output .= $this->last_tag_open.'<a href="'.$this->base_url.$i.'">'.$this->last_link.'</a>'.$this->last_tag_close;
        }
        */
        
        // Kill double slashes.  Note: Sometimes we can end up with a double slash
        // in the penultimate link so we'll kill all double slashes.
        $output = preg_replace("#([^:])//+#", "\1/", $output);

        // Add the wrapper HTML if exists
        $output = $this->full_tag_open.$output.$this->full_tag_close;
       
        return $output;        
    }
    
    function pages_loop ($start,$end) 
    {
        $output = "";
        
        for ($loop = $start; $loop <= $end; $loop++)
        {
            $i = ($loop * $this->per_page) - $this->per_page;
            
            if ($i >= 0)
            {
                if ($loop == $this->cur_page)
                {
                    $output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
                }
                else
                {
                    $n = ($i == 0) ? '' : '/'.$i;
                    $output .= $this->num_tag_open.'<a href="'.$this->base_url.$n.'">'.$loop.'</a>'.$this->num_tag_close."<span> | </span>";
                }
            }
        }
        
        return $output;
    
    }

}
?>