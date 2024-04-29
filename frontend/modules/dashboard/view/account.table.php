<?php
use backend\bus\UserBUS;

?>
<table class="table align-middle table-borderless table-hover text-start">
    <thead>
        <tr class="align-middle">
            <th></th>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Role</th>
            <th>Status</th>
            <th>Manage</th>
        </tr>
    </thead>
    <?php foreach (UserBUS::getInstance()->getAllModels() as $user): ?>
        <tbody>
            <tr>
                <td class='col-1'><img src="<?php echo $user->getImage(); ?>" style="width: 50px; height: 50px;" alt="ATR">
                </td>
                </td>
                <td class='col-1'><?= $user->getUsername() ?></td>
                <!-- <td class='col-1'>
                    <div class="shorten-text"><?= $user->getPassword() ?></div>
                </td> -->
                <td class='col-2'><?= $user->getName() ?></td>
                <td class='col-2'><?= $user->getEmail() ?></td>
                <td class='col-1'><?= $user->getPhone() ?></td>
                <td class="col-2"><?= $user->getAddress() ?></td>
                <td class='col-1'><?= $user->getRoleId() ?></td>
                <td class='col-1'><?= $user->getStatus() ?></td>
                <td class='col-2 userAction'>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal">
                        <span data-feather="lock"></span>
                        Lock
                    </button>
                    <button class="btn btn-sm btn-danger">
                        <span data-feather="trash-2"></span>
                        Delete
                    </button>
                </td>

            </tr>
        </tbody>
    <?php endforeach; ?>
</table>