<?php
if (!empty($chatList)) {
    foreach ($chatList as $chat_key => $chat_value) {
        $chat_type = ($chat_value->chat_user_id != $chat_user->id) ? 'replies' : 'sent';
        $date_time = ($chat_value->chat_user_id != $chat_user->id) ? 'time_date_send' : 'time_date';
        ?>
        <?php
        if ($chat_value->is_first) {
            ?>
            <li class="text text-center" style="margin: 0px;">
                <h4 class="chattitle"><span class=""><?php echo $this->lang->line('you_are_now_connected_on_chat') ?></span></h4>
            </li>
            <?php
        } else {
            ?>
            <li class="<?php echo $chat_type; ?> chat_msg" data-msg_id="<?php echo $chat_value->id; ?>" id="reply_<?php echo $chat_value->id; ?>">
                <p><?php if($chat_type=="replies"  && $delete_chat_enable==1){ ?>
                    <i class="fa fa-remove text-danger point remove_btn"  data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('delete') ?>"  onclick="delete_msg(<?php echo $chat_value->id; ?>)"></i>
                <?php  }  ?>
                <?php echo $chat_value->message; ?></p>
                <span class="<?php echo $date_time; ?>"> <?php echo $this->customlib->dateyyyymmddToDateTimeformat($chat_value->created_at); ?></span>
            </li>
            <?php
        }
        ?>
        <?php
    }
}
?>