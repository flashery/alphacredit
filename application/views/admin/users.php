<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <header><h2>Users List</h2></header>
    <?php
    foreach ($members as $member) {
        echo '<div class="users-image">';
        if (!empty($member['image'])) {
            echo '<a href="' . base_url() . 'admin/users/profile/' . $member['username'] . '">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($member['image']) . '">';
            echo '<h3>' . ucwords(strtolower($member['name'])) . '</h3>';
            echo '</a>';
        } else {
            echo '<a href="' . base_url() . 'admin/users/profile/' . $member['username'] . '">';
            echo '<img src="' . base_url() . 'images/profile/no-photo.jpg" />';
            echo '<h3>' . ucwords(strtolower($member['name'])) . '</h3>';
            echo '</a>';
        }
        echo '</div>';
    }
    ?>
    <div style="clear: both"></div>
    <p class="paginate-links"><?php echo $links; ?></p>
</div>