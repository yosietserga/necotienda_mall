<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<?php if ($success) { ?><div class="grid_24"><div class="message success"><?php echo $success; ?></div></div><?php } ?>
<div class="box">
    <h1><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $Language->get('button_save'); ?></a>
    </div>
        
    <div class="clear"></div>
             
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
            <tr>
                <td><?php echo $Language->get('entry_email_seller_new_order'); ?></td>
                <td>
                    <select name="marketing_email_seller_new_order" title="<?php echo $Language->get('help_email_seller_new_order'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_seller_new_order) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_new_customer'); ?></td>
                <td>
                    <select name="marketing_email_new_customer" title="<?php echo $Language->get('help_page_new_customer'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_new_customer) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_happy_birthday'); ?></td>
                <td>
                    <select name="marketing_email_happy_birthday" title="<?php echo $Language->get('help_page_happy_birthday'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_happy_birthday) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_add_balance'); ?></td>
                <td>
                    <select name="marketing_email_add_balance" title="<?php echo $Language->get('help_page_add_balance'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_add_balance) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_subtract_balance'); ?></td>
                <td>
                    <select name="marketing_email_subtract_balance" title="<?php echo $Language->get('help_page_subtract_balance'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_subtract_balance) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_new_payment'); ?></td>
                <td>
                    <select name="marketing_email_new_payment" title="<?php echo $Language->get('help_page_new_payment'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_new_payment) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_new_comment'); ?></td>
                <td>
                    <select name="marketing_email_new_comment" title="<?php echo $Language->get('help_page_new_comment'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_new_comment) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_new_reply'); ?></td>
                <td>
                    <select name="marketing_email_new_reply" title="<?php echo $Language->get('help_page_new_reply'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_new_reply) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_new_contact'); ?></td>
                <td>
                    <select name="marketing_email_new_contact" title="<?php echo $Language->get('help_page_new_contact'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_new_contact) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_new_order'); ?></td>
                <td>
                    <select name="marketing_email_new_order" title="<?php echo $Language->get('help_page_new_order'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_new_order) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_order_pdf'); ?></td>
                <td>
                    <select name="marketing_email_order_pdf" title="<?php echo $Language->get('help_page_order_pdf'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_order_pdf) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_update_order'); ?></td>
                <td>
                    <select name="marketing_email_update_order" title="<?php echo $Language->get('help_page_update_order'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_update_order) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_old_order'); ?></td>
                <td>
                    <select name="marketing_email_old_order" title="<?php echo $Language->get('help_page_old_order'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_old_order) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_page_recommended_products'); ?></td>
                <td>
                    <select name="marketing_email_recommended_products" title="<?php echo $Language->get('help_page_recommended_products'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_recommended_products) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_email_promote_product'); ?></td>
                <td>
                    <select name="marketing_email_promote_product" title="<?php echo $Language->get('help_email_promote_product'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_promote_product) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_email_invite_friends'); ?></td>
                <td>
                    <select name="marketing_email_invite_friends" title="<?php echo $Language->get('help_email_invite_friends'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_invite_friends) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_email_payment_instructions'); ?></td>
                <td>
                    <select name="marketing_email_payment_instructions" title="<?php echo $Language->get('help_email_payment_instructions'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_email_payment_instructions) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $Language->get('entry_customer_added_successfully'); ?></td>
                <td>
                    <select name="marketing_customer_added_successfully" title="<?php echo $Language->get('help_customer_added_successfully'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $marketing_customer_added_successfully) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php echo $footer; ?>