<?php
    $emailError = $checkboxError = $emailEndingErr = "";
    $email = $checkbox = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["email"])) {
        $emailError = "Email is required!";
      }else{
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailError = "Valid email required";
        }

        if(str_ends_with($email, ".co")){
          $emailEndingErr = "We are not accepting subscriptions from Colombia emails.";
        }
      }
  
      if (empty($_POST["checkbox"])) {
        $checkboxError = "Checkbox is required!";
      }
  
    }

    function test_input($data){
      $data = trim($data);  //Removes the empty spaces from the email
      $data = stripslashes($data);
      $data = htmlspecialchars($data); //Converts special chars to HTML entities to avoid exploits
      return $data;
    }

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="CSS/main_page.css">
        <script src="Vue/vue.js"></script>
    </head>

    <body>
    
    <div class="main">
        <div class="top_bar">
            <img src="Images/logo_pineapple.svg" alt="logo_pineapple">
            <a class="about-link" href="#">About</a>
            <a class="works-link" href="#">How it works</a>
            <a class="contact-link" href="#">Contact</a>
        </div>

        <div id="app">
          <div v-if="subscribed == false">
            <h1>Subscribe to newsletter</h1>
            <h2>Subscribe to our newsletter and get 10% discount on pineapple glasses.</h2>
            <span class="error">* <?php echo $emailError;?></span>
            <br><br>
            <span class="error">* <?php echo $checkboxError;?></span>
            <br><br>
            <span class="error">* <?php echo $emailEndingErr;?></span>
            <br><br>

            <form @input="checkForm" @submit="subscribeSuccess" onsubmit="return false" action="<" method="post" novalidate="true">
                <div class="vl"></div>
                <input type="email" id="email" name="email" placeholder="Type your email address here…" v-model="email">
                <input type="submit" value="Submit" v-bind:disabled="isDisable(email)">

                <div class="TOS" id="v-model-checkbox">
                  <input type="checkbox" id="checkbox" name="checkbox" v-on:click="checkboxChange" v-model="checked" @submit="checkForm">
                  <label for="tos"> I agree to <a href="#">terms of service</a></label>
                </div>

                <p v-if="errors.length">
                    <b>Please correct the following error(s):</b>
                    <ul>
                      <li v-for="error in errors">{{ error }}</li>
                    </ul>
                </p>
                
                
            </form>
        </div>

          <div v-if="subscribed == true">
            <h1>Thanks for subscribing!</h1>
            <h2>You have successfully subscribed to our email listing. Check your email for the discount code.</h2>
          </div>

        </div>
        
        
        <div class="line"></div>
        <div class="icon">
            
        </div>
        <div class="pineapple_image"></div>
    </div>

    </body>
</html>

<script>
    
    const app = new Vue({
    el: '#app',
    data: {
        errors: [],
        email: "",
        coEnding: null,
        checked: false,
        subscribed: false
    },

    methods: {

    isDisable(email) {
      if(email.length == 0 || this.errors.length > 0){
        return true;
      }else{
        return false;
      }
    },

    checkboxChange(){
      if(this.checked == false){
        return this.checked = true;
      }
      if(this.checked == true){
        return this.checked = false;
      }

    },

    validEmail: function (email) {
      var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      
      return re.test(email);
      },

    validEnding: function(email) {
      if(email.endsWith(".co")){
        coEnding = true;
      }else{
        coEnding = false;
      }

      return coEnding;
    },


    checkForm: function (e) {
      this.errors = [];

      if (!this.email) {
        this.errors.push('Email required.');
      } else if (!this.validEmail(this.email)) {
        this.errors.push('Valid email required.');
      }

      if(this.validEnding(this.email)) {
        this.errors.push('We are not accepting subscriptions from Colombia emails.');
      }

      if(this.checked == false){
          this.errors.push('Checkmark required.');
      }

      if (!this.errors.length) {
        return true;
      }

      e.preventDefault();
    },

    subscribeSuccess(){
      this.subscribed = true;
    }

    
    }
})

  </script>

