<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <div class="admin-form">
        <?php echo form_open('admin/users/add_new'); ?>
        <div class="login-input">
            <?php echo validation_errors('<p class="error">'); ?>
            <fieldset>
                <legend>Basic Info:</legend>
                <?php
                echo '<div class="input-label">';
                echo '<label for="member_type">Select Member Type:</label>';
                echo '</div>';
                echo '<div class="form-input">';
                echo form_dropdown('member_type', array('Administrators' => 'Administrators', 'Managers' => 'Managers', 'Customers' => 'Customers'), set_value('member_type'));
                echo '</div>';
                ?>
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
                    <label for="mobile_number">Mobile Number:</label>
                </div>
                <div class="form-input">
                    <input type="text" name="mobile_number" placeholder="e.g., 254XXXXXXXXX" value="<?php echo set_value('mobile_number'); ?>">
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
            <?php echo form_submit('submit', 'Add'); ?>
        </div>
        </form>
    </div>
</div>