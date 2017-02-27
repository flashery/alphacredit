<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <header><h2>Account Details</h2></header>
    <div id="account-action">
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
    <table id="accounts-info-tbl">
        <?php
        foreach ($accounts as $key => $value) {
            echo '<tr>';
            echo '<td>' . $key . '</td>';
            echo '<td id="' . strtolower(str_replace(' ', '-', $key)) . '">' . $value . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>
<div class="one-column">
    <header><h2>Account Reports</h2></header>
    <div class="admin-form">
        <?php
        echo $this->cf_creator->create_form_header('admin/accounts/details/' . $id, 'Select Date');
        echo $this->cf_creator->create_date_input('from', 'From', 'S', set_value('from'));
        echo $this->cf_creator->create_date_input('to', 'To', 'S', set_value('to'));
        echo form_submit('submit', 'Generate');
        echo $this->cf_creator->create_form_footer();
        ?>
    </div>
    <div class="admin-table">
        <header>
            <h3>Withdrawals</h3>
        </header>
        <?php if ($withdrawals != NULL) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($withdrawals as $withdrawal) {
                            foreach ($withdrawal as $key => $value) {
                                if ($key !== 'id') {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                            }
                            echo '<th>Generate Receipt</th>';
                            break;
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($withdrawals as $withdrawal) {
                        echo '<tr>';
                        foreach ($withdrawal as $key => $value) {
                            if ($key !== 'id') {
                                if ($key === 'Account Name') {
                                    echo '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                                } else {
                                    echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                                }
                            }
                        }
                        echo '<td class="extra"><button onclick="regenerate_receipt_click(' . $withdrawal['id'] . ')">Receipt</button></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <a class="csv-link" href="<?php echo $withdrawals_csv; ?>">Download Withdrawal Report</a>
            <?php
        } else {
            echo '<p>No withdrawals on this accounts</p>';
        }
        ?>
    </div>
    <hr>
    <div class="admin-table">
        <header>
            <h3>Loans</h3>
        </header>
        <?php if ($loans != NULL) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($loans as $loan) {
                            foreach ($loan as $key => $value) {
                                if ($key !== 'id') {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                            }
                            echo '<th>Generate Receipt</th>';
                            break;
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($loans as $loan) {
                        echo '<tr>';
                        foreach ($loan as $key => $value) {
                            if ($key !== 'id') {
                                if ($key === 'Account Name') {
                                    echo '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                                } else {
                                    echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                                }
                            }
                        }
                        if (is_numeric($loan['id'])) {
                            echo '<td class="extra"><button onclick="regenerate_receipt_click(' . $loan['id'] . ')">Receipt</button></td>';
                            $i++;
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            echo '<p>No Loans on this accounts</p>';
        }
        ?>
        <a class="csv-link" href="<?php echo $loans_csv; ?>">Download Loans Report</a>
    </div>
    <hr>
    <div class="admin-table">
        <header>
            <h3>Repaid Loans</h3>
        </header>
        <?php if ($loans != NULL) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($repaid_loans as $repaid_loan) {
                            foreach ($repaid_loan as $key => $value) {
                                if ($key !== 'id') {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                            }
                            echo '<th>Generate Receipt</th>';
                            break;
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($repaid_loans as $repaid_loan) {
                        echo '<tr>';
                        foreach ($repaid_loan as $key => $value) {
                            if ($key !== 'id') {
                                if ($key === 'Account Name') {
                                    echo '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                                } else {
                                    echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                                }
                            }
                        }
                        if (is_numeric($repaid_loan['id'])) {
                            echo '<td class="extra"><button onclick="regenerate_receipt_click(' . $repaid_loan['id'] . ')">Receipt</button></td>';
                            $i++;
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            echo '<p>No Repaid Loans on this accounts</p>';
        }
        ?>
        <a class="csv-link" href="<?php echo $repaid_loans_csv; ?>">Download Loans Report</a>
    </div>
    <hr>
    <div class="admin-table">
        <header>
            <h3>Savings</h3>
        </header>
        <?php if ($savings != NULL) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($savings as $saving) {
                            foreach ($saving as $key => $value) {
                                if ($key !== 'id') {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                            }
                            echo '<th>Generate Receipt</th>';
                            break;
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($savings as $saving) {
                        echo '<tr>';
                        foreach ($saving as $key => $value) {
                            if ($key !== 'id') {
                                if ($key === 'Account Name') {
                                    echo '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                                } else {
                                    echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                                }
                            }
                        }
                        echo '<td class="extra"><button onclick="regenerate_receipt_click(' . $saving['id'] . ')">Receipt</button></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <a class="csv-link" href="<?php echo $savings_csv; ?>">Download Savings Report</a>
            <?php
        } else {
            echo '<p>No Savings on this accounts</p>';
        }
        ?>
    </div>
    <hr>
    <div class="admin-table">
        <header>
            <h3>Deposits</h3>
        </header>
        <?php if ($deposits != NULL) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($deposits as $deposit) {
                            foreach ($deposit as $key => $value) {
                                if ($key !== 'id') {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                            }
                            echo '<th>Generate Receipt</th>';
                            break;
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($deposits as $deposit) {
                        echo '<tr>';
                        foreach ($deposit as $key => $value) {
                            if ($key !== 'id') {
                                if ($key === 'Account Name') {
                                    echo '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                                } else {
                                    echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                                }
                            }
                        }
                        echo '<td class="extra"><button onclick="regenerate_receipt_click(' . $deposit['id'] . ')">Receipt</button></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <a class="csv-link" href="<?php echo $deposits_csv; ?>">Download Deposits Report</a>
            <?php
        } else {
            echo '<p>No Deposits on this accounts</p>';
        }
        ?>
    </div>
    <hr>
    <div class="admin-table">
        <header>
            <h3>Investments</h3>
        </header>
        <?php if ($investments != NULL) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($investments as $investment) {
                            foreach ($investment as $key => $value) {
                                if ($key !== 'id') {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                            }
                            echo '<th>Generate Receipt</th>';
                            break;
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($investments as $investment) {
                        echo '<tr>';
                        foreach ($investment as $key => $value) {
                            if ($key !== 'id') {
                                if ($key === 'Account Name') {
                                    echo '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                                } else {
                                    echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                                }
                            }
                        }
                        echo '<td class="extra"><button onclick="regenerate_receipt_click(' . $investment['id'] . ')">Receipt</button></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <a class="csv-link" href="<?php echo $investments_csv; ?>">Download Investments Report</a>
            <?php
        } else {
            echo '<p>No Investments on this accounts</p>';
        }
        ?>
    </div>
    <hr>
    <div class="admin-table">
        <header>
            <h3>Charges</h3>
        </header>
        <?php if ($charges != NULL) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($charges as $charge) {
                            foreach ($charge as $key => $value) {
                                if ($key !== 'id') {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                            }
                            echo '<th>Generate Receipt</th>';
                            break;
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($charges as $charge) {
                        echo '<tr>';
                        foreach ($charge as $key => $value) {
                            if ($key !== 'id') {
                                if ($key === 'Account Name') {
                                    echo '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                                } else {
                                    echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                                }
                            }
                        }
                        echo '<td class="extra"><button onclick="regenerate_receipt_click(' . $charge['id'] . ')">Receipt</button></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <a class="csv-link" href="<?php echo $charges_csv; ?>">Download Charges Report</a>
            <?php
        } else {
            echo '<p>No Charges on this accounts</p>';
        }
        ?>
    </div>
    <hr>
    <div class="admin-table">
        <header>
            <h3>Cumulative Reports</h3>
        </header>
        <?php if ($cumulative_reports != NULL) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($cumulative_reports as $cumulative_report) {
                            foreach ($cumulative_report as $key => $value) {
                                if ($key !== 'id') {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                            }
                            break;
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cumulative_reports as $cumulative_report) {
                        echo '<tr>';
                        foreach ($cumulative_report as $key => $value) {
                            if ($key !== 'id') {
                                echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                            }
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <a class="csv-link" href="<?php echo $cumulative_reports_csv; ?>">Download Cumulative Reports Report</a>
            <?php
        } else {
            echo '<p>No Cumulative Reports on this accounts</p>';
        }
        ?>
    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
        </div>
    </div>
</div>