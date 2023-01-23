<?php
//call the FPDF library
session_start();
if(!isset($_SESSION['login_id'])){
	header("location: login.php");
	exit;
}




include 'db_connect.php';
require('fpdf185/fpdf.php');

    $new_value=$_GET['variable_name'];
	//$price=$_GET['price'];
    //$advance=$_GET['advance'];

$qry = $conn->query("SELECT * FROM parcels where reference_number = $new_value")->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
if($to_branch_id > 0 || $from_branch_id > 0){
	$to_branch_id = $to_branch_id  > 0 ? $to_branch_id  : '-1';
	$from_branch_id = $from_branch_id  > 0 ? $from_branch_id  : '-1';
$branch = array();
 $branches = $conn->query("SELECT *,concat(street,', ',city,', ',state,', ',zip_code,', ',country) as address FROM branches where id in ($to_branch_id,$from_branch_id)");
    while($row = $branches->fetch_assoc()):
    	$branch[$row['id']] = $row['address'];
	endwhile;
}

$qry1 = $conn->query("SELECT * FROM branches where id = $from_branch_id")->fetch_array();
foreach($qry1 as $kk => $vv){
	$$kk = $vv;
}

//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm
$pdf = new FPDF('P','mm','A4');
    /* A4 - 210 * 297 mm */
	$pdf -> AddPage();
//create pdf object

$pdf -> SetFont('Arial','B',10);
 
    //Creating Fill and Border color (RGB color value)
    $pdf -> SetDrawColor(223, 88, 42); 
    $pdf -> SetFillColor(223, 88, 42); 
 
    //Draw rectangle box (x,y,w,h,style)
    $pdf -> Rect(10,10,190,10,'DF');
 
    //Creating Fill and Border color (RGB color value)
    $pdf -> SetDrawColor(243, 243, 243); 
    $pdf -> SetFillColor(243, 243, 243); 
 
    //Draw rectangle box (x,y,w,h,style)
    $pdf -> Rect(10,20,190,50,'DF');
    //Image(string file ,float x ,float y , float w, float h , string type)
    $pdf -> Image("logo.png",20,27,20,20,'PNG');
 
    // Cell(width,hei$ght,text,border,line Break, align,colorFill)
 
    
   
    $pdf -> Cell(33,15,'',0,1);
    $pdf -> Cell(33,7,'',0,0);
    $pdf -> Cell(120,7,'Laxmi Mobile   ',0,1);
 
    
    $pdf -> Cell(33,7,'',0,0);
    $pdf -> Cell(120,7,'Phone : 7381910222',0,1);
    $pdf -> Cell(33,10,'',0,0);
    $pdf -> Cell(100,10,'www.laxmimobile.in',0,0);
    
    $pdf -> Cell(50,10,'INVOICE DETAILS',0,1);
 
	$pdf -> SetFont('Arial','B',10);
    $pdf -> Cell(133,7,'',0,0);
    $pdf -> Cell(50,7,date("Y-m-d"),0,1);
 
    $pdf -> Cell(133,7,'',0,0);
    $pdf -> Cell(50,7,$reference_number,0,1);
	
	
 
    $pdf -> Ln(15);// Line Break
    $pdf -> Cell(185,7,'<Payment terms (due on receipt, due in 15 days)>',0,1,'R');
 
    $pdf -> SetDrawColor(162, 162, 162); 
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(85,10,'BILL TO','B',0);
    $pdf -> Cell(10,7,'',0,0);
    $pdf -> Cell(85,10,'BRANCH DETAILS ','B',1);
 
    $pdf -> Ln(3);// Line Break
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(85,7,'Details :',0,0);
    $pdf -> Cell(10,7,'',0,0);
    $pdf -> Cell(85,7,'Details :',0,1);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(85,7,$sender_name,0,0);
    $pdf -> Cell(10,7,'',0,0);
    $pdf -> Cell(85,7,$street,0,1);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(85,7,$sender_address,0,0);
    $pdf -> Cell(10,7,'',0,0);
    $pdf -> Cell(85,7,$city,0,1);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(85,7,$sender_contact,0,0);
    $pdf -> Cell(10,7,'',0,0);
    $pdf -> Cell(85,7,$contact,0,1);
 
    $pdf -> Ln(3);// Line Break
 
    $pdf -> SetDrawColor(223, 88, 42); 
    $pdf -> SetFillColor(223, 88, 42); 
 
    //Draw rectangle box (x,y,w,h,style)
    $pdf -> Rect(15,130,180,8,'DF');
 
    $pdf -> SetTextColor(255, 255, 255);
    $pdf -> SetFillColor(223, 88, 42);  
 
    $pdf -> Ln(1.5);// Line Break
 
    $pdf -> SetFont('Arial','B',10);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'DESCRIPTION',0,0,'C',true);
    $pdf -> Cell(28.3,7,'QTY',0,0);
    $pdf -> Cell(28.3,7,'ADVANCE',0,0);
    $pdf -> Cell(28.3,7,'ESTIMATE',0,1);
 
 
 
    $pdf -> SetFillColor(225, 225, 225);
    $pdf -> SetTextColor(98,98,98);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Ln(1);// Line Break
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,"  ".$recipient_name,'L',0);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'','R',1,'R');
 
    $pdf -> SetFillColor(243, 243, 243);
    $pdf -> SetTextColor(98,98,98);
    $pdf -> SetDrawColor(162, 162, 162);
 $pro= "  having ". $recipient_address . " problem ";


    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,$pro,'L',0,'',true);
    $pdf -> Cell(28.3,7,'1',0,0,'',true);
    $pdf -> Cell(28.3,7,$width.".00",0,0,'',true);
    $pdf -> Cell(28.3,7,$price.".00",'R',1,'R',true);
 
    $pdf -> SetFillColor(225, 225, 225);
    $pdf -> SetTextColor(98,98,98);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','L',0);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'','R',1,'R');
 
    $pdf -> SetFillColor(243, 243, 243);
    $pdf -> SetTextColor(98,98,98);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','L',0,'',true);
    $pdf -> Cell(28.3,7,'',0,0,'',true);
    $pdf -> Cell(28.3,7,'',0,0,'',true);
    $pdf -> Cell(28.3,7,'','R',1,'R',true);
 
    $pdf -> SetFillColor(225, 225, 225);
    $pdf -> SetTextColor(98,98,98);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','L',0);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'','R',1,'R');
 
    $pdf -> SetFillColor(243, 243, 243);
    $pdf -> SetTextColor(98,98,98);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','L',0,'',true);
    $pdf -> Cell(28.3,7,'',0,0,'',true);
    $pdf -> Cell(28.3,7,'',0,0,'',true);
    $pdf -> Cell(28.3,7,'','R',1,'R',true);
 
    $pdf -> SetFillColor(225, 225, 225);
    $pdf -> SetTextColor(98,98,98);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','L',0);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'','R',1,'R');
 
    $pdf -> SetFillColor(243, 243, 243);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','L',0,'',true);
    $pdf -> Cell(28.3,7,'',0,0,'',true);
    $pdf -> Cell(28.3,7,'',0,0,'',true);
    $pdf -> Cell(28.3,7,'','R',1,'R',true);
 
    $pdf -> SetFillColor(225, 225, 225);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','L',0);
    $pdf -> Cell(28.3,7,'',0,0,true);
    $pdf -> Cell(28.3,7,'',0,0);
    $pdf -> Cell(28.3,7,'','R',1,'R');
 
    $pdf -> SetFillColor(243, 243, 243);
    $pdf -> SetDrawColor(162, 162, 162);
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','L',0,'',true);
    $pdf -> Cell(28.3,7,'',0,0,'',true);
    $pdf -> Cell(28.3,7,'',0,0,'',true);
    $pdf -> Cell(28.3,7,'','R',1,'R',true);
 
    $pdf -> SetFillColor(225, 225, 225);
    $pdf -> SetDrawColor(162, 162, 162);
 
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'','LB',0);
    $pdf -> Cell(28.3,7,'','B',0);
    $pdf -> Cell(28.3,7,'','B',0);
    $pdf -> Cell(28.3,7,'','RB',1,'R');
 
    $pdf -> SetDrawColor(162, 162, 162);
    //Creating Center Line
 
    $pdf -> Line(100,138,100,215);
    $pdf -> Line(128.5,138,128.5,215);
    $pdf -> Line(165,138,165,215);
    $pdf -> SetFont('Arial','B',10);
    $pdf -> Ln(3);// Line Break
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'TERM & CONDITION',0,0,'L');
    $pdf -> Cell(55,7,'ESTIMATE PRICE',0,0,'R');
    $pdf -> Cell(29,7,$price.".00",'B',1,'R');
 
    
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'Collect The Device within 30 days',0,0,'L');
    $pdf -> Cell(55,7,'ADVANCE',0,0,'R');
    $pdf -> Cell(29,7,$width.".00",'B',1,'R');
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'After 30 Days We are not responsible',0,0,'L');
    $pdf -> Cell(55,7,'DISCOUNT',0,0,'R');
    $pdf -> Cell(29,7,'20%','B',1,'R');
 
    
 
    $due=$price-$width;
 
    $pdf -> Cell(5,7,'',0,0);
    $pdf -> Cell(95,7,'For Your device',0,0,'L');
    $pdf -> Cell(55,7,'Amount To Pay',0,0,'R');
    $pdf -> Cell(29,7,$due.".00",'B',1,'R');
 
    $pdf -> SetDrawColor(223, 88, 42); 
    $pdf -> SetFillColor(223, 88, 42); 
 
    //Draw rectangle box (x,y,w,h,style)
    $pdf -> Rect(10,280,190,10,'DF');
 
 
    $pdf -> Output(); // Display output
?>