<div class="container_16"><div id="header" class="grid_16 section"><h1>Header</h1></div></div>
<div class="container_16"><div id="nav" class="grid_16 section"><h1>Nav</h1></div></div>
<div class="clear"></div>
<div id="maincontent">
    <div class="aside section" id="column_left"><h1>Left</h1></div>
    <div class="grid_13 section" id="content"><h1>Content</h1></div>
</div>
<div class="container_16"><div id="footer" class="grid_16 section"><h1>Footer</h1></div></div>

<script>
$(function(){
   $(document.createElement('button')).text("Agregar Bloque").attr('id','btnAddBlockHeader').appendTo("#header");
   $(document.createElement('button')).text("Agregar Menu").attr('id','btnAddBlockNav').appendTo("#nav");
   $(document.createElement('button')).text("Agregar Widget").attr('id','btnAddWidgetLeft').appendTo("#column_left");
   $(document.createElement('button')).text("Agregar Bloque").attr('id','btnAddBlockContent').appendTo("#content");
   $(document.createElement('button')).text("Agregar Bloque").attr('id','btnAddBlockFooter').appendTo("#footer");

    $("#btnAddBlockHeader, #btnAddBlockNav, #btnAddBlockContent, #btnAddBlockFooter").click(function(){
        $("#blocksWrapper").dialog();
   });
   
   $("#header,#nav,#content,#footer").click(function(){
        setSectionActive(this.id);
        $("#header,#nav,#content,#footer").removeClass("sectionActive");
        $(this).addClass("sectionActive");
   });
});
</script>