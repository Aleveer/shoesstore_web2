<?php foreach ($roleList as $role); ?>    
    <table class="table align-middle table-borderless table-hover">
        <thead class="table-light">
            <tr class="align-middle">
                <th>Role ID</th>
                <th>Role Name</th>
                <th>Permission Number</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <tr >
                <td class='col-2'><?= $role -> getId()?></td>
                <td class='col-6'><?= $role -> getName()?></td>
                <td class='col-3'><? echo 'them so luong chuc nang cua role'?> </td>
                <td class='col-1'>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editRoleModal">
                        <span data-feather="tool"></span>
                        Update
                    </button>
                </td>
            </tr>
        </tbody>
    </table>