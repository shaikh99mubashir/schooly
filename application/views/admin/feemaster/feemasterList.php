<style type="text/css">
    .liststyle1 {
        margin: 0;
        list-style: none;
        line-height: 28px;
    }
</style>

<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('fees_master', 'can_add')) {
            ?>
               <div class="col-md-5 col-lg-3">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_fees_master') . " : " . $this->setting_model->getCurrentSessionName(); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/feemaster/save_data" name="feemasterform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                    ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('fees_group'); ?></label> <small class="req">*</small>
                                            <select autofocus="" id="fee_groups_id" name="fee_groups_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($feegroupList as $feegroup) {
                                                ?>
                                                    <option value="<?php echo $feegroup['id'] ?>" <?php
                                                                                                    if (set_value('fee_groups_id') == $feegroup['id']) {
                                                                                                        echo "selected =selected";
                                                                                                    }
                                                                                                    ?>><?php echo $feegroup['name'] ?></option>

                                                <?php
                                                    $count++;
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('fee_groups_id'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('fees_type'); ?></label><small class="req"> *</small>
                                            <select id="feetype_id" name="feetype_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($feetypeList as $feetype) {
                                                ?>
                                                    <option value="<?php echo $feetype['id'] ?>" <?php
                                                                                                    if (set_value('feetype_id') == $feetype['id']) {
                                                                                                        echo "selected =selected";
                                                                                                    }
                                                                                                    ?>><?php echo $feetype['type'] ?></option>

                                                <?php
                                                    $count++;
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('feetype_id'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('due_date'); ?></label><small class="req" id="due_date_error"> </small>
                                            <input id="due_date" name="due_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('due_date'); ?>" />
                                            <span class="text-danger"><?php echo form_error('due_date'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</label><small class="req"> *</small>
                                            <input id="amount" name="amount" placeholder="" type="text" class="form-control" value="<?php echo set_value('amount'); ?>" />
                                            <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                        </div>
                                         <div class="">
                                            <div class="form-group">
                                                <label for="input-type"><?php echo $this->lang->line('fine_type'); ?></label>
                                                <div id="input-type" class="row">
                                                    <div class="col-sm-6 col-lg-6 col-md-12">
                                                        <label class="radio-inline">
                                                            <input name="account_type" class="finetype" id="input-type-student" value="none" type="radio" <?php echo set_radio('account_type', 'none', true); ?> /><?php echo $this->lang->line('none') ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-6 col-md-12">
                                                        <label class="radio-inline">
                                                            <input name="account_type" class="finetype" id="input-type-student" value="percentage" type="radio" <?php echo set_radio('account_type', 'percentage', set_value('percentage')); ?> /><?php echo $this->lang->line('percentage'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="input-type" class="row">
                                                    <div class="col-sm-6 col-lg-6 col-md-12">
                                                        <label class="radio-inline">
                                                            <input name="account_type" class="finetype" id="input-type-tutor" value="fix" type="radio" <?php echo set_radio('account_type', 'fix', set_value('fix')); ?> />
                                                            <?php echo $this->lang->line('fix_amount'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-6 col-md-12">
                                                        <label class="radio-inline">
                                                            <input name="account_type" class="finetype" id="input-type-tutor" value="cumulative" type="radio" <?php echo set_radio('account_type', 'cumulative', set_value('cumulative')); ?> />
                                                            <?php echo $this->lang->line('cumulative'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-6 col-md-12" id="percentage_input">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('percentage') ?> (%)</label><small class="req"> *</small>
                                            <input id="fine_percentage" name="fine_percentage" placeholder="" type="text" class="form-control" value="<?php echo set_value('fine_percentage'); ?>" />
                                            <span class="text-danger"><?php echo form_error('fine_percentage'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-6 col-md-12" id="fix_amount_input">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)</label><small class="req"> *</small>
                                            <input id="fine_amount" name="fine_amount" placeholder="" type="text" class="form-control" value="<?php echo set_value('fine_amount'); ?>" />
                                            <span class="text-danger"><?php echo form_error('fine_amount'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="cumulative_table" hidden>
                                        <input type="hidden" name="count" id="count" value="0">

                                           <table class="table table-striped table-bordered table-hover border-0 mb0">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('per_day'); ?></label>
                                                            <div class="material-switch ">
                                                                <input id="fine_per_day" name="fine_per_day" type="checkbox" class="chk" value="checked" />
                                                                <label for="fine_per_day" class="label-info-success"></label>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th><button type="button" class="btn btn-info pull-right btn-xs" onclick="add_row()"><?php echo $this->lang->line('add_fine'); ?></button></th>
                                                </tr>
                                                <tr>
                                                    <th width="45%"><?php echo $this->lang->line('total_overdue'); ?></th>
                                                    <th width="45%"><?php echo $this->lang->line('fine_amount'); ?></th>
                                                    <th width="10%"><?php echo $this->lang->line('action'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="finetable">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-lg-9 col-md-<?php
                                if ($this->rbac->hasPrivilege('fees_master', 'can_add')) {
                                      echo "7";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('fees_master_list') . " : " . $this->setting_model->getCurrentSessionName(); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('fees_master_list') . " : " . $this->setting_model->getCurrentSessionName(); ?></div>
                        <div class="mailbox-messages">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example1" data-export-title="<?php echo $this->lang->line('fees_master_list') . " : " . $this->setting_model->getCurrentSessionName(); ?>">
                                    <thead>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('fees_group'); ?></th>
                                            <th width="85%">
                                                <div class="row">
                                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5">
                                                        <?php echo $this->lang->line('fees_code'); ?>
                                                    </div>
                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1">
                                                        <?php echo $this->lang->line('amount'); ?>
                                                    </div>
                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1">
                                                        <div class="px-md-2-5 px-lg-2-5"><?php echo $this->lang->line('fine_type'); ?></div>
                                                    </div>
                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1">
                                                        <?php echo $this->lang->line('due_date'); ?>
                                                    </div>
                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1">
                                                        <?php echo $this->lang->line('per_day'); ?>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2">
                                                        <?php echo $this->lang->line('days') . "-" . $this->lang->line('fine_amount'); ?>
                                                    </div>
                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 white-space-nowrap">&nbsp;</div>
                                                </div>
                                            </th>
                                            <th class="noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($feemasterList as $feegroup) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feegroup->group_name; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                     <ul class="liststyle1 min-w-sm-1000 min-w-lg-990">
                                                        <?php
                                                        foreach ($feegroup->feetypes as $feetype_key => $feetype_value) {
                                                        ?>
                                                            <li>
                                                                <div class="row">
                                                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  white-space-nowrap">
                                                                        <i class="fa fa-money"></i>
                                                                        <?php echo $feetype_value->type . "(" . $feetype_value->code . ")"; ?>
                                                                    </div>
                                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 white-space-nowrap">
                                                                        <?php echo $currency_symbol . amountFormat($feetype_value->amount); ?>
                                                                    </div>
                                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1">
                                                                        <div class="px-md-2-5 px-lg-2-5"><?php echo $this->lang->line($feetype_value->fine_type); ?></div>
                                                                    </div>
                                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1">
                                                                        <?php echo $this->customlib->dateformat($feetype_value->due_date); ?>
                                                                    </div>
                                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1">
                                                                        <?php if ($feetype_value->fine_per_day == 1) {
                                                                            echo $this->lang->line('yes');
                                                                        } else {
                                                                            echo $this->lang->line('no');
                                                                        } ?>
                                                                    </div>
                                                                    <!-- show data on table new code section -->
                                                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2">
                                                                        <?php
                                                                        if ($feetype_value->fine_type == 'cumulative') {
                                                                            foreach ($feetype_value->cumulative_fine_data as $fine_key => $fine_value) {
                                                                                echo "Days: " . $fine_value->overdue_day . " - Fine: " . $currency_symbol . '' . $fine_value->fine_amount;
                                                                                echo "<br>";
                                                                            }
                                                                        } else {
                                                                            echo "Fine: " . $feetype_value->fine_amount;
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <!-- show data on table new code section -->
                                                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 white-space-nowrap ">
                                                                        <?php if ($this->rbac->hasPrivilege('fees_master', 'can_edit')) {   ?>
                                                                            <a href="<?php echo base_url(); ?>admin/feemaster/edit/<?php echo $feetype_value->id ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>&nbsp;
                                                                        <?php }
                                                                        if ($this->rbac->hasPrivilege('fees_master', 'can_delete')) {  ?>
                                                                            <a href="<?php echo base_url(); ?>admin/feemaster/delete/<?php echo $feetype_value->id ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');"><i class="fa fa-remove"></i></a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                    <?php if ($this->rbac->hasPrivilege('fees_group_assign', 'can_view')) { ?>
                                                        <a data-placement="top" href="<?php echo base_url(); ?>admin/feemaster/assign/<?php echo $feegroup->id ?>"
                                                            class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('assign_view_student'); ?>">
                                                            <i class="fa fa-tag"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($this->rbac->hasPrivilege('fees_master', 'can_delete')) { ?>
                                                        <a data-placement="top" href="<?php echo base_url(); ?>admin/feemaster/deletegrp/<?php echo $feegroup->id ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table><!-- /.table -->
                            </div>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    </form>
                </div>
            </div><!--/.col (right) -->
            <!-- left column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function() {
        var account_type = "<?php echo set_value('account_type', 0); ?>";
        load_disable(account_type);
    });

    $(document).on('change', '.finetype', function() {
        calculatefine();
    });

    $(document).on('keyup', '#amount,#fine_percentage', function() {
        var finetype = $('input[name=account_type]:checked', '#form1').val();
        if (finetype === "percentage" || finetype === "fix") {
            calculatefine();
        }
    });

    function load_disable(account_type) {
        if (account_type === "percentage") {
            $('#due_date_error').html(' *');
            $('#fine_amount').prop('readonly', true);
            $('#fine_percentage').prop('readonly', false);
        } else if (account_type === "fix") {
            $('#due_date_error').html(' *');
            $('#fine_amount').prop('readonly', false);
            $('#fine_percentage').prop('readonly', true);
        } else {
            $('#due_date_error').html('');
            $('#fine_amount').prop('readonly', true);
            $('#fine_percentage').prop('readonly', true);
        }
    }

    function calculatefine() {
        var amount = $('#amount').val();
        var fine_percentage = $('#fine_percentage').val();
        var finetype = $('input[name=account_type]:checked', '#form1').val();

        if (finetype === "percentage") {
            $('#due_date_error').html(' *');
            fine_amount = ((amount * fine_percentage) / 100).toFixed(2);
            $('#fine_amount').val(fine_amount).prop('readonly', true);
            $('#fine_percentage').prop('readonly', false);

            $("#percentage_input").show();
            $("#fix_amount_input").show();
            $("#cumulative_table").hide();

        } else if (finetype === "fix") {
            $('#due_date_error').html(' *');
            $('#fine_amount').val("").prop('readonly', false);
            $('#fine_percentage').val("").prop('readonly', true);

            $("#percentage_input").show();
            $("#fix_amount_input").show();
            $("#cumulative_table").hide();

        } else if (finetype === "cumulative") {
            $('#due_date_error').html(' *');
            $('#fine_amount').val("").prop('readonly', false);
            $('#fine_percentage').val("").prop('readonly', true);

            $("#percentage_input").hide();
            $("#fix_amount_input").hide();
            $("#cumulative_table").show();

            $("#finetable").empty();
            counter = [];
            $("#count").val(0);
            add_row();

        } else {
            $('#due_date_error').html('');
            $('#fine_amount').val("");
            $('#fine_percentage').val("");
            $('#fine_amount').prop('readonly', true);
            $('#fine_percentage').prop('readonly', true);

            $("#percentage_input").show();
            $("#fix_amount_input").show();
            $("#cumulative_table").hide();

            $("#finetable").empty();
            counter = [];
            $("#count").val(0);
        }
    }

    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        calculatefine();
    });


    // add row function //
    var counter = [];
    var count = 0;

    function add_row() {
        var num_count = $("#count").val();
        $("#count").val(parseInt(num_count) + parseInt(1));
        var count = $("#count").val();
        counter.push(parseInt(count));
        var over_due = "";
        $("#finetable").append(`
            <tr id="row_${count}">
            <td width="45%"><input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" id="days_overdues_${count}" name="overdue_day[]"   value="" class="form-control"></td>    
            <td width="45%"><input type="text" id="fine_amount_${count}" name="overdue_fine[]"  value="" class="form-control"></td>    
            <td width="10%"><center><i class="fa fa-remove cursor text-danger"  onclick="remove_row(${count})" ></i></center></td>    
            </tr>
            `);
    }

    function remove_row(count) {
        const index = counter.indexOf(count);
        if (index > -1) {
            counter.splice(index, 1);
            $("#row_" + count).remove();
        }
    }

    $("#form1").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var url = $(this).attr("action");
        // var $this = $('.video_submit');

        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: new FormData(this),
            beforeSend: function() {},
            success: function(data) {
                if (!data.status) {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.msg);
                    location.reload();
                }

            },
            error: function(xhr) { // if error occured
                // $this.button('reset');
            },
            complete: function() {
                // $this.button('reset');
            },
        });
    });
    $(document).ready(function() {

        displayDataTable('example1',
  columnDefs = [
    {
      targets: [-1],
      orderable: false,
      className: 'dt-body-right dt-head-right'
    }
  ],
   50,  [],true,true,  true, true, 'landscape', rm_export_btn = []);
        $('.example1222').DataTable({
            "aaSorting": [],
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [-1],
                'sClass': 'dt-body-right'
            }, {
                "bSortable": false,
                "aTargets": [1]
            }],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: 'true',
            language: {
                lengthMenu: '_MENU_', // Only show the dropdown, no text
                processing: '<div class="custom-processing"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw ssss"></i><span class="sr-only">Loading...</span></div>',
                emptyTable: `<div align="center" class="dataTables_empty">No data available in table <br> <br><img src="${baseurl}backend/images/addnewitem.svg" width="150"><br><br> <span class="text-success bolds"><i class="fa fa-arrow-left"></i> Add new record or search with different criteria.</span><div></div></div>`
            },

            dom: '<" dt-layout-row d-flex align-items-center justify-content-between mb-2"' +
                '<"dt-layout-cell dt-layout-start pl-0 "f>' +
                '<"dt-layout-cell dt-layout-end text-end"lB>' +
                '>' +
                'rt' +
                '<" dt-layout-row d-flex align-items-center justify-content-between mt-2"' +
                '<"dt-layout-cell dt-layout-start "i>' +
                '<"dt-layout-cell dt-layout-end  text-end"p>' +
                '>',
            "pageLength": 50,
            "lengthMenu": [
                [50, 100, 200, 500, -1],
                [50, 100, 200, 500, "All"]
            ],
            buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },



                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    className: "btn-pdf",
                    title: $('.download_label').html(),
                    exportOptions: {

                        columns: ["thead th:not(.noExport)"]
                    },

                },


                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function(win) {

                        $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                        $(win.document.body).find('td').addClass('display').css('text-align', 'left');
                        $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                        // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
                        $(win.document.body).find('h1').css('text-align', 'center');
                    },
                    exportOptions: {
                        stripHtml: false,
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ],
            initComplete: function() {
                $('.dt-length').addClass('ss-dt-length');
                $('.dt-search input').addClass('pl-0');
            }
        });
    });
</script>