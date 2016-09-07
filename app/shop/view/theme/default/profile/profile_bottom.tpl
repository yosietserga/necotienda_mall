            <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>

        </section>
    </section>
</div>
<script type="text/javascript">
function DropDown(el) {
    this.dd = el;
    this.initEvents();
}
DropDown.prototype = {
    initEvents : function() {
        var obj = this;
        obj.dd.on('click', function(event){
            $(this).toggleClass('active');
            event.stopPropagation();
        });	
    }
};

$(function() {
    var dd = new DropDown( $('#dd') );
    $(document).click(function() {
        $('.wrapper-dropdown').removeClass('active');
    });
});
</script>
<?php echo $footer; ?>