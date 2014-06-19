<li class="nt-editable storeLogoWidget<?php echo ($settings['class']) ? ' '. $settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
	<?php if ($logo) { ?>
		<a title="<?php echo $store; ?>" href="<?php echo str_replace('&', '&amp;', $home); ?>"><img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" width="200" /></a>
		<?php } else { ?>
			<a title="<?php echo $store; ?>" href="<?php echo str_replace('&', '&amp;', $home); ?>"><?php echo $text_store; ?></a>
			<?php } ?>
    <div class="clear"></div><br />
</li>