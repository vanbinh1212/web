<?php
if(!defined("SNAPE_VN")) die('No access');

?>
<script type="text/javascript">
$(function(){
    var hash = document.location.hash;
    if (hash) {
        $(hash).find('a').click();
    }
});
</script>
<div class="center-block">
	<div class="alert alert-danger" role="alert">Ấn Ctrl + F để tìm kiếm nhanh câu hỏi cần muốn</div>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="findcoinxu">
		  <h4 class="panel-title">
			<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			  Làm sao để kiếm Coin và Xu trong game?
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="findcoinxu">
		  <div class="panel-body">
			Game hỗ trợ nhiều hướng cho phép bạn có thể kiếm Coin & Xu trong game mà không cần nạp thẻ. Xu các bạn có thể đi thi đấu tự do để kiếm, sau mỗi trận sẽ có Xu tương ứng được tặng, thắng sẽ được nhiều Xu hơn và thua sẽ ít Xu hơn. Nhưng nếu bạn KHÔNG HOẠT ĐỘNG (như không bắn, không di chuyển, tự sát,...) thì sẽ không được nhận Xu nào cả tránh trường hợp clone acc rồi treo để farm Xu.<br/><br/>Coin có thể kiếm từ thi đấu Tự do và Phó Bản thông qua hệ thống lật thẻ bài sau khi trận đấu kết thúc. Có cơ hội nhận được CoinGame, đổi CoinGame vào website để sử dụng <a href="http://gunnyae.com/tai-khoan/doi-coin-game/" target="_blank">tại đây</a>.
		  </div>
		</div>
	  </div>
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="tranhbachiton">
		  <h4 class="panel-title">
			<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			  Tranh bá Chí Tôn diễn ra như thế nào?
			</a>
		  </h4>
		</div>
		<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tranhbachiton">
		  <div class="panel-body">
			<p>Tương tự như VNG, tranh bá chí tôn diễn ra hằng tuần cho các cao thủ có thể trổ tài và nhận được các vật phẩm đặc biệt mà chỉ có ở Vua Chí Tôn mới có (danh hiệu, vũ khí VIP, vv...)</p>
			<p>Tranh bá Chí Tôn là đấu trường Liên Server được diễn ra từ Thứ Tư đến Chủ Nhật như sau:<br/>
				- Từ Thứ Tư đến Thứ Sáu diễn ra Vòng Loại: Trong thời gian diễn ra người chơi nhấp vô biểu tượng "Vua Chí Tôn" chọn thi đấu và tích điểm.<br/>
				- Thứ Bảy diễn ra Vòng Tiến Cấp: TOP 100 Gunner có điểm cao nhất ở Vòng Loại tiếp tục vào vòng này để thi đấu và tích điểm, cách thi đấu như vòng loại.<br/>
				- Chủ Nhật diễn ra Vòng Chung Kết: TOP 16 Gunner có điểm cao nhất ở Vòng Tiến Cấp tiếp tục vào vòng này thi đấu theo thể lệ bắt cặp do hệ thống sắp xếp theo 2 nhánh để tìm ra người vô địch.</p>
		  </div>
		</div>
	  </div>
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="guildtranhba">
		  <h4 class="panel-title">
			<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  Guild tranh bá diễn ra như thế nào?
			</a>
		  </h4>
		</div>
		<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="guildtranhba">
		  <div class="panel-body">
			<p>Thứ bảy mỗi tuần sẽ diễn ra Guild Tranh Bá</p>
			<p>TOP 8 Guild có cống hiến cao nhất tuần sẽ được hệ thống gửi thư vào 5h AM thứ bảy mỗi tuần để mời tham gia Guild Tranh Bá</p>
			<p>18h PM Thứ Bảy sẽ diễn ra sự kiện Guild Tranh Bá, khi diễn ra người trong Guild được phép tham gia sẽ được phép nhấp vào biểu tượng Guild Tranh Bá trong game.</p>
			<p>Toàn bộ người chơi của 8 Guild sẽ được dẫn vào 1 bản đồ PK nhiều người, người chơi Guild này kiếm người chơi Guild khác để ghép cặp thi đấu và tích điểm</p>
			<p>Guild có điểm càng cao thì phần quà càng giá trị, TOP 20 người chơi có điểm cao nhất của mỗi Guild sẽ được nhận thêm phần thưởng từ hệ thống.</p>
		  </div>
		</div>
	  </div>
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="vatphamweb">
		  <h4 class="panel-title">
			<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
			  Mua đồ từ Webshop hay Hộp quà may mắn không có trong game?
			</a>
		  </h4>
		</div>
		<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="vatphamweb">
		  <div class="panel-body">
			<p>Hệ thống có một tính năng gọi là <a href="http://gunnyae.com/tai-khoan/tui-web/" target="_blank">Túi web</a>. Toàn bộ vật phẩm nhận được từ Webshop, Hộp quà may mắn, Sự kiện trên Fanpage hoặc website đều được đưa vào đây. Bạn phải vào <a href="http://gunnyae.com/tai-khoan/tui-web/" target="_blank">Túi web</a> để chuyển các vật phẩm này vào game.</p>
		  </div>
		</div>
	  </div>
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="mattaikhoan">
		  <h4 class="panel-title">
			<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
			  Tôi bị mất tài khoản, làm sao để lấy lại?
			</a>
		  </h4>
		</div>
		<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="mattaikhoan">
		  <div class="panel-body">
			<p>Vui lòng mở ticket hỗ trợ <a href="http://gunnyae.com/ho-tro/gui-ho-tro/" target="_blank">tại đây</a> (đăng ký tài khoản mới mới truy cập được) để được trợ giúp. BQT chỉ có thể hỗ trợ bạn lấy lại tài khoản khi bạn cung cấp đầy đủ các thông tin cần thiết như Tên tài khoản & Email đăng ký phải là Email thật.</p>
		  </div>
		</div>
	  </div>
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="biluadao">
		  <h4 class="panel-title">
			<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
			  Tôi bị lừa đảo trong game, có được hỗ trợ không?
			</a>
		  </h4>
		</div>
		<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="biluadao">
		  <div class="panel-body">
			<p>BQT không can thiệp vào việc mua bán giữa người chơi với nhau và cũng KHÔNG hỗ trợ bất kỳ trường hợp liên quan tới lừa đảo nào.</p>
			<p>BQT luôn thông báo trên Fanpage hoặc trong Game với dòng chữ màu đỏ. Ngoài ra không có hình thức thông báo nào khác tới người chơi game. Các bạn hãy tỉnh táo vì lừa đảo trong game là ở khắp mọi nơi.</p>
			<p>Sắp tới sẽ có tính năng cho phép người chơi Đăng bán vật phẩm trên Webshop và Bán Tài khoản (bán tài khoản người mua sẽ nhận được tiền VNĐ kèm phí 30% do dịch vụ đổi thẻ cào có chiết khấu)</p>
		  </div>
		</div>
	  </div>
	</div>
</div>