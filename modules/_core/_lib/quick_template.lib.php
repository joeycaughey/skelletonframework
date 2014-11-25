<?PHP 
// v2.0
//****************************************************
// Quick Template Function Library
// Last Modified: Aug 2008
//****************************************************

class QuickTemplate {
	var $variables = array();
	var $replace_templates = array();
	var $output;
	var $template_path;
	
	function QuickTemplate($file = 'index.tpl', $title = '') {
		if (file_exists($file)) {
			$this->set_template($file);
			$this->set_variable('TITLE', $CONFIG["site"]["page_title"].$title);
		} else {
			die ("Template File $file Not Found.");
		}
	}
	
	function set_template($file) {
		$path = explode("/", $file);
		array_pop($path);
		$this->template_path  = implode("/", $path);
		$this->set_variable('TEMPLATE_DIR', $this->template_path);
		ob_start();
			include($file);
		$this->output = ob_get_clean();
	}
	
	function set_variable($name, $value) {
		$this->variables[$name] = $value;
	}
	
	function set_content($value) {
		$this->set_variable('CONTENT', $value);
	}

	function get_template_file($file) {
		global $CONFIG;
		ob_start();
			include($file);
		return ob_get_clean();
	}
	
	function replace_template($name, $value) {
		$this->replace_templates[$name] = $value;
	}
	
    function include_template_file($variable, $file) {
	   $this->set_variable($variable, $this->get_template_file($file));
    }
	
	function load_template_files() {
		$dir=$this->template_path."/includes/";
	
		if (is_dir($dir)) {
		   if ($dh = opendir($dir)) {
			   while (($file = readdir($dh)) !== false) {
				
					$include=explode(".",$file);	
					$name = strtoupper($include[0]);
					if (isset($this->replace_templates[$name])) {
						$this->include_template_file($name, $this->replace_templates[$name]);
					} else if ($include[1]=="inc" AND $include[2]=="php") { 
						$this->include_template_file($name, $this->template_path."/includes/".$file);
					}	
					
					//echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
			   }
			   closedir($dh);
		   }
		}
	}
	
	function show() {
		$this->load_template_files(); 
		foreach ($this->variables as $key => $val) {
			$this->output = str_replace("{".$key."}", $val, $this->output);
		}
		echo $this->output;
	}
}

class EmailTemplate {
	var $variables = array();
	var $output;
	var $HTML_output;
	var $subject;
	var $to, $to_name;
	var $from, $from_name;
	var $templatePath;
	
	function EmailTemplate($file, $subject = 'No Subject') {
		$text_file = $file.".text.tpl";
		$html_file = $file.".html.tpl";
		
		$this->subject = $subject;
		
		if (file_exists($text_file)) {
			ob_start();
				include($text_file);
		    $this->output = ob_get_clean();
		} else {
			$this->output = $file;
			//die ("Required Email Text Tempalte File $text_file Not Found.");
		}
		
		if (file_exists($html_file)) {
			ob_start();
				include($html_file);
		    $this->HTMLoutput = ob_get_clean();
		} else {
			$this->HTMLoutput = false;
		}
	}
	
	
	function send_from($email, $name = '') {
		$this->from = $email;
	}
	
	function set_variable($name, $value) {
		$this->variables[$name] = $value;
	}
	
	function set_variables($array) {
		$this->variables = array_merge($this->variables,$array);
	}
	
	function send($email, $name = '') {
		global $CONFIG;
		global $feedback;

		foreach ($this->variables as $key => $val) {
			$this->output = str_replace("{".strtoupper($key)."}", $val, $this->output);
			$this->subject = str_replace("{".strtoupper($key)."}", $val, $this->subject);
		}
		
		$from = ($this->from) ? $this->from : $CONFIG["email"]["from_address"];
		$from_name = ($this->from_name) ? $this->form_name :  $CONFIG["email"]["from_name"];

		if (phpmailer_mail($email, $this->subject, $this->output)) return true;
		else return false;
	}
}


?>