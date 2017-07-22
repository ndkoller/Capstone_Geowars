<!-- src/Template/Users/add.ctp -->

<div class="col-lg-6">
<?= $this->Flash->render() ?>
    <form role="form" method="post" action="/users/login">
        <div class="form-group">
            <label>Username</label>
            <input id="username" name="username" class="form-control">
            <p class="help-block">This username will be what other users see< p>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input id="password1" name="password" type="password" class="form-control">
        </div>
        
        <button  type="submit" class="btn btn-default">Submit Button</button>
    </form>
    <button  onclick="register()" class="btn btn-default">Dont Submit Button</button>
        <button type="reset" class="btn btn-default">Reset Button</button>

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
            var responseObject = JSON.parse(ajaxreq.responseText);
            if (responseObject.success == true) {
              //Todo
            } else {
              //Todo
            }
          }
    };
    var postString = 'username=' + username + '&password=' + password1 + '&email=' + email;
    ajaxreq.open('POST', '/users/add', true);
    ajaxreq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    ajaxreq.send(postString);

}


</script>