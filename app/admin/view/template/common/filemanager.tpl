<!DOCTYPE html><html lang="es"><head>
    <title><?php echo $title; ?></title>
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <base href="<?php echo $base; ?>" />
    <link rel="stylesheet" type="text/css" href="css/filemanager.css" />
    <link rel="stylesheet" type="text/css" href="css/screen.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="css/filemanager.css" />
    
    <script src="js/vendor/modernizr-2.6.1.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script>window.$ || document.write('<script src="js/vendor/jquery.min-1.8.1.js"><\/script>')</script>
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
    <div class="container_24" style="z-index: 10000;">
    
        <div id="tabs" class="grid_24">
            <ul>
                <li id="browser">Browser</li>
                <li id="frompc">Desde el PC</li>
                <!--<li id="fromurl">Desde URL</li>-->
            </ul>
        </div>
        
        <div class="tabs grid_24" id="tabbrowser">
            <div id="menu">
                <a id="create" class="button" style="background-image: url('image/filemanager/folder.png');"><?php echo $Language->get('button_folder'); ?></a>
                <a id="delete" class="button" style="background-image: url('image/filemanager/edit-delete.png');"><?php echo $Language->get('button_delete'); ?></a>
                <a id="move" class="button" style="background-image: url('image/filemanager/edit-cut.png');"><?php echo $Language->get('button_move'); ?></a>
                <a id="copy" class="button" style="background-image: url('image/filemanager/edit-copy.png');"><?php echo $Language->get('button_copy'); ?></a>
                <a id="rename" class="button" style="background-image: url('image/filemanager/edit-rename.png');"><?php echo $Language->get('button_rename'); ?></a>
                <a onclick="$('.tabs').hide();$('#tabfrompc').show();" class="button" style="background-image: url('image/filemanager/upload.png');"><?php echo $Language->get('button_upload'); ?></a>
                <a id="refresh" class="button" style="background-image: url('image/filemanager/refresh.png');"><?php echo $Language->get('button_refresh'); ?></a>
            </div>
            
            <div class="clear"></div>
            
            <div class="grid_4" id="column_left"></div>
            <form id="form"><div class="grid_18" id="column_right"></div></form>
        </div>
        
        <div class="clear"></div>
        
        <div class="tabs" id="tabfrompc">
            <div class="clear"></div>
            <p><b>Instruccioes:</b> Antes de subir los archivos, debes seleccionar la carpeta donde quieres guardarlos. Haz click <a href="#" title="Seleccionar carpeta" onclick="$('.tabs').hide();$('#tabfrompc').show();return false;">aqu&iacute;</a> para seleccionar la carpeta.</p>
            <a class="uploadStart">Comenzar a Subir</a>
            <div class="clear"></div>
            <input id="fileupload" type="file" name="files[]" multiple="multiple" />
            <input type="hidden" id="directoryForUpload" value="" />
            <div id="dropHere"><p>Arrastra los archivos hasta aqu&iacute;<br /><span>Selecciona los archivos que quieres subir y arr&aacute;stralos hasta aqu&iacute;</span></p></div>
            <ul id="filesUploaded"></ul>
            <div id="scrollDown"></div>
        </div>
        
        <div class="clear"></div>
        <!--
        <div class="tabs" id="tabfromurl">
        
        </div>
        -->
    </div>
<script type="text/javascript" src="js/vendor/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/plugins.js"></script>
<script type="text/javascript" src="js/vendor/jstree/jquery.tree.min.js"></script>
<script type="text/javascript" src="js/vendor/fileUploader/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="js/vendor/fileUploader/jquery.fileupload.js"></script>
<!--[if gte IE 8]><script src="js/cors/jquery.xdr-transport.js"></script><![endif]-->
<script type="text/javascript">
jQuery(function(){
    jQuery(".tabs").hide();
    jQuery("#tabbrowser").show();
    jQuery("#tabs li").click(function(){
        jQuery(".tabs").hide();
        jQuery("#tab" + this.id).show();  
    });
    
    var windowHeight = jQuery(window).height();
    jQuery("#dropHere").css({
        height:(windowHeight * 60 / 100) + 'px'
    });
    
	jQuery('#column_left').tree({
		data: { 
			type: 'json',
			async: true, 
			opts: { 
				method: 'POST', 
				url: '<?php echo $Url::createAdminUrl("common/filemanager/directory"); ?>'
			} 
		},
		selected: 'top',
		ui: {		
			theme_name: 'classic'
		},	
		types: { 
			'default': {
				clickable: true,
				creatable: false,
				renameable: true,
				deletable: false,
				draggable: false,
				max_children: -1,
				max_depth: -1,
				valid_children: 'all'
			}
		},
		callback: {
			beforedata: function(NODE, TREE_OBJ) { 
				if (NODE == false) {
					TREE_OBJ.settings.data.opts.static = [ 
						{
							data: 'image',
							attributes: { 
								'id': 'top',
								'directory': ''
							}, 
							state: 'closed'
						}
					];
					return { 'directory': '' } 
				} else {
					TREE_OBJ.settings.data.opts.static = false;
                    jQuery("#directoryForUpload").val( jQuery(NODE).attr('directory') );
					return { 'directory': jQuery(NODE).attr('directory') } 
				}
			},		
			onselect: function (NODE, TREE_OBJ) {
			    jQuery("#directoryForUpload").val( jQuery(NODE).attr('directory') );
				jQuery.ajax({
					url: '<?php echo $Url::createAdminUrl("common/filemanager/files"); ?>',
					type: 'POST',
					data: 'directory=' + encodeURIComponent(jQuery(NODE).attr('directory')),
					dataType: 'json',
					success: function(json) {
						html = '<ul>';
						if (json) {
							for (i = 0; i < json.length; i++) {
								html += '<li id="file_'+ i +'">';
								name = '';
								filename = json[i]['filename'];
								for (j = 0; j < filename.length; j = j + 15) {
									name += filename.substr(j, 15) + '<br>';
								}
								name += json[i]['size'];
								html += '<a file="' + json[i]['file'] + '">';
								html += '<img src="' + json[i]['thumb'] + '" title="' + json[i]['filename'] + '" />';
								html += '<p>' + name + '</p>';
								html += '</a>';
								html += '<input type="checkbox" name="filess[]" value="' + json[i]['file'] + '" style="display:none" />';
								html += '<a class="selected"></a>';
								html += '<a class="copy" onclick="copy(\'file_'+ i +'\',\'' + json[i]['file'] + '\')"></a>';
								html += '<a class="rename" onclick="rename(\'file_'+ i +'\',\'' + json[i]['file'] + '\')"></a>';
								html += '<a class="move"></a>';
								html += '<a class="delete" onclick="eliminar(\'file_'+ i +'\',\'' + json[i]['file'] + '\')"></a>';
						        html += '</li>';
							}
						}
						html += '</ul>';
						jQuery('#column_right').html(html);
                        
                    	jQuery('#column_right li').on('click', function (e) {
                    	   if(e.shiftKey) {
                                var firstLi = (jQuery('#column_right .liSelected:first-child').index()) ? jQuery('#column_right .liSelected:first-child').index() : jQuery('#column_right li:first-child').index();
                                var lastLi = jQuery(this).index();
                                jQuery('#column_right li').each(function(){
                                    if (jQuery(this).index() >= firstLi && jQuery(this).index() <= lastLi) {
                                        jQuery(this).addClass('liSelected').find('.selected').show();
                                        jQuery(this).find('input').attr('checked','checked');
                                    } else {
                                        jQuery(this).removeClass('liSelected').find('.selected').hide();
                                        jQuery(this).find('input').removeAttr('checked');
                                    }
                                });
                           } else {
                        		$(this).toggleClass('liSelected');
                        		$(this).find('.selected').toggle();
                                var inputCheck = $(this).find('input');
                                if (inputCheck.attr('checked')) {
                                    inputCheck.removeAttr('checked');
                                } else {
                                    inputCheck.attr('checked','checked');
                                }
                           }
                    	});

                    	$('#column_right li').on('dblclick', function () {
                    	   var filename = $(this).find('a:eq(0)').attr('file');
                    		<?php if ($fckeditor) { ?>
                    		window.opener.CKEDITOR.tools.callFunction(1, '<?php echo $directory; ?>' + filename);
                    		self.close();	
                    		<?php } else { ?>
                    		parent.$('#<?php echo $field; ?>').attr('value', 'data/' + filename);
                    		parent.$('#dialog').dialog('close');
                    		parent.$('#dialog').remove();	
                    		<?php } ?>
                    	});
                        
                        function eliminar(path) {
                            $.post('<?php echo $Url::createAdminUrl("common/filemanager/delete"); ?>',{'path':path},function(json) {
                                if (json.success) {
                				    var tree = $.tree.focused();
                					tree.select_branch(tree.selected);
 					            }
                				if (json.error) {
                				    alert(json.error);
                				}
                			});	
                            
                           	
                        }		
		                  
					}
				});
			}
		}
	});	
			
    if (window.FileReader && Modernizr.draganddrop) {
        $('#fileupload').hide().fileupload({
            dataType: 'json',
            url: '<?php echo $Url::createAdminUrl("common/filemanager/uploader"); ?>',
            add: function (e, data) {
                $('#fileupload').fileupload({
                    formData:{directory:encodeURIComponent($("#directoryForUpload").val())}
                });
                $("#dropHere").fadeOut();
                $.each(data.files, function (index, file) {
                    var html = '';
                    if ((file.size / 1024) > 1000) {
                        var size = Math.round(file.size / 1024 / 1024) + ' MB';
                    } else {
                        var size = Math.round(file.size / 1024) + ' KB';
                    }
                    
                    if (file.size > 5000000) {
                        var clase = 'good';
                    } else {
                        var clase = 'error';
                    }
                    
                    var ext = (file.name.substring(file.name.lastIndexOf("."))).toLowerCase();
                    var allowed = [
                        ".gif", 
                        ".jpg", 
                        ".png", 
                        ".doc", 
                        ".docx", 
                        ".xls", 
                        ".xlsx", 
                        ".txt", 
                        ".csv", 
                        ".swf", 
                        ".flv",
                        ".mp3", 
                        ".pdf"
                    ];
                    var goon = false;
                    for (var i = 0; i < allowed.length; i++) {
                        if (allowed[i] == ext) {
                            goon = true;
                            break;
                        }
                    }
                    
                    if (goon) {
                        goon = false;
                        allowed = [
                            'image/jpg',
                            'image/jpeg',
            				'image/pjpeg',
            				'image/png',
            				'image/x-png',
            				'image/gif',
                            "text/csv", 
                            "text/comma-separated-values", 
                            "text/tab-separated-values", 
                            "text/plain",
    					    'application/x-shockwave-flash',
            				'application/msword',
            				'application/pdf',
            				'application/x-pdf',
            				'application/msexcel',
            				'audio/x-mpeg'
                        ];
                        for (var i = 0; i < allowed.length; i++) {
                            
                            if (file.type.toLowerCase() == 'image/jpg' || file.type.toLowerCase() == 'image/jpeg' || file.type.toLowerCase() == 'image/pjpeg' || file.type.toLowerCase() == 'image/png' || file.type.toLowerCase() == 'image/x-png' || file.type.toLowerCase() == 'image/gif' && file.size > 3000) {
                                fileSuggest = "El tamano en KB del archivo es muy grande para utilizarlo en la web. Esto puede causar que el sitio web tarde cargando los contenidos.";
                            }
                            
                            if (allowed[i] == file.type.toLowerCase()) {
                                goon = true;
                                break;
                            }
                        }
                    }
                    
                    if (goon) {
                        var clase = 'good';
                    } else {
                        var clase = 'error';
                    }
                    
                    
                    /* 
                    html += '<div class="grid_2">';
                    html += '<img src="'+ file.mozFullPath +'" alt="'+ file.name +'" />'; 
                    html += '</div>';
                    */
                    html += '<div class="grid_8">';
                    html += file.name;
                    html += '</div>';
                    html += '<div class="grid_2">';
                    html += '<p class="'+ clase +'">'+ size +'</p>';
                    html += '</div>';
                    html += '<div class="grid_2">';
                    html += '<p class="'+ clase +'">'+ file.type +'</p>';
                    html += '</div>';
                    html += '<div class="progress-bar blue stripes grid_6">';
                    html += '<span style="width:0%"></span>';
                    html += '</div>';
                    html += '<div class="grid_2"><a onclick="">[ Eliminar ]</a></div>';
                    
                    var li = $(document.createElement('li'))
                        .css({display:'none'})
                        .html(html)
                        .appendTo(('#filesUploaded'));
                        
                    data.context = li;
                    li.fadeIn();
                });
                
                $(".uploadStart").on('click',function(e){
                    data.submit();
                });
                    
            },
            progress: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                data.context.find('.progress-bar span').css({
                    'width':progress + '%',
                    display:'block'
                });
            },
            dragover: function(e) {
                $("#dropHere").addClass('dropHere');
            },
            done: function (e, data) {
                var that = $(this).data('fileupload');
                if (data.context) {
                    data.context.each(function(index) {
                        $('html,body').animate(
                            {
                                scrollTo:$("#scrollDown").offset().top
                            }
                            ,500
                        );
                        var preview = $(this).find('div:nth-child(1)');
                        var msgWrapper = $(this).find('div:nth-child(4)');
                        $(this).find('.progress-bar span').css({
                            'width':'100%',
                        }).parent().fadeOut();
                        
                        file = (typeof(data.result)=='object') ? data.result : {error:'Error: no se pudo obtener el resultado del archivo'};
                        
                        if (file.error) {
                            msgWrapper.removeClass('progress-bar').html('<b>Error: '+ file.error +'</b>').fadeIn();
                        } else {
                            msgWrapper.removeClass('progress-bar').html('<b>'+ file.success +'</b>').fadeIn();
                            preview.fadeOut(function(e){
                                $(this).html('<img src="'+ file.thumbnail_url +'" alt="'+ file.name +'" />&nbsp;'+ file.name +'').fadeIn();
                            });
                        }
                    });
                } else {
                    if ($.isArray(data.result)) {
                        $.each(data.result, function (index, file) {
                            if (data.maxNumberOfFilesAdjusted && file.error) {
                                that._adjustMaxNumberOfFiles(1);
                            } else if (!data.maxNumberOfFilesAdjusted && !file.error) {
                                that._adjustMaxNumberOfFiles(-1);
                            }
                        });
                        data.maxNumberOfFilesAdjusted = true;
                    }
                    
                    that._transition(template).done(
                        function () {
                            data.context = $(this);
                            that._trigger('completed', e, data);
                        }
                    );
                }
            }
        });
    } else {
        $("#dropHere").hide();
    }
    		
	$('#create').on('click', function () {
		var tree = $.tree.focused();
		if (tree.selected) {
			$('#dialog').remove();
			html  = '<div id="dialog">';
			html += '<?php echo $Language->get('entry_folder'); ?> <input type="text" name="name" value="" /> <input type="button" value="Submit" />';
			html += '</div>';
			$('#column_right').prepend(html);
			$('#dialog').dialog({
				title: '<?php echo $Language->get('button_folder'); ?>',
				resizable: false
			});	
			
			$('#dialog input[type=\'button\']').on('click', function () {
				$.ajax({
					url: '<?php echo $Url::createAdminUrl("common/filemanager/create"); ?>',
					type: 'POST',
					data: 'directory=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							tree.refresh(tree.selected);
						} else {
							alert(json.error);
						}
					}
				});
			});
		} else {
			alert('<?php echo $error_directory; ?>');	
		}
	});
    
	$('#delete').on('click', function () {
	   if (confirm('Est\u00E1s seguro que deseas eliminar estos ficheros?')) {
	       
		path = $("#form").serialize();
        console.log(path);
		if (path) {
			$.ajax({
				url: '<?php echo $Url::createAdminUrl("common/filemanager/delete"); ?>',
				type: 'POST',
				data: $("#form").serialize(),
				dataType: 'json',
				success: function(json) {
					if (json.success) {
						var tree = $.tree.focused();
						tree.select_branch(tree.selected);
					}
					if (json.error) {
						alert(json.error);
					}
				}
			});				
		} else {
			var tree = $.tree.focused();
			if (tree.selected) {
				$.ajax({
					url: '<?php echo $Url::createAdminUrl("common/filemanager/delete"); ?>',
					type: 'POST',
					data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							tree.select_branch(tree.parent(tree.selected));
							tree.refresh(tree.selected);
						} 
						if (json.error) {
							alert(json.error);
						}
					}
				});			
			} else {
				alert('<?php echo $error_select; ?>');
			}			
		}
	   }
	});
	
	$('#move').on('click', function () {
		$('#dialog').remove();
		
		html  = '<div id="dialog">';
		html += '<?php echo $Language->get('entry_move'); ?> <select name="to"></select> <input type="button" value="Submit" />';
		html += '</div>';

		$('#column_right').prepend(html);
		
		$('#dialog').dialog({
			title: '<?php echo $Language->get('button_move'); ?>',
			resizable: false
		});

		$('#dialog select[name=\'to\']').load('<?php echo $Url::createAdminUrl("common/filemanager/folders"); ?>');
		
		$('#dialog input[type=\'button\']').on('click', function () {
			path = $('#column_right a.selected').attr('file');
							 
			if (path) {																
				$.ajax({
					url: '<?php echo $Url::createAdminUrl("common/filemanager/move"); ?>',
					type: 'POST',
					data: 'from=' + encodeURIComponent(path) + '&to=' + encodeURIComponent($('#dialog select[name=\'to\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							var tree = $.tree.focused();
							
							tree.select_branch(tree.selected);
							
							alert(json.success);
						}
						
						if (json.error) {
							alert(json.error);
						}
					}
				});
			} else {
				var tree = $.tree.focused();
				
				$.ajax({
					url: '<?php echo $Url::createAdminUrl("common/filemanager/move"); ?>',
					type: 'POST',
					data: 'from=' + encodeURIComponent($(tree.selected).attr('directory')) + '&to=' + encodeURIComponent($('#dialog select[name=\'to\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							tree.select_branch('#top');
								
							tree.refresh(tree.selected);
							
							alert(json.success);
						}						
						
						if (json.error) {
							alert(json.error);
						}
					}
				});				
			}
		});
	});

	$('#copy').bind('click', function () {
		$('#dialog').remove();
		
		html  = '<div id="dialog">';
		html += '<?php echo $Language->get('entry_copy'); ?> <input type="text" name="name" value=""> <input type="button" value="Submit">';
		html += '</div>';

		$('#column_right').prepend(html);
		
		$('#dialog').dialog({
			title: '<?php echo $Language->get('button_copy'); ?>',
			resizable: false
		});
		
		$('#dialog select[name=\'to\']').load('<?php echo $Url::createAdminUrl("common/filemanager/folders"); ?>');
		
		$('#dialog input[type=\'button\']').bind('click', function () {
			path = $('#column_right a.selected').attr('file');
							 
			if (path) {																
				$.ajax({
					url: '<?php echo $Url::createAdminUrl("common/filemanager/copy"); ?>',
					type: 'POST',
					data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							var tree = $.tree.focused();
							
							tree.select_branch(tree.selected);
							
							alert(json.success);
						}						
						
						if (json.error) {
							alert(json.error);
						}
					}
				});
			} else {
				var tree = $.tree.focused();
				
				$.ajax({
					url: '<?php echo $Url::createAdminUrl("common/filemanager/copy"); ?>',
					type: 'POST',
					data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							tree.select_branch(tree.parent(tree.selected));
							
							tree.refresh(tree.selected);
							
							alert(json.success);
						} 						
						
						if (json.error) {
							alert(json.error);
						}
					}
				});				
			}
		});	
	});
	
	$('#rename').bind('click', function () {
		$('#dialog').remove();
		
		html  = '<div id="dialog">';
		html += '<?php echo $Language->get('entry_rename'); ?> <input type="text" name="name" value=""> <input type="button" value="Submit">';
		html += '</div>';

		$('#column_right').prepend(html);
		
		$('#dialog').dialog({
			title: '<?php echo $Language->get('button_rename'); ?>',
			resizable: false
		});
		
		$('#dialog input[type=\'button\']').bind('click', function () {
			path = $('#column_right a.selected').attr('file');
							 
			if (path) {		
				$.ajax({
					url: '<?php echo $Url::createAdminUrl("common/filemanager/rename"); ?>',
					type: 'POST',
					data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							var tree = $.tree.focused();
					
							tree.select_branch(tree.selected);
							
							alert(json.success);
						} 
						
						if (json.error) {
							alert(json.error);
						}
					}
				});			
			} else {
				var tree = $.tree.focused();
				
				$.ajax({ 
					url: '<?php echo $Url::createAdminUrl("common/filemanager/rename"); ?>',
					type: 'POST',
					data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
								
							tree.select_branch(tree.parent(tree.selected));
							
							tree.refresh(tree.selected);
							
							alert(json.success);
						} 
						
						if (json.error) {
							alert(json.error);
						}
					}
				});
			}
		});		
	});
	
	$('#refresh').on('click', function () {
		var tree = $.tree.focused();
		tree.refresh(tree.selected);
	});	
});
</script>
</body>
</html>