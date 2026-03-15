<?php
$response_json = isJSON($userList);
if ($response_json) {

    $userList = (json_decode($userList));
    
    if (!empty($userList)) {
        foreach ($userList->chat_users as $user_key => $user_value) {
            if (!empty($user_value->messages)) {
                $count_noti = getConnectionNotification($userList, $user_value->id);
                ?>
                <li class="contact" data-chat-connection-id="<?php echo $user_value->id; ?>">
                    <div class="wrap">
                        <?php
                        $img = $this->media_storage->getImageURL("uploads/staff_images/no_image.png");
                        if($user_value->user_details->user_type=="staff"){
                            if ($user_value->user_details->image != "") {
                                 $img =  $this->media_storage->getImageURL("uploads/staff_images/" . $user_value->user_details->image);
                            } 
                        }else if($user_value->user_details->user_type=="student"){
                            if ($user_value->user_details->image != "") {
                                $img = $this->media_storage->getImageURL($user_value->user_details->image);
                            } 
                        }else if($user_value->user_details->user_type=="parent"){
                            if ($user_value->user_details->guardian_pic != "") {
                                $img = $this->media_storage->getImageURL($user_value->user_details->guardian_pic);
                            } 
                        }

                        ?>
                        <img src="<?php echo $img; ?>" alt="">
                        <div class="meta">
                            <p class="name">
                                <?php
                                $staff_name= ($user_value->user_details->surname == "")? $user_value->user_details->name : $user_value->user_details->name." ".$user_value->user_details->surname; 
                                if($user_value->user_details->user_type=="staff"){
                                    echo $staff_name." (" . $this->lang->line('staff') . ")";
                                }else if($user_value->user_details->user_type=="student"){
                                    echo $this->customlib->getFullName($user_value->user_details->firstname,$user_value->user_details->middlename,$user_value->user_details->lastname,$sch_setting->middlename,$sch_setting->lastname)." (" . $this->lang->line('student') . ")";
                                }else if($user_value->user_details->user_type=="parent"){
                                     echo $user_value->user_details->guardian_name." (" . $this->lang->line('parent') . ")";
                                }
                                ?>
                            </p>
                            <p class="preview">
                                <?php
                                if ($chat_user->id != $user_value->messages->chat_user_id) {
                                   // echo "<span>" . $this->lang->line('you') . ": </span>";
                                }
                                ?>
                                <?php echo $user_value->messages->message; ?></p>
                        </div>
                    </div>
                    <?php
                    if ($count_noti > 0) {
                        ?>
                        <span class="chatbadge notification_count"><?php echo $count_noti; ?></span> 
                        <?php
                    } else {
                        ?>
                        <span class="chatbadge notification_count displaynone">0</span> 
                        <?php
                    }
                    ?>

                </li>
                <?php
            }
        }
    }
}

function getConnectionNotification($userList, $chat_connection_id) {
    if (!empty($userList->chat_user_notification)) {
        foreach ($userList->chat_user_notification as $notifiction_key => $notifiction_value) {
            if ($notifiction_value->chat_connection_id == $chat_connection_id) {
                return $notifiction_value->no_of_notification;
            }
        }
    }
    return 0;
}
?>