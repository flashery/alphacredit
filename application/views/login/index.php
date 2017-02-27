<section class="bg_dark content">
    <div class="login-form">
        <h1>Login</h1>
        <?php echo form_open('login/validate_login'); ?>
        <div class="login-input">
            <?php echo validation_errors('<p class="error">'); ?>
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
        </div>
        <div style="clear:both"></div>
        <?php echo form_submit('submit', 'Submit'); ?>
        <?php echo anchor('signup/', 'Signup'); ?>
        </form>
    </div>
</section>