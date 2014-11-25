<?PHP
include("controller.class.php");

class Skelleton {
    public $default_template = "_templates/frontend/index.tpl";
    public $template;
    
    public $default_meta_title = "Skelleton Framework";
    public $default_meta_description = "";
    public $default_meta_keywords = "";
    
    function Skelleton() {
        global $CONFIG;
        $this->template = new QuickTemplate($this->default_template, '');
        $this->template->set_content($this->get_content());
        if ($_GET["preview"]!="true") $this->template->set_variable("PREVIEW_HEADER", "");
        $this->template->set_variable("META_TITLE", $this->default_meta_title);
        $this->template->set_variable("META_DESCRIPTION", $this->default_meta_description);
        $this->template->set_variable("META_KEYWORDS", $this->default_meta_keywords);
        $this->template->set_variable("HOST", $CONFIG["host"]);
        $this->template->show();
    }
    
    function set_template($t) {    
        $this->template->set_template($t);
    }
    
    function get_content() {
        global $routes;
        global $template;
        
        $uri = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = $uri[0];
        $extra_parameters = explode("&", $uri[1]);
        
        if (substr($uri, strlen($uri)-1, strlen($uri))!="/") header("Location: ".$uri."/");
        
        foreach ($extra_parameters as $p) {
            $param = explode("=", $p);
            $key = $param[0];
            $_GET[$key] = $param[1];
        }
    
        // Advanced Routing Loop
        foreach ($routes->get() as $key => $route) {
            $routett = preg_replace("/\/:[a-zA-Z_]+/", "", $key);
            $routett = preg_replace("/\/:[0-9]+/", "", $routett);
    
            if ($routett==$uri && substr_count($uri, ":")==0) {
                $current_route = $route;
                $current_route["params"] = array();
                break;
            } else {
                preg_match("/^(".str_replace("/", "\/", $route["expression"][0]).")$/", $uri, $match);
        
                if ($match[0]!="") {
                    $current_route = $route;
                    preg_match_all("/".str_replace("/", "\/", $route["expression"][1])."/", $uri, $parameters);
                    $count = 1;
                    foreach ($current_route["params"] as $k => $param) {
                        $current_route["params"][$k] = $parameters[$count][0];
                        $count++;
                    }
                    break;
                }
            }
        }
        // use default template if there's none.
        if ( $current_route["template"] == '' ) {
            $current_route["template"] = "_template/index.tpl";
        }
        $this->set_template($current_route["template"]);
        
        // Populate the Get Parameters
        if (count($current_route["params"])>0) {
            foreach($current_route["params"] as $k => $p) {
                $_GET[$k] = $p;
            }
        }   
        // Set the Controller
        $controller = $current_route["controller"];
        
        // Create Dynamic Function to Run View Functions
        $function = explode("/", $view);
        $function = $function[(count($function)-1)];
        
        // Load the Controller
        $controller_file = "controllers/".$controller.".php";
        if (file_exists($controller_file)) {
            include($controller_file);  
            if(function_exists($function)) $function();
        }
        
        $view_file = $current_route["route"].".php";
        //echo $view_file;
        if (!file_exists($view_file)) $view_file = "modules/_core/errors/404.php";
        ob_start();
            include($view_file);    
        return ob_get_clean(); 
    }   
}

try {
    $Skelleton = new Skelleton;
} catch (Exception $e) {
    $subject = "There has been an error on {$CONFIG["site"]["name"]}.";
    $message = $e;  
    header("Location: ".get_uri("overview_url"));
}
