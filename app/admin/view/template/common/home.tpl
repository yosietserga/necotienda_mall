<?php echo $header; ?>
<?php if (!empty($error_warning)) { ?><div class="grid_24 warning"><?php echo $error_warning; ?></div><?php } ?>
<?php if (!empty($success)) { ?><div class="grid_24 success"><?php echo $success; ?></div><?php } ?>

<div class="grid_24" id="msg"></div>

<div class="grid_12">
    <div class="box">
        <div class="header">
            <hgroup><h1><?php echo $Language->get('heading_title'); ?></h1></hgroup>
        </div>
        <div class="clear"></div><br />
        <table style="width: 100%;">
            <tr>
              <td style="width: 80%;"><?php echo $Language->get('text_total_sale'); ?></td>
              <td><?php echo $total_sale; ?></td>
            <tr>
              <td><?php echo $Language->get('text_total_sale_year'); ?></td>
              <td><?php echo $total_sale_year; ?></td>
            </tr>
            <tr>
              <td><?php echo $Language->get('text_total_order'); ?></td>
              <td><?php echo $total_order; ?></td>
            </tr>
            <tr>
              <td><?php echo $Language->get('text_total_customer'); ?></td>
              <td><?php echo $total_customer; ?></td>
            </tr>
            <tr>
              <td><?php echo $Language->get('text_total_customer_approval'); ?></td>
              <td><?php echo $total_customer_approval; ?></td>
            </tr>
            <tr>
              <td><?php echo $Language->get('text_total_product'); ?></td>
              <td><?php echo $total_product; ?></td>
            </tr>
            <tr>
              <td><?php echo $Language->get('text_total_review'); ?></td>
              <td><?php echo $total_review; ?></td>
            </tr>
            <tr>
              <td><?php echo $Language->get('text_total_review_approval'); ?></td>
              <td><?php echo $total_review_approval; ?></td>
            </tr>
          </table>
    </div>
</div>
<div class="grid_12">
    <div id="chartOrders"><div style="margin:20% auto;width:50px;text-align: center;"><img src="image/nt_loader.gif" alt="Cargando..." /></div></div>
</div>
<div class="grid_12">
    <div id="chartCustomers"><div style="margin:20% auto;width:50px;text-align: center;"><img src="image/nt_loader.gif" alt="Cargando..." /></div></div>
</div>
<div class="grid_12">
    <div class="box">
        <div class="header">
            <hgroup><h1><?php echo $Language->get('text_latest_10_orders'); ?></h1></hgroup>
        </div>
        <div class="clear"></div><br />
        <table class="list resize">
            <thead>
                <tr>
                    <th><?php echo $Language->get('column_order'); ?></th>
                    <th><?php echo $Language->get('column_name'); ?></th>
                    <th class="left"><?php echo $Language->get('column_status'); ?></th>
                    <th class="left"><?php echo $Language->get('column_date_added'); ?></th>
                    <th class="right"><?php echo $Language->get('column_total'); ?></th>
                    <th class="right"><?php echo $Language->get('column_action'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                    <td class="right"><?php echo $order['order_id']; ?></td>
                    <td class="left"><?php echo $order['name']; ?></td>
                    <td class="left"><?php echo $order['status']; ?></td>
                    <td class="left"><?php echo $order['date_added']; ?></td>
                    <td class="right"><?php echo $order['total']; ?></td>
                    <td class="right">
                    <?php foreach ($order['action'] as $action) { ?>
                        [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                    <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr><td class="center" colspan="6"><?php echo $Language->get('text_no_results'); ?></td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo $footer; ?>