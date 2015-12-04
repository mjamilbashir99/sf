<?PHP

class UploadImages{


  public function UploadImages(){
		
	}
	 public function getExtension($Filename){ 

		$Extension 	 = explode (".", $Filename);
		$Extension_i = (count($Extension) - 1);
		return $Extension[$Extension_i];
	}

	public function isValidImg($file_type){
		$error	= "";
			
		if(($file_type =='jpeg') || ($file_type =='gif') || ($file_type == 'jpg') || ($file_type =='png')){
			return ;
		}else{		
			 $error .="&nbsp; &bull; &nbsp;Invalid image format.<br>";
		} 
				
		return $error;
	}

	public function uploadFile(&$file_name, $file_temp_name, $path_to_insert, $unlink=false){

		//if file exist then alter its name and upload else upload it to the specific folder.
		$fullpath = $path_to_insert.$file_name;

		if(file_exists($fullpath)){
			
			if($unlink){
				unlink($fullpath);
				//echo $path_to_insert.$file_name;
				if(move_uploaded_file($file_temp_name,$path_to_insert.$file_name))
					return  true;
			}
			else{
				//change file name here and upload.
	
				$isExists=true;
				while($isExists){
					$randNum = rand(1,100);
					$fileextention = $this->getExtension($file_name);
					//echo "ext = " .$fileextention;

					$without_ext_name = substr($file_name,0,(strlen($file_name)-strlen($fileextention)-1));
					//echo "name = " .$without_ext_name;

					$_newfilename = $without_ext_name . $randNum . "." .$fileextention;
					$file_name = $_newfilename;
					if(file_exists($path_to_insert.$_newfilename))
						$isExists=true;
					else{
						$isExists = false;
					}
				}

				if(move_uploaded_file($file_temp_name,$path_to_insert.$file_name))
					return  true;
				//echo $path_to_insert.$file_name;
			}
		}
		else{
			
		
			if(move_uploaded_file($file_temp_name, $path_to_insert.$file_name))
				return  true;
		}
		return false;
	}
	
}

?>