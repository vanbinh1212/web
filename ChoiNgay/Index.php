<?php
session_start();
define("SNAPE_VN", true);

include_once dirname(__FILE__).'/../#config.php';

include dirname(__FILE__).'/../class/class.account.php';

include dirname(__FILE__).'/../class/function.global.php';

$account = new account(0);

$loadserver = loadallserver();

function loadstate($IsState) {
	$state = "";
	switch($IsState)
	{
		case 0:
		$state = "smooth";
		break;
		case 1:
		$state = "hot";
		break;
		case 2:
		$state = "busy";
		break;
		case 3:
		$state = "maintenance";
		break;
		default:
		$state = "NULL";
		break;
	}
	return $state;
}

if(!$account->isLogin()) movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl()); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<link rel="shortcut icon" href="http://img.zing.vn/products/gn/favicon.ico"/>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="Description" content="ddtank" />
        <meta name="Keywords" content="ddtank" />
        <title>Chơi Ngay - <?= $_config['page']['title'] ?></title>
        <link rel="stylesheet" href="./Css/reset.css" />
        <link rel="stylesheet" href="./Css/select-server.css" />
    </head>
    <body>
        <div class="wrapper prc">
            <div class="container pr outmargin">
                <div class="link pa tc">
                    <a href="<?= $_config['page']['fullurl'] ?>" target="_blank">Trang Chủ</a>
                    <a href="<?= $_config['page']['fullurl'] ?>/tai-khoan/nap-momo/" target="_blank">Nạp Thẻ</a>
                    <a href="<?= $_config['Contact']['FB'] ?>" target="_blank">Fanpage</a>
                </div>
                <article>
                    <h1>Danh Sách Máy Chủ</h1>
                    <section class="recom">
                        <h3>Máy chủ đã chơi</h3>
                        <ul class="recommend-list clearfix">
							<?php
								while($svInfo = sqlsrv_fetch_array($loadserver, SQLSRV_FETCH_ASSOC)) {
									echo '
									<li class="server-item '.loadstate($svInfo['IsActive']).'">
										<a href="play.php?id='.$svInfo['ServerID'].'" site_h="57" target="_blank">'.$svInfo['ServerName'].'</a>
									</li>';
								}
							?>
                        </ul>
                    </section>
                    <section class="servers pr outmargin">
                        <h3>Danh sách máy chủ</h3>
                        <ul class="status-list pa">
                            <li class="status smooth">Tốt</li>
                            <li class="status hot">Hot</li>
                            <li class="status busy">Bận</li>
                            <li class="status maintenance">Bảo Trì</li>
                        </ul>
                        <div class="box-server">
                            <ul class="agent-list clearfix">
                                <li class="agent-item" plat_id="30">Ver 3.6</li>
                                <li class="agent-item" plat_id="55">Ver 55</li>
                            </ul>
							<?php
								$loadserver = loadallserver();
								while($svInfo = sqlsrv_fetch_array($loadserver, SQLSRV_FETCH_ASSOC)) {
									echo '
									<ul class="server-list servers30 clearfix">
										<li class="server-item '.loadstate($svInfo['IsActive']).'">
											<a href="play.php?id='.$svInfo['ServerID'].'" target="_blank">'.$svInfo['ServerName'].'</a>
										</li>
									</ul>
									';
								}
							?>
                        </div>
                    </section>
                </article>
            </div>
        </div>
        <script src="./Js/jquery-1.9.1.min.js"></script>
        <script src="./Js/select-server.js"></script>
        <script src="./Js/ddt_common.js"></script>
        <script>
            $(function () {
                $(".agent-item").click(function () {
                    $(".agent-item").removeClass("active");
                    $(".server-list").hide();

                    $(this).addClass("active");
                    var plat_id = $(this).attr("plat_id");
                    $(".servers" + plat_id).show();
                });
                $(".agent-item:eq(0)").click();
            });
        </script>
    </body>
</html>
