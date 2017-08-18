<!-- src/Template/Users/add.ctp -->

<div class="col-lg-6">

    <form role="form">
        <div class="form-group">
            <label>Username</label>
            <input id="username" class="form-control">
            <p class="help-block">This username will be what other users see</p>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input id="email" class="form-control">
            <p class="help-block">Your email will be use if Geo Wars needs to contact you.</p>
        </div>  
        <div class="form-group">
            <label>Password</label>
            <input id="password1" type="password" class="form-control">
        </div>
        <div class="form-group">
            <label>Re-enter Password</label>
            <input id="password2" type="password" class="form-control">   
        </div> 
        
    </form>
    <button  onclick="register()" class="btn btn-default">Submit</button>
        <!-- <button type="reset" class="btn btn-default">Reset</button> -->

</div>

<script type="text/javascript">

function register() {

    var password1 = document.getElementById('password1').value;
    var password2 = document.getElementById('password2').value;
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
        
    
    if (password1.localeCompare(password2) || ! password1.localeCompare("") || ! password2.localeCompare("")) {
        alert("Passwords do not match");
        return;
    }
    
    var ajaxreq = new XMLHttpRequest();
    ajaxreq.onload = function() {
          if (ajaxreq.readyState == 4 && ajaxreq.status === 200) {
            var response = ajaxreq.responseText;
            if(!response.localeCompare("1")) {
                window.location.href = "/users/login";
            } else {
                alert("Registration failed please try again later.");
            }
          }
    };
    var postString = 'username=' + username + '&password=' + password1 + '&email=' + email;
    ajaxreq.open('POST', '/users/processadd', true);
    ajaxreq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    ajaxreq.send(postString);

}


</script>