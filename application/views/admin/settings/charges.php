<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <div class="admin-form">
        <header>
            <h2>Edit Charges</h2>
        </header>
        <?php echo form_open('login/validate_login'); ?>
        <?php echo validation_errors('<p class="error">'); ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($transaction_fees as $transaction_fee) {
                    echo '<tr>';
                    echo '<td>' . ucwords($transaction_fee['name']) . '</td>';
                    if ($transaction_fee['name'] === 'Loan Repayment' || $transaction_fee['name'] === 'Loan Repayment 2') {
                        echo '<td><input class="S" type = "text" name="' . $transaction_fee['name'] . '" value="' . number_format($transaction_fee['amount'], 2) . '">%</td>';
                    } else {
                        echo '<td><input class="S" type = "text" name="' . $transaction_fee['name'] . '" value="' . number_format($transaction_fee['amount'], 2) . '"></td>';
                    }
                    echo '<td>' . ucwords($transaction_fee['description']) . '</td>';
                    echo '<tr>';
                }
                ?>
            </tbody>
        </table>
        <?php echo form_submit('submit', 'Update'); ?>
        </form>
    </div>
</form>
</div>