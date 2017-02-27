<header>
    <h1><?php echo ucwords(strtolower($title)); ?></h1>
</header>
<div class="one-column">
    <header><h2>Generate Reports</h2></header>
    <div id="generate-report">
        <div class="admin-form">
            <?php
            echo $this->cf_creator->create_form_header('admin/reports', 'Select Options');
            echo $this->cf_creator->create_select_input('type', 'Select Type', '', $options);
            echo $this->cf_creator->create_date_input('from', 'From', 'S', set_value('from'));
            echo $this->cf_creator->create_date_input('to', 'To', 'S', set_value('to'));
            echo form_submit('submit', 'Generate');
            echo $this->cf_creator->create_form_footer();
            ?>
        </div>
        <hr>
        <?php if (isset($queries)) { ?>
            <?php if ($report_type === "Loans" || $title == 'sub_reports') { ?>
                <div class="sub-options">
                    <div class="admin-form">
                        <?php
                        $options = array(
                            'Issued Loans' => 'Issued Loans',
                            'Repaid Loans' => 'Repaid Loans',
                            'Expired Loans' => 'Expired Loans',
                            'Due Loans' => 'Due Loans');
                        $js = array(
                            'class' => 'D',
                            'onChange' => 'some_function();'
                        );
                        echo $this->cf_creator->create_form_header('admin/sub_reports', 'Sub Options');
                        echo '<div class="input-label"><label for="from">Select Type:</label></div>';
                        echo '<div class="form-input">';
                        echo form_dropdown('type2', $options, '', $js);
                        echo '</div>';
                        echo form_submit('submit', 'Display');
                        echo $this->cf_creator->create_form_footer();
                        ?>
                    </div>
                </div>
            <?php } ?>
            <header><h2>Members <?php echo $report_type; ?> </h2></header>
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <?php
                            foreach ($queries as $query) {
                                foreach ($query as $key => $value) {
                                    echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                                }
                                break;
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($queries as $query) {
                            echo '<tr>';
                            foreach ($query as $key => $value) {
                                if (is_numeric($value)) {
                                    echo '<td>' . number_format($value, 2) . '</td>';
                                } else {
                                    echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                                }
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <a class="csv-link" href="<?php echo $csv_url; ?>">Download <?php echo $report_type; ?> Report</a>
            <?php
        } else {
            echo '<p>Reports will be generated here.</p>';
        }
        ?>
    </div>
</div>