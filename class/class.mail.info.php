<?php
class MailInfo
{
   public $ID=0;
   public $Annex1="";
   public $Annex2="";
   public $Title="";
   public $Remark ="";
   public $Content = "";
   public $Money=0;
   public $Gold=0;
   public $IsExist = true;
   public $Receiver = "";
   public $ReceiverID = 0;
   public $Sender = "";
   public $SenderID=0;
   public $IfDelS = false;
   public $IsDelete= false;
   public $IsDelR= false;
   public $IsRead= false;
   public $SendTime = "2017-01-01 00:00:00.000";
   public $Type=83;
   public $Annex1Name="";
   public $Annex2Name="";
   public $Annex3="";
   public $Annex4="";
   public $Annex5="";
   public $Annex3Name="";
   public $Annex4Name="";
   public $Annex5Name="";
   public $ValidDate=240;//= 3days
   public $AnnexRemark = "";
   public $GiftToken=0;
   public function __construct($date)
   {
		$this->SendTime = $date->format("Y-m-d H:i:s");
   }
   public function GetValues()
   {
		return array($this->SenderID
           ,$this->Sender
           ,$this->ReceiverID
           ,$this->Receiver
           ,$this->Title
           ,$this->Content
           ,$this->SendTime
           ,$this->IsRead
           ,$this->IsDelR
           ,$this->IfDelS
           ,$this->IsDelete
           ,$this->Annex1
           ,$this->Annex2
           ,$this->Gold
           ,$this->Money
           ,$this->IsExist
           ,$this->Type
           ,$this->Remark
           ,$this->ValidDate
           ,$this->Annex1Name
           ,$this->Annex2Name
           ,$this->SendTime
           ,$this->Annex3
           ,$this->Annex4
           ,$this->Annex5
           ,$this->Annex3Name
           ,$this->Annex4Name
           ,$this->Annex5Name
           ,$this->AnnexRemark
           ,$this->GiftToken);
   }
   
   public function GetQuery()
   {
		return "INSERT INTO [dbo].[User_Messages]
           ([SenderID]
           ,[Sender]
           ,[ReceiverID]
           ,[Receiver]
           ,[Title]
           ,[Content]
           ,[SendTime]
           ,[IsRead]
           ,[IsDelR]
           ,[IfDelS]
           ,[IsDelete]
           ,[Annex1]
           ,[Annex2]
           ,[Gold]
           ,[Money]
           ,[IsExist]
           ,[Type]
           ,[Remark]
           ,[ValidDate]
           ,[Annex1Name]
           ,[Annex2Name]
           ,[SendDate]
           ,[Annex3]
           ,[Annex4]
           ,[Annex5]
           ,[Annex3Name]
           ,[Annex4Name]
           ,[Annex5Name]
           ,[AnnexRemark]
           ,[GiftToken])
     VALUES
           (?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?
           ,?); 
		   SELECT SCOPE_IDENTITY() as MailID ;";
   }
} 
?>