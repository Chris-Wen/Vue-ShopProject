<?php
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';    
     
$allow_origin = array(    
    'http://shop.73776.com',    	
	'http://192.168.1.162'   	//本机本地直接请求
);    
     
if(in_array($origin, $allow_origin)){    
    header('Access-Control-Allow-Origin: '.$origin );    	
	header("Access-Control-Allow-Credentials: true");
	header("Content-Type:application/json;charset=utf8");
}  

class WebShopAction extends Action {
	public function test() {
		// $name = $_POST['name']or die('{"code":401,"msg":"type required"}');

		foreach (getallheaders() as $name => $value) {
			echo "$name: $value\n";
		}

	}	

	public function getgoods() { 		//商品列表 ， 多种排序规则（积分范围，积分升降序，价格升降序）
		$page = I('page') or die('{"code":401,"msg":"page required"}');		//当前页
		@$type = I('type');				//首页进入（取值 1: 积分5k以下, 2: 积分5k~8k, 3: 积分8k以上 ）
		@$rule = I('rule');				//排序规则 （取值 1：积分低到高 2：积分高到低 3：价格低到高 4：价格高到低）
		@$num = I('num');				//每次查询数据量

		if( !$num || intval($num)<15 ) { $num = 15; } 

		$total = M()->query("SELECT id FROM rc_shop_goods WHERE status=1 AND sort=3 ");
		$pageTotal =ceil(count($total)/$num);

		$start = ($page-1)*$num ;

		if ( $page > $pageTotal ){ return;}
		$sql = " SELECT id, sname, logo, price, score, realprice, title, freight_type, ship_address, pic FROM rc_shop_goods  WHERE status=1 AND sort=3 ";
		if ($rule) {
			switch ($rule) {
				case "1":
					$output['list'] = M()->query(" $sql ORDER BY score ASC LIMIT  $start ,$num ");
					break;
				case "2":
					$output['list'] = M()->query(" $sql ORDER BY score DESC LIMIT  $start ,$num ");
					break;
				case "3":
					$output['list'] = M()->query(" $sql ORDER BY price ASC LIMIT  $start ,$num ");
					break;
				case "4":
					$output['list'] = M()->query(" $sql ORDER BY price DESC LIMIT  $start ,$num ");
					break;
			}
		} else {
			switch ($type) {
				case "1":
					$total = M()->query("SELECT id FROM rc_shop_goods WHERE status=1 AND sort=3 AND score<=5000");
					$output['list'] = M()->query(" $sql AND score<=5000 ORDER BY optime ASC LIMIT  $start ,$num ");
					break;
				case "2":
					$total = M()->query("SELECT id FROM rc_shop_goods WHERE status=1 AND sort=3 AND score>5000 AND score<8000");
					$output['list'] = M()->query(" $sql AND score>5000 AND score<8000 ORDER BY optime ASC LIMIT  $start ,$num ");
					break;
				case "3":
					$total = M()->query("SELECT id FROM rc_shop_goods WHERE status=1 AND sort=3 AND score>=8000");
					$output['list'] = M()->query(" $sql AND score>=8000 ORDER BY optime ASC LIMIT  $start ,$num ");
					break;
				default:
					$output['list'] = M()->query(" $sql ORDER BY optime ASC LIMIT  $start ,$num ");
			}
		}

		$output['totalPages'] = $pageTotal;
		if ( $output['list'] ) {
			$output['page'] = $page + 1;
			$output['code'] = 200;
			$output['msg'] = "select succ";
		} else {
			$output['code'] = 201;
			$output['msg'] = "select err";
		}

		echo json_encode($output);
	} 


	public function getIndexInfo() {	//首页信息展示
		$list['banner'] = M()->query("SELECT logo,link FROM rc_shop_goods WHERE status=1 AND sort=1 ORDER BY sort1"); 

		$list['hotSales'] = M()->query("SELECT id, sname, logo, price, score, realprice, detail, title, freight_type, pic FROM rc_shop_goods WHERE status=1 AND sort=2 ORDER BY sort1");

		echo json_encode($list);
	}


	public function dailySignIn() {   		 //日常签到 （+ 8 积分）
		$uid = session('uid') or die('{"code":401,"msg":"user need login"}');
		$currentTime = date("Y/m/d");
	
		$result = M()->query("SELECT sign_time FROM rc_shop_user WHERE uid = $uid ");

		if ( $currentTime==$result[0]["sign_time"] ){
			echo( '{"code":201, "msg":"user already sign in"}' );
		} else {

			$update = M('shop_user')->data(array('sign_time'=>$currentTime))->where(" uid = $uid ")->save();
			$update = M('shop_user')->where(" uid = $uid ")->setInc('score',8);

			// echo  $update;
			if ($update) {
				echo ('{"code":200, "msg":"sign in succ"}');
			} else {
				echo ('{"code":201, "msg":"sign in err"}');
			}
		}

	}

	public function verify() {			//生成验证码
		//去除BOM头  或直接将php文件保存格式改为UTF-8 不带BOM， BOM头只在php输出为图片时会导致出错
		// ob_end_clean();	
		ob_clean();
		import ( "ORG.Util.Image" );
		Image::buildImageVerify ( 4, 1,'png');

	}


	public function register() {		//注册
		$verify = $_POST['verify'] or die('{"code":401,"msg":"verify required"}');
		$uname = $_POST['uname'] or die('{"code":401,"msg":"uname required"}');
		$upwd = $_POST['upwd'] or die('{"code":402,"msg":"upwd required"}');
		
		if ($_SESSION['verify'] != md5($_POST['verify'])) {
			echo ('{"code":301,"msg":"verify failed"}');
			return;
		}

		$userinfo = M('shop_user')->where("uname='".$uname."'")->select();
		if ($userinfo){
			echo ('{"code":201, "msg":"uname same"}');
			return;
		}
		
		$list = M()->query("INSERT INTO rc_shop_user(uname,upwd) VALUES('$uname','$upwd')");
		
		$res = M()->query("SELECT uid FROM rc_shop_user WHERE uname = '$uname'");

		$utoken =  md5(time());
		$data_token['uid'] = $res[0]['uid'];
		$data_token['token'] =$utoken;
		$data_token['token_time'] = time()+86400;
		$data_token['created'] = date ( 'Y-m-d H:i:s' );
		$data_token['logintime'] = date ( 'Y-m-d H:i:s' );
		$insert_token = M('shop_user_token')->add($data_token);
		
		
		$ucookie = hash_hmac("sha256",session('PHPSESSID').time(),md5("zdkjadmin"));
		$data_cookie['uid'] = $res[0]['uid'];
		$data_cookie['cookie'] = $ucookie;
		$data_cookie['cook_times'] = 0;
		$data_cookie['times'] = 0;
		$data_cookie['created'] = date ( 'Y-m-d H:i:s' );
		$insert_cookie = M('shop_user_cookie')->add($data_cookie);
		
		if ( $res&&$insert_token&&$insert_cookie) {        //success
			echo('{"code":200, "msg":"register succ"}');
		} else {           //error
			echo('{"code":500, "msg":"db err"}');
		}  
	}


	public function login() {			//登录
		$verify = $_POST['verify'] or die('{"code":401,"msg":"verify required"}');
		$uname = $_POST['uname'] or die('{"code":401,"msg":"uname required"}');
		$upwd = $_POST['upwd'] or die('{"code":401,"msg":"upwd required"}');

		if ($_SESSION['verify'] != md5($_POST['verify'])) {
			die ('{"code":301,"msg":"verify failed"}');
		}
	
		$res = M("shop_user")->where(array('uname'=>$uname,'upwd'=>$upwd))->getField('uid');
		
		if ( $res) {  
			$score = M("shop_user")->where("uid = $res")->getField('score');
			$res_token =  M('shop_user_token')->where("uid=".$res." and token_time>".time())->find();//获取有效token
			if (!$res_token['token']){
				$utoken =  md5(time());
				$data_token['token'] =$utoken;
				$data_token['token_time'] = time()+86400;
				$data_token['logintime'] = date ( 'Y-m-d H:i:s' );
				$insert_token = M('shop_user_token')->where("uid=".$res)->save($data_token);
			}
			else {
				$utoken = $res_token['token'];
				$data_token['token_time'] = time()+86400;
				$data_token['logintime'] = date ( 'Y-m-d H:i:s' );
				$insert_token = M('shop_user_token')->where("uid=".$res)->save($data_token);
			}
			
			$res_cookie =  M('shop_user_cookie')->where("uid=".$res." and cook_times<1000 ")->find();//获取有效cookie
			if($res_cookie["cookie"]) {
				$ucookie = $res_cookie['cookie'];
			} else {
				$times = M('shop_user_cookie')->where("uid=".$res)->getField('times');
				
				$ucookie = hash_hmac( "sha256", session('PHPSESSID').time(), md5("zdkjadmin"));
				$data_cookie['cookie'] = $ucookie;
				$data_cookie['cook_times'] = 0;

				$update_login_times = M('shop_user_cookie')->where("uid=$res")->setInc('times');
				$insert_cookie = M('shop_user_cookie')->where("uid=".$res)->save($data_cookie);
			}
			
			if ($utoken && $ucookie) {
				session( 'uid', $res );
				session( 'access_token', $utoken );
				cookie( 'PHPSESSID', $ucookie, array('expire'=>3600*3) );

				$data['code'] = 200;
				$data['msg'] = 'login succ';
				$data['uname'] = $uname;
				$data['score'] = $score;
				$data['utoken'] = $utoken;
				$data['ucookie'] = cookie('PHPSESSID');
				echo json_encode($data);
			}
			else 
			{
				echo('{"code":201, "msg":"user login msg Invalid"}');
			}
		}else {           
			echo('{"code":201, "msg":"login err"}');
		}
	}
	
	public function logout() {			//登出
		$uid = session('uid') or die('{"code":402,"msg":"did not login"}');

		$data_token['token_time'] = "";
		$data_token['token'] = "";
		session( null );
		cookie( null );
		$res_token = M('shop_user_token')->where("uid=".$uid)->save($data_token);//token_time 更新
		
		$data_cookie['cook_times'] = "";
		$data_cookie['cookie'] = "";
		$res_cookie = M('shop_user_cookie')->where("uid=".$uid)->save($data_cookie);//token_time 更新
		// echo $res_cookie;
		if ($res_token && $res_cookie){
			echo('{"code":200, "msg":"logout succ"}');
		} else {
			echo('{"code":201, "msg":"logout err"}');
		}
		
	}
	
	public function vaile_login(){				//验证cookie 免密登录 		
		if (session('uid')) {			//session 未失效
			$uid = session('uid'); 
			$res_user = M()->query(" SELECT uname, score FROM rc_shop_user WHERE uid = $uid ");
			$token = M('shop_user_token')->where(" uid=$uid ")->getField('token');
			
			$data['code'] = 200;
			$data['token'] = $token;
			$data['msg'] = 'user already login';
			$data['userInfo'] = $res_user;

			echo json_encode($data);
		} 		

		$cookie = session('PHPSESSID') or die('{"code":401,"msg":"login required"}');
		$m = M('shop_user_cookie');

		$cookie_res = $m->where("cookie='".$cookie."'and cook_times<1000")->find();
		if ($cookie_res){
			$uid = $cookie_res['uid'];
			//更新cookie值
			$cookie = hash_hmac("sha256", rand(1000, 9999)."zdkjshopup".time(), md5("zdkjadmin"));

			$update_data['cook_time'] = $cookie_res['cook_time'] + 1;
			$update_data['times'] = $cookie_res['times'] + 1;
			$update_data['cookie'] = $cookie;

			$update_cookie = $m->data( $update_data )->where(" uid=$uid ")->save();
			//更新token值；
			$utoken =  md5(time());
			$data_token['token'] =$utoken;
			$data_token['token_time'] = time()+86400;
			$data_token['logintime'] = date ( 'Y-m-d H:i:s' );
			$insert_token = M('shop_user_token')->where("uid=".$uid)->save($data_token);

			$res_user = M()->query(" SELECT uname, score FROM rc_shop_user WHERE uid = $uid ");
			if ( $update_cookie && $insert_token) {
				session( 'uid', $uid );
				session( 'access_token', $utoken );
				cookie( 'PHPSESSID', $cookie, array('expire'=>3600*3) );

				$data['code'] = 201;
				$data['token'] = $token;
				$data['cookie'] = cookie('PHPSESSID');
				$data['userInfo'] = $res_user;
				$data['msg'] = 'login succ';

				echo json_encode($data);
			} else {
				echo('{"code":401, "msg":"login set msg err"}');
			}
		} else {
			echo('{"code":401, "msg":"login over frequency"}');
		}
		
	}
	
	public function vaile_token(){//验证token 在通信的时候用
		$uid = session('uid') or die('{"code":401,"msg":"user need login"}');
		$token = $_POST['utoken'] or die('{"code":401,"msg":"token required"}');

		$now_time = time();
		$token_res = M('shop_user_token')->where("token='".$token."'and uid=$uid and token_time>".$now_time)->getField('uid');
	
		if ($token_res){
			$data['code'] = 200;
			$data['msg'] = 'vali succ';
			$data['id'] = $token_res;
			// $data['utoken'] = $token;

			echo json_encode($data);
		} else {
			echo('{"code":400, "msg":"token err"}');
		}
	
	}


	public function getGoodsDetails() {		
		$pid = I('pid') or die('{"code":401,"msg":"goods id required"}');

		$list = M("shop_goods")->where(" id=$pid ")->select();
		$list['base_freight'] = M()->query(" SELECT default_cost FROM rc_shop_goods A JOIN rc_shop_freight_module B ON A.freight_module = B.id WHERE A.id=$pid ");

		if( $list ) { 		
			echo json_encode($list);
		} else {
			echo ('{"code":201, "msg":"get details err"}');
		}
	}

	public function addCart() {			//添加购物车
		$uid = session('uid') or die('{"code":401,"msg":"user need login"}');
		@$pid = I('pid') or die('{"code":401,"msg":"product id required"}');

		$User = M("shop_cart_order_sum"); 
		//判断是否已添加过该商品
		$vali = $User->where(" uid=$uid AND pid=$pid AND status=0 ")->select();
		if ($vali) {  echo ('{"code":301, "msg":"goods existed"}');  return;  }

		// 购物车数据设置上限20条（不同商品最多20条），过多让用户清理购物车 
		$full = $User->where(" uid=$uid AND status=0 ")->select();
		if( count($full) >= 20) { echo ('{"code":302, "msg":"goods full"}');  return;  }

		$data['uid'] = $uid;
		$data['pid'] = $pid;
		$data['status'] = 0;
		$data['add_time'] = date("Y-m-d H:i:s");
		$data['num'] = 1;

		$res = $User->add($data);
		if( $res ) {
			echo('{"code":200, "msg":"add succ"}');
		}else {           
			echo('{"code":201, "msg":"add err"}');
		}
	}


	public function getCart() {			//购物车页面信息 
		$uid = session('uid') or die('{"code":401,"msg":"user need login"}');

		$result = M()->query("SELECT c.cart_id, c.pid, c.num, d.sname, d.score, d.price, d.realprice, d.logo, d.freight_type FROM rc_shop_cart_order_sum c 
		JOIN rc_shop_goods d ON c.pid=d.id WHERE c.uid=$uid AND c.status=0 ORDER BY c.add_time DESC LIMIT 20");
		//判断用户购物车是否有商品再操作     code:200 有，返回数据， 201 无数据
		if ( count($result)>0 ) {
			$data['code'] = 200;
			$data['array'] = $result;

			echo json_encode($data);
		} else {
			$data['code'] = 201;
			$data['msg'] = "no goods added";

			echo json_encode($data);
		}
	}


	public function deleteCartItem() {		//删除购物车商品
		$cart_id = I('cart_id') or die('{"code":401,"msg":"cart id required"}');
		$m = M("shop_cart_order_sum");

		$res = $m->where(" cart_id = $cart_id ")->delete();

		if ( $res>0 ) {
			echo ('{"code":200, "msg":"del succ"}');
		} else {
			echo ('{"code":201, "msg":"del err"}');
		}

	}


	public function commitDemand() {					//购物车页面提交创建订单请求
		$cart_id = $_POST['cart_id'] or die('{"code":401,"msg":"cart id required"}');
		$num = $_POST['num'] or die('{"code":401,"msg":"num required"}');

		$cart_id_array = explode('/',$cart_id);
		$num_array = explode('/',$num);

		for ($index=0; $index<count($cart_id_array); $index++) 
			{ 
				$cart_id =  intval($cart_id_array[$index]);
				$num = intval($num_array[$index]);

				$update = M('shop_cart_order_sum')->data(array('num'=>$num,'status'=>1))->where(" cart_id = $cart_id ")->save();
			} 

		if ($update) {
			echo ('{"code":200, "msg":"commit succ"}');
		} else {
			echo ('{"code":201, "msg":"commit err"}');
		}	
	}


	public function getOrder() {     //确认订单页面信息       包含根据用户选用地址进行的运费计算
														// 1.不考虑不同快递 （中间方案，优先级）
														// 2.包邮商品与非包邮商品合并付款，付续重（续件）运费即可
														// 3.不会有增加两件的续重（续件）计算，不用考虑
														// 4.商品计费方式统一。
		$uid = session('uid') or die('{"code":401,"msg":"user id required"}');
		@$pid = I('pid');
		@$cart_id = I('cart_id');
		@$order_number = I('order_number');

		//获取用户另选地址
		$reserve_address_id = M("shop_user")->where(" uid=$uid ")->getField("reserve_address_id");
		if ( $reserve_address_id ) {
			$sql = " b.aid = $reserve_address_id ";
		} else {
			$sql = " b.uid = $uid AND b.selected=1 ";
		}
		//获取收货地址
		$res_address = M()->query(" SELECT c.area, d.city, e.province, e.provinceid, b.phone, b.address, b.name, b.aid 
							FROM rc_shop_user_address AS b
							JOIN rc_shop_address_areas AS c ON b.area = c.areaid
							JOIN rc_shop_address_cities AS d ON b.city = d.cityid
							JOIN rc_shop_address_provinces AS e ON b.province = e.provinceid
							WHERE $sql ");
		$data['address'] = $res_address;

		if ($pid) {					//商品页面直接发起的付款

			$num = 1;        	//默认付款商品件数为  1
			$res_goods = M()->query("SELECT id, sname, logo, price, realprice, score, freight_type, freight_module FROM rc_shop_goods WHERE id=$pid ");

			foreach($res_goods as $goods) {
				$module_id = $goods['freight_module'];

				if ( $goods['freight_type'] == 3 ) {		//运费计算  非包邮情况下
					if ($res_address) {
						$province_id = M('shop_user_address')->where(" uid=$uid AND selected=1 ")->getField("province");

						$calc_freight = M('shop_freight_module')->where(" id=$module_id ")->getField(" default_cost ");

						$calc_area_freight = M()->query(" SELECT b.base_cost FROM rc_shop_freight_model_apply_area a JOIN rc_shop_freight_calc_model b 
												ON a.model_id = b.mid WHERE a.module_id= $module_id ");
						
						if ($calc_area_freight) {		//若用户地址在运费模板中设置了特殊计算方式， 则采用特殊计算方式
							$freight = $calc_area_freight[0]['base_cost'];
						} else {						//若未设置特殊计算方式，则采用默认运费计算方式
							$freight = $calc_freight[0];
						}
					}
				} else { $freight = 0; }

				$list [] = array (
					'id' => $goods['id'],
					'sname' => $goods['sname'],
					'price' => $goods['price'],
					'logo' => $goods['logo'],
					'realprice' => $goods['realprice'],
					'score' => $goods['score'],
					'freight_type' => $goods['freight_type'],
				);
			}
				//结果输出
			if ($res_goods) {
				$data['code'] = 200;
				$data['details'] = $list;
				$data['freight'] = $freight;
			} else {
				$data['code'] = 201;
				$data['msg'] = "select err";
			}
			echo json_encode($data);
		} 


		if ( $cart_id ) { 			//购物车页面发起付款
			$cart_array = explode('/',$cart_id);

			$calculate = false;				//是否不包邮
			$num = 0;						//合并付款商品总数
			$freight = 0;					//初始化运费为0
			$priority = 100;     			//初始设定值最大，优先级最小

			$data['details'] = array();

			for ($index=0; $index<count($cart_array); $index++) 
			{ 
				$cart_id = intval( $cart_array[$index] );
				$res = M()->query(" SELECT a.id, a.sname, a.logo, a.price, a.realprice, a.score, a.freight_type, a.freight_module, c.num 
				FROM rc_shop_cart_order_sum c JOIN rc_shop_goods a ON a.id=c.pid WHERE c.cart_id=$cart_id ");

				if ($res) {
					$num += intval($res[0]['num']);					//合并付款商品总数量
						//运费计算  商品中有非包邮商品
					if ( $res[0]['freight_type'] == 3 ) {	
						$calculate = true;
						$module_id = $res[0]['freight_module'];

						//取计算运费的优先级 
						$priority_num = M("shop_freight_module")->where(" id=$module_id ")->getField("priority");

						if($priority > intval($priority_num)) { 	//运费计算优先级比较
							$priority = intval($priority_num); 
							$calc_module_id = $module_id;			//最终使用的运费计算模式 id
						}
					} 
					array_push($data['details'], $res[0]);
					// var_dump($data['details']);
					// var_dump($calc_module_id);
				}
			} 

			if ($res_address) {											//用户已设置默认地址 或 重新选择地址时	根据商品数量计算运费
				if ($calculate) {
						//1、查找出订单地址所对应的省份区号id
					$province_id = M('shop_user_address')->where(" uid=$uid AND selected=1 ")->getField("province");		
						//2、默认运费计算模式数据
					$calc_freight = M()->query(" SELECT default_cost, default_amount, default_add_count, default_add_cost FROM rc_shop_freight_module WHERE id=$calc_module_id ");
						//3、查找地址对应省份对应的运费计算模式数据
					$calc_area_freight = M()->query(" SELECT b.base_cost, b.base_amount, b.amount_count, b.amount_cost FROM rc_shop_freight_model_apply_area a 
								JOIN rc_shop_freight_calc_model b ON a.model_id = b.mid WHERE a.module_id= $calc_module_id AND a.province_id=$province_id ");
					// var_dump($calc_freight);
					// var_dump($calc_area_freight);
					if ($calc_area_freight) {							//若用户地址在运费模板中存在特殊计算方式， 则采用特殊计算模式
						$rule = $calc_area_freight[0];

						$overstep = $num - $rule['base_amount'];		//用户购买商品数超出基准部分
						$freight = intval( $rule['base_cost'] );
						if ( $overstep>0 ) {
							$freight += $rule['amount_cost']*$overstep;
						}				

					} else {											//若未设置特殊计算方式，则采用默认运费计算方式
						$rule = $calc_freight[0];

						$overstep = $num - $rule['default_amount'];		//用户购买商品数超出基准部分
						$freight = intval( $rule['default_cost'] );
						if ( $overstep>0 ) {
							$freight += $rule['default_add_cost']*$overstep;
						}	
					}
				} 

				$data['freight'] = $freight;
			} else {
				$data['freight'] = null;
			}

			$data['code'] = 200;
			echo json_encode($data);
		}

		if ($order_number) {		//订单详情页面 展示订单详情 页面可直接发起支付跳转支付接口
			$order_info = M()->query(" SELECT a.id, a.uid, a.order_number, a.creat_time, a.deal_time, a.number, a.address_id, a.status, a.freight, a.delivery_type,  
			a.finish_time, a.remarks, a.tracking_number, a.delivery_time, b.id, b.sname, b.logo, b.price, b.score FROM rc_shop_order_manage a JOIN rc_shop_goods b ON 
			a.pid = b.id WHERE a.order_number = $order_number ");

			$res_address = M()->query(" SELECT c.area, d.city, e.province, e.provinceid, b.phone, b.address, b.name, b.aid 
						FROM rc_shop_order_manage a
						JOIN rc_shop_user_address AS b ON a.address_id = b.aid
						JOIN rc_shop_address_areas AS c ON b.area = c.areaid
						JOIN rc_shop_address_cities AS d ON b.city = d.cityid
						JOIN rc_shop_address_provinces AS e ON b.province = e.provinceid
						WHERE a.order_number = $order_number ");
			
			$freight = M("shop_order_manage")->where("order_number = $order_number")->getField("freight");

			$data['details'] = $order_info;
			$data['address'] = $res_address;
			$data['freight'] = $freight;
			$data['code'] = 200;

			echo json_encode($data);
		}
	}
	

	public function createOrder(){
		$uid = session('uid') or die('{"code":401,"msg":"user need login"}');
		$address_id = $_POST['address_id'] or die('{"code":401,"msg":"address id required"}');
		$pid = $_POST['pid'] or die('{"code":401,"msg":"goods id required"}');
		$num = $_POST['num'] or die('{"code":401,"msg":"num required"}');
		@$freight = $_POST['freight'];
		
		$Order = M("shop_order_manage"); 
		$order_number = time().rand(1000,9999);

		$data['uid'] = intval($uid);
		$data['address_id'] = intval($address_id);
		$data['creat_time'] = date("Y-m-d H:i:s");
		$data['order_number'] = $order_number;
		$data['status'] = -1;
		$data['freight'] = intval($freight);
		$data['time_out'] = date('Y-m-d H:i:s',strtotime('+1day'));				//未付款的超时时间

		$pid_array = explode('/',$pid);
		$num_array = explode('/',$num);

		//删除保存在用户表中的 用户另选地址
		$delete = M("shop_user")->where(" uid=$uid ")->setField('reserve_address_id','');

		if (count($pid_array)==1) 
		{	//单个商品多次创建订单时
			// $pid = intval($pid);
			// $num = intval($num);
			// $freight = intval($freight);

			$update = $Order->data(array('number'=>$num,'freight'=>$freight, 'order_number'=>$order_number, 'address_id'=>$address_id))->where(" uid=$uid AND pid=$pid AND status=0 ")->save();
			if ($update) {
				$res = $update; 
			} else {
				$data['pid'] = $pid;
				$data['number'] = $num;

				$res = $Order->add($data);
			}
		} else {			//购物车页面发起   多个商品，不同数量， 创建同一个订单号
			for ($index=0; $index<count($pid_array); $index++) 
			{ 
				$data['pid'] = intval( $pid_array[$index] );
				$data['number'] = intval( $num_array[$index] );
	
				$res = $Order->add($data);
			} 
		}

		if( $res ) {
			$output['data'] = $order_number;
			$output['msg'] = "create order success";
			$output['code'] = 200; 
			echo json_encode($output);
		}else {           
			echo('{"code":201, "msg":"create order err"}');
		}
	}

	public function getOrderInfo() {				//订单详细信息
		$order_number = I('order_number') or die ('{"code":401,"msg":"order number required"}');

		$list = M()->query(" SELECT a.logo, a.sname, a.score, a.price, c.pid, c.number, c.order_number, c.id, c.status FROM rc_shop_order_manage c 
		JOIN rc_shop_goods a ON a.id=c.pid WHERE c.order_number=$order_number ");

		$output['info'] = $list;
		
		$address = M()->query(" SELECT a.creat_time, a.deal_time, a.freight, a.finish_time, a.tracking_number, a.remarks, a.delivery_time, c.area, d.city, e.province, b.phone, b.address, b.name, b.selected, b.aid 
								FROM rc_shop_user_address AS b 
								JOIN rc_shop_order_manage AS a ON b.aid = a.address_id
								JOIN rc_shop_address_areas AS c ON b.area = c.areaid
								JOIN rc_shop_address_cities AS d ON b.city = d.cityid
								JOIN rc_shop_address_provinces AS e ON b.province = e.provinceid
								WHERE a.order_number=$order_number limit 1");
		$output['unique'] = $address;
		
		if($list && $address) {
			$output['code'] = 200;
			echo json_encode($output);
		}		
	}
	

	public function pay() {				//付款后     待确定
		$uid = session('uid') or die ('{"code":401,"msg":"uid required"}');
		@$score = session('score');
		$order_number = $_POST['order_number'] or die ('{"code":401,"msg":"order number required"}');

		$update1 = M('shop_user')->where(" uid = $uid ")->setDec('score',$score);
		$update2 = M('shop_order_manage')->data(array('status'=>0))->where(" order_number = $order_number ")->save();
		if ($update1 && $update2) {
			echo ('{"code":200, "msg":"operate order succ"}');
		}
	}



	public function getAddress() {		//用户地址
		$uid = session('uid') or die('{"code":401,"msg":"user need login"}');

		$list = M()->query(" SELECT c.area, d.city, e.province, b.phone, b.address, b.name, b.selected, b.aid 
							FROM rc_shop_user_address AS b
							JOIN rc_shop_address_areas AS c ON b.area = c.areaid
							JOIN rc_shop_address_cities AS d ON b.city = d.cityid
							JOIN rc_shop_address_provinces AS e ON b.province = e.provinceid
							WHERE b.uid = $uid ORDER BY selected DESC ");

		if (!$list) {
			$data['code'] = 201;
			$data['msg'] = "user has no address";

			echo json_encode($data);
		} else {
			$data['code'] =200;
			$data['data'] = $list;

			echo json_encode($data);
		}
	}


	public function userSelectedAddress() {			//用户创建订单时 选择地址不使用默认地址
		$uid = session('uid') or die('{"code":401,"msg":"user need login"}');
		$address_id = $_POST['address_id'] or die('{"code":401,"msg":"user id required"}');

		$update = M('shop_user')->where(" uid = $uid ")->setField('reserve_address_id', $address_id);

		if ( $update ) {  echo ('{"code":200, "msg":"update succ"}');  }
	}


	public function setDefaultAddress(){            //设置默认地址
		$uid = session('uid') or die('{"code":401,"msg":"login required"}');
		$aid = I('aid') or die('{"code":401,"msg":"user address id required"}');

		// 先清空默认地址 （ 'selected' 1 为默认地址  ，0 为备用地址  ）
		$empty = M('shop_user_address')->data(array('selected'=>0))->where(" uid=$uid ")->save();

		// 设置默认地址
		$update = M('shop_user_address')->data(array('selected'=>1 ))->where(" aid = $aid AND uid=$uid ")->save();

		if ( $update ) {
			echo ('{"code":200, "msg":"update succ"}');
		} else {
			echo ('{"code":201, "msg":"update err"}');
		}
	}


	public function getEditAddress(){	
		$uid = session('uid') or die('{"code":401,"msg":"login required"}'); 			
		$aid = $_POST['aid'] or die('{"code":401,"msg":"user address id required"}');

		$list = M('shop_user_address')->where(" aid=$aid AND uid=$uid ")->select();
		echo json_encode($list);
	}


	public function newAddress(){
		$uid = session('uid') or die('{"code":401,"msg":"login required"}');
		$name = $_POST['name'] or die('{"code":401,"msg":"address name required"}');
		$phone = $_POST['phone'] or die('{"code":401,"msg":"address phone required"}');
		$address = $_POST['addressDetail'] or die('{"code":401,"msg":"address detail required"}');
		$province = $_POST['province'] or die('{"code":401,"msg":"province required"}');
		$city = $_POST['city'] or die('{"code":401,"msg":"city required"}');
		$area = $_POST['district'] or die('{"code":401,"msg":"area required"}');
		@$aid = $_POST['aid'];
		
		$m = M("shop_user_address"); 

		$data['uid'] = $uid;
		$data['name'] = $name;
		$data['phone'] = $phone;
		$data['address'] = $address;
		$data['province'] = $province;
		$data['city'] = $city;
		$data['area'] = $area;

		$count = $m->where(" uid = $uid ")->count();
		if($count>=10) {		//用户地址不超过十条
			echo ('{"code":201, "msg":"address full"}');
			return;
		}
		//相同地址去重
		$repeat = $m->where(" uid=$uid AND name='$name' AND phone='$phone' AND address='$address' AND province=$province AND city=$city AND area=$area")->select();
		if ($repeat) {
			echo ('{"code":200, "msg":"address exsist"}');
			return ;
		}

		if($aid) {		//执行修改地址操作
			$res = $m->data($data)->where(" aid=$aid ")->save();
		} else {
			//新增地址并设为默认地址
			$update = $m->data(array('selected'=>0))->where(" uid = $uid ")->save();

			$data['selected'] = 1;
			$res = $m->add($data);
		}
		// echo $res;
		if ($res) {
			echo ('{"code":200, "msg":"new address succ"}');
		} else {
			echo ('{"code":202, "msg":"new address err"}');
		}

	}

  
	public function deleteAddress(){
		$aid = $_POST['aid'] or die('{"code":401,"msg":"address id required"}');
		$uid = session('uid');
		$m = M("shop_user_address");

		$res = $m->where(" aid = $aid AND uid=$uid ")->delete();

		if ( $res ) {
			echo ('{"code":200, "msg":"del succ"}');
		} else {
			echo ('{"code":201, "msg":"del err"}');
		}

	}



	public function getAllOrder() {      //已付款或交易成功   获取 二维关联数组
		$uid = session('uid') or die('{"code":401,"msg":"login required"}');
		// $uid = intval($uid);

		//判断是否存在超时未付款订单
		$now = date('Y-m-d H:i:s');
		$delay_order = M('shop_order_manage')->where(" uid=$uid AND time_out <='$now' AND status=-1 ")->getField('id');

		// var_dump($delay_order);
		if ($delay_order) {
			$update = M('shop_order_manage')->data( array('status'=>-2) )->where(" id = $delay_order ")->save();
		}

		//不需要进行 子循环的参数
		$unique = M()->query(" SELECT order_number, status, delivery_type, tracking_number FROM rc_shop_order_manage WHERE uid=$uid GROUP BY order_number ORDER BY status ASC, creat_time DESC ");
		// var_dump($order_number);
		$datalist = array();
		foreach($unique as $num) {
			// var_dump($num);
			$num = $num['order_number'];
			$list = M()->query(" SELECT a.logo, a.sname, a.score, a.price, c.pid, c.number, c.order_number, c.id, c.status FROM rc_shop_order_manage c 
			JOIN rc_shop_goods a ON a.id=c.pid WHERE c.order_number=$num ");

			array_push($datalist, $list);
		}

		// var_dump($datalist);
		if ( $unique ) {
			$data['code'] = 200;
			$data['data'] = $datalist;
			$data['unique'] = $unique;
		} else {
			$data['code'] =201;
			$data['msg'] = "user has no order";
		}
		echo json_encode($data);
	}

	public function deleteOrder() {
		$order_number = $_POST["order_number"] or die ('{"code":401,"msg":"order number required"}');
		$uid = session('uid');
		$cancle = M('shop_order_manage')->where(" order_number=$order_number AND uid=$uid ")->delete();

		if($cancle) {
			echo ('{"code":200, "msg":"del succ"}');
		} else {
			echo ('{"code":201, "msg":"del err"}');
		}
	}


	public function cancleOrder() {			//用户主动取消订单
		$order_number = $_POST['order_number'] or die ('{"code":401,"msg":"order number required"}');

		$cancle = M('shop_order_manage')->data( array('status'=>-2) )->where(" order_number = $order_number ")->save();
		
		if ( $cancle ) {
			echo ('{"code":200, "msg":"cancle succ"}');
		} else {
			echo ('{"code":201, "msg":"cancle err"}');
		}
	}


}