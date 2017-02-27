<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <?php
    echo '<form action="'.base_url().'admin/settings/sms" method="post" id="sms-form" accept-charset="utf-8">';
    echo '<fieldset>';
    echo '<legend>Auto SMS Settings:</legend>';
    foreach ($sms_settings as $sms_setting) {
        echo '<div class="input-label">';
        echo '<label for="' . $sms_setting['name'] . '">' . ucwords(str_replace('_', ' ', $sms_setting['name'])) . ':</label>';
        echo '</div>';
        echo '<div class="form-input">';
        if ($sms_setting['type'] == 'message') {
            echo '<textarea name="' . $sms_setting['name'] . '" id="message" style="margin: 0px; width: 518px; height: 100px;">' . $sms_setting['value'] . '</textarea>';
        } else {
            echo '<input class="D" type="text" name="' . $sms_setting['name'] . '" value="' . $sms_setting['value'] . '">';
        }
        echo '</div>';
    }
    //echo '<input type="submit" id="submit" onclick="send_sms_submit(event)" value="Send" />';
    echo '<input type="submit" value="Update"/>';
    echo '</fieldset>';
    echo '</form>';
    ?>
</div>