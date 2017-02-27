<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <div class="admin-form">
        <header>
            <h2>Edit Your Profile</h2>
        </header>
        <div id="profile-action">
            <?php
            if (!empty($profile_image['image'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($profile_image['image']) . '" />';
            } else {
                echo '<img src="' . base_url() . 'images/profile/no-photo.jpg" />';
            }
            ?>
            <button id="upload-btn">Upload New Photo</button>
            <?php
            if (!empty($signature_image['image'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($signature_image['image']) . '" />';
            } else {
                echo '<img src="' . base_url() . 'images/profile/no-signature.jpg" />';
            }
            ?>
            <button id="upload-sig-btn">Upload New Signature</button>
            <?php $this->user_action->show_admin_actions($user_profile['username'], $member_profile); ?>
        </div>
        <?php echo form_open('admin/users/edit_profile'); ?>
        <?php echo validation_errors('<p class="error">'); ?>
        <fieldset>
            <legend>Basic Info:</legend>
            <div class="input-label">
                <label for="username">Username:</label>
            </div>
            <div class="form-input">
                <input type="text" name="username" readonly="true" value="<?php echo $user_profile['username'] ?>">
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
                <input type="text" readonly="true" name="email" value="<?php echo $member_profile['email'] ?>">
            </div>
            <div class="input-label">
                <label for="name">Name:</label>
            </div>
            <div class="form-input">
                <input type="text" name="name" value="<?php echo $member_profile['name'] ?>">
            </div>
        </fieldset>
        <?php
        /*
         * Call the Custom Fields Creator library function with the arguments 
         * Legend Title and the username
         */
        echo $this->cf_renderer->render_fields('More Info:', $user_profile['username']);
        ?>

        <div style="clear:both"></div>
        <?php echo form_submit('submit', 'Update'); ?>
        </form>

        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <?php //echo $this->modal_creator->create_js_vars(); ?>

            </div>

        </div>
    </div>
</div>