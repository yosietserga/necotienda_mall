<?php   
class ControllerChartProduct extends Controller {   
	public function index() {
        
        $query = $this->db->query("SELECT MONTH(date_added) AS month, COUNT(*) AS total 
            FROM `" . DB_PREFIX . "product_stats` 
            WHERE YEAR(date_added) = '" . date('Y') . "'
            GROUP BY MONTH(date_added)
            ORDER BY MONTH(date_added) ASC");
            
        $this->data['visits'] = array();
        for ($i = 0; $i <= 11; $i++) {
            if (isset($query->rows[$i]['month'])) {
                $this->data['visits'][(int)$query->rows[$i]['month']] = (int)$query->rows[$i]['total'];
            } elseif (!isset($this->data['visits'][$i])) {
                $this->data['visits'][$i] = 0;
            }
        }
       
		$this->template = 'chart/product_visits_line.tpl';
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
}