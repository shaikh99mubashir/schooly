<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<body>       

<!-- ======================== -->
<?php 
// 1st print
$print_copy=explode(',', $settinglist[0]['is_duplicate_fees_invoice']);
    for($i=0;$i<count($print_copy);$i++){   ?>
    <?php if($sch_setting->single_page_print==0 && $i > 0) {  ?>
    <div class="page-break"></div>
    <?php } ?>
<!-- ======================== -->

<div class="container" style="margin:5%;"> 
<div class="row">
                <div id="content" class="col-lg-12 col-md-12 ">
                    <div class="invoice">
                        <div class="row header ">
                            <div class="col-md-12">                               
                                <img src="<?php echo $this->media_storage->getImageURL('/uploads/print_headerfooter/student_receipt/'.$this->setting_model->get_receiptheader()); ?>" style="height: 100px;width: 100%;">
                            </div>
                        </div> 
                            <div class="row">
                                <div class="col-md-12">
                                   <center> 
                                    <h2 style="border-top: 1px solid gray; border-bottom: 1px solid gray; text-align:center;font-size: 13px;padding-top: 5px; padding-bottom: 5px; margin-top: 8px; margin-bottom:5px;">
                                        
                                        <?php
                                        if($print_copy[$i]==0){
                                            echo $this->lang->line('office_copy'); 
                                        }else if($print_copy[$i]==1){
                                            echo $this->lang->line('student_copy');
                                        }else if($print_copy[$i]==2){
                                            echo $this->lang->line('bank_copy'); 
                                        }
                                        ?></h2>
                                    </center>
                                </div>
                            </div>
                        <div class="row" >                           
                            <div class="col-md-12">
                                <table width="100%" style="font-size:12px ;">
                                    <tr>
                                        <td width="50%">
                                            <span>
                                                <strong><?php
                                                echo $this->customlib->getFullName($feeList->firstname,$feeList->middlename,$feeList->lastname,$sch_setting->middlename,$sch_setting->lastname);
                                                ?></strong><?php echo " (".$feeList->admission_no.")"; ?>
                                                <br>
                                                <?php echo $this->lang->line('father_name'); ?>: <?php echo $feeList->father_name; ?><br>
                                                <?php echo $this->lang->line('class'); ?>: <?php echo $feeList->class . " (" . $feeList->section . ")"; ?>
                                            </span>
                                        </td>
                                        <td width="50%" style="vertical-align: top;text-align: right;float: right;">
                                            <span>
                                                <strong><?php echo $this->lang->line('date') ; ?>: <?php
                                                $date = date('d-m-Y');
                                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date));
                                                ?></strong>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr style="margin-top: 0px;margin-bottom:0px;" />
                        <div class="row">
                            <?php
                            if (!empty($feeList)) {
                                ?>
                                <table style="font-size:12px ;margin-top:5px" width="100%">

                                    <thead>
                                        <tr>
                                            <th style="text-align:left;"><?php echo $this->lang->line('fees'); ?></th>
                                            <th><?php echo $this->lang->line('due_date'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th style="text-align:right;"><?php echo $this->lang->line('amount'); ?></th>
                                            <th style="text-align:center;"><?php echo $this->lang->line('payment_id'); ?></th>
                                            <th style="text-align:center;"><?php echo $this->lang->line('mode'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th style="text-align:right;"><?php echo $this->lang->line('paid'); ?></th>
                                            <th style="text-align:right;"><?php echo $this->lang->line('fine'); ?></th>
                                            <th style="text-align:right;"><?php echo $this->lang->line('discount'); ?></th>
                                            <th style="text-align:right;"><?php echo $this->lang->line('balance'); ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $amount = 0;
                                        $discount = 0;
                                        $fine = 0;
                                        $total = 0;
                                        $grd_total = 0;
                                        
                                        if (empty($feeList)) {

                                            ?>
                                            <tr  style="border-top: 1px solid gray;">
                                                <td colspan="11" style="text-align:center;color:red;">
                                                    <?php echo $this->lang->line('no_transaction_found'); ?>
                                                </td>
                                            </tr>
                                            <?php
                                            } else {
                                          
                                            $fee_discount = 0;
                                            $fee_paid = 0;
                                            $fee_fine = 0;
                                            $alot_fee_discount = 0;
                                            if ($feeList->is_system) {
                                                $feeList->amount = $feeList->student_fees_master_amount;
                                            }
                                            if (!empty($feeList->amount_detail)) {
                                                $fee_deposits = json_decode(($feeList->amount_detail));

                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                    $fee_paid = $fee_paid + $fee_deposits_value->amount;
                                                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                    $fee_fine = $fee_fine + $fee_deposits_value->amount_fine;
                                                }
                                            }
                                            $feetype_balance = $feeList->amount - ($fee_paid + $fee_discount);
                                            ?>
                                            <tr  style="border-top: 1px solid gray;">
                                                <td>
                                                    <?php
                                                    if ($feeList->is_system) {
                                                        echo $this->lang->line($feeList->type) . " (" . $this->lang->line($feeList->code) . ")";
                                                    } else {
                                                        echo $feeList->type . " (" . $feeList->code . ")";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="">
                                                    <?php
                                                    if ($feeList->due_date) {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($feeList->due_date));
                                                    } else {
                                                    } ?>
                                                </td>
                                                <td class="">
                                                    <?php
                                                    if ($feetype_balance == 0) {
                                                        echo $this->lang->line('paid');
                                                    } else if (!empty($feeList->amount_detail)) {
                                                        ?><?php echo $this->lang->line('partial'); ?><?php
                                                    } else {
                                                        echo $this->lang->line('unpaid');
                                                    }
                                                    ?>
                                                </td>

                                                <td  style="text-align:right;" class="text text-right">
                                                    <?php echo $currency_symbol . amountFormat($feeList->amount);

                                                    if (($feeList->due_date != "0000-00-00" && $feeList->due_date != null) && (strtotime($feeList->due_date) < strtotime(date('Y-m-d')))) {
                                                                ?>
                                                    <span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . $currency_symbol .amountFormat($feeList->fine_amount); ?></span>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($feeList->fine_amount != "") {
                                                        ?>
                                                        <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
                                                        <?php
                                                            }
                                                         ?>
                                                    </div>
                                                        <?php }     ?>
                                                </td>
                                                <td colspan="3"></td>
                                                <td  style="text-align:right;"><?php
                                                    echo ($currency_symbol . amountFormat($fee_paid));
                                                    ?></td>
                                                <td  style="text-align:right;"><?php
                                                    echo ($currency_symbol . amountFormat($fee_fine));
                                                    ?></td>
                                                <td  style="text-align:right;"><?php
                                                    echo ($currency_symbol . amountFormat($fee_discount, 2));
                                                    ?></td>
                                                <td  style="text-align:right;"><?php
                                                    $display_none = "ss-none";
                                                    if ($feetype_balance > 0) {
                                                        $display_none = "";
                                                        echo ($currency_symbol . amountFormat($feetype_balance));
                                                    }
                                                    ?>
                                                </td>
                                            </tr>

                                            <?php
                                            $fee_deposits = json_decode(($feeList->amount_detail));
                                            if (is_object($fee_deposits)) {

                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                    ?>
                                                    <tr  style="border-top: 1px solid gray;">

                                                        <td colspan="4" style="text-align:right;"><img src="<?php echo $this->media_storage->getImageURL('backend/images/table-arrow.png');?>" alt="" /></td>
                                                    
                                                        <td  style="text-align:center;">
                                                            <?php echo $feeList->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>
                                                        </td>
                                                        <td  style="text-align:center;"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                       
                                                        <td  style="text-align:center;">
                                                            <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                        </td>
                                                        
                                                        <td  style="text-align:right;"><?php echo ($currency_symbol . amountFormat($fee_deposits_value->amount)); ?></td>
                                                        <td  style="text-align:right;"><?php echo ($currency_symbol . amountFormat($fee_deposits_value->amount_fine)); ?></td>
                                                        <td  style="text-align:right;"><?php echo ($currency_symbol . amountFormat($fee_deposits_value->amount_discount)); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row header ">
                    <div class="col-md-12">
                        <?php echo $this->setting_model->get_receiptfooter(); ?>
                        <?php
                        ?>
                    </div>
                </div>  
</div>

</div>


<?php } ?>