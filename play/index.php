<?php
session_start();
define("SNAPE_VN", true);

include_once dirname(__FILE__).'/../#config.php';

include dirname(__FILE__).'/../class/class.account.php';

include dirname(__FILE__).'/../class/function.global.php';

$account = new account(0);

if(!$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());
//die('Mảy chủ Gà Tết khai mở lúc 18h ngày 04/11/2017 bạn vui lòng quay lại sau nhé.');
//exit;
?>
<html xmlns:fb="http://ogp.me/ns/fb#">

<head>
	<link rel="shortcut icon" href="http://img.zing.vn/products/gn/favicon.ico"/>
    <title>Chơi Ngay - <?= $_config['page']['title'] ?></title>

    <meta name="description" content="Gunny phiên bản mới nhất - Miễn phí 500.000 Coin Webshop. Miễn phí pet Tôn Ngộ Không. Thích cái gì là có cái đó. Trải nghiệm ngay nào!!!">
    <meta name="keywords" content="gunny free, ddtank, ddtank private, gunny private, gunny lậu, gunny private, gunny miễn phí, gunny đầy đủ tính năng, gunny phó bản chuẩn zing, gunny có doanh chiến, gunny có guild chiến, gunny có tranh bá chí tôn, gunny có pet tôn ngộ không, gunny miễn phí pet tôn ngộ không, gunny, gunny lau, gunny lau moi nhat, gunny phien ban moi, gunny moi nhat, gunny dai chien 7 thanh pho, gunny lau full xu, gunnyfullxu, gunny lau co pet ton ngo khong, gunny lau mien phi pet ton ngo khong, pet ton ngo khong gunny, free pet ton ngo khong, gunny full xu, gunny cay quoc, gunny day du tinh nang, gunny full pho ban, gunny pho ban chuan, gunny pho ban do ngon">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="vi" />
    <meta name="robots" content="index, follow" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <!-- fix outline ie 9 -->
    <meta property="og:title" content="DDTank 3.6 - Gunny Miễn Phí Đỉnh Cao">
    <meta property="og:image" content="<?=$_config['page']['fullurl']?>/images/meta_logo.png">
    <meta property="og:description" content="Phiên bản luôn theo sát Zing, miễn phí ngay 500.000 Coin Webshop và pet Tôn Ngộ Không. Cày ít mà vẫn VIP, tính năng chuẩn zing nhất. Trải nghiệm ngay bây giờ.">

    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/normalize.css" rel="stylesheet" type="text/css" />

    <link href="css/portal_gunht.css?version=27062014" rel="stylesheet" type="text/css" />

</head>

<body id="channeling" class="home">

    <div class="body-container">
        <div class="wrap-container">
            <div class="menus">
                <a href="<?=$_config['page']['fullurl']?>" class="trangchu active"></a>
                <a href="<?=$_config['page']['fullurl']?>/tai-khoan/nap-the/" class="naptien"></a>
                <div class="clearfix"></div>
            </div>
            <div class="main-content">
                <div class="recent-servers info_ctn">
                    <div class="recent-played">
                        <p class="ser">Hi, <font color="blue"><b><?=$_SESSION['ss_user_email']?></b></font>
                        </p>

                        <div class="select_server">
                            <select class="select_box" id="recentServers">
                                <!--option value="1">S1 - Gà Huyền Thoại </option-->
								<!--option value="0">-- Chọn máy chủ --</option-->
												<?php
				// load server list
				$loadserver = loadallserver();
				while($svInfo = sqlsrv_fetch_array($loadserver, SQLSRV_FETCH_ASSOC)) {
					echo '<option value="'.$svInfo['ServerID'].'">'.$svInfo['ServerName'].'</option>';
				}
				?>
                            </select>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <a  id="btn-play" class="choingay play_now"></a>

                    <div class="clearfix"></div>
                </div>
                <!--Block Server-->

                <div class="block_server server_tab">
				
                    <div id="list_tab_ser" class="inner_control">
                        <ul>
                            <li>
                                <a title="Cụm 1" href="#" id="Sece7a7e2b7ad67670044a9bd3db8e93e">Cụm 1</a>
                            </li>
                        </ul>
                    </div>

                    <div class="ctn_tab_ser">

                        <div id="Sece7a7e2b7ad67670044a9bd3db8e93e" class="ctn_subtab_ser" style="display: none;">
                            <ul>
							<?php 
							$loadserver = loadallserver();
							$ID = 0;
							while($svInfo = sqlsrv_fetch_array($loadserver, SQLSRV_FETCH_ASSOC)) {
								$ID ++;
								$DateOpen = strtotime($svInfo['DateOpen']); 
								$Date = date("16/10/2022", $DateOpen);
								$Time = date("7:00 AM", $DateOpen);
								$XuatThongTin .='
									<li>
                                    <a title="Máy chủ '.$ID.' '.$svInfo['ServerName'].'" href="play.php?id='.$svInfo['ServerID'].'" class="ser_item">
                                        <p class="open_date">'.$Date.'<br/>'.$Time.'</p>
										<p class="name_ser">'.$svInfo['ServerName'].'</p>
                                        <span class="label new_label"></span>
                                    </a>
                                </li>	';
							echo $XuatThongTin;
							}
							?>			
                            </ul>
                        </div>

                    </div>
					
                </div>
                <!--End Block Server-->
            </div>


            <!--End Tab Tin tuc noi bat-->

        </div>
        <!--End Block Tin tuc-->
    </div>
    </div>
    </div>
    <div class="footer-container">
        <div class="Footer" style="">
            <div class="" style="display: inline-block;margin: 0 0 0 -90px;">
                <div class="Center" style="">
                    <a title="Trang chủ Soha game" class="Logo_Soha">
                    </a>
                    <p class="CopyRight" style="   ">
                        <br> Fanpage: https://www.facebook.com/HiGuntimlaikiucga - Zalo hỗ trợ:0961222286
                        <br>
                    </p>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
    <div class="alert alert-danger fade in alert-custom" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <div>
            <strong>Thông báo</strong>
        </div>
        <p></p>
    </div>

    <div class="alert alert-success fade in alert-custom" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <div>
            <strong>Thành công</strong>
        </div>
        <p></p>
    </div>

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>

    <script type="text/javascript" src="js/fadegallery.js"></script>
    <script type="text/javascript" src="js/jquery.selectBox.js"></script>
    <script type="text/javascript" src="js/nanotabs.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $("select.select_box").selectBox();

            $("#list_tab_ser a").on('click', function() {
                var id = $(this).attr('id');

                $("#list_tab_ser a.active").removeClass('active');
                $(this).addClass('active');

                $(".ctn_subtab_ser").css('display', 'none');
                $(".ctn_subtab_ser#" + id).css('display', 'block');

                $.cookie('linhServerGroup_Soha', id);

                return false;
            });

            //acc_nav
            $('.detail_text').hide();
            $('.title_acc_nav').click(function() {
                if ($(this).hasClass('active')) {
                    $(this).next().hide();
                    $(this).removeClass('active');
                } else {
                    $(this).next().show();
                    $(this).addClass('active');
                }
            });

            if ($.cookie('linhServerGroup_Soha') != undefined) {
                $("#list_tab_ser a#" + $.cookie('linhServerGroup_Soha')).trigger('click');
            } else {
                $("#list_tab_ser li:last-child a").trigger('click');
            }

            $("#btn-play").click(function(event){
				event.preventDefault();
				
                var server_id = $("#recentServers option:selected").val();
			
                if (server_id == null) {
                    amoAlert('Vui lòng chọn máy chủ.');

                    return false;
                }
				window.location.href = "play.php?id="+server_id;
				
                //openServerByID(server_id);
            });

            $("a.ser_item2").on('click', function() {
                loadServer($(this));
            });


            $('.Logo_Soha').bind('click', function() {
                window.location.href = 'http://ma.soha.amo.vn/channeling';
            });
        });
    </script>

    <script type="text/javascript">
        function openServerByID(server_id) {
            //var url = $('a[data-id="'+server_id+'"]').first().attr('rel');

            //window.location.href = url;
            var url = $('a[data-id="' + server_id + '"]').first();
            loadServer(url);
        }

        function loadServer(server) {
            var state = $(server).data('state');
			
            if (state != 'publish') {
                switch (state) {
                    default: amoAlert('Có lỗi xảy ra trong quá trình kiểm tra máy chủ S' + $(server).data('title') + '.');
                    break;

                    case 'maintain':
                            amoAlert('Máy chủ S' + $(server).attr('title') + ' bảo trì tới ' + $(server).data('maintain-end'));

                        return false;
                        break;

                    case 'waiting':
                            amoAlert('Máy chủ ' + $(server).attr('title') + ' sẽ mở lúc ' + $(server).data('publish-time'));
                        break;

                    case 'offline':
                            amoAlert('Máy chủ S' + $(server).data('title') + ' đang tắt.');
                        break;
                }
            } else {
                var link = $(server).attr('rel');

                window.location.href = link;
            }

            return false;
        }

        function amoAlert(text) {
            $(".alert-custom.alert-danger").stop(true, true).find('p').html(text);
            $(".alert-custom.alert-danger").stop(true, true).css('display', 'none').css('display', 'block').fadeOut(5000);
        }

        function amoSuccess(text) {
            $(".alert-custom.alert-success").stop(true, true).find('p').html(text);
            $(".alert-custom.alert-success").stop(true, true).css('display', 'none').css('display', 'block').fadeOut(5000);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.menus a').click(function(e) {
                e.preventDefault();

                window.parent.location.href = $(this).attr('href');
            });
        });
    </script>

    <script>
        $(document).ready(function() {});
    </script>
</body>

</html>