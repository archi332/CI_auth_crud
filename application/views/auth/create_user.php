<div class='mainInfo'>

      <h1>Create User</h1>
      <p>Please enter the users information below.</p>



      <form action="<?php echo site_url('/userList/create_user'); ?>" method="post" >

            <div class="form-group">
                  <label for="exampleInputUserName">User name</label>
                  <input type="text"  name="user_name" class="form-control" id="exampleInputUserName" placeholder="username">
            </div>

            <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
            </div>

            <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>


            <button type="submit" class="btn btn-default">Submit</button>

      </form>

</div>
