<?php
	require('./fpdf.php');

	$labels = json_decode($_POST["labels"]);
	$array = get_object_vars($labels);
	// $courseName = $_POST["courseName"];
	$folderName = $_POST["mobile"];
	$fName = $_POST["firstName"];

	$to = "bellamkondamanojb@gmail.com";
    $subject = "Online Form Submission of $fName";

	$errors= array();
	$success = array();

	$folderDatetime = $_POST["folderDatetime"];

	$extensions= array("jpeg","jpg","png", "doc", "docx", "zip", "rar", "pdf");


	class PDF extends FPDF
	{


		// Simple table
		function BasicTable($header, $data)
		{
		    // Header
		    $this->SetFont('','B', 10);
		    foreach($header as $col)
		        $this->Cell(70,7,$col,1);
		    $this->Ln();
		    // Data
		    foreach ($data as $key => $value) {
		    	if ($key !== "labels" && $key !== "agreement" && $key !== "folderDatetime") {
					$this->SetFont('','', 10);
					// if ($key === "courses") $value = $courseName;
					// $message .= "<tr><td>$array[$key]</td><td>:</td><td>$value</td></tr>";
					$this->Cell(70,6,$key,1);
					$this->Cell(70,6,$value,1);
				}
				$this->Ln();
			}
		    // foreach($data as $row)
		    // {
		    //     foreach($row as $col)
		    //         $this->Cell(40,6,$col,1);
		    //     $this->Ln();
		    // }
		}
	}

	$pdf = new PDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);



	if (!is_dir('./online-forms/'.$folderName)) {
	    mkdir('./online-forms/'.$folderName);
	    $isCreated = true;
	} else {
		$isCreated = true;
	}

	$message = "";

	$rootUrl = "http://localhost/pbs/pbsApp/pbs/";

	// $message = "<table>";

	// foreach ($_POST as $key => $value) {
	// 	# code...
	// 	if ($key !== "labels" && $key !== "courseName" && $key !== "agreement") {
	// 		if ($key === "courses") $value = $courseName;
	// 		$message .= "<tr><td>$array[$key]</td><td>:</td><td>$value</td></tr>";
	// 	}
	// }

	// $message .= "</table>";

	$tableHeaders = array('Name', 'Value');

	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(70,10,'Student Information PDF File');
	$pdf->Ln();
	$pdf->SetFont('Arial','U',10);
	$pdf->Write(5, $fName, $rootUrl."/online-forms/".$folderName."/".$folderDatetime.".pdf");
 	$pdf->Ln();
 	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(70,10,'Form Data');
	$pdf->Ln();
	$pdf->BasicTable($tableHeaders,$_POST);
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(70,10,'Uploaded Documents');
	$pdf->Ln();
	if ($isCreated === true) {
		foreach ($_FILES as $key => $value) {
			# code...
			$fileName = $value["name"];
			$fileSize = $value["size"];
			$fileType = $value["type"];
			$fileTempName = $value["tmp_name"];

			if ($fileName !== '') {
				$tempFile = explode('.', $fileName);
				$file_ext = end($tempFile);

				$location = "online-forms/".$folderName."/".$fileName;

				if(in_array($file_ext,$extensions) === false){
			    	$errors[]="".$fileName." - extension not allowed, please choose ".implode(", ", $extensions)." file extensions.";
			    }
			      
			    if($fileSize > 2097152) {
					$errors[]="".$fileName." - File size must be less than 2 MB";
			    }

				if(empty($errors)==true) {
				 	move_uploaded_file($fileTempName, $location);
				 	$pdf->SetFont('','U', 8);
				 	$fileManePair = "$key - $fileName";
				 	$pdf->Write(5, $fileManePair, $rootUrl."/online-forms/".$folderName."/".$fileName);
				 	$pdf->Ln();
				 	$success[]='Files uploaded successfully';
				} else {
				 	die(json_encode(array('message' => $errors, 'type' => 'error')));
				}
			}
		}
	}

	$filename="./online-forms/".$folderName."/".$folderDatetime.".pdf";

	$pdf->Output($filename,'F');

	$message .= "<p>Hi There,</p>";
	$message .= "<p>Here is the application of ".$fName."</p>";
	$message .= $rootUrl."/online-forms/".$folderName."/".$folderDatetime.".pdf";

	$message .= "<p>Please check all the documents at below path:</p>";
	$message .= $rootUrl."/online-forms/".$folderName."";

	// $header = "From:admissions@citycollegelondon.co.uk \r\n";
   $headers = "Reply-To: The Sender bellamkondamanojb@gmail.com\r\n"; 
   $headers .= "Return-Path: The Sender bellamkondamanojb@gmail.com\r\n"; 
   $headers .= "From: bellamkondamanojb@gmail.com" ."\r\n" .
   $headers .= "Organization: PBS\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Content-type: text/html; charset=utf-8\r\n";
   $headers .= "X-Priority: 3\r\n";
   $headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;

	// $retval = mail ($to,$subject,$message,$headers);
	$retval = true;

	if( $retval == true ) {
	$success[]="email sent";
	echo json_encode(array('message' => $success, 'type' => 'success'));
	}else {
	die(json_encode(array('message' => "not sent", 'type' => 'error')));
	}
	// $pdf->Output();
?>