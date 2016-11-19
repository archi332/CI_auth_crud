<div class='mainInfo'>

    <h1>Update User</h1>
    <p>Please enter the users information below.</p>

    <form action="<?php echo site_url('/userList/update/' . $id); ?>" method="post" >

        <div class="form-group">
            <label for="exampleInputUserName">User name</label>
            <input type="text"  name="user_name" class="form-control" id="exampleInputUserName" placeholder="username" value="<?= $username ?>">
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="<?= $email ?>">
        </div>

        <button type="submit" class="btn btn-default">Submit</button>

    </form>

</div>

