<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <div class="admin-form">
        <header>
            <h2>Bulk SMS</h2>
        </header>
        <?php
        $options = array(
            'Select' => 'Please Select',
            'Males' => 'All Male Account',
            'Females' => 'All Female Account',
            'Select Account' => 'Select Account');
        $js = array(
            'class' => 'D',
            'onChange' => 'selectNumber();'
        );
        echo '<div class="input-label"><label for="from">Send To:</label></div>';
        echo '<div class="form-input">';
        echo form_dropdown('select', $options, '', $js);
        echo '</div>';
        ?>
        <div id="select-accounts">
            <?php
            foreach ($accounts as $account) {
                $pieces = explode("/", $account['Mobile Number']);
                echo '<p><input type="checkbox" name="accounts" onclick="addPhoneNumber(' . $pieces[0] . ')" value="Bike">' . $account['Account Name'] . '</p>';
            }
            ?>
        </div>
        <form action="http://www.wivupsms.com/sendsms.php" method="get" accept-charset="utf-8">
            <fieldset>
                <legend>Send Bulk SMS:</legend>
                <div class="form-input">
                    <input type="hidden" name="user" value="Alphacredit">
                </div>
                <div class="form-input">
                    <input type="hidden" name="password" value="Xtr@Dcoded4u">
                </div>
                <div class="input-label">
                    <label for="mobile">Mobile:</label>
                </div>
                <div class="form-input">
                    <input class="D" type="text" name="mobile" value="">
                    <input class="S" type="button" onclick="removePhoneNumber()" value="Clear">
                </div>
                <div class="form-input">
                    <input type="hidden" name="senderid" value="ALPHACREDIT">
                </div>
                <div class="input-label">
                    <label for="message">Message:</label>
                </div>
                <div class="form-input">
                    <textarea name="message" style="margin: 0px; width: 218px; height: 100px;"></textarea>
                </div>
                <input type="submit" value="Send"/>
            </fieldset>
        </form>
    </div>