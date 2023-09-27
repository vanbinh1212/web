<?php
class ItemInfo
{
    public $ItemID = 0;
    public $UserID = 0;
    public $BagType = 0;
    public $TemplateID = 11025;
	public $Place = 0;
	public $Count = 1;
	public $IsJudge=true;
	public $Color = "";
	public $IsExist = true;
	public $StrengthenLevel = 0;
	public $AttackCompose = 0;
	public $DefendCompose = 0;
	public $LuckCompose = 0;
	public $AgilityCompose = 0;
	public $IsBinds = false;
	public $BeginDate="2017-01-01 00:00:00.000";
	public $ValidDate = 0;
	public $Skin = "";
	public $IsUsed = false;
	public $RemoveType = 103;
	public $Hole1= -1;
	public $Hole2= -1;
	public $Hole3= -1;
	public $Hole4= -1;
	public $Hole5= -1;
	public $Hole6= -1;
	public $StrengthenTimes = 0;
	public $Hole5Level = 0;
	public $Hole5Exp = 0;
	public $Hole6Level = 0;
	public $Hole6Exp = 0;
	public $IsGold = false;
	public $goldBeginTime="2017-01-01 00:00:00.000";
	public $goldValidDate = 0;
	public $StrengthenExp = 0;
	public $beadExp = 0;
	public $beadLevel = 0;
	public $beadIsLock = false;
	public $isShowBind = false;
	public $latentEnergyCurStr="0,0,0,0";
	public $latentEnergyNewStr="0,0,0,0";
	public $latentEnergyEndTime="2017-01-01 00:00:00.000";
	public $Damage = 0;
	public $Guard = 0;
	public $Blood = 0;
	public $Bless = 0;
	public $AdvanceDate="2017-01-01 00:00:00.000";
	public $AvatarActivity = false;
	public $goodsLock = false;
	public $MagicAttack = 0;
	public $MagicDefence = 0;
	public $MagicExp = 0;
	public $MagicLevel = 0;
	public $curExp = 0;
	public $cellLocked = false;
	public function __construct($date)
   {
		$this->BeginDate = $date->format("Y-m-d H:i:s");
		$this->goldBeginTime = $date->format("Y-m-d H:i:s");
		$this->latentEnergyEndTime = $date->format("Y-m-d H:i:s");
		$this->AdvanceDate = $date->format("Y-m-d H:i:s");
   }
   public function GetValues()
   {
		return array($this->UserID,
		$this->BagType,
		$this->TemplateID, 
		$this->Place, 
		$this->Count, 
		$this->IsJudge, 
		$this->Color, 
		$this->IsExist, 
		$this->StrengthenLevel, 
		$this->AttackCompose, 
		$this->DefendCompose, 
		$this->LuckCompose, 
		$this->AgilityCompose, 
		$this->IsBinds, 
		$this->BeginDate, 
		$this->ValidDate,
		$this->Skin,
		$this->IsUsed,
		$this->RemoveType,
		$this->Hole1,
		$this->Hole2,
		$this->Hole3,
		$this->Hole4,
		$this->Hole5,
		$this->Hole6,
		$this->StrengthenTimes,
		$this->Hole5Level,
		$this->Hole5Exp,
		$this->Hole6Level,
		$this->Hole6Exp,
		$this->IsGold,
		$this->goldBeginTime,
		$this->goldValidDate,
		$this->StrengthenExp, 
		$this->beadExp,
		$this->beadLevel,
		$this->beadIsLock,
		$this->isShowBind,
		$this->latentEnergyCurStr,
		$this->latentEnergyNewStr,
		$this->latentEnergyEndTime,
		$this->Damage,
		$this->Guard,
		$this->Blood,
		$this->Bless,
		$this->AdvanceDate,
		$this->AvatarActivity,
		$this->goodsLock,
		$this->MagicAttack,
		$this->MagicDefence,
		$this->MagicExp,
		$this->MagicLevel,
		$this->curExp,
		$this->cellLocked);
   }
   public function GetQuery()
   {
		return "INSERT INTO dbo.Sys_Users_Goods
		(UserID, 
		BagType,
		TemplateID, 
		Place, 
		Count, 
		IsJudge, 
		Color, 
		IsExist, 
		StrengthenLevel, 
		AttackCompose, 
		DefendCompose, 
		LuckCompose, 
		AgilityCompose, 
		IsBinds, 
		BeginDate, 
		ValidDate,
		Skin,
		IsUsed,
		RemoveType,
		Hole1,
		Hole2,
		Hole3,
		Hole4,
		Hole5,
		Hole6,
		StrengthenTimes,
		Hole5Level,
		Hole5Exp,
		Hole6Level,
		Hole6Exp,
		IsGold,
		goldBeginTime,
		goldValidDate,
		StrengthenExp, 
		beadExp,
		beadLevel,
		beadIsLock,
		isShowBind,
		latentEnergyCurStr,
		latentEnergyNewStr,
		latentEnergyEndTime,
		Damage,
		Guard,
		Blood,
		Bless,
		AdvanceDate,
		AvatarActivity,
		goodsLock,
		MagicAttack,
		MagicDefence,
		MagicExp,
		MagicLevel,
		curExp,
		cellLocked) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?); 
		SELECT SCOPE_IDENTITY() as ItemID";
   }
} 
?>