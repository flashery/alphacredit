<section class="bg_dark content">
    <div class="signup-form">
        <h1>Sign Up!</h1>

        <?php echo form_open('signup'); ?>
        <div class="login-input">
            <?php echo validation_errors('<p class="error">'); ?>
            <fieldset>
                <legend>Basic Info:</legend>
                <div class="input-label">
                    <label for="username">Username:</label>
                </div>
                <div class="form-input">
                    <input type="text" name="username" placeholder="Username" value="<?php echo set_value('username'); ?>">
                </div>
                <div class="input-label">
                    <label for="password">Password:</label>
                </div>
                <div class="form-input">
                    <input type="password" name="password" placeholder="Password">
                </div>
                <div class="input-label">
                    <label for="passconf">Re-type Password:</label>
                </div>
                <div class="form-input">
                    <input type="password" name="passconf" placeholder="Re-type Password">
                </div>
                <div class="input-label">
                    <label for="email">Email:</label>
                </div>
                <div class="form-input">
                    <input type="text" name="email" placeholder="Email" value="<?php echo set_value('email'); ?>">
                </div>
                <div class="input-label">
                    <label for="name">Name:</label>
                </div>
                <div class="form-input">
                    <input type="text" name="name" placeholder="Name" value="<?php echo set_value('name'); ?>">
                </div>
            </fieldset>
        </div>
        <div style="clear:both"></div>
        <?php echo form_submit('submit', 'Create'); ?>
        <?php echo anchor('login/', 'Log in'); ?>
        </form>
    </div>
</section>