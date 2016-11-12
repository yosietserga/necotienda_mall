</div>
<div id="footer">
  <?php echo $Language->get('text_footer'); ?>
</div>

<?php if ($javascripts) foreach ($javascripts as $js) echo '<script src="'. $js .'"></script>'; ?>
<script>
$(function(){
    var menu = '<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inicio'; ?>';
    menuOn('#' + menu);
});
</script>
<script type="text/javascript" src="js/vendor/fileUploader/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="js/vendor/fileUploader/jquery.fileupload.js"></script>
<?php if ($scripts) echo $scripts; ?>
</body></html>