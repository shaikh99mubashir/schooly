<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
if (!empty($student_list)) {
?>

    <div class="table-responsive">
        <div class="download_label"><?php echo $this->lang->line('student_list'); ?></div>
        <table class="table table-striped table-bordered table-hover ViewData" id="ViewData"  data-export-title="<?php echo $this->lang->line('student_list');?>">
            <thead>
                <tr>
                    <th  class="dt-body-left dt-head-left"><?php echo $this->lang->line('admission_no'); ?></th>
                    <th><?php echo $this->lang->line('student_name'); ?></th>
                    <th><?php echo $this->lang->line('class'); ?></th>
                    <?php if ($sch_setting->father_name) { ?>
                        <th><?php echo $this->lang->line('father_name'); ?></th>
                    <?php } ?>
                    <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                    <th><?php echo $this->lang->line('gender'); ?></th>
                    <?php if ($sch_setting->category) {
                    ?>
                        <?php if ($sch_setting->category) { ?>
                            <th><?php echo $this->lang->line('category'); ?></th>
                        <?php }
                    }
                    if ($sch_setting->mobile_no) {
                        ?>
                        <th><?php echo $this->lang->line('mobile_number'); ?></th>
                        <?php
                    }

                    if (!empty($fields)) {

                        foreach ($fields as $fields_key => $fields_value) {
                        ?>
                            <th><?php echo $fields_value->name; ?></th>
                    <?php
                        }
                    }

                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($student_list)) {
                ?>

                    <?php
                } else {
                    $count = 1;

                    foreach ($student_list as $student_key => $student) {
                    ?>
                        <tr>

                            <td  class="dt-body-left dt-head-left"><?php echo $student['admission_no']; ?></td>

                            <td>

                                <a target="_blank" href="<?php echo site_url('student/view/' . $student['id']); ?>"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                </a>
                            </td>
                            <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                            <?php if ($sch_setting->father_name) { ?>
                                <td><?php echo $student['father_name']; ?></td>
                            <?php } ?>
                            <td><?php
                                if ($student["dob"] != null && $student["dob"] != '0000-00-00') {
                                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                }
                                ?></td>
                            <td><?php if($student['gender']){ echo $this->lang->line(strtolower($student['gender'])); } ?></td>
                            <?php if ($sch_setting->category) { ?>
                                <td><?php echo $student['category']; ?></td>
                            <?php }
                            if ($sch_setting->mobile_no) { ?>
                                <td><?php echo $student['mobileno']; ?></td>
                                <?php }
                            if (!empty($fields)) {

                                foreach ($fields as $fields_key => $fields_value) {
                                    $display_field = $student[$fields_value->name];
                                    if ($fields_value->type == "link") {
                                        $display_field = "<a href=" . $student[$fields_value->name] . " target='_blank'>" . $student[$fields_value->name] . "</a>";
                                    }
                                ?>
                                    <td>
                                        <?php echo $display_field; ?>

                                    </td>
                            <?php
                                }
                            }
                            ?>


                        </tr>
                <?php
                        $count++;
                    }
                }
                ?>
            </tbody>

        </table>
    </div>
<?php

} else {
?>
    <div class="alert alert-info">
        <?php echo $this->lang->line('no_record_found'); ?>
    </div>
<?php
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        displayDataTable('ViewData');
    });
</script>