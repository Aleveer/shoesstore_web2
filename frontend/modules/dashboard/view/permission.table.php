
    <table class="table align-middle table-borderless table-hover">
        <thead class="table-light">
            <tr class="align-middle">
                <th>Permissions ID</th>
                <th>Permissions Name</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($permissionList as $permission): ?>
            <tr >
                <td class='col-3'><?= $permission -> getId();?></td>
                <td class='col-8'><?= $permission -> getName();?></td>
                <td class='col-1'>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPermissionModal">
                        <span data-feather="tool"></span>
                        Update
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>