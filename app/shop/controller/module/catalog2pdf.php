<?php  
class ControllerModuleCatalog2Pdf extends Controller {
	protected $pdfFile = "";
	
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
        if ($this->request->hasQuery('r')) {
            switch(strtolower($this->request->getQuery('r'))) {
                case 'store/category':
                    $ids = explode("_",$this->request->getQuery('path'));
                    $category_id = $ids[ (count($ids) - 1) ];
                    $this->data['href'] = Url::createUrl("module/catalog2pdf/generate",
                        array(
                            'o'=>'category',
                            'id'=>(int)$category_id,
                        ));
                    break;
                case 'store/manufacturer':
                    $this->data['href'] = Url::createUrl("module/catalog2pdf/generate",
                        array(
                            'o'=>'manufacturer',
                            'id'=>(int)$this->request->getQuery('manufacturer_id'),
                        ));
                    break;
                case 'store/product':
                    $this->data['href'] = Url::createUrl("module/catalog2pdf/generate",
                        array(
                            'o'=>'product',
                            'id'=>(int)$this->request->getQuery('product_id'),
                        ));
                    break;
                case 'store/product':
                    break;
            }
		}
        
		$this->language->load('module/catalog2pdf');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->id = 'catalog2pdf';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/catalog2pdf.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/catalog2pdf.tpl';
		} else {
			$this->template = 'default/module/catalog2pdf.tpl';
		}
		
		$this->render();
  	}
    
    protected function renderFile() {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($this->pdfFile));
// to open in browser
        header('Content-Disposition: inline; filename=' . basename($this->pdfFile));
        readfile($this->pdfFile);
    }
    
	public function generate() {
	   $this->language->load('module/catalog2pdf');
        if ($this->request->hasQuery('o') && $this->request->hasQuery('id')) {
            
            $cache_file = DIR_CACHE. 
            $this->language->get('text_catalog') ."_". 
            $this->config->get('config_language_id') ."_". 
            $this->request->getQuery('o') ."_". 
            $this->request->getQuery('id') .".pdf";
                
            str_replace(" ","_",$cache_file);
            
            if (file_exists($cache_file)) {
                $this->pdfFile = $cache_file;
                $this->renderFile();
            } else {
                $this->load->model('store/product');
                $this->load->model("marketing/newsletter");
                $this->load->library('tcpdf/config/lang/spa');
                $this->load->library('tcpdf/tcpdf');
                
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                
                $pdf->SetCreator("Powered By NecoTienda&reg;");
                $pdf->SetTitle($this->config->get('config_name'));
                $pdf->SetAuthor($this->config->get('config_name'));
                
                $pdf->SetHeaderData($this->config->get('config_logo'), PDF_HEADER_LOGO_WIDTH, $this->config->get('config_owner'), $this->config->get('config_name'));
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                $pdf->setLanguageArray($l);
                $pdf->SetFont('dejavusans', '', 12);
                
                $newsletter = $this->modelNewsletter->getById(1);
                
                switch(strtolower($this->request->getQuery('o'))) {
                    case 'category':
                        $category_id = $this->request->getQuery('id');
                        $limit  = $this->modelProduct->getTotalProductsByCategoryId($category_id);
                        
                        if ($limit > 1000) $limit = 1000;
                        
                        $results = $this->modelProduct->getProductsByCategoryId($category_id, 'p.sort_order', 'ASC', 0, $limit);
                        $html = str_replace("{%products%}",$this->renderHtml($results),htmlspecialchars_decode($newsletter['htmlbody']));
                        
                        $pdf->SetSubject($this->config->get('config_owner') ." ". $this->language->get('text_category') ." #". $category_id);
                        $pdf->AddPage();
                        $pdf->writeHTML($html, true, false, true, false, '');
                        
                        break;
                    case 'manufacturer':
                        $manufacturer_id = $this->request->getQuery('id');
                        $limit  = $this->modelProduct->getTotalProductsByManufacturerId($manufacturer_id);
                            
                        if ($limit > 1000) $limit = 1000;
                        
                        $results = $this->modelProduct->getProductsByManufacturerId($manufacturer_id, 'p.sort_order', 'ASC', 0, $limit);
                        $html = str_replace("{%products%}",$this->renderHtml($results),htmlspecialchars_decode($newsletter['htmlbody']));
                            
                        $pdf->SetSubject($this->config->get('config_owner') ." ". $this->language->get('text_manufacturer') ." #". $manufacturer_id);
                        $pdf->AddPage();
                        $pdf->writeHTML($html);
                        break;
                    case 'product':
                        break;
                    case 'search':
                        break;
                    default:
                        return false;
                        break;
                }
                //Close and output PDF document
                $pdf->Output($cache_file, 'F');
                $this->pdfFile = $cache_file;
                $this->renderFile();
            }
		}
  	}
    
    protected function renderHtml($results) {
        $this->language->load('module/catalog2pdf');
        $this->load->model('store/product');
        $this->load->model('store/review');
        $this->load->library('BarcodeQR');
        
        $qr = new BarcodeQR;
        $html = "<table>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<td>". $this->language->get('column_image') ."</td>";
        $html .= "<td>". $this->language->get('column_description') ."</td>";
        $html .= "<td>". $this->language->get('column_model') ."</td>";
        $html .= "<td>". $this->language->get('column_rating') ."</td>";
        $html .= "<td>". $this->language->get('column_price') ."</td>";
        $html .= "<td>". $this->language->get('column_qr') ."</td>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
           
        foreach ($results as $result) {
            $image   = !empty($result['image']) ? $result['image'] : 'no_image.jpg';
            $rating  = ($this->config->get('config_review')) ? $this->modelReview->getAverageRating($result['product_id']) : false;
            $special = false;
            $discount= $this->modelProduct->getProductDiscount($result['product_id']);
                        			
            if ($discount) {
                $price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                $special = $this->modelProduct->getProductSpecial($result['product_id']);
                if ($special) {
                    $special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
                }						
            }
                                    
            $qr->url(Url::createUrl('store/product',array('product_id'=>$result['product_id'])));
            $qr->draw(100,DIR_IMAGE . "cache/" . str_replace(".","_","qr_code_product_". $result['product_id']).'.png');
                                    
            $html .= '<tr>';
            $html .= '<td><img src="'. NTImage::resizeAndSave($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')) .'" /></td>';
            $html .= '<td>'. $result['name'] .'</td>';
            $html .= '<td>'. $result['model'] .'</td>';
            $html .= '<td><img src="'. HTTP_IMAGE .'stars_'. (int)$rating .'.png" /></td>';
            $html .= '<td>'. $price;
            if ($special) {
                $html .= '<br /><b>'. $this->language->get('text_special') .'</b>';
            }
            $html .= '</td>';
            $html .= '<td><img src="'. HTTP_IMAGE . "cache/" . str_replace(".","_","qr_code_product_". $result['product_id']).'.png' .'" /></td>';
            $html .= '</tr>';
        }
        
        $html .= "</tbody>";
        $html .= "<tfoot>";
        $html .= "<tr>";
        $html .= '<td colspan="6" style="text-align:center">Powered By NecoTienda&reg;</td>';
        $html .= "</tr>";
        $html .= "</tfoot>";
        $html .= "</table>";
        return $html;                        
    }
}
