<style type="text/css">
    .checkbox-inline+.checkbox-inline, .radio-inline+.radio-inline {
    margin-left: 8px;}
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$language        = $this->customlib->getLanguage();
$language_name   = $language["short_code"];
?>
<div class="content-wrapper">
    <div class="row">
        <div>
            <a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a>
            <aside class="studentsidebar">
                <div class="stutop" id="">
                    <!-- Create the tabs -->
                    <div class="studentsidetopfixed">
                        <p class="classtap"><?php echo $student["class"]; ?><a href="#" data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i></a></p>
                        <ul class="nav nav-justified studenttaps">
                            <?php foreach ($class_section as $skey => $svalue) { ?>
                                <li <?php
if ($student["section_id"] == $svalue["section_id"]) {
        echo "class='active'";
    }
    ?> ><a href="#section<?php echo $svalue["section_id"] ?>" data-toggle="tab"><?php print_r($svalue["section"]);?></a></li>
                                <?php }?>
                        </ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php foreach ($class_section as $skey => $snvalue) {
    ?>
                            <div class="tab-pane <?php
if ($student["section_id"] == $snvalue["section_id"]) {
        echo "active";
    }
    ?>" id="section<?php echo $snvalue["section_id"]; ?>">
                                 <?php
foreach ($studentlistbysection as $stkey => $stvalue) {
        if ($stvalue['section_id'] == $snvalue["section_id"]) { ?>
                                        <div class="studentname">
                                            <a class="" href="<?php echo base_url() . "studentfee/addfee/" . $stvalue["student_session_id"] ?>">

                                            

                                                <div class="icon"><img src="<?php 
                                            if (!empty($stvalue["image"])) {
                                                echo $this->media_storage->getImageURL($stvalue["image"]);
                                            } else {
                                                if ($stvalue['gender'] == 'Female') {
                                                    echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
                                                } elseif ($stvalue['gender'] == 'Male') {
                                                    echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
                                                }
                                            } 
                                            ?>" alt="User Image"></div>
                                                <div class="student-tittle"><?php echo $stvalue["firstname"] . " " . $stvalue["lastname"]; ?></div></a>
                                        </div>
                                        <?php
}
    }
    ?>
                            </div>
                        <?php }?>
                        <div class="tab-pane" id="sectionB">
                            <h3 class="control-sidebar-heading">Recent Activity 2</h3>
                        </div>
                        <div class="tab-pane" id="sectionC">
                            <h3 class="control-sidebar-heading">Recent Activity 3</h3>
                        </div>
                        <div class="tab-pane" id="sectionD">
                            <h3 class="control-sidebar-heading">Recent Activity 3</h3>
                        </div>
                    </div>
                </div>
            </aside>
        </div></div>
    <!-- /.control-sidebar -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo $this->lang->line('student_fees'); ?></h3>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>studentfee" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div><!--./box-header-->
                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="sfborder-top-border">
                                    <div class="col-md-2">
                                        <img width="115" height="115" class="mt5 mb10 sfborder-img-shadow img-responsive img-rounded" src="<?php
if (!empty($student["image"])) {
    echo $this->media_storage->getImageURL($student["image"]);
} else {
    if ($student['gender'] == 'Female') {
        echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
    } elseif ($student['gender'] == 'Male') {
        echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
    }
}
?>
                " alt="No Image">
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <table class="table table-striped mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td class="bozero"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                                        <th class="bozero"><?php echo $this->lang->line('class_section'); ?></th>
                                                        <td class="bozero"><?php echo $student['class'] . " (" . $student['section'] . ")" ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                                        <td><?php echo $student['mobileno']; ?></td>
                                                        <th><?php echo $this->lang->line('roll_number'); ?></th>
                                                        <td> <?php echo $student['roll_no']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('category'); ?></th>
                                                        <td>
                                                            <?php
foreach ($categorylist as $value) {
    if ($student['category_id'] == $value['id']) {
        echo $value['category'];
    }
}
?>
                                                        </td>
                                                        <?php if ($sch_setting->rte) {?>
                                                            <th><?php echo $this->lang->line('rte'); ?></th>
                                                            <td><b class="text-danger"> <?php echo $student['rte']; ?> </b>
                                                            </td>
                                                        <?php }?>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div></div>
                            <div class="col-md-12">
                                <div class="settinghr"></div>
                            </div>
                        </div>
                        <div class="row no-print mb10">
                            <div class="col-md-12 mDMb10">
                                <div class="float-rtl-right float-left">
                                <button type="button" class="btn btn-sm btn-primary printSelected" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-print"></i> <?php echo $this->lang->line('print_selected'); ?></button>
                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) { ?>
                                <button type="button" class="btn btn-sm btn-warning collectSelected" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('collect_selected'); ?></button><?php } ?>
                                <?php if ($student_processing_fee) {?>
                                    <a  href="javascript:void(0)" class="btn btn-sm btn-primary getProcessingfees" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('processing_fees') ?></a>
                                <?php } ?>
								</div>
                                <span class="pull-right pt5"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></span>
                            </div>
                        </div>
                        <?php
$student_admission_no = '';
if ($student['admission_no'] != '') {
    $student_admission_no = ' (' . $student['admission_no'] . ')';
}
?>
	 <!-- //============================= -->


                       <?php 
                       foreach ($session_fees as $session_fee_key => $student_due_fee) {
                       ?>
                     		 
                        <div class="table-responsive overflow-y-hidden">
                            <div class="download_label"><?php echo $this->lang->line('student_fees') . ": " . $student['firstname'] . " " . $student['lastname'] . $student_admission_no; ?> </div>

                            <h5 class="text text-center bolds font13">Session : <?php echo $student_due_fee['session'];?></h5>
                            <table class="table table-striped table-bordered table-hover example table-fixed-header" data-export-title="<?php echo $this->lang->line('student_fees') . ": " . $student['firstname'] . " " . $student['lastname'] . $student_admission_no; ?>">
                                <thead class="header">
                                    <tr>
                                        <th style="width: 10px"><?php 
                                            if($student_due_fee['session_id'] == $current_session_id['session_id']){ ?>
                                                 <input style="" type="checkbox" class="select_all_session" data-session-id="21" id="select_all_<?php echo $student_due_fee['session_id'];?>"/>
                                            <?php } ?>
                                            </th>
                                        </th>
                                        <th align="left"><?php echo $this->lang->line('fees'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('due_date'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text text-right"><?php echo $this->lang->line('amount') ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-left"><?php echo $this->lang->line('payment_id'); ?></th>
                                        <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                        <th  class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text text-right" ><?php echo $this->lang->line('discount'); ?> <span><?php echo "(".$currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

$total_amount           = 0;
$total_deposite_amount  = 0;
$total_fine_amount      = 0;
$total_fees_fine_amount = 0;
$total_discount_amount = 0;
$total_balance_amount  = 0;
$alot_fee_discount     = 0;

foreach ($student_due_fee['fees'] as $key => $fee) {

    foreach ($fee->fees as $fee_key => $fee_value) {
        $fee_paid         = 0;
        $fee_discount     = 0;
        $fee_fine         = 0;
        $fees_fine_amount = 0;
        $feetype_balance  = 0;
        if (!empty($fee_value->amount_detail)) {
            $fee_deposits = json_decode(($fee_value->amount_detail));

            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
            }
        }
        if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
          // get cumulative fine amount as delay days 
            if($fee_value->fine_type=='cumulative'){
                $date1=date_create("$fee_value->due_date");
                $date2=date_create(date('Y-m-d'));
                $diff=date_diff($date1,$date2);
                $due_days= $diff->format("%a");;
                
                if($this->customlib->get_cumulative_fine_amount($fee_value->fee_groups_feetype_id,$due_days)){
                    $due_fine_amount=$this->customlib->get_cumulative_fine_amount($fee_value->fee_groups_feetype_id,$due_days);
                }else{
                    $due_fine_amount=0;
                }
                $fees_fine_amount       = $due_fine_amount;
                $total_fees_fine_amount = $total_fees_fine_amount + $due_fine_amount;

            }else if($fee_value->fine_type=='fix' || $fee_value->fine_type=='percentage'){
                $fees_fine_amount       = $fee_value->fine_amount;
                $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
            }
            // get cumulative fine amount as delay days
        }

        $total_amount += $fee_value->amount;
        $total_discount_amount += $fee_discount;
        $total_deposite_amount += $fee_paid;
        $total_fine_amount += $fee_fine;
        $feetype_balance = $fee_value->amount - ($fee_paid + $fee_discount);
		if($feetype_balance<0){
		$feetype_balance=0;}		  
        $total_balance_amount += $feetype_balance;
        ?>
        <?php
        if (!empty($fee_value->due_date) && $feetype_balance > 0 && strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) { ?>
            <tr class="danger font12"><?php
        } else {    ?>
            <tr class="dark-gray">
        <?php }   ?>
            <td>
                <?php if($student_due_fee['session_id'] == $current_session_id['session_id']){  ?>									   
                <input class="checkbox fee_checkbox_session" type="checkbox" name="fee_checkbox" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>" data-fee_category="fees"  data-trans_fee_id="0">
                <?php } ?>								  
            </td>
            <td>
                <?php  if ($fee_value->is_system) {
                        echo $this->lang->line($fee_value->type)." (".$this->lang->line($fee_value->code) . ")";
                    } else {
                        echo $fee_value->type." (".$fee_value->code . ")";
                    }   ?>
            </td>
            <td align="left" class="text text-left"><?php
                if ($fee_value->due_date == "0000-00-00") {
                } else {
                if ($fee_value->due_date) {
                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                }
                }
                ?>
            </td>
            <td align="left" class="">
                <?php
                if ($feetype_balance == 0) {
                ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                } else if (!empty($fee_value->amount_detail)) {
                ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                } else {
                ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                }
                ?>
            </td>
            <td class="text text-right">
                <?php echo amountFormat($fee_value->amount);
                if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {  ?>
                <span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . amountFormat($fees_fine_amount); ?></span>
                <div class="fee_detail_popover" style="display: none">
                <?php  if ($fees_fine_amount != "") {   ?>
                    <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
                <?php  }   ?>
                </div>
                <?php } ?>
            </td>
            <td class="text text-left"></td>
            <td class="text text-left"></td>
            <td class="text text-left"></td>
            <td class="text text-right"><?php echo amountFormat($fee_discount); ?> </td>
            <td class="text text-right"><?php echo amountFormat($fee_fine);  ?></td>
            <td class="text text-right"><?php echo amountFormat($fee_paid); ?></td>
            <td class="text text-right"><?php 
                $display_none = "ss-none";
                if ($feetype_balance > 0) {
                    $display_none = "";
                    echo amountFormat($feetype_balance);
                } ?>
            </td>
            <td width="100" class="white-space-nowrap">
                <div class="btn-group">
                <div class="pull-right"><?php
                if($student_due_fee['session_id'] == $current_session_id['session_id']){
				    if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) { ?>
                        <button type="button" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-student_fees_master_id="<?php echo $fee->id; ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id; ?>" data-group="<?php echo ($fee_value->is_system) ? $this->lang->line($fee_value->name). " (" . $this->lang->line($fee_value->type) . ")" : $fee_value->name. " (" . $fee_value->type . ")"; ?>"  data-type="<?php echo ($fee_value->is_system) ? $this->lang->line($fee_value->type) : $fee_value->code; ?>"  class="btn btn-xs btn-default myCollectFeeBtn <?php echo $display_none; ?>"  title="<?php echo $this->lang->line('add_fees'); ?>" data-toggle="modal" data-target="#myFeesModal" data-fee-category="fees"  data-trans_fee_id="0"><i class="fa fa-plus"></i></button>
                    <?php } ?>
                    <button  class="btn btn-xs btn-default printInv" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>" data-fee-category="fees"  data-trans_fee_id="0" title="<?php echo $this->lang->line('print'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i>"><i class="fa fa-print"></i></button>
                <?php } ?>																		 
                </div>
                </div>
            </td>
        </tr>

        <?php 
         if (!empty($fee_value->amount_detail)) {
            $fee_deposits = json_decode(($fee_value->amount_detail));
            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {  ?>
                                                    <tr class="white-td">
                                                        <td align="left"></td>
                                                        <td align="left"></td>
                                                        <td align="left"></td>
                                                        <td align="left"></td>
                                                        <td class="text-right"><img src="<?php echo $this->media_storage->getImageURL('backend/images/table-arrow.png'); ?>" alt="" /></td>
                                                        <td class="text text-left"><a href="#" data-toggle="popover" class="detail_popover" > <?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                            <div class="fee_detail_popover" style="display: none">
                                                                <?php if ($fee_deposits_value->description == "") {   ?>
                                                                    <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                <?php } else {  ?>
                                                                    <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                <?php }   ?>
                                                            </div>
                                                        </td>
                                                        <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                        <td class="text text-left">
                                                            <?php if ($fee_deposits_value->date) {echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date));}?>
                                                        </td>
                                                        <td class="text text-right">
                                                            <a href="#" class="text text-right display-inline" title="<?php echo $this->lang->line('discount'); ?>" data-fee-deposite-id="<?php echo $fee_value->student_fees_deposite_id;?>" data-toggle="modal" data-target="#myfeeDiscountModal"><?php echo amountFormat($fee_deposits_value->amount_discount); ?></a>
                                                        </td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_fine); ?></td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount); ?></td>
                                                        <td></td>
                                                        <td class="text text-right white-space-nowrap">
                                                            <div class="btn-group">
                                                               <div class="pull-right">
                                                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) {?>
                                                                    <button class="btn btn-default btn-xs" data-invoiceno="<?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>" data-main_invoice="<?php echo $fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>" data-toggle="modal" data-target="#confirm-delete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                        <i class="fa fa-undo"> </i>
                                                                    </button>
                                                                <?php }?>
                                                                <button  class="btn btn-xs btn-default printDoc" data-main_invoice="<?php echo $fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>"  title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i></button>
                                                            </div>
                                                          </div>
                                                        </td>
                                                    </tr>
            <?php } 
        }  ?>

    <?php
}
}
?>

<?php
if (!empty($transport_fees)) {
    foreach ($transport_fees as $transport_fee_key => $transport_fee_value) {
        $fee_paid         = 0;
        $fee_discount     = 0;
        $fee_fine         = 0;
        $fees_fine_amount = 0;
        $feetype_balance  = 0;

        if (!empty($transport_fee_value->amount_detail)) {
            $fee_deposits = json_decode(($transport_fee_value->amount_detail));
            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
            }
        }

        $feetype_balance = $transport_fee_value->fees - ($fee_paid + $fee_discount);
        if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
            $fees_fine_amount       = is_null($transport_fee_value->fine_percentage) ? $transport_fee_value->fine_amount : percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
            $total_fees_fine_amount = $total_fees_fine_amount + $fees_fine_amount;
        }

        $total_amount += $transport_fee_value->fees;
        $total_discount_amount += $fee_discount;
        $total_deposite_amount += $fee_paid;
        $total_fine_amount += $fee_fine;
        $total_balance_amount += $feetype_balance;

        if (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d'))) {   ?>
            <tr class="danger font12">
        <?php } else {  ?>
                <tr class="dark-gray">
        <?php }  ?>
                <td>
                <?php 
                    if($student_due_fee['session_id'] == $current_session_id['session_id']){ ?>
                        <input class="checkbox fee_checkbox_session" type="checkbox" name="fee_checkbox" data-fee_master_id="0" data-fee_session_group_id="0" data-fee_groups_feetype_id="0" data-fee_category="transport"  data-trans_fee_id="<?php echo $transport_fee_value->id; ?>">
                <?php } ?>
                </td>
                <td align="left" class="text-rtl-right"><?php echo $this->lang->line('transport_fees')." (".$this->lang->line(strtolower($transport_fee_value->month)).")"; ?></td>
				
                <td align="left" class="text text-left">
                    <?php echo $this->customlib->dateformat($transport_fee_value->due_date); ?>
                </td>
                <td align="left" class="text text-left width85">
                    <?php
                    if ($feetype_balance == 0) {
                                ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                    } else if (!empty($transport_fee_value->amount_detail)) {
                                ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                    } else {
                                ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                    } ?>
                </td>
                <td class="text text-right"><?php
                    echo amountFormat($transport_fee_value->fees);
                    if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
                        $tr_fine_amount = $transport_fee_value->fine_amount;
                        if ($transport_fee_value->fine_type != "" && $transport_fee_value->fine_type == "percentage") {
                            $tr_fine_amount = percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
                        }   ?>

                    <span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . amountFormat($tr_fine_amount); ?></span>
                    <div class="fee_detail_popover" style="display: none">
                        <?php
                        if ($tr_fine_amount != "") { ?>
                            <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
                        <?php }  ?>
                    </div>
                    <?php }   ?> 
                </td>
                <td class="text text-left"></td>
                <td class="text text-left"></td>
                <td class="text text-left"></td>
                <td class="text text-right"><?php echo amountFormat($fee_discount); ?></td>
                <td class="text text-right"><?php echo amountFormat($fee_fine);  ?></td>
                <td class="text text-right"><?php echo amountFormat($fee_paid); ?></td>
                <td class="text text-right"><?php 
                    $display_none = "ss-none";
                        if ($feetype_balance > 0) {
                            $display_none = "";
                            echo amountFormat($feetype_balance);
                        }  ?>
                </td>
                <td class="white-space-nowrap"><?php
                    if($student_due_fee['session_id'] == $current_session_id['session_id']){
				    if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) { ?>
                        <button type="button" data-student_session_id="<?php echo $transport_fee_value->student_session_id; ?>" data-student_fees_master_id="0" data-fee_groups_feetype_id="0"  data-group="<?php echo $this->lang->line('transport_fees'); ?>"  data-type="<?php echo $transport_fee_value->month; ?>" class="btn btn-xs btn-default myCollectFeeBtn <?php echo $display_none; ?>"  title="<?php echo $this->lang->line('add_fees'); ?>" data-toggle="modal" data-target="#myFeesModal"  data-fee_session_group_id="0"   data-fee-category="transport" data-trans_fee_id="<?php echo $transport_fee_value->id; ?>"><i class="fa fa-plus"></i></button><?php } ?>

                        <button  class="btn btn-xs btn-default printInv"  data-student_session_id="<?php echo $transport_fee_value->student_session_id; ?>" data-fee_master_id="0" data-fee_session_group_id="0" data-fee_groups_feetype_id="0" data-fee-category="transport"  data-trans_fee_id="<?php echo $transport_fee_value->id; ?>" title="<?php echo $this->lang->line('print'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i>"><i class="fa fa-print"></i></button>
                    <?php } ?>
                </td>
            </tr>

<?php
if (!empty($transport_fee_value->amount_detail)) {
            $fee_deposits = json_decode(($transport_fee_value->amount_detail));
            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) { ?>
                                                    <tr class="white-td">
                                                        <td align="left"></td>
                                                        <td align="left"></td>
                                                        <td align="left"></td>
                                                        <td align="left"></td>
                                                        <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                        <td class="text text-left">
                                                            <a href="#" data-toggle="popover" class="detail_popover" > <?php echo $transport_fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                            <div class="fee_detail_popover" style="display: none">
                                                                <?php if ($fee_deposits_value->description == "") {   ?>
                                                                    <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                    <?php } else {  ?>
                                                                    <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                    <?php }  ?>
                                                            </div>
                                                        </td>
                                                        <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                        <td class="text text-left">
                                                            <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                        </td>
                                                        <td class="text text-right"><a href="#" class="text text-right display-inline" title="<?php echo $this->lang->line('discount'); ?>" data-fee-deposite-id="<?php echo $transport_fee_value->student_fees_deposite_id;?>" data-toggle="modal" data-target="#myfeeDiscountModal"><?php echo amountFormat($fee_deposits_value->amount_discount); ?></a></td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_fine); ?></td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount); ?></td>
                                                        <td></td>
                                                        <td class="text text-right">
                                                            <?php 
                                                             if($student_due_fee['session_id'] == $current_session_id['session_id']){ ?>
                                                            <div class="btn-group ">
                                                               <div class="pull-right">
                                                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) {?>
                                                                    <button class="btn btn-default btn-xs" data-invoiceno="<?php echo $transport_fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>" data-main_invoice="<?php echo $transport_fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>" data-toggle="modal" data-target="#confirm-delete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                        <i class="fa fa-undo"> </i>
                                                                    </button>
                                                                <?php }?>
                                                                    <button  class="btn btn-xs btn-default printDoc" data-fee-category="transport" data-main_invoice="<?php echo $transport_fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>"  title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i></button>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
           <?php } ?>
<?php }   ?>

<?php
}
}

?>                                   

                                    <tr class="box box-solid total-bg">                                       
                                        <td align="left" ></td>
                                        <td align="left" ></td>
                                        <td align="left" ></td>
                                        <td align="left" class="text text-left" ><?php echo $this->lang->line('grand_total'); ?></td>
                                        <td class="text text-right">

<?php
echo $currency_symbol . (amountFormat($total_amount));
?>

<span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . (amountFormat($total_fees_fine_amount)); ?></span>

<div class="fee_detail_popover" style="display: none">
    
        <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
 
</div>
                                            </td>
                                        <td class="text text-left"></td>
                                        <td class="text text-left"></td>
                                        <td class="text text-left"></td>
                                        <td class="text text-right"><?php
echo $currency_symbol . amountFormat(($total_discount_amount + $alot_fee_discount));
?></td>
                                        <td class="text text-right"><?php
echo $currency_symbol . amountFormat(($total_fine_amount));
?></td>
                                        <td class="text text-right"><?php
echo $currency_symbol . amountFormat(($total_deposite_amount));
?></td>
                                        <td class="text text-right"><?php
echo $currency_symbol . amountFormat(($total_balance_amount - $alot_fee_discount));
?></td>  <td class="text text-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
  
                       <?php 
                       }
                       
                       ?>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section>
</div>

<div class="modal fade" id="myfeeDiscountModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center fees_discount_title"></h4>
            </div>
            <div class="modal-body minheight260"> 
                <div class="modal_loader_div" style="display: none;"></div>
                <div class="modal-body-inner">
                </div>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myFeesModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center fees_title"></h4>
            </div>
            <div class="modal-body minheight260"> 

                <div class="modal_loader_div" style="display: none;"></div>

                <div class="modal-body-inner">
                    
                </div>

                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myDisApplyModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center discount_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal">
                    <div class="box-body">
                        <input  type="hidden" class="form-control" id="student_fees_discount_id"  value=""/>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment_id'); ?> <small class="req">*</small></label>
                            <div class="col-sm-9">

                                <input type="text" class="form-control" id="discount_payment_id" >

                                <span class="text-danger" id="discount_payment_id_error"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('description'); ?></label>

                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="dis_description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn cfees dis_apply_button" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('apply_discount'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="delmodal modal fade" id="confirm-discountdelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_want_to_revert'); ?> <b class="discount_title"></b> <?php echo $this->lang->line('discount_this_action_is_irreversible'); ?></p>
                <p><?php echo $this->lang->line('do_you_want_to_proceed') ?></p>
                <p class="debug-url"></p>
                <input type="hidden" name="discount_id"  id="discount_id" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-discountdel"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_want_to_delete'); ?> <b class="invoice_no"></b> <?php echo $this->lang->line('invoice_this_action_is_irreversible') ?></p>
                 <p><?php echo $this->lang->line('do_you_want_to_proceed') ?></p>
                <p class="debug-url"></p>
                <input type="hidden" name="main_invoice"  id="main_invoice" value="">
                <input type="hidden" name="sub_invoice" id="sub_invoice"  value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="norecord modal fade" id="confirm-norecord" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p><?php echo $this->lang->line('no_record_found'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div id="listCollectionModal" class="modal fade">
    <div class="modal-dialog">
        <form action="<?php echo site_url('studentfee/addfeegrp'); ?>"   method="POST" id="collect_fee_group">
            <div class="modal-content">
<!-- //================ -->
 <input  type="hidden" class="form-control" id="group_std_id" name="student_session_id" value="<?php echo $student["student_session_id"]; ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_parent_app_key" name="parent_app_key" value="<?php echo $student['parent_app_key'] ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_guardian_phone" name="guardian_phone" value="<?php echo $student['guardian_phone'] ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_guardian_email" name="guardian_email" value="<?php echo $student['guardian_email'] ?>" readonly="readonly"/>
<!-- //================ -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('collect_fees'); ?></h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </form>
    </div>
</div>

<div id="processing_fess_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('processing_fees'); ?></h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#listCollectionModal,#processing_fess_modal,#confirm-norecord,#myFeesModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
     
    $(document).ready(function () {
            $(document).on('click', '.printDoc', function () {
            var main_invoice = $(this).data('main_invoice');
            var sub_invoice = $(this).data('sub_invoice');
            var fee_category = $(this).data('fee-category');
            var student_session_id = '<?php echo $student['student_session_id'] ?>';
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByName") ?>',
                type: 'post',
                dataType:"JSON",
                data: {'fee_category': fee_category,'student_session_id': student_session_id, 'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (response) {
                    Popup(response.page);
                }
            });
        });

        $(document).on('click', '.printInv', function () {
            var $this= $(this);
            var fee_master_id = $(this).data('fee_master_id');
            var student_session_id = $(this).data('student_session_id');
            var fee_session_group_id = $(this).data('fee_session_group_id');
            var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
            var trans_fee_id = $(this).data('trans_fee_id');
            var fee_category = $(this).data('feeCategory');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByGroup") ?>',
                type: 'post',
                dataType:"JSON",
                data: {'trans_fee_id':trans_fee_id,'fee_category':fee_category,'fee_groups_feetype_id': fee_groups_feetype_id, 'fee_master_id': fee_master_id, 'fee_session_group_id': fee_session_group_id, 'student_session_id': student_session_id},
                 beforeSend: function() {
        $this.button('loading');
    },

                success: function (response) {

                    Popup(response.page);
                     $this.button('reset');
                },
                  error: function(xhr) { // if error occured
        alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
         $this.button('reset');
    },
    complete: function() {
      $this.button('reset');
    }

            });
        });
    });

      $(document).on('click', '.getProcessingfees', function () {
            var $this = $(this);
                var student_session_id = '<?php echo $student['student_session_id'] ?>';
                $.ajax({
                type: 'POST',
                url: base_url + "studentfee/getProcessingfees/"+student_session_id,

                dataType: "JSON",
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {
                    $("#processing_fess_modal .modal-body").html(data.view);
                    $("#processing_fess_modal").modal('show');
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });

</script>

<script type="text/javascript">
    $(document).on('click', '.save_button', function (e) {
        var $this = $(this);
        var action = $this.data('action');
        $this.button('loading');
        var form = $(this).attr('frm');
        var feetype = $('#feetype_').val();
        var date = $('#date').val();
        var student_session_id = $('#std_id').val();
        var amount = $('#amount').val();
        var amount_discount = $('#amount_discount').val();
        var amount_fine = $('#amount_fine').val();
        var description = $('#description').val();
        var parent_app_key = $('#parent_app_key').val();
        var guardian_phone = $('#guardian_phone').val();
        var guardian_email = $('#guardian_email').val();
        var student_fees_master_id = $('#student_fees_master_id').val();
        var fee_groups_feetype_id = $('#fee_groups_feetype_id').val();
        var transport_fees_id = $('#transport_fees_id').val();
        var fee_category = $('#fee_category').val();
        var payment_mode = $('input[name="payment_mode_fee"]:checked').val();
        var selectedDiscounts = [];
        $('input[name="fee_discount_group[]"]:checked').each(function() {
            selectedDiscounts.push($(this).val());
        });
        var student_fees_discount_id = $('#discount_group').val();
		  var fee_session_group_id = $('#fee_session_group_id').val();	  

        $.ajax({
            url: '<?php echo site_url("studentfee/addstudentfee") ?>',
            type: 'post',
            data: {action: action, student_session_id: student_session_id, date: date, type: feetype, amount: amount, amount_discount: amount_discount, amount_fine: amount_fine, description: description, student_fees_master_id: student_fees_master_id, fee_groups_feetype_id: fee_groups_feetype_id,fee_category:fee_category, transport_fees_id:transport_fees_id, payment_mode: payment_mode, guardian_phone: guardian_phone, guardian_email: guardian_email, student_fees_discount_id: student_fees_discount_id, parent_app_key: parent_app_key,discounts: selectedDiscounts,fee_session_group_id:fee_session_group_id},
            dataType: 'json',
            success: function (response) {
                $this.button('reset');
                if (response.status === "success") {
                    if (action === "collect") {
                        location.reload(true);
                    } else if (action === "print") {
                        Popup(response.print, true);
                    }
                } else if (response.status === "fail") {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>

<script>
    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false)
    {
        var frameDoc=window.open('', 'Print-Window');
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body onload="window.print()">');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            frameDoc.close();      
            if (winload) {
                window.location.reload(true);
            }
        }, 5000);

        return true;
    }
    
    $(document).ready(function () {
        $('.delmodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $('#listCollectionModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('#confirm-delete').on('show.bs.modal', function (e) {
            $('.invoice_no', this).text("");
            $('#main_invoice', this).val("");
            $('#sub_invoice', this).val("");
            $('.invoice_no', this).text($(e.relatedTarget).data('invoiceno'));
            $('#main_invoice', this).val($(e.relatedTarget).data('main_invoice'));
            $('#sub_invoice', this).val($(e.relatedTarget).data('sub_invoice'));
        });

        $('#confirm-discountdelete').on('show.bs.modal', function (e) {
            $('.discount_title', this).text("");
            $('#discount_id', this).val("");
            $('.discount_title', this).text($(e.relatedTarget).data('discounttitle'));
            $('#discount_id', this).val($(e.relatedTarget).data('discountid'));
        });

        $('#confirm-delete').on('click', '.btn-ok', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee") ?>',
                dataType: 'JSON',
                data: {'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });
        });

        $('#confirm-discountdelete').on('click', '.btn-discountdel', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var discount_id = $('#discount_id').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteStudentDiscount") ?>',
                dataType: 'JSON',
                data: {'discount_id': discount_id},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });
        });

        $(document).on('click', '.btn-ok', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee") ?>',
                dataType: 'JSON',
                data: {'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });

        });
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
    var fee_amount = 0;
    var fee_type_amount = 0;
</script>

<script type="text/javascript">

$("#myfeeDiscountModal").on('hidden.bs.modal', function (e) {
   
    $('#myfeeDiscountModal .modal-body .modal-body-inner').html("");  
});



$("#myFeesModal").on('hidden.bs.modal', function (e) {
    $('#myFeesModal .modal-body .modal-body-inner').html(""); 
});


$("#myfeeDiscountModal").on('shown.bs.modal', function (e) {
        e.stopPropagation();
        $('.fees_discount_title').html("<?php echo $this->lang->line('discount'); ?>");
        var student_fees_deposite = $(e.relatedTarget).data('feeDepositeId');
       
        var modal_discount = $(this);
        $.ajax({
            type: "post",
            url: base_url+"studentfee/getAppliedDiscounts",
            dataType: 'JSON',
            data: {'student_fees_deposite': student_fees_deposite
            },
            beforeSend: function () {
                fee_type_amount=0;
                        $('#myfeeDiscountModal .modal-body .modal-body-inner').html(""); 
                        $('#myfeeDiscountModal .modal-body .modal_loader_div').css("display", "block"); 
            },
            success: function (response) {
                if(response.status){
             
                    $('#myfeeDiscountModal .modal-body .modal-body-inner').html(response.page); 
                    $('#myfeeDiscountModal .modal-body .modal_loader_div').fadeOut(400);
                }

            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function () { 
            }
        });
    });



$("#myFeesModal").on('shown.bs.modal', function (e) {
        e.stopPropagation();
        var discount_group_dropdown = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var data = $(e.relatedTarget).data();
        console.log(data);

        var modal = $(this);
        var type = data.type;
        var amount = data.amount;
        var group = data.group;
        var fee_groups_feetype_id = data.fee_groups_feetype_id;
        var student_fees_master_id = data.student_fees_master_id;
        var student_session_id = data.student_session_id;
        var fee_category=data.feeCategory;
        var trans_fee_id=data.trans_fee_id;
		 var fee_session_group_id=data.fee_session_group_id;

        $('.fees_title').html("");
        $('.fees_title').html("<b>" + group + ":</b> " + type);
        $('#fee_groups_feetype_id').val(fee_groups_feetype_id);
        $('#student_fees_master_id').val(student_fees_master_id);
        $('#transport_fees_id').val(trans_fee_id);
        $('#fee_category').val(fee_category);
		 $('#fee_session_group_id').val(fee_session_group_id);

        $.ajax({
            type: "post",
            url: '<?php echo site_url("studentfee/getBalanceFee") ?>',
            dataType: 'JSON',
            data: {'fee_groups_feetype_id': fee_groups_feetype_id,
                'student_fees_master_id': student_fees_master_id,
                'student_session_id': student_session_id,
                'fee_category' : fee_category,
                'trans_fee_id' : trans_fee_id,
				 'fee_session_group_id' : fee_session_group_id
            },
            beforeSend: function () {
                fee_type_amount=0;
                        $('#myFeesModal .modal-body .modal-body-inner').html(""); 
                        $('#myFeesModal .modal-body .modal_loader_div').css("display", "block"); 
            },
            success: function (response) {
                if(response.status){
                    fee_type_amount=response.balance;
                    $('#myFeesModal .modal-body .modal-body-inner').html(response.page); 
                    $('#myFeesModal .modal-body .modal_loader_div').fadeOut(400);
                }

            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function () { 
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        modal_click_disabled('myfeeDiscountModal,myFeesModal');

        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });

    $(document).ready(function () {
        $('.table-fixed-header').fixedHeader();
    });

    (function ($) {

        $.fn.fixedHeader = function (options) {
            var config = {
                topOffset: 50
            };
            if (options) {
                $.extend(config, options);
            }
            return this.each(function () {
                var o = $(this);
                var $win = $(window);
                var $head = $('thead.header', o);
                var isFixed = 0;
                var headTop = $head.length && $head.offset().top - config.topOffset;

                function processScroll() {
                    if (!o.is(':visible')) {
                        return;
                    }
                    if ($('thead.header-copy').size()) {
                        $('thead.header-copy').width($('thead.header').width());
                    }
                    var i;
                    var scrollTop = $win.scrollTop();
                    var t = $head.length && $head.offset().top - config.topOffset;
                    if (!isFixed && headTop !== t) {
                        headTop = t;
                    }
                    if (scrollTop >= headTop && !isFixed) {
                        isFixed = 1;
                    } else if (scrollTop <= headTop && isFixed) {
                        isFixed = 0;
                    }
                    isFixed ? $('thead.header-copy', o).offset({
                        left: $head.offset().left
                    }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
                }
                $win.on('scroll', processScroll);

                // hack sad times - holdover until rewrite for 2.1
                $head.on('click', function () {
                    if (!isFixed) {
                        setTimeout(function () {
                            $win.scrollTop($win.scrollTop() - 47);
                        }, 10);
                    }
                });

                $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
                var header_width = $head.width();
                o.find('thead.header-copy').width(header_width);
                o.find('thead.header > tr:first > th').each(function (i, h) {
                    var w = $(h).width();
                    o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
                });
                $head.css({
                    margin: '0 auto',
                    width: o.width(),
                    'background-color': config.bgColor
                });
                processScroll();
            });
        };

    })(jQuery);

    $(".applydiscount").click(function () {
        $("span[id$='_error']").html("");
        $('.discount_title').html("");
        $('#student_fees_discount_id').val("");
        var student_fees_discount_id = $(this).data("student_fees_discount_id");
        var modal_title = $(this).data("modal_title");
        $('.discount_title').html("<b>" + modal_title + "</b>");
        $('#student_fees_discount_id').val(student_fees_discount_id);
        $('#myDisApplyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });

    $(document).on('click', '.dis_apply_button', function (e) {
        var $this = $(this);
        $this.button('loading');
        var discount_payment_id = $('#discount_payment_id').val();
        var student_fees_discount_id = $('#student_fees_discount_id').val();
        var dis_description = $('#dis_description').val();

        $.ajax({
            url: '<?php echo site_url("admin/feediscount/applydiscount") ?>',
            type: 'post',
            data: {
                discount_payment_id: discount_payment_id,
                student_fees_discount_id: student_fees_discount_id,
                dis_description: dis_description
            },
            dataType: 'json',
            success: function (response) {
                $this.button('reset');
                if (response.status === "success") {
                    location.reload(true);
                } else if (response.status === "fail") {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.printSelected', function () {
            var array_to_print = [];
                var $this = $(this);
            $.each($("input[name='fee_checkbox']:checked"), function () {
                var trans_fee_id = $(this).data('trans_fee_id');
                var fee_category = $(this).data('fee_category');
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item ["fee_category"] = fee_category;
                item ["trans_fee_id"] = trans_fee_id;
                item ["fee_session_group_id"] = fee_session_group_id;
                item ["fee_master_id"] = fee_master_id;
                item ["fee_groups_feetype_id"] = fee_groups_feetype_id;

                array_to_print.push(item);
            });
            if (array_to_print.length === 0) {
                alert("<?php echo $this->lang->line('no_record_selected'); ?>");
            } else {
                $.ajax({
                    url: '<?php echo site_url("studentfee/printFeesByGroupArray") ?>',
                    type: 'post',
                    data: {'data': JSON.stringify(array_to_print)},
                     beforeSend: function () {
                    $this.button('loading');
                    },
                    success: function (response) {
                        Popup(response);
                         $this.button('reset');
                    },
                     error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {
                    $this.button('reset');
                }
                });
            }
        });

        $(document).on('click', '.collectSelected', function () {
            var $this = $(this);
            var array_to_collect_fees = [];
            $.each($("input[name='fee_checkbox']:checked"), function () {
                var trans_fee_id = $(this).data('trans_fee_id');
                var fee_category = $(this).data('fee_category');
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item ["fee_category"] = fee_category;
                item ["trans_fee_id"] = trans_fee_id;
                item ["fee_session_group_id"] = fee_session_group_id;
                item ["fee_master_id"] = fee_master_id;
                item ["fee_groups_feetype_id"] = fee_groups_feetype_id;
                array_to_collect_fees.push(item);
            });

            $.ajax({
                type: 'POST',
                url: base_url + "studentfee/getcollectfee",
                data: {'data': JSON.stringify(array_to_collect_fees)},
                dataType: "JSON",
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {
                    $("#listCollectionModal .modal-body").html(data.view);
                    $("#listCollectionModal").modal('show');
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });
    });

    $(function () {
        $(document).on('change', '.grp_discount', function () {
            let selectedDiscounts = [];
            let final_discount_amount=0;
            let balance_amount=fee_type_amount;
          
      // Iterate through all checked checkboxes
      $('.grp_discount:checked').each(function () {
        let _this=$(this);
          var amount = _this.data('disamount');
          var type = _this.data('type');
          var percentage = _this.data('percentage');
          if (type == "fix") {
                final_discount_amount += parseFloat((parseFloat(amount)).toFixed(2));
          }else if (type == "percentage") {
                var per_amount=((parseFloat(fee_type_amount) * parseFloat(percentage))/100).toFixed(2);
                final_discount_amount += parseFloat(per_amount);
          }

         balance_amount= (parseFloat(fee_type_amount)-final_discount_amount).toFixed(2);
        });
            
            if (typeof final_discount_amount !== typeof undefined && final_discount_amount !== false) {
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', true).val(final_discount_amount);
                $('div#myFeesModal').find('input#amount').val(balance_amount);
            } else {
                $('div#myFeesModal').find('input#amount').val(fee_type_amount);
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', false).val(0);
            }
            selectedDiscounts.push($(this).val());
    });
    });

    $("#collect_fee_group").submit(function (e) {
        var total_paying=$("#total_paying").val();
        if(total_paying==""){
            alert("<?php echo $this->lang->line('please_enter_paying_amount');?>");
            return false;
        }
        var form = $(this);
        var url = form.attr('action');
        var smt_btn = $(this).find("button[type=submit]");
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'JSON',
            data: form.serialize(), // serializes the form's elements.
            beforeSend: function () {
                smt_btn.button('loading');
            },
            success: function (response) {
                if (response.status === 1) {
                    location.reload(true);
                } else if (response.status === 0) {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#form_collection_' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function () {
                smt_btn.button('reset');
            }
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


// $(document).on('change','#select_all',function(){
//     $('input:checkbox').not(this).prop('checked', this.checked);
// });

// Handle select_all for each table separately
    $(document).on('change', '.select_all_session', function() {
        var isChecked = $(this).prop('checked');
        
        // Find the closest table containing this checkbox
        var $table = $(this).closest('table');
        
        // Only select/deselect checkboxes within the same table
        $table.find('.fee_checkbox_session').prop('checked', isChecked);
    });
</script>


