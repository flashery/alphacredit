<section class="bg_dark content">
    <div class="post">
        <header>
            <h2><?php echo ucfirst($user_profile['username']); ?> Profile</h2>
        </header>
        <div id="profile-action">
            <?php
            if (!empty($profile_image['image'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($profile_image['image']) . '" />';
            } else {
                echo '<img src="' . base_url() . 'images/profile/no-photo.jpg" />';
            }
            if (!empty($signature_image['image'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($signature_image['image']) . '" />';
            } else {
                echo '<img src="' . base_url() . 'images/profile/no-signature.jpg" />';
            }
            ?>
            <?php $this->user_action->show_actions($user_profile['username'], $user_profile['subclass']); ?>
        </div>
        <div class="edit_profile">
            <a href="<?php echo base_url(); ?>index/edit_profile">Edit Profile</a>
        </div>
        <table id="members-info-tbl">
            <tbody>
                <tr>
                    <td>Username:</td>
                    <td><?php echo $user_profile['username'] ?></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><?php echo $member_profile['name'] ?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?php echo $member_profile['email'] ?></td>
                </tr>
                <?php
                /*
                 * Call the Custom Fields Creator library function with the arguments 
                 * Legend Title and the username
                 */
                echo $this->cf_renderer->render_td($user_profile['username']);
                ?>
            </tbody>
        </table>
        <div class="edit_profile">
            <a href="<?php echo base_url(); ?>index/edit_profile">Edit Profile</a>
        </div>
        <!-- The Modal -->
        <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
            </div>
        </div>
    </div>
</section>