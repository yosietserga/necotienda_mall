<?php
class ControllerAccountReview extends Controller {
	private $error = array();

	public function index() {
	   if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect', Url::createUrl("account/review"));
			$this->redirect(Url::createUrl("account/login"));
    	}
        
        $this->language->load('account/review');
		$this->load->model('store/review');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 


      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("account/review"),
        	'text'      => $this->language->get('text_review'),
        	'separator' => $this->language->get('text_separator')
      	);


		$this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');

	   $page =  ($this->request->get['page']) ? $this->request->get['page'] : 1;
	   $data['sort']   = $sort =  ($this->request->get['sort']) ? $this->request->get['sort'] : 'c.date_added';
	   $data['order']  = $order =  ($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
	   $data['limit']  = $limit =  ($this->request->get['limit']) ? $this->request->get['limit'] : 15;
	   $data['keyword']=  ($this->request->get['keyword']) ? $this->request->get['keyword'] : null;
	   $data['letter'] =  ($this->request->get['letter']) ? $this->request->get['letter'] : null;
	   $data['status'] =  ($this->request->get['status']) ? $this->request->get['status'] : null;
       $data['start']  = ($page - 1) * $limit;
       
	   $url = '';
        if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }	
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }		
		if (isset($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; }			
		
		$this->data['letters'] = range('A', 'Z');
        
        $review_total = $this->modelReview->getAllByCustomerTotal($this->customer->getId());
        
        if ($review_total) {
            $reviews = $this->modelReview->getAllByCustomer($this->customer->getId(),$data);
            foreach ($reviews as $key => $value) {
                
                $this->data['reviews'][] = array(
                    'review_id' => $value['review_id'],
                    'product_id'=> $value['product_id'],
                    'product'   => $value['product'],
                    'product_href'=> Url::createUrl("store/product",array('product_id'=>$value['product_id'])),
                    'rating'    => $value['rating'],
                    'status'    => $value['status'] ? $this->language->get('text_approve') : $this->language->get('text_no_approve'),
                    'date_added'=> date('d/m/Y h:i A',strtotime($value['dateAdded'])),
                    'text'      => substr($value['text'],0,130) . "..."
                );
            }
            
            $this->load->library('pagination');
            $pagination = new Pagination(true);
            $pagination->total = $review_total;
            $pagination->page  = $page;
            $pagination->limit = $limit;
    		$pagination->text  = $this->language->get('text_pagination');
            $pagination->url   = Url::createUrl('account/review') . $url . '&page={page}';
            $this->data['pagination'] = $pagination->render();
        }

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/review.tpl';
		} else {
			$this->template = 'default/account/review.tpl';
		}
        

		$this->children = array(
			'common/nav',
			'account/column_left',
			'account/footer',
			'account/header'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
	}
    
    public function read() {
	   if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect', Url::createUrl("account/review"));
			$this->redirect(Url::createUrl("account/login"));
    	}
        
        $this->language->load('account/review');
    	$this->load->model('store/review');
        
        $review_id = $this->request->get['review_id'];
        $review = $this->modelReview->getById($review_id);
        
        if ($review) {
          	$this->document->breadcrumbs = array();
    
          	$this->document->breadcrumbs[] = array(
            	'href'      => Url::createUrl("common/home"),
            	'text'      => $this->language->get('text_home'),
            	'separator' => false
          	); 
    
          	$this->document->breadcrumbs[] = array(
            	'href'      => Url::createUrl("account/review"),
            	'text'      => $this->language->get('text_review'),
            	'separator' => $this->language->get('text_separator')
          	);
    
          	$this->document->breadcrumbs[] = array(
            	'href'      => Url::createUrl("account/review/read") . '&review_id=' . $this->request->get['review_id'],
            	'text'      => $this->language->get('text_review') . $this->request->get['review_id'],
            	'separator' => $this->language->get('text_separator')
          	);
    
    		$this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');
    
            $review_id = $this->request->get['review_id'];
            $this->data['review'] = array();
            if ((int)$review_id) {
    		    $this->data['heading_title'] = "Comentario #" . $review_id;
                $this->data['review']  = $this->modelReview->getById($review_id);
                $this->data['replies'] = $this->modelReview->getReplies($review_id);
                $this->load->auto('image');
                $image = !empty($this->data['review']['image']) ? $this->data['review']['image'] : 'no_image.jpg';
                $this->data['review']['thumb'] = NTImage::resizeAndSave($image, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
                $this->data['review']['description'] = html_entity_decode($this->data['review']['description'], ENT_QUOTES, 'UTF-8');
            }
            
            $this->data['action'] = Url::createUrl("account/review/reply");
            
            // style files
            $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;
            str_replace('%theme%',$this->config->get('config_template'),HTTP_THEME_CSS);
    		if (file_exists(str_replace('%theme%',$this->config->get('config_template'),HTTP_THEME_CSS) . 'neco.form.css')) {
    			$styles[] = array('media'=>'all','href'=> str_replace('%theme%',$this->config->get('config_template'),HTTP_THEME_CSS) . 'neco.form.css');
    		} else {
    			$styles[] = array('media'=>'all','href'=>$csspath . 'neco.form.css');
    		}
            $this->styles = array_merge($styles,$this->styles);
            
            // javascript files
            $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
            $javascripts[] = $jspath."necojs/neco.form.js";
            $this->javascripts = array_merge($this->javascripts, $javascripts);
            
            // SCRIPTS
            $scripts[] = array('id'=>'reviewScripts','method'=>'ready','script'=>
            "$('#reviewForm').ntForm({
                ajax:true,
                url:'{$this->data['action']}',
                success:function(data) {
                    if (data.success) {
                        window.location.href = '". Url::createUrl('account/review/read',array('review_id'=>$review_id)) ."';
                    }
                    if (data.error) {
                        $('#reviewForm').append(data.msg);
                    }
                }
            });
            
            $('#reviewForm textarea').ntInput();
            
            var cache = {};
            $( '#addresses' ).on( 'keydown', function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( 'autocomplete' ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                    var term = request.term;
                    if ( term in cache ) {
                        response( cache[ term ] );
                        return;
                    }
                    $.getJSON( '". Url::createUrl('account/review/getcustomers') ."', {
                        term: extractLast( request.term )
                    }, 
                    function( data, status, xhr ) {
                        cache[ term ] = data;
                        response( data );
                    });
                },
                search: function() {
                    var term = extractLast( this.value );
                    if ( term.length < 2 ) {
                        return false;
                    }
                },
                focus: function() {
                    return false;
                },
                select: function( event, ui ) {
                    
                    var ids = split( $('#to').val() );
                    ids.pop();
                    ids.push( ui.item.id );
                    ids.push( '' );
                    $('#to').val(ids.join( '; ' ));
                    
                    var terms = split( this.value );
                    terms.pop();
                    terms.push( ui.item.value );
                    terms.push( '' );
                    this.value = terms.join( '; ' );
                    
                    return false;
                }
            });");
            
            $scripts[] = array('id'=>'reviewFunctions','method'=>'function','script'=>
            "function split( val ) { 
                return val.split( /;\s*/ ); 
            }
            function extractLast( term ) {
                return split( term ).pop();
            }");
            
            $this->scripts = array_merge($this->scripts,$scripts);
            
    		$this->children = array(
    			'common/nav',
    			'account/column_left',
    			'account/footer',
    			'account/header'
    		);
    
    		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/review_read.tpl')) {
    			$this->template = $this->config->get('config_template') . '/account/review_read.tpl';
    		} else {
    			$this->template = 'default/account/review_read.tpl';
    		}
        } else {
      		$this->document->title      = $this->data['heading_title'] = $this->language->get('text_error');
      		$this->data['continue']     = Url::createUrl('account/review');
	  
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/error/not_found.tpl';
			} else {
				$this->template = 'default/error/not_found.tpl';
			}
			
    		$this->children = array(
    			'account/header',
    			'common/nav',
    			'common/column_left',
    			'common/column_right',
    			'account/footer'
    		);
            
			$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        }
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
    }
    
     public function delete() {
        $this->load->auto('account/review');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelReview->delete($id,$this->customer->getId());
            }
		} else {
            $this->modelReview->delete($_GET['id'],$this->customer->getId());
		}
     }
    
}