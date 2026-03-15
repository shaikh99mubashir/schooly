            <style type="text/css">
                *{margin: 0; padding: 0;}
            </style>
            <body style="color: #000; font-family: Arial; font-size: 14px; line-height: normal;background-color: #e3e3e3; margin: 0; padding: 0;">
                <div style="width:820px; margin: 0 auto;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f5f9; color:#000000; height:100%; width: 800px; margin: 0 auto;">
                        <tr><td valign="top" height="4" bgcolor="#eb9502"></td></tr>
                        <tr><td valign="top" height="4" bgcolor="#f5cb67"></td></tr>
                        <tr>
                            <td valign="top">
                                <table width="95%" align="center" cellpadding="0" cellspacing="0" style="padding-left:20px;padding-right:20px;padding-bottom:20px; padding-top:20px; width: 95%; margin: 15px auto 10px;">
                                    <tr>
                                        <td valign="top">
                                            <img src="<?php echo $this->customlib->getBaseUrl() ?>./uploads/print_headerfooter/email/<?php echo $email_header ; ?>" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%; border-radius: 12px 12px 0px 0px;" width="100%" height="auto">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <table width="100%" cellpadding="0" cellspacing="0" bgcolor="white" style="border:1px solid #ddd;padding-left:20px;padding-right:20px;padding-bottom:5px; padding-top:15px;border-radius: 0px 0px 12px 12px;display: block; background: #fff;">
                                                <tr>
                                                    <td valign="top">
                                                        <h2 style="padding-bottom:5px;color: #000; margin:0; font-size: 20px; font-weight: bold"><?php echo $this->lang->line('subject');?>: <?php echo $subject; ?></h2>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top">
                                                        <p style="padding-bottom:5px;color: #000;"><?php echo $body; ?></p>                                                        
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr><td valign="top" height="10"></td></tr>
                                    <tr><td valign="top" align="center" style="text-align: center; padding-left: 15px; padding-right: 15px; padding-top: 10px; color: #000;"><p><?php echo $email_footer ; ?></p></td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr><td valign="top" height="4" bgcolor="#5eadfd"></td></tr>
                        <tr><td valign="top" height="4" bgcolor="#0073e6"></td></tr>
                    </table>
                </div>
            </body>