<?php foreach ($permissionList as $permission): ?>    
    <input class="form-check-input mx-1 col-1" type="checkbox" value="" id="<?= $permission -> getId()?>">
    <label class="form-check-label col-5" for="flexCheckDefault">
        <?= $permission -> getName() ?>
    </label>
<?php endforeach; ?>