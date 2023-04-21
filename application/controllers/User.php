<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('order_model');
        $this->load->model('order_model');
        $this->load->model('product_model');
        $this->load->model('sizedetail_model');
        $this->load->model('size_model');
		$this->load->model('transaction_model');
		$this->load->library('form_validation');
		$this->load->helper('form');
                $this->load->model('size_model');
	}

	public function index()
	{
		$this->data['temp']='site/user/index.php';
		$this->load->view('site/layoutsub',$this->data);
	}
	public function register()
	{
		$message_success = $this->session->flashdata('message_success');
		$this->data['message_success'] = $message_success;

		$message_fail = $this->session->flashdata('message_fail');
		$this->data['message_fail'] = $message_fail;

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert" style="padding:5px;border-bottom:0px;">', '</div>');
		if ($this->input->post()) {
			$this->form_validation->set_rules('name','Họ tên','required');
			$this->form_validation->set_rules('email', 'Email đăng nhập', 'required|valid_email|callback_check_email');
			$this->form_validation->set_rules('password','Mật khẩu','required');
			$this->form_validation->set_rules('re_password','Mật khẩu nhập lại','matches[password]');
		
			$this->form_validation->set_rules('phone','Điện thoại','required');
			if ($this->form_validation->run()) {
				$password = $this->input->post('password');
				$data = array();
				$data = array(
					'name' => $this->input->post('name'),
					'email' => $this->input->post('email'),
					'password' => md5($password),
					'address' => $this->input->post('address'),
					'phone' => $this->input->post('phone'),
					'created' => date('Y-m-d H:i:s')
					);
				if ($this->user_model->create($data)) {
					$this->session->set_flashdata('message_success', 'Đăng ký thành công');
				}else{
					$this->session->set_flashdata('message_fail', 'Đăng ký thất bại');
				}
				redirect(base_url('user/register'));
			}
		}
		$this->load->view('site/user/register',$this->data);
	}
	function check_email()
	{
		$email = $this->input->post('email');
		$where = array('email'=> $email);
		if ($this->user_model->check_exists($where))
		{
			$this->form_validation->set_message(__FUNCTION__,'Tên đăng nhập đã tồn tại');
			return FALSE;
		}
		return TRUE;
	}
	public function login()
	{
		$this->form_validation->set_error_delimiters('<p class="text-center" style="padding:5px;border-bottom:0px;">', '</p>');
		$user = $this->session->userdata('user');
		if(isset($user)) {
			redirect(base_url());
		}
		$message_success = $this->session->flashdata('message_success');
		$this->data['message_success'] = $message_success;

		$message_fail = $this->session->flashdata('message_fail');
		$this->data['message_fail'] = $message_fail;
		if ($this->input->post()) {
			$this->form_validation->set_rules('email', 'Email đăng nhập', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'required');
			$this->form_validation->set_rules('login', 'login', 'callback_check_login');
			if ($this->form_validation->run())
			{
				$user = $this->get_info_user();
				$this->session->set_userdata('user', $user);
				redirect(base_url('user/login'));
			}
		}
		$this->load->view('site/user/login',$this->data);
	}
	public function check_login()
	{
		$user = $this->get_info_user();
		if($user) {
			return true;
		}
		$this->form_validation->set_message(__FUNCTION__,'Sai email hoặc mật khẩu');
		return false;
	}
	public function get_info_user()
	{
		$user = array();
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$where = array ('email' => $email, 'password' => md5($password));
		$user = $this->user_model->get_info_rule($where);
		return $user;
	}
	public function logout()
	{
		if ($this->session->userdata('user')) {
			$this->session->unset_userdata('user');
		}
		redirect(base_url());
	}
	public function order()
	{
		$message_success = $this->session->flashdata('message_success');
        $this->data['message_success'] = $message_success;

        $message_fail = $this->session->flashdata('message_fail');
        $this->data['message_fail'] = $message_fail;

        $total = $this->transaction_model->get_total();
        $this->data['total'] = $total;

        $this->load->library('pagination');
        $config = array();
        $base_url = base_url('user/order');
        $per = 10;
        $uri = 4;
        $config = pagination($base_url, $total, $per, $uri);


        $this->pagination->initialize($config);

        $segment = isset($this->uri->segments['4']) ? $this->uri->segments['4'] : NULL;
        $segment = intval($segment);

        $input['limit'] = array($config['per_page'], $segment);

		$str = '1';
		$user = $this->session->userdata('user');
	
        //$input['order'] = array('created', 'DESC');
		$input['where'] = array('user_id' => $user->id);
        $transaction = $this->transaction_model->get_list($input);
        $this->data['transaction'] = $transaction;

		$this->data['temp']='site/user/userorder.php';
		$this->load->view('site/layoutsub',$this->data);
	}
	public function detailorder() {
		$id = $this->uri->segment(3);
        $transaction = $this->transaction_model->get_info($id);
        $this->data['transaction'] = $transaction;

		$input = array();
        $input['where'] = array('transaction_id' => $transaction->id);
        $info = $this->order_model->get_list($input);

        $list_product = array();
        foreach ($info as $key => $value) {
            $this->db->select('`order`.`id` as `order_id`,`product`.`id` as `id`, `product`.`name` as `name`, `image_link`, `order`.`qty` as `qty`, `order`.`amount` as `price`, `sizes`.`name` as `size_name` ');
            $this->db->join('product', 'order.product_id = product.id');
            $this->db->join('sizes', 'order.size_id = sizes.id');
            $where = array();
            $where = array('order.id' => $value->id);
            $list_product[] = $this->order_model->get_info_rule($where);
        }
        $this->data['list_product'] = $list_product;
        $this->data['temp']='site/user/orderdetail';
        $this->load->view('site/user/orderdetail',$this->data);
	}
	public function delorder() {
        $id = $this->uri->segment(3);
        $transaction = $this->transaction_model->get_info($id);

        if (empty($transaction)) {
            $this->session->set_flashdata('message_fail', 'Đơn đặt hàng không tồn tại');
            redirect(admin_url('transaction'));
        }
        $this->data['transaction'] = $transaction;
        if ($id != 0) {
            $transaction = $this->transaction_model->get_info($id);

            $input = array();
            $input['where'] = array('transaction_id' => $transaction->id);
            $info = $this->order_model->get_list($input);

            
            foreach ($info as $key => $value) {
                $sl = 0;
                //cộng số lượng size
                $input1['where'] = array('product_id' => $value->product_id, 'size_id' => $value->size_id);
                $size_detail = $this->sizedetail_model->get_list($input1);
                $sl = $sl +  $value->qty;
                if (sizeof($size_detail) != 0) {
                    $id_update_size = $size_detail[0]->id;
                    $amount = $size_detail[0]->quantity + $value->qty;
                    $data2 = array();
                    $data2 = array(
                        'product_id' => $value->product_id,
                        'size_id' => $value->size_id,
                        'quantity' => $amount,
                    );
                    $this->sizedetail_model->update($id_update_size, $data2);
                } else {
                    $data3 = array();
                    $data3 = array(
                        'product_id' => $value->product_id,
                        'size_id' => $value->size_id,
                        'quantity' => $value->qty,
                    );
                    $sl = $sl +  $value->qty;
                    $this->sizedetail_model->create($data3);
                }
                //Trừ lượt mua
                $product = $this->product_model->get_info($value->product_id);
                $data4 = array();
                $data4['buyed'] = $product->buyed - $sl;
                $this->product_model->update($value->product_id, $data4);
                
                $this->order_model->delete($value->id);
            }
            $this->transaction_model->delete($id);
            $this->session->set_flashdata('message_success', 'Xóa đơn đặt hàng thành công');
			//$message_success = $this->session->flashdata('Xóa đơn đặt hàng thành công');
			//$this->data['message_success'] = $message_success;
        } else {
            $this->session->set_flashdata('message_fail', 'Xóa đơn đặt hàng thất bại');
			//$message_fail = $this->session->flashdata('Xóa đơn đặt hàng thất bại');
			//$this->data['message_fail'] = $message_fail;
        }
        redirect(base_url('user/order'));
    }
}
