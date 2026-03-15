<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <style>
                .color-btn i {
                    font-size: 24px;
                }

                #colorPickerContainer {
                    position: absolute;
                    display: none;
                    z-index: 1000;
                }
            </style>
            <?php $this->load->view('setting/_settingmenu'); ?>
           <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css" />
            <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
            <!-- left column -->
             
            <div class="col-lg-9 col-md-8 col-sm-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('backend_theme'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->

                    <?php $theme_Setting =  $this->customlib->getCurrentThemeSetting();                    
                  
                    ?>

                    <form role="form" id="theme_setting_form" method="post" enctype="multipart/form-data">
                        <div class="">

                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <input type="hidden" name="theme_navigation" value="<?php echo $result->theme_navigation; ?>" class="theme_navigation">
                            <input type="hidden" name="theme_color" value="<?php echo $result->theme_color; ?>" class="theme_color">
                            <input type="hidden" name="theme_type" value="<?php echo $result->theme_type; ?>" class="theme_type">
                            <input type="hidden" name="theme_shadow" value="<?php echo set_value('theme_shadow', !empty($result->theme_shadow) ? $result->theme_shadow : NULL); ?>" class="theme_shadow">
                            <input type="hidden" name="theme_background" value="<?php echo set_value('theme_background', !empty($result->theme_background) ? $result->theme_background : 'light-mode'); ?>" class="theme_background">
                            <input type="hidden" name="theme_content" value="<?php echo set_value('theme_content', !empty($result->theme_content) ? $result->theme_content : 'container-fluid'); ?>" class="theme_content">
                            <input type="hidden" name="theme_font_color" value="<?php echo set_value('theme_font_color', !empty($result->theme_font_color) ? $result->theme_font_color : '#fff'); ?>" class="theme_font_color">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="template-form-label mt5"><?php echo $this->lang->line('theme_mode_light_dark');?></h3> 
                                        <ul class="template-custom-option">
                                            <?php
                                            if ($theme_Setting['theme_background'] == "light-mode") {
                                            ?>
                                                <li><a onclick="toggleTheme()" type="button" id="toggle-btn" class="template-custom-large">
                                                        <i id="theme-icon" class="icon-sun"></i>
                                                    </a></li>
                                            <?php
                                            } elseif ($theme_Setting['theme_background'] == "dark") {
                                            ?>
                                                <li><a onclick="toggleTheme()" type="button" id="toggle-btn" class="template-custom-large dark-mode-button">
                                                        <i id="theme-icon" class="icon-moon-star"></i>
                                                    </a></li>
                                            <?php
                                            } else {
                                            ?>
                                                <li><a onclick="toggleTheme()" type="button" id="toggle-btn" class="template-custom-large">
                                                        <i id="theme-icon" class="icon-sun"></i>
                                                    </a></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                  
                                        <h3 class="template-form-label"><?php echo $this->lang->line('skins_shadow_bordered');?></h3>
                                        <ul class="template-custom-option">
                                            <?php
                                            if ($theme_Setting['theme_shadow'] == "shadow-applied") {
                                            ?>
                                                <li>
                                                    <a type="button" id="toggleBtn" class="template-custom-large theme_shadow_btn">
                                                        <i id="iconskins" class="icon-grid-2x2"></i>
                                                    </a>
                                                </li>
                                            <?php
                                            } else {
                                            ?>

                                                <li>
                                                    <a type="button" id="toggleBtn" class="template-custom-large theme_shadow_btn">
                                                        <i id="iconskins" class="bi bi-shadows"></i>
                                                    </a>
                                                </li>
                                            <?php
                                            }

                                            ?>

                                        </ul>
                                        <h3 class="template-form-label"><?php echo $this->lang->line('side_menu_navigation');?></h3>
                                        <ul class="template-custom-option">
                                                <li>
                                                    <a type="button" id="toggleBtn1" class="template-custom-large theme_navigation_btn">
                                                       
                                                        <i id="icon_theme_navigation"  class="icon-unfold-horizontal"></i>

                                                    </a>
                                                </li>
                                            <?php 
                                            if ($theme_Setting['theme_navigation'] == "expanded") {
                                            ?>
                                             
                                            <?php
                                            } else {
                                            ?>
                                                
                                            <?php
                                            }
                                            ?>
                                        </ul>

                                        <h3 class="template-form-label displayinline"><?php echo $this->lang->line('primary_color');?></h3>

                                        <ul class="template-custom-option pb10">
                                            <li class="d-inline-flex">
                                                <a type="button" class="theme-link <?php echo ($theme_Setting['theme_color'] == "#7367f0") ? "active" : ""; ?>" data-theme="#7367f0"><span class="color-btn" style="background-color: #7367f0;"></span></a>
                                            </li>
                                            <li class="d-inline-flex">
                                                <a type="button" class="theme-link <?php echo ($theme_Setting['theme_color'] == "#2092EC") ? "active" : ""; ?>" data-theme="#2092EC"><span class="color-btn" style="background-color: #2092EC;"></span></a>
                                            </li>
                                            <li class="d-inline-flex">
                                                <a type="button" class="theme-link <?php echo ($theme_Setting['theme_color'] == "#FFAB1D") ? "active" : ""; ?>" data-theme="#FFAB1D"><span class="color-btn" style="background-color: #FFAB1D;"></span></a>
                                            </li>
                                            <li class="d-inline-flex">
                                                <a type="button" class="theme-link <?php echo ($theme_Setting['theme_color'] == "#0D9394") ? "active" : ""; ?>" data-theme="#0D9394"><span class="color-btn" style="background-color: #0D9394;"></span></a>
                                            </li>
                                            <li class="d-inline-flex">
                                                <a type="button" class="theme-link <?php echo ($theme_Setting['theme_color'] == "#EB3D63") ? "active" : ""; ?>" data-theme="#EB3D63"><span class="color-btn" style="background-color: #EB3D63;"></span></a>
                                            </li>
                                            <li class="d-inline-flex">
                                                <?php 
                                                if($theme_Setting['theme_type'] == "custom"){
                                                    $theme_color_custom=$theme_Setting['theme_color'];
                                                }else{
                                                     $theme_color_custom='#f0852eff';
                                                }
                                                ?>
                                                <a type="button" class="theme-link colorPickerBtn <?php echo ($theme_Setting['theme_type'] == "custom") ? "active" : ""; ?>" id="colorPickerBtn" data-theme="<?php echo $theme_color_custom;?>"><span class="color-btn" ><i class="fa fa-pencil" style="color:<?php echo $theme_color_custom;?>"></i>
                                                        <div id="colorPickerContainer"></div>
                                                    </span></a>

                                            </li>

                                        </ul>
                                        <h3 class="template-form-label"><?php echo $this->lang->line('box_content_compact_wide');?></label></h3>
                                        <ul class="template-custom-option d-inline-flex">
                                            <?php
                                            if ($theme_Setting['theme_content'] == "container-fluid") {
                                            ?>
                                               <li>
                                                <a type="button" class="togglewide template-custom-large togglewide2" id="compacticonicon">
                                                    <i id="content-icon" class="icon-maximize"></i>
                                                </a>
                                            </li>
                                            <?php
                                            } else {
                                            ?>
                                            <li>
                                                <a type="button" class="togglewide template-custom-large togglewide2" id="compacticonicon">
                                                    <i id="content-icon" class="icon-minimize"></i>
                                                </a>
                                            </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <?php
                            if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                            ?>
                                <button type="submit" class="btn btn-primary submit_schsetting pull-right edit_theme_setting" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('saving'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                            <?php
                            }
                            ?>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div>
        </div><!--/.col (left) -->
        <!-- right column -->
</div>
</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->

</div><!-- /.content-wrapper -->

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    let theme_data_array = <?php echo json_encode($this->customlib->getCurrentThemeSetting()); ?> || {};


    $(document).on('submit', '#theme_setting_form', function(e) {
        e.preventDefault(); // prevent default form submission

        var $form = $(this);
        var $submitButton = $form.find('button[type="submit"]');

        $.ajax({
            url: baseurl + "schsettings/savebackendtheme",
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',

            // 🔄 Before sending request
            beforeSend: function() {
                console.log($submitButton.html());
                $submitButton.button('loading');
            },

            // ✅ On success
            success: function(data) {
                console.log(data);
                if (data.status === "fail") {
                    let message = "";
                    $.each(data.error, function(index, value) {
                        message += value + "<br>";
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                }
                $submitButton.button('reset');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                errorMsg("An unexpected error occurred. Please try again.");
                $submitButton.button('reset');
            },
            complete: function() {
                $submitButton.button('reset');
            }
        });
    });
</script><script>
  document.addEventListener('DOMContentLoaded', () => {
    const buttonContainer = document.getElementById('colorPickerBtn');

    const pickr = Pickr.create({
      el: buttonContainer, // mount inside the button
      theme: 'nano',
      default: (theme_data_array.theme_type === "custom") ? theme_data_array.theme_color : '#f0852eff',
      useAsButton: true, // let Pickr create a button inside
      closeOnScroll: true,
      components: {
        preview: true,
        opacity: false,
        hue: true,
        interaction: {
          input: true,
          clear: false,
          save: false
        }
      }
    });

    pickr.on('change', (color) => {
      const hex = color.toHEXA().toString();
      const root = document.documentElement;

      // Update CSS variables
      root.style.setProperty('--bs-primary', hex);
      root.style.setProperty('--bs-btn-border-color', hex);
      root.style.setProperty('--bs-primary-hover', hex);
      root.style.setProperty('--bs-hover-color', hex);
      root.style.setProperty('--bs-alert-bg', hex);
      root.style.setProperty('--custom-hover-theme', hex);

     let set_color_picker=isTooLight(hex) ? '#000' : '#fff'
      // Determine contrast
      root.style.setProperty('--bs-primary-contrast', set_color_picker);

      // Update hidden inputs
      const themeColorInput = document.querySelector('.theme_color');
      const themeType = document.querySelector('.theme_type');
      const theme_font_color = document.querySelector('.theme_font_color');
      if (themeColorInput) themeColorInput.value = hex;
      if (themeType) themeType.value = 'custom';
      if (theme_font_color) theme_font_color.value = set_color_picker;

      // Update icon color inside the button (optional)
      const span = buttonContainer.querySelector('.color-btn');
      if (span) {
        const icon = span.querySelector('i');
        if (icon) {
          icon.style.color = hex;
        }
      }

      // Add border to the button
      buttonContainer.style.border = `2px solid ${hex}`;
    });

    // Utility to check color brightness
    function isTooLight(hex) {
      const rgb = hex.replace('#', '').match(/.{2}/g).map(x => parseInt(x, 16));
      const brightness = (rgb[0] * 299 + rgb[1] * 587 + rgb[2] * 114) / 1000;
      return brightness > 180;
    }
  });
</script>
