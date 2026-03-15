<?php

$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$allow_partial_payment = true;
if (isset($this->sch_setting_detail) && isset($this->sch_setting_detail->student_partial_payment)) {
    $student_partial_setting_normalized = strtolower(trim((string)$this->sch_setting_detail->student_partial_payment));
    $allow_partial_payment = in_array($student_partial_setting_normalized, array('enabled', '1', 'true', 'yes'), true);
}
$partial_payment_note = $this->lang->line('partial_payment_disabled_note');
if ($partial_payment_note === "" || $partial_payment_note === false) {
    $partial_payment_note = 'Partial payment is disabled. Please pay the full selected amount.';
}
?>
<style type="text/css">
	.collect_grp_fees {
		font-size: 15px;
		font-weight: 600;
		padding-bottom: 15px;
	}

	.fees-list {
		list-style: none;
		margin: 0;
		padding: 0;
	}

	.fees-list>.item {
		border-radius: 3px;
		-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
		padding: 10px 0;
		/*background: #fff;*/
	}

	.fees-list>.item:before,
	.fees-list>.item:after {
		content: " ";
		display: table;
	}

	.fees-list>.item:after {
		clear: both;
	}

	.fees-list .product-img {
		float: left;
	}

	.fees-list .product-img img {
		width: 50px;
		height: 50px;
	}

	.fees-list .product-info {
		margin-left: 0px;
	}

	.fees-list .product-title {
		font-family: 'Roboto-Medium';
		font-size: 15px;
		display: inline-flex;
		justify-content: space-between;
		align-items: center;
		width: 100%;
		/*color: #333;*/
	}

	.fees-list .product-title span {

		font-size: 15px;
		display: inline;
		font-weight: 100 !important;
	}

	.fees-list .product-description {
		display: flex;
		/*color: #000;*/
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
		justify-content: space-between;
		align-items: center;
	}

	.fees-list-in-box>.item {
		-webkit-box-shadow: none;
		box-shadow: none;
		border-radius: 0;
		/*padding: 15px 0px 0px 0px;*/
		border-bottom: 1px solid var(--bs-hr-color);

	}

	.fees-list-in-box>.item:last-of-type {
		/*border-bottom-width: 100;*/
		margin-bottom: 10px;
	}

	.fees-footer {
		margin-top: 15px;
		border-top-color: var(--bs-hr-color);
	}

	.fees-footer {
		padding: 15px 0px 0px 0px;
		text-align: right;
		border-top: 1px solid var(--bs-hr-color);
	}
</style>
<?php if (!$allow_partial_payment): ?>
<style type="text/css">
    .partial-payment-input,
    #total_paying.no-partial-payment {
        display: none !important;
    }
    #total_paying_display.full-payment-display {
        width: 90px;
        display: inline-block;
        margin-left: 10px;
        text-align: right;
        font-weight: 600;
    }
</style>
<?php endif; ?>

<div class="row">
	<div class="col-lg-12">
		<div class="form-horizontal pr-0-5 pr-rtl-0 sticky-lg-top">
			
		</div>
		<ul class="fees-list fees-list-in-box">
			<hr>
		<div class="scroll-lg">	
			
			<div class="row sticky-lg-top">
				<div class="col-md-12">
					<div class="col-md-6"> <label for="inputPassword3" class=" control-label"> <?php echo $this->lang->line('fees'); ?></label></div>
					<div class="col-md-3 text-right"> <label for="inputPassword3" class=" control-label"> <?php echo $this->lang->line('fine_amount'); ?></label></div>
					<div class="col-md-3 text-right"> <label for="inputPassword3" class=" control-label"> <?php echo $this->lang->line('fees_amount'); ?></label></div>
				</div>
			</div>
    <?php
$row_counter  = 1;
$total_amount = 0;
$total_fine_amount = 0;

foreach ($feearray as $fee_key => $fee_value) {

    $amount_prev_paid   = 0;
    $fees_fine_amount   = 0;
    $fine_amount_paid   = 0;
    $fine_amount_status = false;

    if ($fee_value->fee_category == "fees") {
        $amount_to_be_pay = $fee_value->amount;

        if ($fee_value->is_system) {
            $amount_to_be_pay = $fee_value->student_fees_master_amount;
        }

        if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {

            $amount_data = json_decode($fee_value->amount_detail);

            foreach ($amount_data as $amount_data_key => $amount_data_value) {
                $fine_amount_paid += $amount_data_value->amount_fine;
                $amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
            }

            if ($fee_value->is_system) {
                $amount_to_be_pay = $fee_value->student_fees_master_amount - $amount_prev_paid;
            } else {
                $amount_to_be_pay = $fee_value->amount - $amount_prev_paid;
            }
        }

        if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) && $amount_to_be_pay > 0) {
 
            $fine_amount_status = true;
            
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
                $fees_fine_amount   = $due_fine_amount - $fine_amount_paid;

            }else if($fee_value->fine_type=='fix' || $fee_value->fine_type=='percentage'){
                $fees_fine_amount   = $fee_value->fine_amount - $fine_amount_paid;
            }
            // get cumulative fine amount as delay days
        }
        
		$total_amount = $total_amount + $amount_to_be_pay;
		if ($amount_to_be_pay > 0) {  ?>

			<li class="item ptt10">
				<input name="row_counter[]" type="hidden" value="<?php echo $row_counter; ?>">
				<input name="student_fees_master_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->id; ?>">
				<input name="fee_groups_feetype_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_groups_feetype_id; ?>">
				<input name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>" id="fee_groups_feetype_fine_amount_hidden_<?php echo $row_counter; ?>" data-original-fine="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>">
				<input name="fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" id="fee_amount_hidden_<?php echo $row_counter; ?>" data-original-amount="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>">
				<input name="fee_category_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_category; ?>">
				<input name="fee_session_group_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_session_group_id; ?>">
				<input name="trans_fee_id_<?php echo $row_counter; ?>" type="hidden" value="0">
				<div class="product-info pb15">

					<div class="row">
						<a>
							<div class="col-md-12">
								<div class="col-md-6">
									<?php
									if ($fee_value->is_system) {
										echo $this->lang->line($fee_value->type) . " (" . $this->lang->line($fee_value->code) . ")";
									} else {
										echo $fee_value->type . " (" . $fee_value->code . ")";
									}
									?>
								</div>
								<div class="col-md-3 text text-danger text-right">
									<?php if ($fine_amount_status && ($fees_fine_amount > 0)) {
										echo  $currency_symbol . amountFormat($fees_fine_amount);
										$total_fine_amount = $total_fine_amount + $fees_fine_amount;
									} ?>
								</div>
								<div class="col-md-3 text-right">
									<?php echo  $currency_symbol . amountFormat($amount_to_be_pay); ?>
								</div>
							</div>
						</a>
					</div>
					
					<?php if($allow_partial_payment){ ?>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6">

							</div>
							<div class="col-md-3 pull-right">

								<input class="pull-right form-control fee-amount-field" style="width:80px" name="fee_amount_<?php echo $row_counter; ?>" id="fee_amount_<?php echo $row_counter; ?>" type="text" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" data-row="<?php echo $row_counter; ?>" max="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" data-original-amount="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" />								

								<input class="pull-right form-control" name="pay_fee_amount_<?php echo $row_counter; ?>" id="pay_fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>">							

							</div>

							<div class="col-md-3 pull-right">
								<?php if ($fine_amount_status && ($fees_fine_amount > 0)) {    ?>

									<input type="hidden" id="actual_fine_amount_<?php echo $row_counter; ?>" value="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>">

									<input class="form-control total_fine_paying pull-right" style="width:80px" name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" id="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="text" value="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>" readonly />

								<?php }   ?>
							</div>
						</div>
					</div>
					<?php } ?>

				</div>
			</li>
		<?php  }
    } elseif ($fee_value->fee_category == "transport") {
        $amount_to_be_pay = $fee_value->fees;
        if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {
            $amount_data = json_decode($fee_value->amount_detail);
            foreach ($amount_data as $amount_data_key => $amount_data_value) {
                $fine_amount_paid += $amount_data_value->amount_fine;
                $amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
            }

            $amount_to_be_pay = $fee_value->fees - $amount_prev_paid;
        }

        if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) && $amount_to_be_pay > 0) {

            $transport_fine_amount = is_null($fee_value->fine_percentage) ? $fee_value->fine_amount : percentageAmount($fee_value->fees, $fee_value->fine_percentage);

            $fees_fine_amount   = $transport_fine_amount - $fine_amount_paid;
            $fine_amount_status = true;
        }

        $total_amount = $total_amount + $amount_to_be_pay;
        if ($amount_to_be_pay > 0) { ?>

			<li class="item pb10">
				<input name="row_counter[]" type="hidden" value="<?php echo $row_counter; ?>">
				<input name="student_fees_master_id_<?php echo $row_counter; ?>" type="hidden" value="0">
				<input name="fee_groups_feetype_id_<?php echo $row_counter; ?>" type="hidden" value="0">
				<input name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>" id="fee_groups_feetype_fine_amount_hidden_<?php echo $row_counter; ?>" data-original-fine="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>">
				<input name="fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" id="fee_amount_hidden_<?php echo $row_counter; ?>" data-original-amount="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>">
				<div class="product-info pb10 pt5">
					<input name="fee_category_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_category; ?>">
					<input name="trans_fee_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->id; ?>">

					<div class="row pt5">
						<a>
							<div class="col-md-12">
								<div class="col-md-6">
									<?php echo $this->lang->line("transport_fees") ?>
								</div>
								<div class="col-md-3 text text-danger text-right">
									<?php if ($fine_amount_status && ($fees_fine_amount > 0)) {
										echo $currency_symbol . amountFormat($fees_fine_amount);
										$total_fine_amount = $total_fine_amount + $fees_fine_amount;
									} ?>
								</div>
								<div class="col-md-3 text-right">
									<?php echo  $currency_symbol . amountFormat($amount_to_be_pay); ?>
								</div>
							</div>
						</a>
					</div>

					<?php if($allow_partial_payment){ ?>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6">
								
							</div>
							<div class="col-md-3 pull-right">

								<input class="pull-right form-control fee-amount-field" style="width:80px" name="fee_amount_<?php echo $row_counter; ?>" id="fee_amount_<?php echo $row_counter; ?>" type="text" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" data-row="<?php echo $row_counter; ?>" data-original-amount="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" />

								<input class="pull-right" style="width:80px;" name="pay_fee_amount_<?php echo $row_counter; ?>" id="pay_fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>">

							</div>

							<div class="col-md-3 pull-right">
								<?php if ($fine_amount_status && ($fees_fine_amount > 0)) { ?>
									
									<input type="hidden" id="actual_fine_amount_<?php echo $row_counter; ?>" value="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>">
		   
									<input class="form-control total_fine_paying pull-right" style="width:80px" name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" id="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="text" value="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>" readonly />
								<?php }  ?>
							</div>
						</div>
					</div>
					<?php } ?>					
					
				</div>
			</li>
			
	<?php
		}
    }
    $row_counter++;
}
?>

		</div>	
	</ul>
</div>
</div>

<?php if ($total_amount > 0) { ?>

	<div class="row ptt10">
		<div class="col-md-12">
			<div class="col-md-3">
				<label for="inputPassword3" class=" control-label"> <?php echo $this->lang->line('total'); ?></label>
			</div>
			<div class="col-md-3 text-right">
			
				<?php $total_amount_for_pay_numeric = $total_amount + $total_fine_amount;?>
				<input type="hidden" id="total_amount_for_pay" name="total_amount_for_pay" value="<?php echo convertBaseAmountCurrencyFormat($total_amount_for_pay_numeric); ?>" />
				<label for="inputPassword3" class=" control-label" id="">
				<?php echo $currency_symbol . amountFormat($total_amount_for_pay_numeric); ?></label>								
					
			 
			</div>
			<div class="col-md-3 text-right">
				<label for="inputPassword3" class=" control-label" id=""> <?php echo $currency_symbol . amountFormat($total_fine_amount); ?></label>
			</div>
			 
			<div class="col-md-3 text-right">
				<label for="inputPassword3" class=" control-label" id=""> <?php echo $currency_symbol . amountFormat($total_amount); ?></label>
			</div>						
		</div>
	</div>		

	<div class="row">
		<div class="col-md-12">
			<div class="col-lg-6 col-md-6 col-xs-6">
				<span class="bmedium">
					<?php echo $this->lang->line("paying_amount") . " (" . $currency_symbol . ")"; ?> <small class="req"> *</small>
				</span>
			</div>			

			<div class="col-lg-6 col-md-6 col-xs-6">
				<span class="pull-right ">
					<input class="total_paying form-control <?php echo $allow_partial_payment ? '' : 'no-partial-payment'; ?>" style="width:200px" name="total_paying" id="total_paying" type="text" value="" placeholder="0.00" readonly>
                    <?php if (!$allow_partial_payment): ?>
                    <span id="total_paying_display" class="full-payment-display"><?php echo $currency_symbol; ?>0.00</span>
                    <?php endif; ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row ">
		<div class="col-lg-4 col-md-4 col-xs-4 text-right">
		</div>
		<div class="col-lg-8 col-md-8 col-xs-8">
			<span class="pull-right pr-1 pt5">
				<span id="form_collection_total_paying_error" class="text text-danger"></span>
			</span>
		</div>
	</div>

	<div class="row  hidden">
		<div class="col-lg-8 col-md-8 col-xs-8">
			<span>
				<?php echo "Paying Amount"; ?>
			</span>
		</div>
		<div class="col-lg-4 col-md-4 col-xs-4">
			<span class="pull-right">
				<?php echo $currency_symbol; ?><span id="paying_amount">0.00</span>
			</span>
		</div>
	</div>
	<div class="fees-footer">
		<div class="row">
			<div class="col-md-12">
				<button type="submit" class="btn btn-primary pull-right payment_collect" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('processing') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay'); ?></button>
			</div>
		</div>
	</div>

	<?php } else {  ?>

		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-info mb0">
					<?php echo $this->lang->line('no_fees_found'); ?>
				</div>
			</div>
		</div>

	<?php }  ?>

<script type="text/javascript">
	const currencySymbol = "<?php echo $currency_symbol; ?>";
    const allowPartialPayment = <?php echo $allow_partial_payment ? 'true' : 'false'; ?>;
    const rowCount = <?php echo $row_counter; ?>;

	const sanitizeNumber = (value) => {
		if (typeof value === 'number') {
			return value;
		}
		if (value === undefined || value === null) {
			return 0;
		}
		return parseFloat(value.toString().replace(/[^0-9.\-]/g, '')) || 0;
	};

	const assignFeeAmount = (index, amount) => {
		var feeInput = $("#fee_amount_" + index + "[type='text']");
		var feeHidden = $("#fee_amount_hidden_" + index);
		var feeHiddenNamed = $("input[name='fee_amount_" + index + "']");
		amount = parseFloat(amount) || 0;

		if (amount > 0) {
			var formatted = amount.toFixed(2);
			if (feeInput.length) {
				feeInput.val(formatted);
			}
			if (feeHidden.length) {
				feeHidden.val(formatted);
			}
			if (feeHiddenNamed.length) {
				feeHiddenNamed.val(formatted);
			}
		} else {
			if (feeInput.length) {
				feeInput.val('');
			}
			if (feeHidden.length) {
				feeHidden.val('0');
			}
			if (feeHiddenNamed.length) {
				feeHiddenNamed.val('0');
			}
		}
	};

	const setFineState = (index, amount) => {
		var fineInput = $("#fee_groups_feetype_fine_amount_" + index + "[type='text']");
		var fineHidden = $("#fee_groups_feetype_fine_amount_hidden_" + index);
		var fineHiddenNamed = $("input[name='fee_groups_feetype_fine_amount_" + index + "']");
		var fineRow = $("#fine_row_" + index);
		var fineDisplay = $("#fine_display_" + index);

		amount = parseFloat(amount) || 0;

		if (fineRow.length) {
			fineRow.show();
		}

		if (amount > 0) {
			var formatted = amount.toFixed(2);
			if (fineInput.length) {
				fineInput.val(formatted);
			}
			if (fineHidden.length) {
				fineHidden.val(formatted);
			}
			if (fineHiddenNamed.length) {
				fineHiddenNamed.val(formatted);
			}
			if (fineDisplay && fineDisplay.length) {
				fineDisplay.text("+ " + currencySymbol + formatted).show();
			}
		} else {
			if (fineInput.length) {
				fineInput.val('');
			}
			if (fineHidden.length) {
				fineHidden.val('0');
			}
			if (fineHiddenNamed.length) {
				fineHiddenNamed.val('0');
			}
			if (fineDisplay && fineDisplay.length) {
				fineDisplay.hide().text('');
			}
		}
	};

	const getOriginalFee = (index) => {
		var payFeeVal = $("#pay_fee_amount_" + index).val();
		var amount = sanitizeNumber(payFeeVal);

		if (amount <= 0) {
			amount = sanitizeNumber($("#fee_amount_" + index + "[type='text']").data('original-amount'));
		}

		if (amount <= 0) {
			amount = sanitizeNumber($("#fee_amount_hidden_" + index).data('original-amount'));
		}

		return amount;
	};

	const getOriginalFine = (index) => {
		var fineInput = $("#fee_groups_feetype_fine_amount_" + index);
		var fineHidden = $("#fee_groups_feetype_fine_amount_hidden_" + index);
		var amount = sanitizeNumber(fineInput.data('original'));

		if (amount <= 0) {
			amount = sanitizeNumber(fineInput.attr('data-original-fine'));
		}

		if (amount <= 0) {
			amount = sanitizeNumber(fineHidden.data('original'));
		}

		if (amount <= 0) {
			amount = sanitizeNumber(fineHidden.attr('data-original-fine'));
		}

		return amount;
	};

	const resetAllRows = (count) => {
		for (let i = 1; i < count; i++) {
			assignFeeAmount(i, 0);
			setFineState(i, 0);
		}
	};

	const update_fine_amount = () => {
		var count = <?php echo $row_counter; ?>;
		var totalFineAmount = 0;
		for (let i = 1; i < rowCount; i++) {
			var fineValue = sanitizeNumber($("#fee_groups_feetype_fine_amount_" + i + "[type='text']").val());
			if (fineValue > 0) {
				totalFineAmount += fineValue;
			}
		}

		if ($("#update_fine").length) {
			$("#update_fine").html(currencySymbol + totalFineAmount.toFixed(2));
		}
	};

    const updateTotalPayingDisplay = (amount) => {
        if (allowPartialPayment) {
            return;
        }
        const displayEl = $("#total_paying_display");
        if (!displayEl.length) {
            return;
        }
        const sanitized = parseFloat(amount) || 0;
        displayEl.text(currencySymbol + sanitized.toFixed(2));
    };

	const enforceFullPayment = () => {
        let totalFeeDue = 0;
        let totalFineDue = 0;

        for (let i = 1; i < rowCount; i++) {
            const originalFee = getOriginalFee(i);
            if (originalFee > 0) {
                assignFeeAmount(i, originalFee);
                totalFeeDue += originalFee;
            } else {
                assignFeeAmount(i, 0);
            }

            const originalFine = getOriginalFine(i);
            if (originalFine > 0) {
                setFineState(i, originalFine);
                totalFineDue += originalFine;
            } else {
                setFineState(i, 0);
            }
        }

        const combinedDue = totalFeeDue + totalFineDue;
        const totalPayingInput = $("#total_paying");

        if (combinedDue > 0) {
            totalPayingInput.val(combinedDue.toFixed(2));
            $('.payment_collect').prop('disabled', false);
            updateTotalPayingDisplay(combinedDue);
        } else {
            totalPayingInput.val('');
            $('.payment_collect').prop('disabled', true);
            updateTotalPayingDisplay(0);
        }

        totalPayingInput.prop('readonly', true);
        update_fine_amount();
    };

    const recalcTotalsFromRows = (skipPayingAmountUpdate = false, skipFeeInputUpdate = false) => {
        if (!allowPartialPayment) {
            update_fine_amount();
            return;
        }

        let combinedTotal = 0;

        for (let i = 1; i < rowCount; i++) {
            const feeInput = $("#fee_amount_" + i + "[type='text']");
            if (feeInput.length) {
                const maxFee = getOriginalFee(i);
                let feeValue = sanitizeNumber(feeInput.val());
                if (maxFee > 0 && feeValue > maxFee) {
                    feeValue = maxFee;
                   
                    if (!skipFeeInputUpdate) {
                        assignFeeAmount(i, feeValue);
                    } else {
                        
                        var feeHidden = $("#fee_amount_hidden_" + i);
                        var feeHiddenNamed = $("input[name='fee_amount_" + i + "']");
                        var formatted = feeValue.toFixed(2);
                        if (feeHidden.length) {
                            feeHidden.val(formatted);
                        }
                        if (feeHiddenNamed.length) {
                            feeHiddenNamed.val(formatted);
                        }
                    }
                }
                combinedTotal += feeValue;
            }

            const fineInput = $("#fee_groups_feetype_fine_amount_" + i + "[type='text']");
            if (fineInput.length) {
                const maxFine = getOriginalFine(i);
                let fineValue = sanitizeNumber(fineInput.val());
                if (maxFine > 0 && fineValue > maxFine) {
                    fineValue = maxFine;
                    setFineState(i, fineValue);
                }
                combinedTotal += fineValue;
            }
        }
       
        if (!skipPayingAmountUpdate) {
            if (combinedTotal > 0) {
                $('#total_paying').val(combinedTotal.toFixed(2));
            } else {
                $('#total_paying').val('');
            }
        }

        updateTotalPayingDisplay(combinedTotal);
        update_fine_amount();        
      
        updateBottomTotalsDisplay();
    };    
   
    const updateBottomTotalsDisplay = () => {
        let totalFeesAmount = 0;
        let totalFineAmount = 0;        
    
        for (let i = 1; i < rowCount; i++) {
            const feeInput = $("#fee_amount_" + i + "[type='text']");
            if (feeInput.length) {
                const feeValue = sanitizeNumber(feeInput.val());
                totalFeesAmount += feeValue;
            }
            
            const fineInput = $("#fee_groups_feetype_fine_amount_" + i + "[type='text']");
            if (fineInput.length) {
                const fineValue = sanitizeNumber(fineInput.val());
                totalFineAmount += fineValue;
            }
        }
        
		const total_selected_amount = "<?php echo $total_amount_for_pay_numeric; ?>";	  
    
        const totalAmountForPay = totalFeesAmount + totalFineAmount;        
    
        const displayTotal = $("#display_total_amount_for_pay");
        const displayFine = $("#display_total_fine_amount");
        const displayFees = $("#display_total_fees_amount");
        
        if (displayTotal.length) {
            displayTotal.text(currencySymbol + totalAmountForPay.toFixed(2));
        }
        if (displayFine.length) {
            displayFine.text(currencySymbol + totalFineAmount.toFixed(2));
        }
        if (displayFees.length) {
            displayFees.text(currencySymbol + totalFeesAmount.toFixed(2));
        }
        
		let diff = total_selected_amount-totalAmountForPay;
		
		if (diff > 0) {
			$("#form_collection_total_paying_error").html(
				"<?php echo $this->lang->line('balance_amount') .' '. $currency_symbol;?>" + Math.abs(diff).toFixed(2) 
			);
		} else {
			$("#form_collection_total_paying_error").html('');
		}
    
        $('#total_amount_for_pay').val(totalAmountForPay.toFixed(2));
    };

    const initializePartialPaymentView = () => {
        if (!allowPartialPayment) {
            return;
        }

        for (let i = 1; i < rowCount; i++) {
            const originalFee = getOriginalFee(i);
            if (originalFee > 0) {
                assignFeeAmount(i, originalFee);
            } else {
                assignFeeAmount(i, 0);
            }

            const originalFine = getOriginalFine(i);
            if (originalFine > 0) {
                setFineState(i, originalFine);
            } else {
                setFineState(i, 0);
            }
        }

        recalcTotalsFromRows();
    };

	$(document).ready(function() {
		for (let i = 1; i < rowCount; i++) {
			var feeHidden = $("#fee_amount_hidden_" + i);
			var feeOriginal = sanitizeNumber(feeHidden.attr('data-original-amount'));
			if (feeOriginal > 0) {
				feeHidden.data('original-amount', feeOriginal);
				$("#fee_amount_" + i + "[type='text']").data('original-amount', feeOriginal);
			}

			var fineHidden = $("#fee_groups_feetype_fine_amount_hidden_" + i);
			var fineOriginal = sanitizeNumber(fineHidden.attr('data-original-fine'));
			if (fineOriginal > 0) {
				fineHidden.data('original', fineOriginal);
				$("#fee_groups_feetype_fine_amount_" + i).data('original', fineOriginal);
			}
		}

        if (allowPartialPayment) {
            initializePartialPaymentView();
        
           const recalculateTotalPaying = () => {
    let totalFee = 0;
    let totalFine = 0;
    let hasError = false;
    
    for (let i = 1; i < rowCount; i++) {
        const feeInput = $("#fee_amount_" + i);
        const pay_fee_amount = $("#pay_fee_amount_" + i);
        
        let feeamt = sanitizeNumber(feeInput.val());
        let payamt = sanitizeNumber(pay_fee_amount.val());
        console.log(feeamt); console.log(payamt);
        // Fix: Proper validation logic
        if (feeamt > payamt) {
            
            // Fix: Set the input value to the maximum allowed amount
			setTimeout(() => {
    errorMsg('<?php echo $this->lang->line('enter_valid_amount_you_are_entering_more_than_required_amount')?>');
}, 1000);
            feeInput.val(payamt.toFixed(2));
            feeamt = payamt; // Use corrected value for calculation
            hasError = true;
        }
        
        if (feeInput.length) {
            totalFee += feeamt; // Use the (possibly corrected) feeamt
        }
        
        const fineInput = $("#fee_groups_feetype_fine_amount_" + i);
        if (fineInput.length) {
            totalFine += sanitizeNumber(fineInput.val());
        }
    }
    
    let grandTotal = totalFee + totalFine;
    
    // Update paying amount (readonly field)
    if (grandTotal > 0) {
        $('#total_paying').val(grandTotal.toFixed(2));
    } else {
        $('#total_paying').val('');
    }
    
    // Update bottom totals display
    updateBottomTotalsDisplay();
    
    return !hasError; // Return whether validation passed
};
            
            // Real-time update during typing - only update paying amount, don't modify input field
            $(document).on('input paste', '.fee-amount-field', function () {
                // Simply recalculate total paying amount (readonly field update, no input field modification)
                recalculateTotalPaying();
            });            
         
            $(document).on('blur change', '.fee-amount-field', function () {
                var $this = $(this);
                var row = $this.data('row');
                if (!row) {
                    return;
                }                
           
                var rawValue = $this.val();
                var maxValue = getOriginalFee(row);
                var currentValue = sanitizeNumber(rawValue);                
           
                if (maxValue > 0 && currentValue > maxValue) {
                    currentValue = maxValue;
                }                
             
                var formattedValue = currentValue > 0 ? currentValue.toFixed(2) : '';
                if (currentValue > 0) {
                    $this.val(formattedValue);
                } else if (rawValue !== '') {
                   
                    $this.val('');
                }                
               
                var feeHidden = $("#fee_amount_hidden_" + row);
                var feeHiddenNamed = $("input[name='fee_amount_" + row + "']");
                var hiddenFormatted = currentValue > 0 ? currentValue.toFixed(2) : '0';
                
                if (feeHidden.length) {
                    feeHidden.val(hiddenFormatted);
                }
                if (feeHiddenNamed.length) {
                    feeHiddenNamed.val(hiddenFormatted);
                }                
             
                recalcTotalsFromRows(false, false);
            });

            $(document).on('input paste keyup', '.total_fine_paying', function () {
                var row = $(this).data('row');
                if (!row) {
                    recalcTotalsFromRows();
                    return;
                }
                var maxFine = getOriginalFine(row);
                var fineValue = sanitizeNumber($(this).val());
                if (maxFine > 0 && fineValue > maxFine) {
                    fineValue = maxFine;
                }
                setFineState(row, fineValue);
                recalcTotalsFromRows();
            });
        } else {
            enforceFullPayment();
            $(document).on('input paste keyup', '.total_fine_paying', function () {
                update_fine_amount();
            });
        }
    });

	// Sync text input values to hidden fields before form submission
	$(document).on('submit', '#collect_fee_group', function(e) {
		// Calculate total fine amount (original fine amounts)
		var total_fine_available = 0;
		for (let i = 1; i < rowCount; i++) {
			total_fine_available += getOriginalFine(i);
		}		
	
		var total_paying = sanitizeNumber($('#total_paying').val());
		var total_amount_for_pay = sanitizeNumber($('#total_amount_for_pay').val());
		var total_with_fine = total_amount_for_pay + total_fine_available;		
	
		if (total_fine_available > 0 && total_paying > 0 && total_paying < total_fine_available) {
			e.preventDefault();
			var fineAmountMsg = "Paying amount must be at least equal to total fine amount. Total fine amount is " + total_fine_available.toFixed(2);
			alert(fineAmountMsg);
			$('#form_collection_total_paying_error').text(fineAmountMsg);
			return false;
		}		
	
		if (total_paying > total_with_fine) {
			e.preventDefault();
			var maxAmountMsg = "Deposit Amount Can Not Be Greater Than Remaining";
			alert(maxAmountMsg);
			$('#form_collection_total_paying_error').text(maxAmountMsg);
			return false;
		}		
	
		for (let i = 1; i < rowCount; i++) {
			var feeTextInput = $("#fee_amount_" + i + "[type='text']");
			var feeHiddenField = $("input[name='fee_amount_" + i + "']");
			
			if (feeTextInput.length && feeHiddenField.length) {
				var feeValue = sanitizeNumber(feeTextInput.val());
				feeHiddenField.val(feeValue.toFixed(2));
			}
			
			var fineTextInput = $("#fee_groups_feetype_fine_amount_" + i + "[type='text']");
			var fineHiddenField = $("input[name='fee_groups_feetype_fine_amount_" + i + "']");
			
			if (fineTextInput.length && fineHiddenField.length) {
				var fineValue = sanitizeNumber(fineTextInput.val());
				fineHiddenField.val(fineValue.toFixed(2));
			}
		}		
	
		$('#form_collection_total_paying_error').text('');
		
	
		return true;
	});

	$(document).ready(function() {
		if ($('.date_fee').length) {
			$('.date_fee').datepicker({
				autoclose: true,
				format: '<?php echo $this->customlib->getSchoolDateFormat(); ?>'
			});
		}
	});
</script>
