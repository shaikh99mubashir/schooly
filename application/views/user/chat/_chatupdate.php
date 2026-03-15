<?php
if (!empty($updated_chat)) {

    foreach ($updated_chat as $chat_key => $chat_value) {

        $chat_type = ($chat_value->chat_user_id == $chat_user_id) ? 'replies' : 'sent';
        $date_time = ($chat_value->chat_user_id == $chat_user_id) ? 'time_date_send' : 'time_date';
        ?>

        <li class="<?php echo $chat_type; ?> chat_msg" data-msg_id="<?php echo $chat_value->id; ?>" id="reply_<?php echo $chat_value->id;?>">
            <p><?php echo $chat_value->message; ?></p>
            <span class="<?php echo $date_time; ?>"> <?php echo $this->customlib->dateyyyymmddToDateTimeformat($chat_value->created_at); ?></span>
        </li>
        <?php
}
}
?>