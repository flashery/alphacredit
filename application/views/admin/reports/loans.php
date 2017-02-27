<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <header><h2>Members Loans</h2></header>
    <table>
        <thead>
            <tr>
                <?php
                foreach ($loans as $loan) {
                    foreach ($loan as $key => $value) {
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
            foreach ($loans as $loan) {
                echo '<tr>';
                echo '<td>' . ucwords(strtolower($loan['name'])) . '</td>';
                echo '<td>' . $loan['date'] . '</td>';
                echo '<td>' . $loan['total_amount'] . '</td>';
                echo '<td>' . $loan['repaid_amount'] . '</td>';
                echo '<td>' . $loan['repayment_date'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <a class="csv-link" href="<?php echo $csv_url; ?>">Download <?php echo $title; ?> Report</a>
</div>