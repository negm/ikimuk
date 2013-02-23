<?php
/*********************************************************


*********************************************************/
require_once $_SERVER["DOCUMENT_ROOT"]."/class/settings.php";
class ip2country {
        private $settings;
	public $mysql_host;
	public $db_name;
	public $db_user;
	public $db_pass;
	public $table_name="ip2country";

	private $ip_num=0;
	private $ip='';
	private $country_code='';
	private $country_name='';
        public  $delivery_charge='';
        public $phone_code="";
        private $con=false;
        
        public function __construct() {
		$this->settings = new settings();
                $this->mysql_host = $this->settings->config["host"];
                $this->db_name = $this->settings->config["database"];
                $this->db_user = $this->settings->config["username"];
                $this->db_pass = $this->settings->config["password"];
	}
	function ip2country()
	{
		$this->set_ip();
	}

	public function get_ip_num()
	{
		return $this->ip_num;
	}
	public function set_ip($newip='')
	{
		if($newip=='')
		$newip=$this->get_client_ip();

		$this->ip=$newip;
		$this->calculate_ip_num();
		$this->country_code='';
		$this->country_name='';
                $this->phone_code='';
	}
	public function calculate_ip_num()
	{
		if($this->ip=='')
		$this->ip=$this->get_client_ip();

		$this->ip_num=sprintf("%u",ip2long($this->ip));
	}
	public function get_country_code($ip_addr='')
	{
		if($ip_addr!='' && $ip_addr!=$this->ip)
		$this->set_ip($ip_addr);

		if($ip_addr=='')
		{
			if($this->ip!=$this->get_client_ip())
			$this->set_ip();
		}

		if($this->country_code!='')
		return $this->country_code;

		if(!$this->con)
		$this->mysql_con();
                $this->ip_num = mysqli_escape_string($this->con, $this->ip_num);
		$sq='SELECT * FROM ip2nation_countries c,ip2nation i WHERE i.ip < INET_ATON("'.$this->ip.'") AND c.country_code = i.country_code ORDER BY  i.ip DESC LIMIT 0,1';
		$r= mysqli_query($this->con, $sq);

		if(!$r)
		return '';

		$row=mysqli_fetch_assoc($r);
		$this->close();
		$this->country_name=$row['country_name'];
		$this->country_code=$row['country_code'];
                $this->phone_code = $row["phone_code"];
                $this->delivery_charge = $row["delivery_charge"];
		return $row['country_code'];
	}

	public function get_country_name($ip_addr='')
	{
		$this->get_country_code($ip_addr);
		return $this->country_name;
	}

	public function get_client_ip()
	{
		$v='';
		$v= (!empty($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR'] :((!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR']: @getenv('REMOTE_ADDR'));
		if(isset($_SERVER['HTTP_CLIENT_IP']))
		$v=$_SERVER['HTTP_CLIENT_IP'];
		return htmlspecialchars($v,ENT_QUOTES);
	}

	public function mysql_con()
	{
            $this->con = mysqli_connect($this->mysql_host, $this->db_user, $this->db_pass, $this->db_name);
            return true;

	}
	public function get_mysql_con()
	{
		return $this->con;
	}

	public function close()
	{
		if(!(($this->con == NULL) || ($this->con == FALSE))) {
                mysqli_close($this->con);
		$this->con=false;
                }
	}
}
?>
