<?php   
class ControllerChartOrder extends Controller {   
	public function index() {
        
        $query = $this->db->query("SELECT MONTH(date_added) AS month, COUNT(*) AS total 
            FROM `" . DB_PREFIX . "order` 
            WHERE order_status_id > '0' 
                AND YEAR(date_added) = '" . date('Y') . "'
            GROUP BY MONTH(date_added)
            ORDER BY MONTH(date_added) ASC");
            
        $this->data['orders'] = array();
        for ($i = 0; $i <= 11; $i++) {
            if (isset($query->rows[$i]['month'])) {
                $this->data['orders'][(int)$query->rows[$i]['month']] = (int)$query->rows[$i]['total'];
            } elseif (!isset($this->data['orders'][$i + 1])) {
                $this->data['orders'][$i + 1] = 0;
            }
        }
       
		$this->template = 'chart/order_line.tpl';
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
}