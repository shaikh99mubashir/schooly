<style>
@media print {
    .no_print,
    .close,
    .modal,
    .modal-backdrop,
    .modal-footer {
        display: none !important;
    }
}
</style>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('exam_schedule'); ?></h3>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body table-responsive">

                        <table class="table table-striped table-bordered table-hover dt_table" data-export-title="<?php echo $this->lang->line('exam_schedule'); ?>">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('s_no'); ?></th>
                                    <th><?php echo $this->lang->line('exam'); ?></th>
                                    <th><?php echo $this->lang->line('description'); ?></th>
                                    <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($examSchedule)) {
                                ?>
                                    <?php
                                } else {
                                    $count = 1;
                                    foreach ($examSchedule as $exam) {
                                    ?>
                                        <tr>
                                            <td><?php echo $count; ?>.</td>
                                            <td><?php echo $exam->exam; ?></td>
                                            <td><?php echo $exam->description; ?></td>
                                               <td class="pull-right white-space-nowrap">
                                                <form method="post" action="<?php echo base_url('user/examschedule/printCard') ?>" id="printCard">
                                                    <a class="btn btn-primary btn-xs schedule_modal pull-right" data-toggle="tooltip" title="" data-examname="<?php echo $exam->exam; ?>" data-examid="<?php echo $exam->exam_group_class_batch_exam_id; ?>">
                                                        <i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?>
                                                    </a>
                                                    <input type="hidden" name="admitcard_template" value="<?php echo $get_active_admitcard->id; ?>">
                                                    <input type="hidden" name="post_exam_id" value="<?php echo $exam->exam_group_class_batch_exam_id; ?>">
                                                    <input type="hidden" name="post_exam_group_id" value="<?php echo $exam->exam_group_id; ?>">
                                                    <input type="hidden" class="form-control" name="exam_group_class_batch_exam_student_id[]"
                                                        value="<?php echo $student_id; ?>">
                                                    <?php if ($sch_setting->download_admit_card == 1) { ?>
                                                        <button type="submit" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i>
                                                        </button>
                                                    <?php } ?>

                                                </form>
                                            </td>
                                        </tr>

                                <?php
                                        $count++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="tabledata">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="box-tools pull-right">
                    <div class="dt-buttons btn-group btn-group2 msr-3 pt4">

                        <a class="btn btn-primary btn-xs no_print  bg-transparent border-0" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('print'); ?>" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a>

                    </div>
                </div>
                <h4 class="modal-title"></h4>



            </div>
            <div class="modal-body">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        displayDataTable('dt_table',
            [{
                    targets: [0, -2],

                    className: 'dt-body-left dt-head-left'
                },
                {
                    targets: [-1],
                    orderable: false,
                    className: 'dt-body-right dt-head-right'
                }
            ]
        );
    });


function printDiv() {
    // Clean up classes (optional)
    $('.bg-green').removeClass('label');
    $('.label-danger').removeClass('label');
    $('.label-success').removeClass('label');
    $('.modal-footer').addClass('hide');

    var divElements = document.getElementById('tabledata').innerHTML;
    var oldPage = document.body.innerHTML;

    // Include CSS to hide elements with class "no_print"
    var printStyles = `
           <style>
            @media print {
                .no_print,
                .close,
                .modal,
                .modal-backdrop,
                .modal-footer {
                    display: none !important;
                }
            }
        </style>
    `;

    document.body.innerHTML =
        "<html><head><title>Print</title>" +
        printStyles +
        "</head><body>" +
        divElements +
        "</body></html>";

    window.print();
    document.body.innerHTML = oldPage;
    location.reload(); // Optional: Restore event handlers
}

</script>
<script>
    $(document).ready(function() {
        $('#scheduleModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script type="text/javascript">
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';
    $(document).on('click', '.schedule_modal', function() {
        $('.modal-title').html("");
        var exam_id = $(this).data('examid');
        var examname = $(this).data('examname');

        $('.modal-title').html(examname);
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            type: "post",
            url: base_url + "user/examschedule/getexamscheduledetail",
            data: {
                'exam_id': exam_id
            },
            dataType: "json",
            success: function(response) {
                $('.modal-body').html(response.result);
                $("#scheduleModal").modal('show');


            }
        });
    });
</script>


<script>
    $(document).on('submit', 'form#printCard', function(e) {
        e.preventDefault();
        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var formdata = form.serializeArray();

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function() {
                subsubmit_button.button('loading');
            },
            success: function(response) {
                Popup(response.page);

            },
            error: function(xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                subsubmit_button.button('reset');
            },
            complete: function() {
                subsubmit_button.button('reset');
            }
        });
    });

    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false) {
        var frameDoc = window.open('', 'Print-Window');
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
        setTimeout(function() {
            frameDoc.close();
            if (winload) {
                window.location.reload(true);
            }
        }, 5000);

        return true;
    }
</script>