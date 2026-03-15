<style type="text/css">
    @media print {
        .no-print {
            visibility: hidden !important;
            display: none !important;
        }
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo  $this->lang->line('content_share_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                            <table class="table table-striped table-bordered table-hover content-list" data-export-title="<?php echo  $this->lang->line('content_share_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('send_to'); ?></th>
                                        <th><?php echo $this->lang->line('share_date'); ?></th>
                                        <th><?php echo $this->lang->line('valid_upto'); ?></th>
                                        <th><?php echo $this->lang->line('shared_by'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="pull-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div id="linkModal" class="modal fade modalmark" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('link'); ?></h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div id="viewShareModal" class="modal fade modalmark" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('shared_contents'); ?></h4>
            </div>
            <div class="modal-body minheight260">

                <div class="modal_loader_div" style="display: none;"></div>

                <div class="modal-body-inner">

                </div>

            </div>
        </div>
    </div>
</div>

<!-- view modal for pdf and other document presentation new code -->
<div id="viewModel" class="modal fade bg-transparent-alpha" role="dialog">
    <div class="modal-dialog full-width mt0">
        <div class="modal-content m-0 bg-transparent modal-body-scroll">
            <div class="modal-gradient">
                <div class="p0 border0">
                    <div class="d-flex pdficon">
                        <a href="#" data-dismiss="modal"><i class="fa fa-arrow-left"></i></a>
                        <span class="text-white text-nowrap2 model_file_name"></span>
                    </div>
                    <a href="#" class="pdfdownload-icon"><i class="fa fa-download"></i></a>
                    <button type="button" class="popupclose" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="h-50"></div>
            <div class="modal-body p0 w-75 mx-auto text-center w-sm-100 ">
            </div>
        </div>
    </div>
</div>
<!-- view modal for pdf and other document presentation new code -->

<script>
    // Fill modal with content from link href
    $("#viewShareModal").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        let recordid = link.data('recordid');
        $.ajax({
            url: baseurl + 'admin/content/getsharedcontents',
            type: "POST",
            data: {
                "share_content_id": recordid
            },
            dataType: 'json',
            beforeSend: function() {
                $('#viewShareModal .modal-body .modal-body-inner').html("");
                $('#viewShareModal .modal-body .modal_loader_div').css("display", "block");

            },
            success: function(data) {
                $('#viewShareModal .modal-body .modal-body-inner').html(data.page);
                $('#viewShareModal .modal-body .modal_loader_div').fadeOut(400);
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {

            }
        });
    });

    (function($) {
        'use strict';
        $(document).ready(function() {

            $('#viewShareModal,#linkModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: false
            });

            initDatatable('content-list', 'admin/content/getsharelist', [], [], 50, [{
                    targets: [-1], // last column
                    orderable: false,
                    className: 'dt-body-right dt-head-right'
                },
                {
                    targets: [-2], // last column
                    orderable: false,
                    className: 'dt-body-right dt-head-right'
                }
            ]);
        });
    }(jQuery))

    $("#linkModal").on('hidden.bs.modal', function() {
        $('#linkModal .modal-body').html("");
    });

    $('#linkModal').on('show.bs.modal', function(event) {
        var filtre = $(event.relatedTarget).data();
        var link_model = $('<a/>', {
            class: 'share_link',
            href: filtre.link,
            target: "_blank"
        }).text(filtre.link);

        var span = $('<span/>', {
            class: "btn btn-xs btn-info ms-2",
            style: "margin-left: 10px;",
            onclick: "copylink()",
            id: "basic-addon1",
            'data-toggle': 'tooltip',
            "data-original-title": "<?php echo $this->lang->line('copy'); ?>"
        }).append($('<i/>', {
            class: "fa fa-copy"

        }));


        var sss = $('<div/>').append(link_model).append(span);

        $('.modal-body', this).html(sss);

    });

    function copylink() {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($('a.share_link').text()).select();
        document.execCommand("copy");
        $temp.remove();
    }
</script>


<script type="text/javascript">
    var branch_base_url = "<?php echo $branch_url; ?>";

    $(document).on('click', '.div_image', function() {
        let fileType = $(this).data('filetype');
        let real_name = $(this).data('realname');
        let file_upload_name = $(this).data('name');
        let filepath = $(this).data('path');
        let recordId = $(this).data('recordid');

        $('.pdfdownload-icon').attr('href', baseurl + "admin/content/download_content/" + recordId);
        $('.model_file_name').text(real_name);
        let modal_view = false;

        if (fileType == "jpg" || fileType == "jpeg" || fileType == "png" || fileType == "svg" || fileType == "webp" || fileType == "gif") {
            modal_view = true;;
            var img = $('<img />', {
                id: 'image',
                width: 'auto',
                height: 'auto',
                class: 'img-fluid',
                src: branch_base_url + filepath + file_upload_name,
                alt: real_name
            });
            $('#viewModel .modal-body').html(img)
        } else if (fileType == "pdf") {
            modal_view = true;
            var pdf = $('<embed />', {
                src: branch_base_url + filepath + file_upload_name + "#toolbar=0",
                width: '100%',
                height: '100vh',

            });
            $('#viewModel .modal-body').html(pdf)

        } else if (fileType == "mp4" || fileType == "webm" || fileType == "3gp" || fileType == "m4a") {
            modal_view = true;
            var video = $('<video />', {
                src: branch_base_url + filepath + file_upload_name,
                width: '100%',
                height: '80vh',
                controls: 'controls',

            });
            $('#viewModel .modal-body').html(video)

        } else if (fileType == "video") {
            modal_view = true;
            var youtubeID = YouTubeGetID(file_upload_name);

            content_popup = '<object data="https://www.youtube.com/embed/' + youtubeID + '" width="100%" height="400"></object>';
            $('#viewModel .modal-body').html(content_popup);

        } else if (fileType == "mp4" || fileType == "webm" || fileType == "3gp" || fileType == "m4a") {
            modal_view = true;
            var video = $('<video />', {
                src: branch_base_url + filepath + file_upload_name,
                controls: 'controls',

            });
            $('#viewModel .modal-body').html(video)

        }
        if (modal_view) {
            $('#viewModel').modal('show');
        }
    });
</script>