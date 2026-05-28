<?php
    /** @var $pizza ?\App\Model\Pizza */
?>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="pizza[name]" value="<?= $pizza ? $pizza->getName() : '' ?>">
</div>

<div class="form-group">
    <label for="size">Size</label>
    <input type="text" id="size" name="pizza[size]" value="<?= $pizza ? $pizza->getSize() : '' ?>">
</div>

<div class="form-group">
    <label></label>
    <input type="submit" value="Submit">
</div>