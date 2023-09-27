<?php
if(!defined("SNAPE_VN")) die('No access');

$qSelect = sqlsrv_query($conn, "select * from Mem_Support where UserID = ? ORDER by ID DESC, Status ASC", array($_SESSION['ss_user_id']), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

?>

						<div class="center-block">
							<p align="right"><a href="<?=$_config['Contact']['FB']?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Gửi hỗ trợ mới</a></p>
							<table class="table table-striped">
								<thead>
									<tr>
									  <th>Mã</th>
									  <th>Tiêu đề</th>
									  <th>Loại hỗ trợ</th>
									  <th>Tình trạng</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(sqlsrv_num_rows($qSelect) > 0) {
										while($supportInfo = sqlsrv_fetch_array($qSelect, SQLSRV_FETCH_ASSOC)) {
											$sst = statusName($supportInfo['Status']);
											echo '<tr>
													  <th scope="row">#'.$supportInfo['ID'].'</th>
													  <td><a href="javascript:;" onclick="SnapeClass.readSupport('.$supportInfo['ID'].')">'.((strlen($supportInfo['Title']) > 50) ? substr($supportInfo['Title'], 0, 50).'...' : $supportInfo['Title']).'</a></td>
													  <td>'.loadSupportCategory($supportInfo['Type'])['Name'].'</td>
													  <td><span class="label" style="background-color:'.$sst['color'].'">'.$sst['name'].'</span></td>
													</tr>';
										}
									} else {
										echo '<tr><td align="center" colspan="4"><b>Bạn chưa gửi hỗ trợ nào</b></td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>