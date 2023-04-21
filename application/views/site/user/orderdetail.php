
			<div class="panel panel-info">
					<div class="col-lg-7">
					<span class="btn btn-info close">&times;</span>
						<div class="panel panel-info">
							<div class="panel-body">
								<h4>Thông tin khách hàng</h4>
								<div class="table-responsive">
									<table class="table table-bordered">
									<tbody>
										<tr>
											<td style="width: 100px">Họ và tên</td>
											<td><?php echo $transaction->user_name; ?></td>
										</tr>
										<tr>
											<td>Số điện thoại</td>
											<td><?php echo $transaction->user_phone; ?></td>
										</tr>
										<tr>
											<td>Địa chỉ</td>
											<td><?php echo $transaction->user_address; ?></td>
										</tr>
										<tr>
											<td>Tin nhắn</td>
											<td><?php echo $transaction->message; ?></td>
										</tr>
										<tr>
											<td>Ngày đặt</td>
											<td><?php echo mdate("%H:%i:%s %d/%m/%Y",$transaction->created); ?></td>
										</tr>
									</tbody>
									</table>
								</div>
								<h4>Chi tiết đơn đặt hàng</h4>
								<div class="table-responsive" style="height: 300px;overflow: auto;">
									<table class="table table-hover">
										<thead style="background-color: rgb(240, 93, 64);color: #fff;font-size: 14px">
											<tr class="info">										
												<th class="text-center">STT</th>
												<th>Tên sản phẩm</th>
												<th>Số lượng</th>
												<th>Size</th>
												<th>Tổng Giá</th>

											</tr>
										</thead>
										<tbody >
											<?php 
											$stt = 0;
											foreach ($list_product as $value) { 
												$stt = $stt + 1 ;?>
												<tr>
													<td style="vertical-align: middle;text-align: center;"><strong><?php echo $stt ?></strong></td>
													<td><img src="<?php echo base_url(); ?>upload/product/<?php echo $value->image_link; ?>" alt="" style="width: 50px;float:left;margin-right: 10px;"><strong><?php echo $value->name; ?></strong>
													</td>
													<td style="vertical-align: middle"><strong ><?php echo $value->qty; ?></strong></td>
																						<td style="vertical-align: middle"><strong ><?php echo $value->size_name; ?></strong></td>
																						<td style="vertical-align: middle">
														<?php echo number_format($value->price); ?> VNĐ
													</td>
												</tr>
											<?php } ?>

										</tbody>

									</table>				
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
</script>
