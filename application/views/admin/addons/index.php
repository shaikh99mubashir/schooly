<style>
    .card-body {
        flex: 1 1 auto;
        padding: 1.25rem;
    }
.addon-services {
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
}

</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('addons'); ?></h3>
                        <div class="box-tools pull-right displaynone">
                            <div class="row pb20">
                                <div class="col-md-offset-1 col-lg-9 col-md-9 col-sm-12">
                                    <form method="POST" action="#" id="searchform">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="table_search" class="form-control pull-right post_search_text" placeholder="<?php echo $this->lang->line('search'); ?>">
                                            <div class="input-group-btn">
                                                <button type="submit" class="btn btn-default  post_search_submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row pb20">
                            <div class="col-md-offset-2 col-lg-8 col-md-8 col-sm-12">
                                <form class="form-inline row" role="form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data" id="local_form">
                                    <?php //echo validation_errors(); ?>
                                    <div class="form-group mb-2 col-lg-9 col-md-9 col-sm-8 relative z-index-1">
                                        <label for="staticEmail2" class="sr-only">Email</label>
                                        <input class="filestyle form-control" data-height="40" type="file" name="file" id="exampleInputFile" style="height:40px;">
										<span class="text-danger"><?php echo form_error('file'); ?></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4">
                                        <button type="submit" class="btn btn-primary mb-2 full-width ms-md-n1-3 ms-lg-n1-3" style="height:42px;"><i class="fa fa-upload"></i> <?php echo $this->lang->line('upload'); ?></button>                                       
                                    </div>     
                                </form>
                            </div>
                        </div>
                        <form class="post-list">
                            <input type="hidden" value="" />
                        </form>
                        <div style="position: relative; min-height: 300px;">                            
                            <div class="modal_loader_div" style="display: none;"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pagination-container"></div>
                                    <div class="pagination-nav pull-right"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div id="addonUpdateModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('update_your_addon'); ?></h4>
            </div>
            <form action="<?php echo site_url('admin/admin/updateAddonVerify'); ?>" method="POST" id="update_addon_verify">
                <div class="modal-body addon_update_modal-body">
                    <div class="error_message">
                    </div>
                    <input type="hidden" name="addon" class="addon_name" value="">
                    <input type="hidden" name="product_id" class="product_id" value="">
                    <div class="form-group">
                        <label class="ainline"><span>Envato Market Purchase Code for Addon Update ( <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"> How to find it?</a> )</span></label>
                        <input type="text" class="form-control" id="input-addon_check_update_envato_market_purchase_code" name="addon_check_update_envato_market_purchase_code">
                        <div id="error" class="input-error text text-danger"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('update'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    var app = {

        Posts: function() {

            /**
             * This method contains the list of functions that needs to be loaded
             * when the "Posts" object is instantiated.
             *
             */
            this.init = function() {
                this.get_items_pagination();
            }

            /**
             * Load items pagination.
             */
            this.get_items_pagination = function() {

                _this = this;

                /* Check if our hidden form input is not empty, meaning it's not the first time viewing the page. */
                if ($('form.post-list input').val()) {
                    /* Submit hidden form input value to load previous page number */
                    data = JSON.parse($('form.post-list input').val());
                    _this.ajax_get_items_pagination(data.page);
                } else {
                    /* Load first page */
                    _this.ajax_get_items_pagination(1, 'name');
                }

                /* Search */
                $(document).on('submit', 'form#searchform', function(e) {
                    e.preventDefault(); // avoid to execute the actual submit of the form.
                    _this.ajax_get_items_pagination(1);
                });

                $(document).on('click', '.pagination-nav .pagination li.unactive', function() {
                    var page = $(this).attr('p');
                    _this.ajax_get_items_pagination(page);
                });
            }

            /**
             * AJAX items pagination.
             */
            this.ajax_get_items_pagination = function(page) {
                if ($(".pagination-container").length) {

                    var post_data = {
                        page: page,
                        search: $('.post_search_text').val(),
                    };

                    $('form.post-list input').val(JSON.stringify(post_data));
                    var data = {
                        data: JSON.parse($('form.post-list input').val()),
                    };

                    $.ajax({
                        url: baseurl + 'admin/addons/getuploaddata',
                        type: 'POST',
                        data: data,
                        dataType: 'JSON',
                        beforeSend: function() {
                            $('.modal_loader_div').css("display", "block");
                        },
                        success: function(response) {
                            $(".pagination-container").html(response.content);
                            $('.pagination-nav').html(response.navigation);
                            $('.modal_loader_div').fadeOut(400);

                        },
                        error: function(xhr) { // if error occured
                            alert("<?php echo $this->lang->line('error_occured_please_try_again'); ?>");
                            $('.modal_loader_div').fadeOut(400);
                        },
                        complete: function() {
                            $('.modal_loader_div').fadeOut(400);
                        }
                    });
                }
            }
        }
    }

    /**
     * When the document has been loaded...
     *
     */
    jQuery(document).ready(function() {
        modal_click_disabled('addonUpdateModal');
        posts = new app.Posts(); /* Instantiate the Posts Class */
        posts.init(); /* Load Posts class methods */

    });

    $(document).on('click', '.install', function(e) {
        e.preventDefault();
        let _button = $(this);
        let product_id = _button.data('productId');
        let directory = _button.data('directory');

        _button.button('loading');
        $.ajax({
            url: base_url + 'admin/addons/install',
            type: "POST",
            data: {
                'addon': directory,
                'product_id': product_id
            },
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                _button.button('loading');
            },
            success: function(data) {

                if (!data.status) {
                    var message = "";

                    errorMsg(data.msg);
                } else {
                    successMsg(data.msg);
                    posts.init();
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                _button.button('reset');
            },
            complete: function() {
                _button.button('reset');
            }
        });
    });

    $('#addonUpdateModal').on('shown.bs.modal', function(e) {

        let product_id = $(e.relatedTarget).data('productId');
        let directory = $(e.relatedTarget).data('directory');
        let addon_name = $(e.relatedTarget).data('addonName');
        $(this).find("input[class=product_id]").val(product_id);
        $(this).find("input[class=addon_name]").val(addon_name);
        $(this).find("button[type=submit]").data('product_id', product_id);
        $(this).find("button[type=submit]").data('directory', directory);
    });


    $("#update_addon_verify").on('submit', (function(e) {
        e.preventDefault();
        let form = $(this);
        let $this = $(this).find("button[type=submit]:focus");
        $this.button('loading');
        let actionUrl = form.attr('action');
        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            dataType: 'json',
              
            beforeSend: function() {
                $('.addon_update_modal-body .error_message').html("");
                $("[class^='input-error']").html("");
                $this.button('loading');
            },
            success: function(response, textStatus, xhr) {
                if (xhr.status != 200) {
                 
                 }else if(xhr.status == 200){

                     if (response.status == 0) {
                         $.each(response.error, function(key, value) {
                        
                        $('#input-' + key).parents('.form-group').find('#error').html(value);
                    });
                     } else if(response.status == 2){
     
                         errorMsg(response.message);
                     }else if(response.status == 1){     
    
                        let product_id = $this.data('product_id');
                        let directory =   $this.data('directory');
                        update_addon(product_id,directory);
                     }

                 }
              
                $this.button('reset');
            },
            error: function(xhr) { // if error occured
             
                $this.button('reset');

                if (xhr.status != 200) {
                    console.log("sdfsdfdsf");
                    var r = jQuery.parseJSON(xhr.responseText);          
               var $newmsgDiv = $("<div/>") // creates a div element              
                         .addClass("alert alert-danger") // add a class
                         .html(r.message);
                     $('.addon_update_modal-body .error_message').append($newmsgDiv);
                 }

            },
            complete: function() {
                $this.button('reset');
            }

        });
    }));

let update_addon=(product_id,directory)=>{
        let _button = $(this);     
        $.ajax({
            url: base_url + 'admin/addons/install',
            type: "POST",
            data: {
                'addon': directory,
                'product_id': product_id
            },
            dataType: 'json',
            cache: false,
            beforeSend: function() {
              
            },
            success: function(data) {

                if (!data.status) {
                    var message = "";

                    errorMsg(data.msg);
                } else {
                    successMsg(data.msg);
                    
                    posts.init();
                    $('#addonUpdateModal').modal('hide');
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
           
            },
            complete: function() {
           
            }
        });
    
}

    $(document).on('click', '.update', function(e) {
        e.preventDefault();
        let _button = $(this);
        let product_id = _button.data('productId');
        let directory = _button.data('directory');

        _button.button('loading');
        $.ajax({
            url: base_url + 'admin/addons/install',
            type: "POST",
            data: {
                'addon': directory,
                'product_id': product_id
            },
            dataType: 'json',

            cache: false,

            beforeSend: function() {
                _button.button('loading');
            },
            success: function(data) {

                if (!data.status) {
                    var message = "";

                    errorMsg(data.msg);
                } else {
                    successMsg(data.msg);
                    posts.init();
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                _button.button('reset');
            },
            complete: function() {
                _button.button('reset');
            }
        });
    });


    $(document).on('click', '.uninstall', function(e) {
		
		if (confirm('<?php echo $this->lang->line("are_you_sure"); ?>')) {
			
			e.preventDefault();
			let _button = $(this);
			let product_id = _button.data('productId');
			let directory = _button.data('directory');

			_button.button('loading');
			$.ajax({
				url: base_url + 'admin/addons/uninstall',
				type: "POST",
				data: {
					'addon': directory,
					'product_id': product_id
				},
				dataType: 'json',
	
				cache: false,
	
				beforeSend: function() {
					_button.button('loading');
				},
				success: function(data) {
	
					if (!data.status) {
						var message = "";
	
						errorMsg(data.msg);
					} else {
						successMsg(data.msg);
						posts.init();
						// location.reload(true);
					}
				},
				error: function(xhr) { // if error occured
					alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
					_button.button('reset');
				},
				complete: function() {
					_button.button('reset');
				}
			});
		}
    });
</script>