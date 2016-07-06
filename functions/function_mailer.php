<?

require_once("email_template.php");
////////////////////////////////////////////////
// Ban khong thay doi cac dong sau:
function send_mailer($to, $title, $content, $attach_file = "", $id_error = "", $mail_reply = "", $title_reply = ""){

	 //if($_SERVER['SERVER_NAME'] == "localhost") return true;
		 if(file_exists("../classes/mailer/class.phpmailer.php")){
			require_once("../classes/mailer/class.phpmailer.php");
		 }

		$content             = eregi_replace("[\]", '', $content);
		$title_from_name     =  translate("Thông báo từ hott.vn");

		$mail      = new PHPMailer();
		$arrayHost    = array(1=> "mta8.mailmytour.com", 2=>"mta6.mailmytour.com");
		$host = array_rand($arrayHost);
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		//$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
		$mail->Host       = $arrayHost[$host]; // SMTP server
		$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
		$mail->From   = "noreply@mailmytour.com";
		$mail->FromName = $title_from_name;
		$mail->Username   = "noreply@mailmytour.com";  // GMAIL username
		$mail->Password   = "0L6r2XJcyK9JxjQbnRLo";            // GMAIL password
		$mail->CharSet    = "UTF-8";
		$mail->ContentType  = "text/html";
		$mail->Subject    = $title;
		$mail->AddAddress($to);
		$mail->Body       = $content;                      //HTML Body

		if ($attach_file != "") {
			$mail->AddAttachment($attach_file);
		}

		$mail->IsHTML(true); // send as HTML
		$mail->SMTPDebug = 1;
		//$mail->SMTPDebug = true;   //Print bug info when sent
		$send = $mail->Send();
		$arrayError = array();
		$arrayError["Host"]    = $mail->Host;
		$arrayError["From"]    = $mail->FromName;
		$arrayError["Email"]   = $to;
		$arrayError["subject"]   = $mail->Subject;
		$arrayError["Error"]   = $mail->ErrorInfo;
		saveLog1("all_email",  json_encode($arrayError));
		if(!$send)
		{
		//Nếu không send được thì thử lại với account khác, chỉ thử lại max đến 2 lần là dừng lại
		//strlen($id_error) <= 3 - Ứng với 1 lần retry
		if (strlen($id_error) <= 3){
		///send_mailer($to, $title, $content, $id_error);
		}
		/*
		echo "Email chua duoc gui di! <p>";
		echo "Loi: " . $mail->ErrorInfo;
		*/
		//exit;

			saveLog1("error_email",  "Loi: " . $mail->ErrorInfo);

		return false;
		}else{
		//trường hợp mail gửi thành công

		//echo $user_name . "<br>";
		//echo "Email da duoc gui!";
		return true;
		}
}

/**
 * Function create voucher PDF file
 */
function create_voucher_file($path_voucher, $file_name, $content, $title = "Mytour Voucher", $author = "Mytour.vn", $subject = "Mytour.vn", $keyword = "mytour, mytour.vn, voucher"){

	 global $l;

	 // create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($author);
		$pdf->SetTitle($title);
		$pdf->SetSubject($subject);
		$pdf->SetKeywords($keyword);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(5, 10, 5);
		$pdf->SetHeaderMargin(-10);
		$pdf->SetFooterMargin(10);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 10);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->addFont('DejaVuSerif', '',  'dejavuserif.php');
		$pdf->SetFont('DejaVuSerif', '', 9, '', 'true');

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $content, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output($path_voucher . $file_name . '.pdf', 'F');
}

 /**
	* Gui Email thong bao ve don dat phong cho khach version 2
	*/
 function booking_mail_customer_order_info($booking_id){
		//Bien config
		global  $footer_of_email;
	 global   $lang_time_format;
	 global   $array_pay_method;
	 global   $table_hotel_description;
	 global   $var_domain;
		//Content mail
		$content = "";

	 $db_booking  =  new db_query("SELECT booking_hotel.*, hot_name, hot_address
																 FROM booking_hotel
																 STRAIGHT_JOIN " . $table_hotel_description . " ON(boo_hotel = hot_hotel_id)
																 WHERE boo_id = " . intval($booking_id));
		if($booking_info = mysqli_fetch_assoc($db_booking->result)){

				$content .= '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444;">';

				$content .= '<p align="right" style="border-bottom: 1px solid #C8D6FF; font-weight: bold;">' . $booking_info["boo_bill_code"] . '</p>';

				$content .= '<p>' . translate("Xin chào") . ', <b>' . $booking_info["boo_customer_name"] . '</b></p>';

				$content    .=  '<p style="margin-bottom: 10px;">' . translate("Cảm ơn quý khách đã sử dụng dịch vụ đặt phòng của Mytour. Sau đây là thông tin đặt phòng của quý khách.") . '</p>';
			$content .= '<p style="margin-bottom: 20px; font-weight: bold;"><span style="color: #ff0000;">' . translate("Quý khách lưu ý") . ':</span> ' . translate("Việc thanh toán chỉ được tiến hành sau khi quý khách nhận được xác nhận phòng trống từ Mytour. Xác nhận đặt phòng thành công sẽ được gửi đến Quý khách khi Mytour nhận được thanh toán đầy đủ cho đơn phòng.") . '</p>';

			//Tu van vien
			$db_admin   =  new db_query("SELECT adm_name, adm_email, adm_phone
																		FROM admin_user
																		WHERE adm_id = " . $booking_info['boo_admin_divide_allow']);
			if($row = mysqli_fetch_assoc($db_admin->result)){
				 $content .= '<p style="margin-bottom: 20px;"><b>Nhân viên tư vấn đơn phòng của Quý khách là:</b> ' . $row['adm_name'] . '. <b>Email:</b> ' . $row['adm_email'] . '. <b>ĐT:</b> ' . $row['adm_phone'] . '</p>';
			}
			unset($db_admin);

				$content    .=  '<table width="100%" cellspacing="0" cellpadding="1" style="border: 2px solid #C8D6FF; padding: 10px;" border="0">';
				$content    .=  '<tr>';
				$content    .=  '<td colspan="2"><h3 style="color: #FD7000; margin-top: 5px; border-bottom: 2px solid #C8D6FF; padding-bottom: 5px;">' . translate("Thông tin đặt phòng") . '</h3></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Họ tên") . ':</td>';
				$content    .=  '<td><b>' . $booking_info["boo_customer_name"] . '</b></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_address"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Điện thoại") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_phone"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Email") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_email"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Mã đơn đặt phòng:") . '</td>';
				$content    .=  '<td>' . $booking_info["boo_bill_code"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Khách sạn") . ':</td>';
				$content    .=  '<td><a style="font-weight: bold;" href="' . $var_domain . url_hotel_detail(array('hot_id' => $booking_info['boo_hotel'], 'hot_name' => $booking_info['hot_name'])) . '">' . $booking_info["hot_name"] . '</a></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content    .=  '<td>' . $booking_info["hot_address"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thông tin phòng") . ':</td>';
				$content    .=  '<td>';

			//decode thong tin dat phong

			$array_room =  json_decode($booking_info['boo_book_info'], true);
			if(count($array_room) > 0){
				 $style   =  ' style="border-color: #AAAAAA;"';
				 $content .= '<table border="1" cellpadding="3" cellspacing="2" bordercolor="#E2E2E2" style="border-collapse: collapse; margin: 10px 0px; border-color: #AAAAAA;">';

				 foreach($array_room as $rom_id => $info){
						if(isset($info['numroom']) && isset($info['adults']) && isset($info['children']) && isset($info['money']) && isset($info['extra'])){
							 $db_select  =  new db_query("SELECT rom_id, rom_name
																						 FROM rooms
																						 WHERE rom_id = " . intval($rom_id));
							 if($row = mysqli_fetch_assoc($db_select->result)){

									$content .= '<tr>
																 <td rowspan="4" width="120" align="center"' . $style . '><b>' . $row['rom_name'] . '</b></td>
																 <td' . $style . '>' . translate("Số lượng") . '</td>
																 <td' . $style . '><b>' . $info['numroom'] . '</b></td>
															</tr>
															<tr>
																 <td' . $style . '>' . translate("Số người") . '</td>
																 <td' . $style . '><b>' . $info['adults'] . '</b> ' . translate("người lớn") . ', <b>' . $info['children'] . '</b> ' . translate("trẻ em") . '</td>
															</tr>
															<tr>
																 <td' . $style . '>' . translate("Số tiền") . '</td>
																 <td' . $style . '><b style="color: #FF0000;">' . format_number($info['money']) . ' VNĐ</b></td>
															</tr>
															<tr>
																 <td' . $style . '>' . translate("Thêm giường") . '</td>
																 <td' . $style . '>
																		<p style="margin: 0;">' . ($info['extra'] == 0 ? '<b>' . translate("Không") . '</b>' : '<b>' . $info['extra'] . '</b> ' . translate("giường") . '') . '</p>
																 </td>
															</tr>';
							 }
							 unset($db_select);
						}
				 }
				 $content .= '</table>';
			}
			$content .= '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Ngày nhận phòng") . ':</td>';
				$content    .=  '<td>' . date($lang_time_format, $booking_info["boo_time_start"]) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Ngày trả phòng") . ':</td>';
				$content    .=  '<td>' . date($lang_time_format, $booking_info["boo_time_finish"]) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Tổng số tiền") . ':</td>';
				$content    .=  '<td><b style="color: #FF0000;">' . format_number($booking_info['boo_total_money']) . ' VNĐ</b></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thanh toán") . ':</td>';
				$content    .=  '<td>' . (isset($array_pay_method[$booking_info['boo_payment_method']]) ? $array_pay_method[$booking_info['boo_payment_method']] : translate("Thanh toán tại văn phòng của Mytour")) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Yêu cầu riêng") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_comment"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr valign="top">';
				$content    .=  '<td>' . translate("Ghi chú") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_service_info"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '</table>';

				$content    .=  '<p style="margin-bottom: 10px;">' . translate("Quý khách vui lòng kiểm tra lại các thông tin trên. Nếu có sai sót, vui lòng liên hệ ngay với Mytour để cập nhật lại thông tin cho đơn đặt phòng của Quý khách.") . '</p>';
				$content    .=  '<p style="margin-top: 2px;">' . translate("Chân thành cảm ơn") . '!</p>';

				$content    .=  $footer_of_email;

				$content .= "</div>";

				if(send_mailer($booking_info['boo_customer_email'], translate("Thông tin đặt phòng trên website Mytour"), $content)) {
						return true;
				}
		}
	 unset($db_booking);

		return false;

 }

 /**
	* function mail thong tin cho KH sau khi don dat phong da o trang thai thanh cong
	*/
 function booking_mail_customer_success_info($booking_id){
		global  $footer_of_email;
	 global   $array_pay_method;
	 global   $table_hotel_description;
	 global   $var_domain;
	 global   $fs_path_domain;
	 $lang_time_format =  "d/m/Y";

		//Content mail
		$content       =  "";
	 $width_td      =  150;
	 $email_reply   =  "booking@mytour.vn";
	 $db_booking  =  new db_query("SELECT booking_hotel.*, hot_name, hot_address, adm_email, adm_name, adm_phone
																 FROM booking_hotel
																 STRAIGHT_JOIN " . $table_hotel_description . " ON(boo_hotel = hot_hotel_id)
																 STRAIGHT_JOIN admin_user ON(boo_admin_check = adm_id)
																 WHERE boo_id = " . intval($booking_id));
		if($booking_info = mysqli_fetch_assoc($db_booking->result)){
				if($booking_info['boo_customer_email'] != ""){
					 if($booking_info['adm_email'] != "") $email_reply = $booking_info['adm_email'];

						$content .= '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444;">';

						$content .= '<p align="right" style="border-bottom: 1px solid #C8D6FF; font-weight: bold;">' . $booking_info["boo_bill_code"] . '</p>';

						$content .= '<p>' . translate("Xin chào") . ', <b>' . $booking_info["boo_customer_name"] . '</b></p>';

						$content    .=  '<p style="margin-bottom: 5px;">' . translate("Cảm ơn Quý khách đã sử dụng dịch vụ đặt phòng của Mytour") . '.</p>';
				 $content   .=  '<p style="margin-bottom: 20px;">' . translate("Chúng tôi gửi Email xác nhận đơn đặt phòng của Quý khách đã được xử lý thành công.") . '</p>';

				 $content .= '<p style="margin-bottom: 20px;"><b>Nhân viên tư vấn đơn phòng của Quý khách là:</b> ' . $booking_info['adm_name'] . '. <b>Email:</b> ' . $booking_info['adm_email'] . '. <b>ĐT:</b> ' . $booking_info['adm_phone'] . '</p>';

						$content    .=  '<table width="100%" cellspacing="0" cellpadding="1" style="border: 2px solid #C8D6FF; padding: 10px;" border="0">';
						$content    .=  '<tr>';
						$content    .=  '<td colspan="2"><h3 style="color: #FD7000; margin-top: 5px; border-bottom: 2px solid #C8D6FF; padding-bottom: 5px;">' . translate("Thông tin đặt phòng") . '</h3></td>';
						$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Họ tên") . ':</td>';
				$content    .=  '<td><b>' . $booking_info["boo_customer_name"] . '</b></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_address"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Điện thoại") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_phone"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Email") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_email"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Mã đơn đặt phòng:") . '</td>';
				$content    .=  '<td>' . $booking_info["boo_bill_code"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Khách sạn") . ':</td>';
				$content    .=  '<td><a style="font-weight: bold;" href="' . $fs_path_domain . url_hotel_detail(array('hot_id' => $booking_info['boo_hotel'], 'hot_name' => $booking_info['hot_name'])) . '">' . $booking_info["hot_name"] . '</a></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content    .=  '<td>' . $booking_info["hot_address"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thông tin phòng") . ':</td>';
				$content    .=  '<td>';

				 //decode thong tin dat phong

				 $array_room =  json_decode($booking_info['boo_book_info'], true);
				 if(count($array_room) > 0){
						$style   =  ' style="border-color: #AAAAAA;"';
						$content .= '<table border="1" cellpadding="3" cellspacing="2" bordercolor="#E2E2E2" style="border-collapse: collapse; margin: 10px 0px; border-color: #AAAAAA;">';

						foreach($array_room as $rom_id => $info){
							 if(isset($info['numroom']) && isset($info['adults']) && isset($info['children']) && isset($info['money']) && isset($info['extra'])){
									$db_select  =  new db_query("SELECT rom_id, rom_name
																								FROM rooms
																								WHERE rom_id = " . intval($rom_id));
									if($row = mysqli_fetch_assoc($db_select->result)){

										 $content .= '<tr>
																		<td rowspan="4" width="120" align="center"' . $style . '><b>' . $row['rom_name'] . '</b></td>
																		<td' . $style . '>' . translate("Số lượng") . '</td>
																		<td' . $style . '><b>' . $info['numroom'] . '</b></td>
																 </tr>
																 <tr>
																		<td' . $style . '>' . translate("Số người") . '</td>
																		<td' . $style . '><b>' . $info['adults'] . '</b> ' . translate("người lớn") . ', <b>' . $info['children'] . '</b> ' . translate("trẻ em") . '</td>
																 </tr>
																 <tr>
																		<td' . $style . '>' . translate("Số tiền") . '</td>
																		<td' . $style . '><b style="color: #FF0000;">' . format_number($info['money']) . ' VNĐ</b></td>
																 </tr>
																 <tr>
																		<td' . $style . '>' . translate("Thêm giường") . '</td>
																		<td' . $style . '>
																			 <p style="margin: 0;">' . ($info['extra'] == 0 ? '<b>' . translate("Không") . '</b>' : '<b>' . $info['extra'] . '</b> ' . translate("giường") . '') . '</p>
																		</td>
																 </tr>';
									}
									unset($db_select);
							 }
						}
						$content .= '</table>';
				 }
				 $content .= '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Ngày nhận phòng") . ':</td>';
				$content    .=  '<td>' . date($lang_time_format, $booking_info["boo_time_start"]) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Ngày trả phòng") . ':</td>';
				$content    .=  '<td>' . date($lang_time_format, $booking_info["boo_time_finish"]) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Tổng số tiền") . ':</td>';
				$content    .=  '<td><b style="color: #FF0000;">' . format_number($booking_info['boo_total_money']) . ' VNĐ</b></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thanh toán") . ':</td>';
				$content    .=  '<td>' . (isset($array_pay_method[$booking_info['boo_payment_method']]) ? $array_pay_method[$booking_info['boo_payment_method']] : translate("Thanh toán tại văn phòng của Mytour")) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Yêu cầu riêng") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_comment"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr valign="top">';
				$content    .=  '<td>' . translate("Ghi chú") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_service_info"] . '<p style="margin: 0;">' . $booking_info["boo_voucher_note"] . '</p></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td width="' . $width_td . '" valign="top">' . translate("Chính sách hủy") . ':</td>';
				$content    .=  '<td>' . $booking_info["boo_voucher_cancel"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td width="' . $width_td . '" valign="top">' . translate("Link down voucher") . ':</td>';
				$content    .=  '<td><p style="margin: 0px;"><a href="' . ($fs_path_domain . '/vouchers/hotel/' . $booking_info["boo_bill_code"] . '.pdf') . '">Download</a></p><p style="margin: 0px;">(' . translate("Quý khách vui lòng mang theo Voucher này khi đến khách sạn") . ')</td>';
				$content    .=  '</tr>';

						$content    .=  '</table>';

						$content    .=  '<p><b>' . translate("Quý khách muốn xuất hóa đơn tiền phòng vui lòng gửi thông tin cho Mytour") . ' <a href="mailto:' . $email_reply . '">' . translate("tại đây") .  '</a></b></p>';

						$content    .=  '<p style="margin-bottom: 2px;">' . translate("Chúc Quý khách có những ngày nghỉ thật vui vẻ") . '.</p>';

						$content    .=  $footer_of_email;

						$content .= "</div>";

				 //Luu log lai de ktra
				 save_log_info("booking/bk_hotel_sent_customer", $booking_info['boo_customer_email']);

						if(send_mailer($booking_info['boo_customer_email'], translate("Xác nhận đặt phòng thành công từ Mytour"), $content)) {
								save_log_info("booking/bk_hotel_sent_customer_success", $booking_info['boo_customer_email']);
						return true;
						}else{
							 save_log_info("booking/bk_hotel_sent_customer_error", $booking_info['boo_customer_email']);
						}
			}
		}else{
			 //Luu log lai de ktra
			save_log_info("booking/bk_hotel_sent_customer_no_result", "Booking ID:" . $booking_id);
		}
	 unset($db_select);
		return false;
 }


 /**
	* Gui mail cho KS khi co don dat phong thanh cong
	*/

 function booking_mail_hotel_success_info($booking_id){
		global  $footer_of_email;
	 global   $array_pay_method;
	 global   $fs_path_domain;
	 global   $con_email_support;
	 global   $con_end_email_string;
	 global   $con_hotline;
	 global   $table_hotel_description;

	 $email_reply   =  "booking@mytour.vn";
		//Content mail
		$content = '';
	 $db_select  =  new db_query("SELECT booking_hotel.*, hot_id, hot_name, hot_email, hot_address, adm_email, adm_name, adm_phone
																 FROM booking_hotel
																 STRAIGHT_JOIN hotels ON(boo_hotel = hot_id)
																 STRAIGHT_JOIN " . $table_hotel_description . " ON(boo_hotel = hot_hotel_id)
																 STRAIGHT_JOIN admin_user ON(boo_admin_check = adm_id)
																 WHERE boo_id = " . intval($booking_id));
		if($booking_info = mysqli_fetch_assoc($db_select->result)){
			if($booking_info['hot_email'] != ""){
				 //Email reply den admin check
				 if($booking_info['adm_email'] != "") $email_reply = $booking_info['adm_email'];

						$content .= '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444">';

						$content .= "<p align='right' style='border-bottom: 1px solid #C8D6FF; font-weight: bold;'>" . $booking_info["boo_bill_code"] . "</p>";
						$content .= "<p>Xin chào, <b>" . $booking_info["hot_name"] . "</b></p>";
						$content    .=  '<p style="margin-bottom: 20px;">Quý khách sạn có một đơn đặt phòng mới trên hệ thống đặt phòng của Mytour.</p>';

						$content    .=  '<table width="100%" cellspacing="0" cellpadding="1" style="border: 2px solid #C8D6FF; padding: 10px;" border="0">';
						$content    .=  '<tr>';
						$content    .=  '<td colspan="2"><h3 style="color: #FD7000; margin-top: 5px; border-bottom: 2px solid #C8D6FF; padding-bottom: 5px;">Thông tin đặt phòng</h3></td>';
						$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>Họ tên người đặt:</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_name"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>Địa chỉ:</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_address"] . '</td>';
				$content    .=  '</tr>';

				 $content   .=  '<tr>';
				$content    .=  '<td>Số điện thoại:</td>';
				$content    .=  '<td>' . $booking_info["boo_customer_phone"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>Mã đơn đặt phòng:</td>';
				$content    .=  '<td>' . $booking_info["boo_bill_code"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>Thông tin phòng:</td>';
				$content    .=  '<td>';

				 //decode thong tin dat phong

				 $array_room =  json_decode($booking_info['boo_book_info'], true);
				 if(count($array_room) > 0){
						$style   =  ' style="border-color: #AAAAAA;"';
						$content .= '<table border="1" cellpadding="3" cellspacing="2" bordercolor="#E2E2E2" style="border-collapse: collapse; margin: 10px 0px; border-color: #AAAAAA;">';

						foreach($array_room as $rom_id => $info){
							 $db_room  =  new db_query("SELECT rom_id, rom_name
																					FROM rooms
																					WHERE rom_id = " . intval($rom_id));
							 if($row = mysqli_fetch_assoc($db_room->result)){

									$content .= '<tr>
																 <td rowspan="3" width="120" align="center"' . $style . '><b>' . $row['rom_name'] . '</b></td>
																 <td' . $style . '>Số lượng</td>
																 <td' . $style . '><b>' . $info['numroom'] . '</b></td>
															</tr>
															<tr>
																 <td' . $style . '>Số người</td>
																 <td' . $style . '><b>' . $info['adults'] . '</b> người lớn, <b>' . $info['children'] . '</b> trẻ em</td>
															</tr>
															<tr>
																 <td' . $style . '>Thêm giường</td>
																 <td' . $style . '>
																		<p style="margin: 0;">' . ($info['extra'] == 0 ? '<b>Không</b>' : '<b>' . $info['extra'] . '</b> giường') . '</p>
																 </td>
															</tr>';
							 }
							 unset($db_room);
						}
						$content .= '</table>';
				 }
				 $content .= '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>Ngày nhận phòng:</td>';
				$content    .=  '<td>' . date("d/m/Y", $booking_info["boo_time_start"]) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>Ngày trả phòng:</td>';
				$content    .=  '<td>' . date("d/m/Y", $booking_info["boo_time_finish"]) . '</td>';
				$content    .=  '</tr>';

				 $content   .=  '<tr>';
				$content    .=  '<td>Ghi chú:</td>';
				$content    .=  '<td>' . $booking_info["boo_service_info"] . '<p style="margin: 0;">' . $booking_info["boo_voucher_note"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>Thanh toán:</td>';
				$content    .=  '<td>' . $array_pay_method[$booking_info["boo_payment_method"]] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>Mytour thanh toán cho khách sạn:</td>';
				$content    .=  '<td><b style="color: #FF0000;">' . format_number($booking_info["boo_hotel_amount"]) . ' VNĐ</b></td>';
				$content    .=  '</tr>';

						$content    .=  '</table>';

				 $content .= '<p><b>Tư vấn viên:</b> ' . $booking_info['adm_name'] . '. <b>Email:</b> ' . $booking_info['adm_email'] . '. <b>ĐT:</b> ' . $booking_info['adm_phone'] . '</p>';
				 $content .= '<p><b>Quý khách sạn vui lòng xuất hóa đơn và gửi về địa chỉ sau:</b></p>';
				 $content .= '<p style="margin: 2px 0px;"><b>CÔNG TY TNHH MYTOUR VIỆT NAM</b></p>';
				 $content .= '<p style="margin: 2px 0px;"><b>Trụ sở:</b> Tầng 4, TTTM Vân Hồ, 51 Lê Đại Hành, p. Lê Đại Hành, q. Hai Bà Trưng, Hà Nội</p>';
				 $content .= '<p style="margin: 2px 0px;"><b>MS thuế:</b> 0105983269</p>';

						$content    .=  '<p style="margin-top: 20px;">-----------------------------------------------------------------------</p>';
				 $content .= '<p style="font-weight: bold; margin-bottom: 0px;">' . $con_end_email_string . '</p>';
				 $content .= '<p style="margin: 2px 0px;"><b>Hotline:</b> ' . $con_hotline . '</p>';
				 $content .= '<p style="margin: 2px 0px;"><b>Email:</b> ' . $con_email_support . '</p>';

						$content .= '</div>';

				 //Luu log lai de ktra
				 save_log_info("booking/bk_hotel_sent_hotel", $booking_info['hot_email']);

						if(send_mailer($booking_info['hot_email'], "Thông tin đặt phòng trên website Mytour", $content, "", $email_reply, "Xác nhận đơn phòng")) {
							 save_log_info("booking/bk_hotel_sent_hotel_success", $booking_info['hot_email']);
								return true;
						}else{
							 save_log_info("booking/bk_hotel_sent_hotel_error", $booking_info['hot_email']);
						}
				}
		}else{
			 //Luu log lai de ktra
			save_log_info("booking/bk_hotel_sent_hotel_no_result", "Booking ID:" . $booking_id);
		}
	 unset($db_select);

		return false;

 }


 /**
	* Gui mail moi danh gia cho KS sau khi khach checkout
	*/

 function invite_rate_mailing($email, $booking_info){
		global  $footer_of_email;
		//Content mail
		$content = "";
		if($booking_info != array()){

				$content .= "<div style='border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444'>";

				$content .= "<p>Thân gửi <b>" . $booking_info["orderName"] . ",</b></p>";

				$content    .=  '<p>Hi vọng Quý khách đã có khoảng thời gian vui vẻ và thoải mái tại khách sạn ' . $booking_info['hotelName'] . '. Quý khách vui lòng dành ít phút để chia sẻ kinh nghiệm, nhận xét của mình về khách sạn với hàng trăm nghìn thành viên trên website của Mytour bằng cách click vào link dưới đây:</p>';
				$content    .=  '<p><a title="Đến trang viết đánh giá cho khách sạn" href="' . $booking_info['rateLink'] . '" target="_blank">' . $booking_info['rateLink'] . '</a></p>';
				$content    .=  '<p>Nếu Quý khách không truy cập được vào link trên, vui lòng sao chép và dán vào trình duyệt đang dùng.</p>';
				$content    .=  '<p>Quý khách sẽ được thưởng 100 điểm khi Quý khách đăng nhập và có một bài nhận xét đầy đủ về khách sạn tại Mytour. Số điểm thưởng sẽ được quy ra tiền thưởng để Quý khách tích lũy và có cơ hội đặt được phòng miễn phí trên hệ thống của Mytour</p>';
				$content    .=  '<p>Cảm ơn Quý khách rất nhiều và mong rằng Mytour sẽ luôn là người bạn đồng hành đáng tin cậy của Quý khách trong tương lai.</p>';
				$content    .=  '<p>Chân thành cảm ơn.</p>';

				$content    .=  $footer_of_email;

				$content .= "</div>";

				if(send_mailer($email, "Nhận ngay 100 điểm thưởng từ Mytour cho bài viết đánh giá khách sạn " . $booking_info['hotelName'], $content)){
						return true;
				}

		}

		return false;

 }


 /**
	* function mail thong tin khach hang sau khi giao dich hoan thanh
	*/
 function confirm_transaction_mailing_customer($transaction_info) {
		global $footer_of_email;
	 $str = "đặt phòng";
	 if($transaction_info['type'] == "tour") $str = "đặt tour";
	 if($transaction_info['type'] == "deal") $str = "đặt deal";

		$content    =   "";
		$content .= "<div style='border:3px double #94c7ff; padding: 10px; line-height: 19px; color: #444'>";
		$content .= translate("Xin chào") . ", <b>" . $transaction_info["customName"] . "</b><br />";
		$content .= translate("Bạn đã thanh toán thành công đơn" . " " . $str . " với mã đơn là") . ": <b>" . $transaction_info["billCode"] . "</b><br />";
		$content .= translate("Tổng số tiền đã thanh toán") . ": <font color='red'>" . format_number($transaction_info["amountPayment"]) . "</font>"  . " VNĐ<br /><br />";
		$content .= translate("Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Chúc bạn sẽ có những ngày nghỉ vui vẻ!") . "<br /><br />";

		$content .= $footer_of_email;

		$content .= "</div>";
		if(send_mailer($transaction_info["customEmail"], translate("Thông tin thanh toán dịch vụ " . $str . " trên website Mytour"), $content)) {
				return true;
		} else {
				return false;
		}
 }

/**
	* function mail thong tin khach hang sau khi giao dich hoan thanh
	*/
function confirm_transaction_mailing_merchant($transaction_info) {
	 global $footer_of_email;
	 $str = 'đặt phòng';
	 if($transaction_info['type'] == "tour") $str = 'đặt tour';
	 if($transaction_info['type'] == "deal") $str = 'đặt deal';

	 $content = '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444">';

		$content .= '<p>Xin chào,</p>';
		$content    .=  '<p>Bạn vừa có một giao dịch thanh toán thành công cho đơn ' . $str . ' có mã  <b>' . $transaction_info["billCode"] . '</b> từ khách hàng <b>' . $transaction_info["customName"] . '</b></p>';
		$content    .=  '<p>Số tiền giao dịch: <font color="red">' . format_number($transaction_info["amountPayment"]) . ' VNĐ</font></p>';

		$content    .=  $footer_of_email;

		$content .= '</div>';

	 if(send_mailer($transaction_info["merchantEmail"], "Thông tin thanh toán dịch vụ " . $str . " trên website Mytour", $content)) {
		return true;
	 } else {
		return false;
	 }
}

/**
 * Mail cancel booking to customer
 * Type = 0: Cancel o list don dat moi
 * Type = 1: Cancel o list da thanh cong
 */

function mail_cancel_booking($booking_id){

		global  $footer_of_email;
	 $email_reply   =  "booking@mytour.vn";
	 global $table_hotel_description;
	 global   $var_domain;
	 global   $fs_path_domain;
	 $lang_time_format =  "d/m/Y";

	 $db_select  =  new db_query("SELECT booking_hotel.*, hot_name, hot_email, hot_address, hot_phone
																 FROM booking_hotel
																 STRAIGHT_JOIN hotels ON(boo_hotel = hot_id)
																 STRAIGHT_JOIN " . $table_hotel_description . " ON(boo_hotel = hot_hotel_id)
																 WHERE boo_id = " . $booking_id);
	 if($booking_info = mysqli_fetch_assoc($db_select->result)){
			//Thong tin dat phong
			$content_booking  =   '<table width="100%" cellspacing="0" cellpadding="1" style="border: 2px solid #C8D6FF; padding: 10px;" border="0">';
				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td colspan="2"><h3 style="color: #FD7000; margin-top: 5px; border-bottom: 2px solid #C8D6FF; padding-bottom: 5px;">' . translate("Thông tin đặt phòng") . '</h3></td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Họ tên") . ':</td>';
				$content_booking    .=  '<td><b>' . $booking_info["boo_customer_name"] . '</b></td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content_booking    .=  '<td>' . $booking_info["boo_customer_address"] . '</td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Điện thoại") . ':</td>';
				$content_booking    .=  '<td>' . $booking_info["boo_customer_phone"] . '</td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Email") . ':</td>';
				$content_booking    .=  '<td>' . $booking_info["boo_customer_email"] . '</td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Khách sạn") . ':</td>';
				$content_booking    .=  '<td><a style="font-weight: bold;" href="' . $fs_path_domain . url_hotel_detail(array('hot_id' => $booking_info['boo_hotel'], 'hot_name' => $booking_info['hot_name'])) . '">' . $booking_info["hot_name"] . '</a></td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content_booking    .=  '<td>' . $booking_info["hot_address"] . '</td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Thông tin phòng") . ':</td>';
				$content_booking    .=  '<td>';

			//decode thong tin dat phong

			$array_room =  json_decode($booking_info['boo_book_info'], true);
			if(count($array_room) > 0){
				 $style   =  ' style="border-color: #AAAAAA;"';
				 $content_booking .= '<table border="1" cellpadding="3" cellspacing="2" bordercolor="#E2E2E2" style="border-collapse: collapse; margin: 10px 0px; border-color: #AAAAAA;">';

				 foreach($array_room as $rom_id => $info){
						if(isset($info['numroom']) && isset($info['adults']) && isset($info['children']) && isset($info['money']) && isset($info['extra'])){
							 $db_select  =  new db_query("SELECT rom_id, rom_name
																						 FROM rooms
																						 WHERE rom_id = " . intval($rom_id));
							 if($row = mysqli_fetch_assoc($db_select->result)){

									$content_booking .= '<tr>
																 <td rowspan="4" width="120" align="center"' . $style . '><b>' . $row['rom_name'] . '</b></td>
																 <td' . $style . '>' . translate("Số lượng") . '</td>
																 <td' . $style . '><b>' . $info['numroom'] . '</b></td>
															</tr>
															<tr>
																 <td' . $style . '>' . translate("Số người") . '</td>
																 <td' . $style . '><b>' . $info['adults'] . '</b> ' . translate("người lớn") . ', <b>' . $info['children'] . '</b> ' . translate("trẻ em") . '</td>
															</tr>
															<tr>
																 <td' . $style . '>' . translate("Số tiền") . '</td>
																 <td' . $style . '><b style="color: #FF0000;">' . format_number($info['money']) . ' VNĐ</b></td>
															</tr>
															<tr>
																 <td' . $style . '>' . translate("Thêm giường") . '</td>
																 <td' . $style . '>
																		<p style="margin: 0;">' . ($info['extra'] == 0 ? '<b>' . translate("Không") . '</b>' : '<b>' . $info['extra'] . '</b> ' . translate("giường") . '') . '</p>
																 </td>
															</tr>';
							 }
							 unset($db_select);
						}
				 }
				 $content_booking .= '</table>';
			}
			$content_booking .= '</td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Ngày nhận phòng") . ':</td>';
				$content_booking    .=  '<td>' . date($lang_time_format, $booking_info["boo_time_start"]) . '</td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Ngày trả phòng") . ':</td>';
				$content_booking    .=  '<td>' . date($lang_time_format, $booking_info["boo_time_finish"]) . '</td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '<tr>';
				$content_booking    .=  '<td>' . translate("Yêu cầu riêng") . ':</td>';
				$content_booking    .=  '<td>' . $booking_info["boo_customer_comment"] . '</td>';
				$content_booking    .=  '</tr>';

				$content_booking    .=  '</table>';

			//Gui cho KH
			$content =  '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444">
												<p>Thân gửi Quý khách ' . $booking_info['boo_customer_name'] . ',</p>
												<p>Cảm ơn Quý khách đã đặt phòng trên website Mytour</p>';

			//Noi dung gui Mail cho KS
			$content_hotel =  '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444">
																<p>Thân gửi Quý khách sạn ' . $booking_info['hot_name'] . ',</p>';
			//Neu don phongn chua duco duyet
			if($booking_info['boo_view'] != 3){
				 $content .= '<p>
														Chúng tôi rất tiếc phải thông báo rằng thời gian từ ' . date("d/m/Y", $booking_info['boo_time_start']) . ' đến ' . date("d/m/Y", $booking_info['boo_time_finish']) . '
														không còn phòng trống nào tại khách sạn ' . $booking_info['hot_name'] . '.
												</p>';
			}else{
				 //Neu don phong da duoc check thanh cong
				 $content .= '<p>
														Mytour xin thông báo đơn phòng mã ' . $booking_info['boo_bill_code'] . ' có các thông tin đặt phòng như dưới đây đã bị hủy.
												Voucher Mytour đã gửi cho Khách hàng và Khách sạn hiện không còn giá trị sử dụng.
												</p>';
				 $content_hotel .= '<p>
																Mytour xin thông báo khách hàng đã hủy đơn phòng có mã ' . $booking_info['boo_bill_code'] . ' với các thông tin đặt phòng chi tiết dưới đây.
													 Voucher Mytour đã gửi cho Khách hàng và Khách sạn hiện không còn giá trị sử dụng.
														</p>';
				 $content .= $content_booking;
				 $content_hotel .= $content_booking;
			}

			$content .=    '<p>
														Hi vọng rằng Mytour có thể hỗ trợ Quý khách vào một dịp khác trong tương lai.
												</p>
										 <p>Chân thành cảm ơn Quý khách!</p>
												' . $footer_of_email . '
										</div>';

			$content_hotel .= $footer_of_email . '
														</div>';
			if($booking_info['boo_view'] != 3){
				 if(send_mailer($booking_info['boo_customer_email'], "Thông báo hết phòng tại " . $booking_info['hot_name'] . " từ Mytour", $content, $email_reply, "Đặt phòng Mytour")) {
						return true;
				}
			}else{
				 $check   =  0;
				 if(send_mailer($booking_info['boo_customer_email'], "Thông báo hủy đơn phòng tại " . $booking_info['hot_name'] . " từ Mytour", $content, $email_reply, "Đặt phòng Mytour")) {
						$check++;
				}
				 if($booking_info['hot_email'] != "" && send_mailer($booking_info['hot_email'], "Thông báo hủy đơn phòng từ Mytour", $content_hotel, $email_reply, "Đặt phòng Mytour")) {
						$check++;
				}

				 if($check > 0) return true;
			}

	 }

		return false;
}

//Create voucher pdf - NQH
function generate_voucher($boo_id, $type = "hotel", $signal = 1){
		global  $array_pay_method;
		global  $con_email_support;
		global  $con_hotline;
		global  $arr_currency;
	 global   $con_end_email_string;
	 global   $array_attribute;
	 global   $table_hotel_description;

		//Nếu là đặt phòng khuyến mại thì lấy thêm thông tin khuyến mại
		$sql = "";
		if ($type == "deal") {
				$sql = "STRAIGHT_JOIN promotionals ON (boo_promotion_id = pro_id)";
		}

		$str_return =   '';

		$style_none_border  =   ' style="height: 5px; line-height: 5px;"';
		$style_have_border  =   ' style="height: 7px; line-height: 7px;"';
	 $style_oneline       =  ' style="height: 1px; line-height: 1px;"';

		$db_select  =   new db_query("SELECT *
																						FROM    booking_hotel
																						STRAIGHT_JOIN hotels ON(boo_hotel = hot_id)
																						". $sql ."
																 STRAIGHT_JOIN " . $table_hotel_description . " ON(boo_hotel = hot_hotel_id)
																 STRAIGHT_JOIN admin_user ON(boo_admin_check = adm_id)
																						WHERE boo_id = " . intval($boo_id));
		if($row =   mysqli_fetch_assoc($db_select->result)){
			$str_note   =  '';
				$str_voucher = '';
				if ($row['boo_voucher_note'] != "") {
				$str_voucher = '<p style="line-height: 5px;">' . $row['boo_voucher_note'] . '</p>';
		}
			$str_note   .= '<tr>
												<td colspan="2" width="700">
													 <p style="height: 0px; line-height: 0px;"></p>
													 <p style="height: 1px; line-height: 1px;"><b>Ghi chú:</b></p>
													 '. $str_voucher .'
													 <p style="line-height: 2px;">' . translate("Giờ nhận phòng :") . ' ' . ($row['hot_time_checkin'] != "" ? $row['hot_time_checkin'] : "14:00") . '</p>
													 <p style="line-height: 2px;">' . translate("Giờ trả phòng :") . ' ' . ($row['hot_time_checkout'] != "" ? $row['hot_time_checkout'] : "11:30") . '</p>
												</td>
										 </tr>';


			$str_attribute   = '<tr><td>&bull; Giá đã bao gồm VAT và phí dịch vụ.</td></tr>';

			foreach($array_attribute as $key => $attribute){
						$col    =   "col" . $attribute["col"];
						if(isset($row[$col])){
								if((intval($row[$col]) & intval($attribute['value'])) != 0){
										$str_attribute .= '<tr><td>&bull; ' . $attribute['title'] . '.</td></tr>';
								}
						}
				}
				//Nếu là đặt phòng khuyến mại thì thêm ghi chú này
				if ($type == "deal") {
						$str_attribute .= '<tr><td style="color: red">&bull;Đặt phòng khuyến mại : "'. $row['pro_title'] .'"</td></tr>';
						$str_attribute .= '<tr><td>&bull;Áp dụng đến : '. date("d/m/Y",$row['pro_dateend']) .'</td></tr>';
				}

			//phan hien thi cac phong
			$str_list_room =  '<tr>
																		<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;Mã đơn</p></td>
																		<td><p' . $style_have_border . '>&nbsp;&nbsp;<b>'. $row['boo_bill_code'] .'</b></p></td>
																 </tr>';


			//Signal
			$html_signal   =  '';

			if($signal == 1){
				 $html_signal   =  '<img style="height: 100px; line-height: 100px" src="/themes/images/mytour.png" />';
			}

			$array_room =  json_decode($row['boo_book_info'], true);
			if(count($array_room) > 0){

				 foreach($array_room as $rom_id => $info){
						$db_room  =  new db_query("SELECT rom_id, rom_name
																			 FROM rooms
																			 WHERE rom_id = " . intval($rom_id));
						if($row_room = mysqli_fetch_assoc($db_room->result)){

							 $str_list_room .= '<tr>
																						<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;Tên phòng</p></td>
																						<td>
																			 <table width="100%" border="0" cellpadding="1" cellspacing="1">
																				 <tr><td><p style="height: 5px; line-height: 5px;"><b>' . $row_room['rom_name'] . '</b></p></td></tr>
																			 </table>
																		</td>
																				</tr>
																				<tr>
																						<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;Số lượng</p></td>
																						<td><p' . $style_have_border . '>&nbsp;&nbsp;<b>' . $info['numroom'] .'</b> phòng' . ($info['extra'] > 0 ? ' (Thêm ' . $info['extra'] . ' giường)' : '') . '</p></td>
																				</tr>
																				<tr>
																						<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;Số người</p></td>
																						<td><p' . $style_have_border . '>&nbsp;&nbsp;Người lớn: <b>' . $info['adults'] .'</b>, Trẻ em: <b>' . $info['children'] .'</b></p></td>
																				</tr>';
						}
						unset($db_room);
				 }
			}
			$str_list_room .= '<tr>
																<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;<b>Ghi chú</b></p></td>
													 <td>
															<table width="100%" border="0" cellpadding="1" cellspacing="1">
																' . $str_attribute . '
															</table>
													 </td>
												</tr>';

				$str_return =   '<div style="border-bottom: 3px solid #EBF5FF;">
						<table width="100%" border="0" style="border-collapse: collapse;">
								<tr>
										<td width="25%"><img border="0" src="/themes/images/logo_new.png" /></td>
										<td width="25%">' . $html_signal . '</td>
										<td width="50%" align="right">
												<h1 style="color: #0099FF; font-size: 120px;">Hotel <span style="color: #C4BB95;">Voucher</span></h1>
										</td>
								</tr>
								<tr>
										<td colspan="3" align="right" style="font-style: inherit;">Hà Nội, ' . date("d/m/Y") . '</td>
								</tr>
						</table>
				</div>
				<div>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
								<tr>
										<td width="430">
												<table border="0" cellpadding="0" cellspacing="0" bordercolor="#C1C1C1" width="100%" style="border-collapse: collapse; margin: 0px;">

														<tr>
																<td width="25%"><p' . $style_none_border . '>Khách hàng:</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . $row['boo_customer_name'] . '</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>Địa chỉ:</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . $row['boo_customer_address'] . '</b></p></td>
														</tr>
										 <tr><td colspan="2">&nbsp;</td></tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>Khách sạn:</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . $row['hot_name'] . '</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>Địa chỉ:</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . $row['hot_address'] . '</b></p></td>
														</tr>
										 <tr>
																<td width="25%"><p' . $style_none_border . '>Điện thoại:</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . $row['hot_phone'] . '</b></p></td>
														</tr>
										 <tr><td colspan="2">&nbsp;</td></tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>Nhận phòng:</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . date("d/m/Y", $row['boo_time_start']) . '</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>Trả phòng:</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . date("d/m/Y", $row['boo_time_finish']) . '</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>Số tiền:</p></td>
																<td width="60%"><p' . $style_none_border . '><b style="color: #FF0000;">' . format_number($row['boo_total_money']) . ' VNĐ</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>Thanh toán:</p></td>
																<td width="60%">
																		<p' . $style_none_border . '>' . $array_pay_method[$row['boo_payment_method']] . '</p>
																</td>
														</tr>
												</table>
										</td>
										<td width="350">
												<table border="1" style="border-collapse: collapse; background-color: #F6F6F6;" cellpadding="0" cellspacing="0" width="100%">
										 ' . $str_list_room . '
									</table>
										</td>
								</tr>
						' . $str_note . '
						<tr>
							 <td colspan="2" width="700">
									<p style="height: 1px; line-height: 1px;"></p>
									<p style="height: 1px; line-height: 1px;"><b>Chính sách hủy phòng:</b></p>
									<p style="height: 5px; line-height: 5px;">' . removeHTML($row['boo_voucher_cancel']) . '</p>
							 </td>
						</tr>
						</table>
				</div>

				<p style="height: 1px; line-height: 1px;"><b>Được đặt bởi:</b></p>
				<table width="100%" border="0" cellpadding="2" cellspacing="2" style="border-collapse: collapse; background-color: #FFF; border: 1px solid #000000;">
						<tr>
								<td width="700">
										<table border="0" cellpadding="2" cellspacing="3">
												<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>CÔNG TY TNHH MYTOUR VIỆT NAM</b></p></td></tr>
												<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Trụ sở :</b> Tầng 9, TTTM Vân Hồ, 51 Lê Đại Hành, p. Lê Đại Hành, q. Hai Bà Trưng, Hà Nội</p></td></tr>
												<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Điện thoại :</b> ' . $con_hotline . '</p></td></tr>
									<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Fax :</b> 04.3974 7881</p></td></tr>
												<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Email: <a style="text-decoration: none;" href="mailto:' . $con_email_support . '">' . $con_email_support . '</a> - Website: <a href="http://mytour.vn" style="text-decoration: none;">http://mytour.vn</a></b></p></td></tr>
										</table>
								</td>
						</tr>
				</table>

				<p' . $style_none_border . '></p>
				<table width="100%" border="0" cellpadding="2" cellspacing="2" style="border-collapse: collapse; border: 1px solid #000000;">
						<tr><td colspan="2" style="font-weight: bold;"><p' . $style_have_border . '>&nbsp;&nbsp;&nbsp;&nbsp;Ghi chú:</p></td></tr>
						<tr>
								<td width="30">&nbsp;&nbsp;&nbsp;&nbsp;-</td>
								<td width="660"><p style="line-height: 5px;"><b style="color: #FF0000;">QUAN TRỌNG</b>: Khi checkin, Quý khách phải xuất trình phiếu voucher này và giấy Chứng minh thư nhân dân. Trường hợp Quý khách không xuất trình được có thể dẫn đến việc khách sạn yêu cầu trả thêm chi phí hoặc không cho quý khách checkin.</p></td>
						</tr>
						<tr>
								<td width="30">&nbsp;&nbsp;&nbsp;&nbsp;-</td>
								<td width="660"><p style="line-height: 5px;">Tất cả các phòng đặt trước đều được đảm bảo còn trống trong ngày khách đến. Trong trường hợp Quý khách không đến, phòng đặt sẽ được giải phóng và được xử lý theo quy định và điều khoản cho trường hợp hủy / không đến đã được ghi rõ ở bên trên.</p></td>
						</tr>
						<tr>
								<td width="30">&nbsp;&nbsp;&nbsp;&nbsp;-</td>
								<td width="660"><p style="line-height: 5px;">Tổng số tiền cho đơn đặt phòng này không bao gồm chi phí ăn uống tại quầy bar của khách sạn, chi phí điện thoại, dịch vụ giặt là... Quý khách sẽ thanh toán trực tiếp với khách sạn.</p></td>
						</tr>
				</table>

				<p' . $style_none_border . '></p>
				<p style="height: 0px; line-height: 0px; border-top: 2px dashed #E2E2E2;"></p>
			<p align="right" style="color: #3399FF; line-height: 1px; height: 1px;">NV tư vấn: ' . $row['adm_name'] . ' - ĐT: ' . $row['adm_phone'] . '</p>';
		}
		unset($db_select);

		//return content
		return $str_return;
}



 /**
	* function mail xac nhan thong tin dat phong cho khach
	*/
 function booking_tour_order_mailing($booking_id){
		//Bien config
		global  $footer_of_email;
	 global   $lang_time_format;
	 global   $array_pay_method;
	 global   $table_hotel_description;
	 global   $var_domain;
		//Content mail
		$content = "";

	 $db_select  =  new db_query("SELECT tour_booking.*, tou_id, tou_name
																 FROM tour_booking
																 STRAIGHT_JOIN tours ON(tbo_tour_id = tou_id)
																 WHERE tbo_id = " . $booking_id);

		if($booking_info = mysqli_fetch_assoc($db_select->result)){

				$content .= '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444;">';

				$content .= '<p align="right" style="border-bottom: 1px solid #C8D6FF; font-weight: bold;">' . $booking_info["tbo_code"] . '</p>';

				$content .= '<p>' . translate("Xin chào") . ', <b>' . $booking_info["tbo_customer_name"] . '</b></p>';

				$content    .=  '<p style="margin-bottom: 10px;">' . translate("Cảm ơn quý khách đã sử dụng dịch vụ đặt tour của Mytour. Sau đây là thông tin đặt tour của Quý khách.") . '</p>';
			$content .= '<p style="margin-bottom: 20px; font-weight: bold;">' . translate("Quý khách lưu ý: Dịch vụ vẫn chưa được xác nhận cho tới khi đơn đặt tour được thanh toán và nhận được thông báo đặt tour thành công từ Mytour.") . '</p>';

				$content    .=  '<table width="100%" cellspacing="0" cellpadding="1" style="border: 2px solid #C8D6FF; padding: 10px;" border="0">';
				$content    .=  '<tr>';
				$content    .=  '<td colspan="2"><h3 style="color: #FD7000; margin-top: 5px; border-bottom: 2px solid #C8D6FF; padding-bottom: 5px;">' . translate("Thông tin đặt") . '</h3></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Họ tên") . ':</td>';
				$content    .=  '<td><b>' . $booking_info["tbo_customer_name"] . '</b></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_customer_address"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Điện thoại") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_customer_phone"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Email") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_customer_email"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Mã đơn đặt tour:") . '</td>';
				$content    .=  '<td>' . $booking_info["tbo_code"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Tên tour") . ':</td>';
				$content    .=  '<td><a style="font-weight: bold;" href="' . $var_domain . url_tour_detail($booking_info) . '">' . $booking_info["tou_name"] . '</a></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thông tin đặt tour") . ':</td>';
				$content    .=  '<td>';

			$style   =  ' style="border-color: #AAAAAA;"';
			$content .= '<table border="1" cellpadding="3" cellspacing="2" bordercolor="#E2E2E2" style="border-collapse: collapse; margin: 10px 0px; border-color: #AAAAAA;">';

			$content .=    '<tr>
												<td' . $style . '>' . translate("Số người") . '</td>
												<td' . $style . '><b>' . $booking_info['tbo_person'] . '</b></td>
										 </tr>
										 <tr>
												<td' . $style . '>' . translate("Số tiền") . '</td>
												<td' . $style . '><b style="color: #FF0000;">' . format_number($booking_info['tbo_total_money']) . ' VNĐ</b></td>
										 </tr>
										 <tr>
												<td' . $style . '>' . translate("Ngày khởi hành") . '</td>
												<td' . $style . '><b>' . ($booking_info['tbo_departure_time'] > 0 ? date($lang_time_format, $booking_info['tbo_departure_time']) : "") . '</b></td>
										 </tr>
									</table>';

			$content .= '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thanh toán") . ':</td>';
				$content    .=  '<td>' . (isset($array_pay_method[$booking_info['tbo_payment_method']]) ? $array_pay_method[$booking_info['tbo_payment_method']] : translate("Thanh toán tại văn phòng của Mytour")) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Yêu cầu riêng") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_customer_comment"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '</table>';

				$content    .=  '<p style="margin-bottom: 10px;">' . translate("Quý khách vui lòng kiểm tra lại các thông tin trên. Nếu có sai sót, vui lòng liên hệ ngay với Mytour để cập nhật lại thông tin cho đơn đặt tour của Quý khách.") . '</p>';
				$content    .=  '<p style="margin-top: 2px;">' . translate("Chân thành cảm ơn") . '!</p>';

				$content    .=  $footer_of_email;

				$content .= "</div>";

				if(send_mailer($booking_info['tbo_customer_email'], translate("Thông tin đặt tour trên website Mytour"), $content)) {
						return true;
				}
		}
	 unset($db_select);

		return false;

 }

 /**
	* function mail thong tin cho KH sau khi don dat tour da o trang thai thanh cong
	*/
 function sendmail_tour_booking_customer_success($booking_id, $type="tour"){
		global  $footer_of_email;
	 global   $array_pay_method;
	 global   $table_hotel_description;
	 global   $fs_path_domain;

	 $lang_time_format =  "d/m/Y";
	 $sql_join = "STRAIGHT_JOIN tours ON(tbo_tour_id = tou_id)";
	 if ($type == "deal") {
		$sql_join = "STRAIGHT_JOIN promotionals ON(tbo_promotion_id = pro_id)";
	 }
		//Content mail
		$content = "";
	 $width_td      =  150;
	 $email_reply   =  "booking@mytour.vn";

	 $db_booking  =  new db_query("SELECT *
																 FROM tour_booking
																 STRAIGHT_JOIN admin_user ON(tbo_admin_check = adm_id)
																 ". $sql_join ."
																 WHERE tbo_id = " . intval($booking_id));
		if($booking_info = mysqli_fetch_assoc($db_booking->result)){
				if($booking_info['tbo_customer_email'] != ""){
					 if($booking_info['adm_email'] != "") $email_reply = $booking_info['adm_email'];

						$content .= '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444;">';

						$content .= '<p align="right" style="border-bottom: 1px solid #C8D6FF; font-weight: bold;">' . $booking_info["tbo_code"] . '</p>';

						$content .= '<p>' . translate("Xin chào") . ', <b>' . $booking_info["tbo_customer_name"] . '</b></p>';

						$content    .=  '<p style="margin-bottom: 5px;">' . translate("Cảm ơn Quý khách đã sử dụng dịch vụ đặt tour tại Mytour") . '.</p>';
				 $content   .=  '<p style="margin-bottom: 20px;">' . translate("Chúng tôi gửi Email xác nhận đơn đặt tour của Quý khách đã được xử lý thành công.") . '</p>';

						$content    .=  '<table width="100%" cellspacing="0" cellpadding="1" style="border: 2px solid #C8D6FF; padding: 10px;" border="0">';
						$content    .=  '<tr>';
						$content    .=  '<td colspan="2"><h3 style="color: #FD7000; margin-top: 5px; border-bottom: 2px solid #C8D6FF; padding-bottom: 5px;">' . translate("Thông tin đặt tour") . '</h3></td>';
						$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Họ tên") . ':</td>';
				$content    .=  '<td><b>' . $booking_info["tbo_customer_name"] . '</b></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_customer_address"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Điện thoại") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_customer_phone"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Email") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_customer_email"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Mã đơn đặt tour:") . '</td>';
				$content    .=  '<td>' . $booking_info["tbo_code"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . ($type == "deal" ? translate("Tên Tour khuyến mại") : translate("Tên tour")) . ':</td>';
				$content    .=  '<td><a style="font-weight: bold;" href="' . $fs_path_domain . ($type == "deal" ? url_deal_detail($booking_info) : url_tour_detail($booking_info)) . '">' . ($type == "deal" ? $booking_info['pro_title'] : $booking_info["tou_name"]) . '</a></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thông tin đặt tour") . ':</td>';
				$content    .=  '<td>';

				 $style   =  ' style="border-color: #AAAAAA;"';
				 $content .= '<table border="1" cellpadding="3" cellspacing="2" bordercolor="#E2E2E2" style="border-collapse: collapse; margin: 10px 0px; border-color: #AAAAAA;">';

				 $content .=    '<tr>
													 <td' . $style . '>' . translate("Số người") . '</td>
													 <td' . $style . '><b>' . $booking_info['tbo_person'] . '</b></td>
												</tr>
												<tr>
													 <td' . $style . '>' . translate("Số tiền") . '</td>
													 <td' . $style . '><b style="color: #FF0000;">' . format_number($booking_info['tbo_total_money']) . ' VNĐ</b></td>
												</tr>
												<tr>
													 <td' . $style . '>' . translate("Ngày khởi hành") . '</td>
													 <td' . $style . '><b>' . ($booking_info['tbo_departure_time'] > 0 ? date($lang_time_format, $booking_info['tbo_departure_time']) : "") . '</b></td>
												</tr>
										 </table>';

				 $content .= '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thanh toán") . ':</td>';
				$content    .=  '<td>' . (isset($array_pay_method[$booking_info['tbo_payment_method']]) ? $array_pay_method[$booking_info['tbo_payment_method']] : translate("Thanh toán tại văn phòng của Mytour")) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Yêu cầu riêng") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_customer_comment"] . '</td>';
				$content    .=  '</tr>';

				 $content   .=  '<tr valign="top">';
				$content    .=  '<td>' . translate("Ghi chú") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_voucher_note"] . '</td>';
				$content    .=  '</tr>';

				 $content   .=  '<tr valign="top">';
				$content    .=  '<td>' . translate("Chính sách hủy") . ':</td>';
				$content    .=  '<td>' . $booking_info["tbo_voucher_cancel"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td valign="top">' . translate("Link down voucher") . ':</td>';
				$content    .=  '<td><p style="margin: 0px;"><a href="' . ($fs_path_domain . '/vouchers/tour/' . $booking_info["tbo_code"] . '.pdf') . '">Download</a></p><p style="margin: 0px;">(' . translate("Quý khách vui lòng mang theo Voucher này khi khởi hành tour") . ')</td>';
				$content    .=  '</tr>';

						$content    .=  '</table>';

						$content    .=  '<p style="margin-bottom: 10px;">' . translate("Quý khách vui lòng kiểm tra lại các thông tin trên. Nếu có sai sót, vui lòng liên hệ ngay với Mytour để cập nhật lại thông tin cho đơn đặt tour của Quý khách.") . '</p>';
						$content    .=  '<p style="margin-top: 2px;">' . translate("Chân thành cảm ơn") . '!</p>';

						$content    .=  '<p><b>' . translate("Quý khách muốn xuất hóa đơn tiền đặt tour vui lòng gửi thông tin cho Mytour") . ' <a href="mailto:' . $email_reply . '">' . translate("tại đây") .  '</a></b></p>';

				 //Nhan vien tu van
				 $db_admin   =  new db_query("SELECT adm_name, adm_phone
																			 FROM admin_user
																			 WHERE adm_id = " . intval($booking_info['tbo_admin_check']));
				 if($row_admin = mysqli_fetch_assoc($db_admin->result)){
						$content .= '<p><b>' . translate("Tư vấn viên") . ':</b> ' . $row_admin['adm_name'] . '. <b>ĐT:</b> ' . $row_admin['adm_phone'] . '</p>';
				 }
				 unset($db_admin);

						$content    .=  '<p style="margin-bottom: 2px;">' . translate("Chúc Quý khách có những ngày nghỉ thật vui vẻ") . '.</p>';

						$content    .=  $footer_of_email;

						$content .= "</div>";

				 //Luu log lai de ktra
				 save_log_info("booking/bk_tour_sent_customer", $booking_info['tbo_customer_email']);

						if(send_mailer($booking_info['tbo_customer_email'], translate("Xác nhận đặt tour thành công từ Mytour"), $content)) {
							 save_log_info("booking/bk_tour_sent_customer_success", $booking_info['tbo_customer_email']);
								return true;
						}else{
							 save_log_info("booking/bk_tour_sent_customer_error", $booking_info['tbo_customer_email']);
						}
			}
		}else{
			 //Luu log lai de ktra
			save_log_info("booking/bk_tour_sent_customer_no_result", "Booking ID:" . $booking_id);
		}
	 unset($db_select);
		return false;
 }

//Create voucher pdf - NQH
function generate_voucher_tour($tbo_id, $type="tour", $signal = 1){
		global  $array_pay_method;
		global  $con_email_support;
		global  $con_hotline;
		global  $arr_currency;
	 global   $con_end_email_string;
	 global   $service_attribute;
	 global   $table_hotel_description;

		$str_return =   '';
		$sql_join = "STRAIGHT_JOIN tours ON(tbo_tour_id = tou_id)";
		if ($type == "deal") {
				$sql_join = "STRAIGHT_JOIN promotionals ON(tbo_promotion_id = pro_id)";
		}

		$style_none_border  =   ' style="height: 5px; line-height: 5px;"';
		$style_have_border  =   ' style="height: 7px; line-height: 7px;"';
	 $style_oneline       =  ' style="height: 1px; line-height: 1px;"';

		$db_select  =   new db_query("SELECT *
																						FROM    tour_booking
																						". $sql_join ."
																 STRAIGHT_JOIN admin_user ON(tbo_admin_check = adm_id)
																						WHERE tbo_id = " . intval($tbo_id));
		if($row =   mysqli_fetch_assoc($db_select->result)){
			$str_note   =  '';
			if($row['tbo_voucher_note'] != ""){
				 $str_note   .= '<tr>
													 <td colspan="2" width="700">
															<p style="height: 0px; line-height: 0px;"></p>
															<p style="height: 1px; line-height: 1px;"><b>' . translate("Ghi chú") . ':</b></p>
															<p style="line-height: 5px;">' . $row['tbo_voucher_note'] . '</p>
													 </td>
												</tr>';
			}

			$str_attribute =  '';
			if ($type == "tour") {
					if((intval($row['col2']) & intval($service_attribute)) != 0){
						 $str_attribute .=  '<tr><td>&bull; ' . translate("Đã bao gồm thuế và phí dịch vụ") . '.</td></tr>';
					}else{
						 $str_attribute .=  '<tr><td>&bull; ' . translate("Chưa bao gồm thuế và phí dịch vụ") . '.</td></tr>';
					}
			}
			//Nếu là tour khuyến mại
			if ($type == "deal") {
				$str_attribute .= '<tr><td style="color: red;">&bull; ' . translate("Đặt tour khuyến mại") . '.</td></tr>';
			}

			//Signal
			$html_signal   =  '';

			if($signal == 1){
				 $html_signal   =  '<img style="height: 100px; line-height: 100px" src="/themes/images/mytour.png" />';
			}

				$str_return =   '<div style="border-bottom: 3px solid #EBF5FF;">
												<table width="100%" border="0" style="border-collapse: collapse;">
														<tr>
																<td width="25%"><img border="0" src="/themes/images/logo_new.png" /></td>
																<td width="25%">' . $html_signal . '</td>
																<td width="50%" align="right">
																		<h1 style="color: #0099FF; font-size: 120px;">Tour <span style="color: #C4BB95;">Voucher</span></h1>
																</td>
														</tr>
														<tr>
																<td colspan="3" align="right" style="font-style: inherit;">Hà Nội, ' . date("d/m/Y") . '</td>
														</tr>
												</table>
										</div>
										<div>
												<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
														<tr>
																<td width="400">
																		<table border="0" cellpadding="0" cellspacing="0" bordercolor="#C1C1C1" width="100%" style="border-collapse: collapse; margin: 0px;">
																				<tr>
																						<td width="25%"><p' . $style_none_border . '>' . translate("Khách hàng") . ':</p></td>
																						<td width="60%"><p' . $style_none_border . '><b>' . $row['tbo_customer_name'] . '</b></p></td>
																				</tr>
																				<tr>
																						<td width="25%"><p' . $style_none_border . '>' . translate("Địa chỉ") . ':</p></td>
																						<td width="60%"><p' . $style_none_border . '><b>' . $row['tbo_customer_address'] . '</b></p></td>
																				</tr>
																		<tr>
																						<td width="25%"><p' . $style_none_border . '>' . translate("Điện thoại") . ':</p></td>
																						<td width="60%"><p' . $style_none_border . '><b>' . $row['tbo_customer_phone'] . '</b></p></td>
																				</tr>
																				<tr>
																						<td width="25%"><p' . $style_none_border . '>' . translate("Số tiền") . ':</p></td>
																						<td width="60%"><p' . $style_none_border . '><b style="color: #FF0000;">' . format_number($row['tbo_total_money']) . ' VNĐ</b></p></td>
																				</tr>
																				<tr>
																						<td width="25%"><p' . $style_none_border . '>' . translate("Thanh toán") . ':</p></td>
																						<td width="60%" valign="top">' . $array_pay_method[$row['tbo_payment_method']] . '</td>
																				</tr>
																				<tr>
																						<td width="25%"><p' . $style_none_border . '>' . translate("Ngày khởi hành") . ':</p></td>
																						<td width="60%" valign="top">
																								<b>' . ($row['tbo_departure_time'] > 0 ? date("d/m/Y", $row['tbo_departure_time']) : '') . '</b>
																						</td>
																				</tr>
																				<tr>
																						<td width="25%"><p' . $style_none_border . '>' . translate("Ngày về") . ':</p></td>
																						<td width="60%" valign="top">
																								<b>' . ($row['tbo_time_finish'] > 0 ? date("d/m/Y", $row['tbo_time_finish']) : '') . '</b>
																						</td>
																				</tr>
																		</table>
																</td>
																<td width="350">
																		<table border="1" style="border-collapse: collapse; background-color: #F6F6F6;" cellpadding="0" cellspacing="0" width="100%">
																				<tr>
																										<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;Mã đơn</p></td>
																										<td><p' . $style_have_border . '>&nbsp;&nbsp;<b>'. $row['tbo_code'] .'</b></p></td>
																								</tr>
																		<tr>
																						<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;' . ($type == "deal" ? translate("Tên Tour khuyến mại") : translate("Tên tour")) . '</p></td>
																						<td>
																					<table width="100%" border="0" cellpadding="1" cellspacing="1">
																						<tr><td><p style="height: 5px; line-height: 5px;"><b>' . ($type == "deal" ? $row['pro_title'] : $row['tou_name']) . '</b></p></td></tr>
																					</table>
																			 </td>
																				</tr>
																				<tr>
																						<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;' . translate("Số người đặt") . '</p></td>
																						<td><p' . $style_have_border . '>&nbsp;&nbsp;<b>' . $row['tbo_person'] .'</b> ' . translate("người") . '</p></td>
																				</tr>
																		<tr>
																						<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;<b>' . translate("Ghi chú") . '</b></p></td>
																			 <td>
																					<table width="100%" border="0" cellpadding="1" cellspacing="1">
																						' . $str_attribute . '
																					</table>
																			 </td>
																		</tr>
																 </table>
																</td>
														</tr>
													 ' . $str_note . '

												</table>
										</div>

										<p style="height: 1px; line-height: 1px;"><b>' . translate("Được đặt bởi") . ':</b></p>
										<table width="100%" border="0" cellpadding="2" cellspacing="2" style="border-collapse: collapse; background-color: #FFF; border: 1px solid #000000;">
												<tr>
														<td width="700">
																<table border="0" cellpadding="2" cellspacing="3">
																		<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>CÔNG TY TNHH MYTOUR VIỆT NAM</b></p></td></tr>
																		<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Trụ sở :</b> Tầng 9, TTTM Vân Hồ, 51 Lê Đại Hành, p. Lê Đại Hành, q. Hai Bà Trưng, Hà Nội</p></td></tr>
																		<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Điện thoại :</b> ' . $con_hotline . '</p></td></tr>
																 <tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Fax :</b> 04.3974 7881</p></td></tr>
																		<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Email: <a style="text-decoration: none;" href="mailto:' . $con_email_support . '">' . $con_email_support . '</a> - Website: <a href="http://mytour.vn" style="text-decoration: none;">http://mytour.vn</a></b></p></td></tr>
																</table>
														</td>
												</tr>
										</table>

										<p' . $style_none_border . '></p>
										<p style="height: 0px; line-height: 0px; border-top: 2px dashed #E2E2E2;"></p>
										 <p align="right" style="color: #3399FF; line-height: 1px; height: 1px;">NV tư vấn: ' . $row['adm_name'] . ' - ĐT: ' . $row['adm_phone'] . '</p>';
		}
		unset($db_select);

		//return content
		return $str_return;
}

/**
 * Bitly URL
 */
function getSmallLink($longurl, $error = 0){
		// Bit.ly
		$url = "http://api.bit.ly/shorten?version=2.0.1&longUrl=" . $longurl . "&login=mytour&apiKey=R_db376e66a04df853ab47af1a931fc43b&format=json&history=1";

		$s = curl_init();
		curl_setopt($s,CURLOPT_URL, $url);
		curl_setopt($s,CURLOPT_HEADER,false);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($s);
		curl_close( $s );

		$obj = json_decode($result, true);
		if(isset($obj["results"][$longurl]["shortUrl"])){
				return $obj["results"][$longurl]["shortUrl"];
		}else{
			$error++;
		 if($error <= 2){
			 return getSmallLink($longurl,$error);
		 }else{
				 return $longurl;
		 }
		}
}

/**
	* function mail xac nhan thong tin dat phong cho khach
	*/
 function send_mail_booking_deal_order($booking_id,$type){
		//Bien config
		global  $footer_of_email;
	 global   $lang_time_format;
	 global   $array_pay_method;
	 global   $fs_path_domain;
		//Content mail
		$content = "";
		//Check kiểu đặt Deal -- khách sạn hay gói tour
	 if ($type == "deal_hotel") {
		$table             = "booking_hotel";
		$id_fied           = "boo_id";
		$id_promo_field   = "boo_promotion_id";
		$customer_name    = "boo_customer_name";
		$customer_address = "boo_customer_address";
		$customer_phone   = "boo_customer_phone";
		$customer_email   = "boo_customer_email";
		$customer_comment = "boo_customer_comment";
		$code                   = "boo_bill_code";
		$total_money      = "boo_total_money";
		$payment_method = "boo_payment_method";
	 } else {
		$table             = "tour_booking";
		$id_fied           = "tbo_id";
		$id_promo_field   = "tbo_promotion_id";
		$customer_name    = "tbo_customer_name";
		$customer_address = "tbo_customer_address";
		$customer_phone   = "tbo_customer_phone";
		$customer_email   = "tbo_customer_email";
		$customer_comment = "tbo_customer_comment";
		$code                   = "tbo_code";
		$total_money      = "tbo_total_money";
		$payment_method = "tbo_payment_method";
	 }
	 $db_select  =  new db_query("SELECT ". $table .".*, pro_title, pro_id
																 FROM ". $table ."
																 STRAIGHT_JOIN promotionals ON(". $id_promo_field ." = pro_id)
																 WHERE ". $id_fied ." = " . $booking_id);
		if($booking_info = mysqli_fetch_assoc($db_select->result)){

				$content .= '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444;">';

				$content .= '<p align="right" style="border-bottom: 1px solid #C8D6FF; font-weight: bold;">' . $booking_info[$code] . '</p>';

				$content .= '<p>' . translate("Xin chào") . ', <b>' . $booking_info[$customer_name] . '</b></p>';

				$content    .=  '<p style="margin-bottom: 10px;">' . translate("Cảm ơn quý khách đã sử dụng dịch vụ đặt deal của Mytour. Sau đây là thông tin đặt deal của Quý khách.") . '</p>';
			$content .= '<p style="margin-bottom: 20px; font-weight: bold;">' . translate("Quý khách lưu ý: Dịch vụ vẫn chưa được xác nhận cho tới khi đơn đặt được thanh toán và nhận được thông báo đặt thành công từ Mytour.") . '</p>';

				$content    .=  '<table width="100%" cellspacing="0" cellpadding="1" style="border: 2px solid #C8D6FF; padding: 10px;" border="0">';
				$content    .=  '<tr>';
				$content    .=  '<td colspan="2"><h3 style="color: #FD7000; margin-top: 5px; border-bottom: 2px solid #C8D6FF; padding-bottom: 5px;">' . translate("Thông tin đặt Deal") . '</h3></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Họ tên") . ':</td>';
				$content    .=  '<td><b>' . $booking_info[$customer_name] . '</b></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content    .=  '<td>' . $booking_info[$customer_address] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Điện thoại") . ':</td>';
				$content    .=  '<td>' . $booking_info[$customer_phone] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Email") . ':</td>';
				$content    .=  '<td>' . $booking_info[$customer_email] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Mã đơn đặt:") . '</td>';
				$content    .=  '<td>' . $booking_info[$code] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Tên deal") . ':</td>';
				$content    .=  '<td><a style="font-weight: bold;" href="' . $fs_path_domain . url_deal_detail($booking_info) . '">' . $booking_info["pro_title"] . '</a></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thông tin đặt Deal") . ':</td>';
				$content    .=  '<td>';

				 $style   =  ' style="border-color: #AAAAAA;"';
				 $content .= '<table border="1" cellpadding="3" cellspacing="2" bordercolor="#E2E2E2" style="border-collapse: collapse; margin: 10px 0px; border-color: #AAAAAA;">';

				 $content .=    '
												<tr>
													 <td' . $style . '>' . translate("Số tiền") . '</td>
													 <td' . $style . '><b style="color: #FF0000;">' . format_number($booking_info[$total_money]) . ' VNĐ</b></td>
												</tr>
										 </table>';

			$content .= '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thanh toán") . ':</td>';
				$content    .=  '<td>' . (isset($array_pay_method[$booking_info[$payment_method]]) ? $array_pay_method[$booking_info[$payment_method]] : translate("Thanh toán tại văn phòng của Mytour")) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Yêu cầu riêng") . ':</td>';
				$content    .=  '<td>' . $booking_info[$customer_comment] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '</table>';

				$content    .=  '<p style="margin-bottom: 10px;">' . translate("Quý khách vui lòng kiểm tra lại các thông tin trên. Nếu có sai sót, vui lòng liên hệ ngay với Mytour để cập nhật lại thông tin cho đơn đặt deal của Quý khách.") . '</p>';
				$content    .=  '<p style="margin-top: 2px;">' . translate("Chân thành cảm ơn") . '!</p>';

				$content    .=  $footer_of_email;

				$content .= "</div>";

				if(send_mailer($booking_info[$customer_email], translate("Thông tin đặt deal trên website Mytour"), $content)) {
						return true;
				}
		}
	 unset($db_select);

		return false;

 }

 /**
	* function mail thong tin cho KH sau khi don dat Deal da o trang thai thanh cong
	*/
 function sendmail_deal_booking_customer_success($booking_id){
		global  $footer_of_email;
	 global   $array_pay_method;
	 global   $var_domain;
	 global   $fs_path_domain;

	 $lang_time_format =  "d/m/Y";

		//Content mail
		$content = "";
	 $width_td   =  150;
	 $db_booking  =  new db_query("SELECT *
																 FROM booking_deal
																 STRAIGHT_JOIN promotionals ON(bod_promotion_id = pro_id)
																 WHERE bod_id = " . intval($booking_id));
		if($booking_info = mysqli_fetch_assoc($db_booking->result)){

				$content .= '<div style="border:3px double #94C7FF; padding: 10px; line-height: 19px; color: #444444;">';

				$content .= '<p align="right" style="border-bottom: 1px solid #C8D6FF; font-weight: bold;">' . $booking_info["bod_code"] . '</p>';

				$content .= '<p>' . translate("Xin chào") . ', <b>' . $booking_info["bod_customer_name"] . '</b></p>';

				$content    .=  '<p style="margin-bottom: 5px;">' . translate("Cảm ơn Quý khách đã sử dụng dịch vụ đặt deal tại Mytour") . '.</p>';
			$content  .=  '<p style="margin-bottom: 20px;">' . translate("Chúng tôi gửi Email xác nhận đơn đặt deal của Quý khách đã được xử lý thành công.") . '.</p>';

				$content    .=  '<table width="100%" cellspacing="0" cellpadding="1" style="border: 2px solid #C8D6FF; padding: 10px;" border="0">';
				$content    .=  '<tr>';
				$content    .=  '<td colspan="2"><h3 style="color: #FD7000; margin-top: 5px; border-bottom: 2px solid #C8D6FF; padding-bottom: 5px;">' . translate("Thông tin đặt Deal") . '</h3></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Họ tên") . ':</td>';
				$content    .=  '<td><b>' . $booking_info["bod_customer_name"] . '</b></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Địa chỉ") . ':</td>';
				$content    .=  '<td>' . $booking_info["bod_customer_address"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Điện thoại") . ':</td>';
				$content    .=  '<td>' . $booking_info["bod_customer_phone"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Email") . ':</td>';
				$content    .=  '<td>' . $booking_info["bod_customer_email"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Mã đơn đặt khuyến mại:") . '</td>';
				$content    .=  '<td>' . $booking_info["bod_code"] . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Tên khuyến mại") . ':</td>';
				$content    .=  '<td><a style="font-weight: bold;" href="' . $fs_path_domain . url_deal_detail($booking_info) . '">' . $booking_info["pro_title"] . '</a></td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thông tin đặt Deal") . ':</td>';
				$content    .=  '<td>';

			$style   =  ' style="border-color: #AAAAAA;"';
			$content .= '<table border="1" cellpadding="3" cellspacing="2" bordercolor="#E2E2E2" style="border-collapse: collapse; margin: 10px 0px; border-color: #AAAAAA;">';

			$content .=    '<tr>
												<td' . $style . '>' . translate("Số lượng") . '</td>
												<td' . $style . '><b>' . $booking_info['bod_quantity'] . '</b></td>
										 </tr>
										 <tr>
												<td' . $style . '>' . translate("Số tiền") . '</td>
												<td' . $style . '><b style="color: #FF0000;">' . format_number($booking_info['bod_total_money']) . ' VNĐ</b></td>
										 </tr>
									</table>';

			$content .= '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Thanh toán") . ':</td>';
				$content    .=  '<td>' . (isset($array_pay_method[$booking_info['bod_payment_method']]) ? $array_pay_method[$booking_info['bod_payment_method']] : translate("Thanh toán tại văn phòng của Mytour")) . '</td>';
				$content    .=  '</tr>';

				$content    .=  '<tr>';
				$content    .=  '<td>' . translate("Yêu cầu riêng") . ':</td>';
				$content    .=  '<td>' . $booking_info["bod_customer_comment"] . '</td>';
				$content    .=  '</tr>';

			$content  .=  '<tr valign="top">';
				$content    .=  '<td>' . translate("Ghi chú") . ':</td>';
				$content    .=  '<td>' . $booking_info["bod_voucher_note"] . '</td>';
				$content    .=  '</tr>';

			$content  .=  '<tr>';
				$content    .=  '<td valign="top">' . translate("Link down voucher") . ':</td>';
				$content    .=  '<td><p style="margin: 0px;"><a href="' . ($fs_path_domain . '/vouchers/deal/' . $booking_info["bod_code"] . '.pdf') . '">Download</a></p><p style="margin: 0px;">(' . translate("Quý khách vui lòng mang theo Voucher này khi đi nhận khuyến mại") . ')</td>';
				$content    .=  '</tr>';

				$content    .=  '</table>';

				$content    .=  '<p style="margin-bottom: 10px;">' . translate("Quý khách vui lòng kiểm tra lại các thông tin trên. Nếu có sai sót, vui lòng liên hệ ngay với Mytour để cập nhật lại thông tin cho đơn đặt deal của Quý khách.") . '</p>';
				$content    .=  '<p style="margin-top: 2px;">' . translate("Chân thành cảm ơn") . '!</p>';

				$content    .=  '<p><b>' . translate("Quý khách muốn xuất hóa đơn tiền đặt deal vui lòng gửi thông tin cho Mytour") . ' <a href="mailto:booking@mytour.vn">' . translate("tại đây") .  '</a></b></p>';

			//Nhan vien tu van
			$db_admin   =  new db_query("SELECT adm_name, adm_phone
																		FROM admin_user
																		WHERE adm_id = " . intval($booking_info['bod_admin_check']));
			if($row_admin = mysqli_fetch_assoc($db_admin->result)){
				 $content .= '<p><b>' . translate("Tư vấn viên") . ':</b> ' . $row_admin['adm_name'] . '. <b>ĐT:</b> ' . $row_admin['adm_phone'] . '</p>';
			}
			unset($db_admin);

				$content    .=  '<p style="margin-bottom: 2px;">' . translate("Chúc Quý khách có những ngày nghỉ thật vui vẻ") . '.</p>';

				$content    .=  $footer_of_email;

				$content .= "</div>";

				if(send_mailer($booking_info['bod_customer_email'], translate("Xác nhận đặt deal thành công từ Mytour"), $content)) {
						return true;
				}
		}
	 unset($db_select);
		return false;
 }

 //Create Deal voucher pdf - NQH
function generate_voucher_deal($boo_id){
		global  $array_pay_method;
		global  $con_email_support;
		global  $con_hotline;
		global  $arr_currency;
	 global   $con_end_email_string;
	 global   $service_attribute;

		$str_return =   '';

		$style_none_border  =   ' style="height: 5px; line-height: 5px;"';
		$style_have_border  =   ' style="height: 7px; line-height: 7px;"';
	 $style_oneline       =  ' style="height: 1px; line-height: 1px;"';

		$db_select  =   new db_query("SELECT *
																						FROM    booking_deal
																						STRAIGHT_JOIN promotionals ON(bod_promotion_id = pro_id)
																						WHERE bod_id = " . intval($boo_id));
		if($row =   mysqli_fetch_assoc($db_select->result)){
			$str_note   =  '';
			if($row['bod_voucher_note'] != ""){
				 $str_note   .= '<tr>
													 <td colspan="2" width="700">
															<p style="height: 0px; line-height: 0px;"></p>
															<p style="height: 1px; line-height: 1px;"><b>' . translate("Ghi chú") . ':</b></p>
															<p style="line-height: 5px;">' . $row['bod_voucher_note'] . '</p>
													 </td>
												</tr>';
			}
			$str_attribute =  translate("Đã bao gồm thuế và phí dịch vụ");
//      if((intval($col2) & intval($service_attribute)) != 0){
//         $str_attribute .=  '<tr><td>&bull; ' . translate("Đã bao gồm thuế và phí dịch vụ") . '.</td></tr>';
//      }else{
//         $str_attribute .=  '<tr><td>&bull; ' . translate("Chưa bao gồm thuế và phí dịch vụ") . '.</td></tr>';
//      }

				$str_return =   '<div style="border-bottom: 3px solid #EBF5FF;">
						<table width="100%" border="0" style="border-collapse: collapse;">
								<tr>
										<td width="50%"><img border="0" src="http://mytour.vn/themes/images/logo_new.png" /></td>
										<td width="50%" align="right">
												<h1 style="color: #0099FF; font-size: 120px;">Deal <span style="color: #C4BB95;">Voucher</span></h1>
										</td>
								</tr>
								<tr>
										<td colspan="2" align="right" style="font-style: inherit;">Hà Nội, ' . date("d/m/Y") . '</td>
								</tr>
						</table>
				</div>
				<div>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
								<tr>
										<td width="430">
												<table border="0" cellpadding="0" cellspacing="0" bordercolor="#C1C1C1" width="100%" style="border-collapse: collapse; margin: 0px;">

														<tr>
																<td width="25%"><p' . $style_none_border . '>' . translate("Khách hàng") . ':</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . $row['bod_customer_name'] . '</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>' . translate("Địa chỉ") . ':</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . $row['bod_customer_address'] . '</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>' . translate("Số lượng") . ':</p></td>
																<td width="60%"><p' . $style_none_border . '><b>' . $row['bod_quantity'] . '</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>' . translate("Số tiền") . ':</p></td>
																<td width="60%"><p' . $style_none_border . '><b style="color: #FF0000;">' . format_number($row['bod_total_money']) . ' VNĐ</b></p></td>
														</tr>
														<tr>
																<td width="25%"><p' . $style_none_border . '>' . translate("Thanh toán") . ':</p></td>
																<td width="60%">
																		<p' . $style_none_border . '>' . $array_pay_method[$row['bod_payment_method']] . '</p>
																</td>
														</tr>
												</table>
										</td>
										<td width="350">
												<table border="1" style="border-collapse: collapse; background-color: #F6F6F6;" cellpadding="0" cellspacing="0" width="100%">
										 <tr>
																<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;' . translate("Tên Deal") . '</p></td>
																<td>
													 <table width="100%" border="0" cellpadding="1" cellspacing="1">
														 <tr><td><p style="height: 5px; line-height: 5px;"><b>' . $row['pro_title'] . '</b></p></td></tr>
													 </table>
												</td>
														</tr>
														<tr>
																<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;' . translate("Áp dụng đến") . '</p></td>
																<td><p' . $style_have_border . '>&nbsp;&nbsp;<b>' . date("d/m/Y",$row['pro_dateend']) .'</b></p></td>
														</tr>
										 <tr>
																<td width="30%"><p' . $style_have_border . '>&nbsp;&nbsp;<b>' . translate("Ghi chú") . '</b></p></td>
												<td>
														 ' . $str_attribute . '
												</td>
										 </tr>
									</table>
										</td>
								</tr>
						' . $str_note . '

						</table>
				</div>

				<p style="height: 1px; line-height: 1px;"><b>' . translate("Được đặt bởi") . ':</b></p>
				<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse; background-color: #F6F6F6;">
						<tr>
								<td width="450">
										<table border="0" cellpadding="2" cellspacing="3">
												<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>Mytour.vn</b></p></td></tr>
												<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>' . translate("Địa chỉ") . ':</b>51 Lê Đại Hành, Hai Bà Trưng, Hà Nội</p></td></tr>
												<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>' . translate("Điện thoại") . ':</b> ' . $con_hotline . '</p></td></tr>
									<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>' . translate("Fax") . ':</b> 04.3974 7881</p></td></tr>
												<tr><td><p style="height: 3px; line-height: 3px;">&nbsp;&nbsp;<b>' . translate("Email") . ': <a href="mailto:' . $con_email_support . '">' . $con_email_support . '</a> - Website: <a href="http://mytour.vn">http://mytour.vn</a></b></p></td></tr>
										</table>
								</td>
								<td>
										<p style="height: 7px; line-height: 7px; text-transform: uppercase;">&nbsp;&nbsp;<b>Công ty TNHH Bán lẻ Nhanh</b></p>
								</td>
						</tr>
				</table>

				<p' . $style_none_border . '></p>
				<p' . $style_none_border . '></p>
				<p style="height: 0px; line-height: 0px; border-top: 2px dashed #E2E2E2;"></p>
				<p align="right" style="color: #3399FF; line-height: 5px; height: 5px;">Mytour.vn - Holine: ' . $con_hotline . ' - Email: ' . $con_email_support . '</p>';
		}
		unset($db_select);

		//return content
		return $str_return;
}

?>