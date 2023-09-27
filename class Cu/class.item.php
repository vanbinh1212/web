<?php
class item_gunny {
	
	public static function getCategoryName($catId) {
		
		$name = 'Unknown';
		
		switch($catId) {
			case 1:
			$name = 'Nón';
			break;
			
			case 2:
			$name = 'Kính';
			break;
			
			case 3:
			$name = 'Tóc';
			break;
			
			case 4:
			$name = 'Mặt';
			break;
			
			case 5:
			$name = 'Áo';
			break;
			
			case 6:
			$name = 'Mắt';
			break;
			
			case 7:
			$name = 'Vũ khí';
			break;
			
			case 8:
			$name = 'Vòng tay';
			break;
			
			case 9:
			$name = 'Nhẫn';
			break;
			
			case 11:
			$name = 'Đạo cụ';
			break;
			
			case 13:
			$name = 'Set';
			break;
			
			case 14:
			$name = 'Dây truyền';
			break;
			
			case 15:
			$name = 'Cánh';
			break;
			
			case 16:
			$name = 'Bong bóng';
			break;
			
			case 17:
			$name = 'Vũ khí phụ';
			break;
			
			case 18:
			$name = 'Hộp thẻ';
			break;
			
			case 19:
			$name = 'Đá hồi phục';
			break;
			
			case 24:
			$name = 'Thẻ danh hiệu';
			break;
			
			case 35:
			$name = 'Trứng thú cưng';
			break;
			
			case 40:
			$name = 'Huy Hiệu';
			break;
			
			case 50:
			$name = 'Vũ khí thú cưng';
			break;
			
			case 51:
			$name = 'Nón thú cưng';
			break;
			
			case 52:
			$name = 'Áo thú cưng';
			break;
			
			case 53:
			$name = 'Nước tu luyện ma pháp';
			break;
			
			case 62:
			$name = 'Thẻ phụ kiện';
			break;
			
			case 69:
			$name = 'Bùa Thú Cưỡi';
			break;
			
			case 80:
			$name = 'Thuốc ma pháp';
			break;
			
			
			
		}
		
		return $name;
	}
	
	public static function getImage($gender, $catid, $pic) {
		$imagePatch = NULL;
		$gender = $gender == 1 ? 'm' : 'f';
		switch ($catid) {
			case 1:
				$imagePatch = "image/equip/".$gender.
				"/head/".$pic.
				"/icon_1.png";
				break;
			case 2:
			
				$imagePatch = "image/equip/".$gender.
				"/glass/".$pic.
				"/icon_1.png";
				break;
			case 3:
				$imagePatch = "image/equip/".$gender.
				"/hair/".$pic.
				"/icon_1.png";
				break;
			case 4:
				$imagePatch = "image/equip/".$gender.
				"/eff/".$pic.
				"/icon_1.png";
				break;
			case 5:
			
				$imagePatch = "image/equip/".$gender.
				"/cloth/".$pic.
				"/icon_1.png";
				break;
			case 6:
				$imagePatch = "image/equip/".$gender.
				"/face/".$pic.
				"/icon_1.png";
				break;
			case 27:
			case 7:
				$imagePatch = "image/arm/".$pic.
				"/00.png";
				break;
			case 8:
				$imagePatch = "image/equip/armlet/".$pic.
				"/icon.png";
				break;
			case 9:
				$imagePatch = "image/equip/ring/".$pic.
				"/icon.png";
				break;
			case 33:
				$imagePatch = "image/farm/fertilizer/".$pic.
				"/icon.png";
				break;
			case 32:
				$imagePatch = "image/farm/crops/".$pic.
				"/seed.png";
				break;
			case 34:
			case 35:
			case 36:
			case 30:
			case 40:
			case 60:
			case 11:
			case 62:
			case 69:
			case 53:
			case 24:
				$imagePatch = "image/unfrightprop/".$pic.
				"/icon.png";
				break;
			case 12:
				$imagePatch = "image/task/".$pic.
				"/icon.png";
				break;
			case 13:
				$imagePatch = "image/equip/".$gender.
				"/suits/".$pic.
				"/icon_1.png";
				break;
			case 14:
				$imagePatch = "image/equip/necklace/".$pic.
				"/icon.png";
				break;
			case 15:
				$imagePatch = "image/equip/wing/".$pic.
				"/icon.png";
				break;
			case 16:
				$imagePatch = "image/specialprop/chatBall/".$pic.
				"/icon.png";
				break;
			case 17:
			case 31:
				$imagePatch = "image/equip/offhand/".$pic.
				"/icon.png";
				break;
			case 18:
				$imagePatch = "image/cardbox/".$pic.
				"/icon.png";
				break;
			case 19:
				$imagePatch = "image/equip/recover/$pic/icon.png";
				break;
			case 20:
			case 23:
			case 28:
			case 29:
			case 80:
				$imagePatch = "image/prop/".$pic.
				"/icon.png";
				break;
			case 26:
				$imagePatch = "image/card/".$pic.
				"/icon.jpg";
				break;
			case 25:
				$imagePatch = "image/gift/".$pic.
				"/icon.png";
				break;
			case 50:
				$imagePatch = "image/petequip/arm/".$pic.
				"/icon.png";
				break;
			case 51:
				$imagePatch = "image/petequip/hat/".$pic.
				"/icon.png";
				break;
			case 52:
				$imagePatch = "image/petequip/cloth/".$pic.
				"/icon.png";
				break;
			default:
				$imagePatch = "image/error/".$pic.
				"/icon.png";
				break;
		}
		return $imagePatch;
	}
	
}
?>