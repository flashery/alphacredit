<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <header><h2>Members Investments</h2></header>
    <table>
        <thead>
            <tr>
                <?php
                foreach ($investments as $investment) {
                    foreach ($investment as $key=>$value) {
                        echo '<th>'.ucwords(str_replace('_', ' ', $key)).'</th>';
                    }
                    break;
                }
                ?>
                <!--<th>Username</th><th>Credit Limit</th><th>Creation Date</th><th>Last Closing Date</th><th>Status</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($investments as $investment) {
                echo '<tr>';
                echo '<td>' . ucwords(strtolower($investment['name'])) . '</td>';
                echo '<td>' . $investment['amount'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>