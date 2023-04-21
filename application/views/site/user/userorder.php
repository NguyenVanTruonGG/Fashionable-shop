<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed;
  z-index: 1; /* Sit on top */
  padding-top: 30px; /* Location of the box */
  padding-left: 30%;
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>
</head>


<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    
  </div>

</div>

<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Trang chủ</a></li>
			<li class="active">Thông tin đơn hàng</li>
		</ol>
		<div class="col-md-12 clearpadding">
			<div class="panel panel-info">
				
				<div class="panel-heading">
					<h3 class="panel-title">Thông tin đơn hàng</h3>
				</div>
				<div class="panel-body">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
						<table class="table table-bordered">
							<tbody>
								
									
											<thead style="background-color: rgb(240, 93, 64);color: #fff;font-size: 14px">
												<tr class="info">
													<th class="text-center">STT</th>
													<th>Tên khách hàng</th>
													<th>Ngày đặt</th>
													<th>Số ĐT</th>
													<th>Giá tiền</th>
													<th>Trạng thái</th>
													<th>Hành động</th>
												</tr>
											</thead>
											<tbody>

												<?php
												$stt = 0;
												foreach ($transaction as $value) {
													$stt = $stt + 1;?>
													<tr>
														<td style="vertical-align: middle;text-align: center;"><strong><?php echo $stt; ?></strong></td>
														<td style="vertical-align: middle;"><strong><?php echo $value->user_name; ?></strong></td>
														<td style="vertical-align: middle;"><strong><?php echo mdate('%H:%i:%s %d/%m/%Y', $value->created); ?></strong></td>
														<td style="vertical-align: middle;"><strong><?php echo $value->user_phone; ?></strong></td>
														<td style="vertical-align: middle;"><strong><?php echo number_format($value->amount); ?></strong> VNĐ</td>
														<td style="vertical-align: middle;">
															<?php
															switch ($value->status) {
																case '0':
																	echo "<p style='color:red'>Đang chờ </p>";
																	break;
																case '1':
																	echo "<p style='color:green'>Đã xác nhận</p>";
																	break;
																default:
																	echo 'Đang chờ';
																	break;
															}
															?>
														</td>
														<td class="list_td aligncenter" style="vertical-align: middle;">

															<button onclick="a1_onclick('<?php echo base_url('user/detailorder/' . $value->id); ?>')" title="Chi tiết"><span class="glyphicon glyphicon-list-alt"></span> Chi tiết</button>&nbsp;&nbsp;&nbsp;
															<?php if ($value->status != 1) { ?>
																<a href="<?php echo base_url('/user/delorder/' . $value->id); ?>" title="Xóa"> <span class="glyphicon glyphicon-remove" onclick=" return confirm('Bạn chắc chắn muốn xóa')"></span> Hủy đơn</a>
															<?php } ?>


														</td>
													</tr>
												<?php } ?>

											</tbody>

										</table>
															
										<?php //echo $this->pagination->create_links(); ?>


									
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	// Get the modal
	var modal = document.getElementById("myModal");

	// Get the button that opens the modal
	var btn = document.getElementById("myBtn");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	// When the user clicks the button, open the modal 
	
	function a1_onclick(url) {
        $('#myModal').load(url, function(){
    		// show your modal here
			modal.style.display = "block";
		});	
    }

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
		modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
	
    $(function () {
        $('#AlertBox').removeClass('hidden');
        $('#AlertBox').delay(1500).slideUp(500);
    });
</script>
