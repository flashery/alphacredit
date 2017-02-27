<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <header><h2>Members Savings</h2></header>
    <table>
        <thead>
            <tr>
                <?php
                foreach ($savings as $saving) {
                    foreach ($saving as $key => $value) {
                        echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                    }
                    break;
                }
                ?>
                <!--<th>Username</th><th>Credit Limit</th><th>Creation Date</th><th>Last Closing Date</th><th>Status</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($savings as $saving) {
                echo '<tr>';
                echo '<td>' . ucwords(strtolower($saving['name'])) . '</td>';
                echo '<td>' . $saving['amount'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <a class="csv-link" href="<?php echo $csv_url; ?>">Download <?php echo $title; ?> Report</a>
</div>