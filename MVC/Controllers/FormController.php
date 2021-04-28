<?php 

class FormController{
    
    public function process(){
        $email = $this->processInput($_POST['email']);
        
        
    }

    public function processInput($data){
        $data = trim($data);  //Removes the empty spaces from the email
        $data = stripslashes($data);
        $data = htmlspecialchars($data); //Converts special chars to HTML entities to avoid exploits
        return $data;
     
    }
}

?> 
