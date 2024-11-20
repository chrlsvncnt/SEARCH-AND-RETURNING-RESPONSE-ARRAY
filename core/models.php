<?php  

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}



function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}




function getAllUsers($pdo) {
	$sql = "SELECT * FROM users
			ORDER BY Name ASC";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getUserByID($pdo, $id) {
	$sql = "SELECT * from users WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function searchForAUser($pdo, $searchQuery) {
	
	$sql = "SELECT * FROM users WHERE 
			CONCAT(Name,PoliceRanking,Email,PhoneNumber,
				LicenseNumber,SubmissionDate) 
			LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%".$searchQuery."%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}



function insertNewUsers($pdo, $Name, $PoliceRanking, $Email, 
	$PhoneNumber, $LicenseNumber) {

	$sql = "INSERT INTO users
			(
				Name,
				PoliceRanking,
				Email,
				PhoneNumber,
				LicenseNumber
			)
			VALUES (?,?,?,?,?)
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([
		$Name, $PoliceRanking, $Email, 
		$PhoneNumber, $LicenseNumber
	]);

	if ($executeQuery) {
		return true;
	}

}

function editUser ($pdo, $Name, $PoliceRanking, $Email, $PhoneNumber, 
	$LicenseNumber, $id) {

	$sql = "UPDATE users
				SET Name = ?,
					PoliceRanking = ?,
					Email = ?,
					PhoneNumber = ?,
					LicenseNumber = ?
				WHERE id = ? 
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$Name, $PoliceRanking, $Email, $PhoneNumber, 
		$LicenseNumber, $id]);

	if ($executeQuery) {
		return true;
	}
}


function deleteUser($pdo, $id) {
	$sql = "DELETE FROM users
			WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$id]);

	if ($executeQuery) {
		return true;
	}
}


?>
