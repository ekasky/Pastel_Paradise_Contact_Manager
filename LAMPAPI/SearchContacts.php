<?php

	require_once "../utils.php";

	$inData = getRequestInfo();

	// Validate the token
	$token              = get_jwt();
	$id                 = validate_token($token);

	if($id === false) {

		http_response_code(401);

		echo json_encode([
			'error' => 'Invalid token'
		]);

		exit();

	}
	
	$searchResults = "";
	$searchCount = 0;

	$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("select * from Contacts where (FirstName like ? OR LastName like ? OR Phone like ? OR Email like ?) and UserID=?");
		$colorName = "%" . $inData["search"] . "%";
		$stmt->bind_param("sssss", $colorName, $colorName, $colorName, $colorName, $id);
		$stmt->execute();
		
		$result = $stmt->get_result();
		
		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '{"FirstName" : "' . $row["FirstName"] .'","LastName" : "' . $row["LastName"] .'", "Phone" : "' . $row["Phone"] .'", "Email" : "' . $row["Email"] .'"}';
			//$searchResults .= '"' . $row["Name"] . '"';
		}
		
		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
		}
		else
		{
			returnWithInfo( $searchResults );
		}
		
		$stmt->close();
		$conn->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>