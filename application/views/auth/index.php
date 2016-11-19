<div class='mainInfo'>

    <h1>Users</h1>
    <p>Below is a list of the users.</p>


    <table class="table">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['first_name'] ?></td>


                <td><?php echo $user['email']; ?></td>


                <td><?php echo $user['group_description']; ?></td>

                <td>
                    <a href="<?= site_url('/userList/delete/' . $user['id']) ?>" class="btn btn-danger">delete</a>
                    <a href="<?= site_url('/userList/update/' . $user['id']) ?>" class="btn btn-primary">update</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="<?php echo base_url('/userList/create_user'); ?>">Create a new user</a></p>

</div>
