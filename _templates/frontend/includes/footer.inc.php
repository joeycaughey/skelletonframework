<?php 
global $NavigationGroupsModel;

?>
<div class="limit-width">
    <div class="three-column-layout">
        <div class="column first">
            <div class="inset">
	            <h3>About Us</h3>
	            <ul class="navigation">
	                <?= $NavigationGroupsModel->build(2) ?>
	            </ul>
	        </div>
        </div>
        <div class="column">
            <div class="inset">
            <h3>Productions</h3>
	            <ul class="navigation">
	                <?= $NavigationGroupsModel->build(3) ?>
	            </ul>
            </div>
        </div>
        <div class="column">
	        <ul class="social-icons right">
		        <li class="vimeo"><a href="javascript: void(0);" target="_blank"></a></li>
		        <li class="youtube"><a href="http://www.youtube.com/bigsoul1999" target="_blank"></a></li>
		    </ul>
		    
            <div class="legal">
		       <div class="links">
		            <?= $NavigationGroupsModel->build(4);?>
		        </div>
		    
		        Copyright &copy;Big Soul Productions Inc. All Rights Reserved
		        <br />Site Hosting and Design by <a href="http://www.sitemafia.com" target="_blank">Site Mafia Interactive</a>
		    </div>
        </div>
        <div style="clear:both;"></div>
    </div>  
</div>