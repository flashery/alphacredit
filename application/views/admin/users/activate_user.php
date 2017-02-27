<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <h3>Activate <?php echo $username; ?></h3>
    <div class="admin-form">
        <?php echo form_open('admin/users/activate_user'); ?>
        <?php
        /*
         * Call the Custom Fields Creator library function with the arguments 
         * Legend Title and the username
         */
        echo $this->cf_renderer->render_fields('More Info:', $username);
        ?>
        <?php echo form_submit('submit', 'Activate'); ?>

        </form>
    </div>
</div>