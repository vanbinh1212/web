<?php
if(!defined("SNAPE_VN")) die('No access');

if(!$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());

?>
<script type="text/javascript" src="<?=$_config['page']['fullurl']?>/js/load_history.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
    	SnapeHistory.init(1);
    });
</script>
<div class="history_list">
	
</div>
<div class="page_history"></div>