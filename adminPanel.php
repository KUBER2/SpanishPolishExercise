<?php
session_start();


if(!isset($_SESSION['logged_id'])){

	if(isset($_POST['login'])){
		
		$login = filter_input(INPUT_POST, 'login');
		$password = filter_input(INPUT_POST, 'pswd');
		require_once 'database.php';
		
		$userQuery = $db->prepare('SELECT id , password FROM admins where login = :login');
		$userQuery->bindValue(':login', $login, PDO::PARAM_STR);
		$userQuery->execute();
		
		$user = $userQuery->fetch();
		//echo $user['id'] . " " . $user['password'].$_POST['pswd'];
		
		if($user && password_verify($password,$user['password'])){
			$_SESSION['logged_id']=$user['id'];
			unset($_SESSION['bad_attempt']);
		}else{
			$_SESSION['bad_attempt'] = true;
			header('Location: adminLog.php');
			exit();
		}
		
	}else{
		header('loacation: adminLog.php');
		exit();
	}
}


?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Panel admina</title>
    <meta charset="utf-8">
    <meta name="Stona Jakub Rola" content="Język hiszpański nauka" />
	<meta name="keywords" content="Jakub Rola, Hiszpański, nauka języka" />
	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<script src="JS/searchScript.js"></script>
	
	<link rel="stylesheet" href="css/style.css" type="text/css"/>
</head>
<body class="bg-dark">

	

	<div class="container">
	
		<!-- Nav pills -->
		<ul class="nav nav-pills" role="tablist">
			<li class="nav-item">
			    <a class="nav-link active" data-toggle="pill" href="#addWord">Dodaj słowo</a>
			</li>
			<li class="nav-item">
			    <a class="nav-link" data-toggle="pill" href="#verbs">Czasowniki</a>
			</li>
			<li class="nav-item">
			    <a class="nav-link" data-toggle="pill" href="#menu2">Menu 2</a>
			</li>
		</ul>

	    <!-- Tab panes -->
	    <div class="tab-content">
			<div id="addWord" class="container tab-pane active mt-2"> <!-- Adding word tab -->
			   
				<div class="col-sm-6 offset-md-2 mt-4">
				
					<?php //Display message if last action has been succeeded
						if(isset($_SESSION['successMsg'])){
							echo '<div class="alert alert-success alert-dismissible"><strong>Success!</strong>' . $_SESSION['successMsg'] . '</div>';
							unset($_SESSION['successMsg']);
						}
					?>
					
					<form action="addWord.php" method="post">
			
						<div class="form-group">
							<label for="word">Słowo po hiszpańsku</label>
							<input type="text" class="form-control" id="word" name="word">
						</div>
						
						<div class="form-group">
							<label for="translation">Tłumaczenie</label>
							<input type="text" class="form-control" id="translation" name="translation">
						</div>
						
						<div class="form-group">
							<label for="category">Kategoria</label>
							<input type="text" class="form-control" id="category" name="category">
						</div>
						
						<div class="form-group">
							<label for="example">Przykład użycia</label>
							<input type="text" class="form-control" id="example" name="example">
						</div>
						
						
						<div class="form-group">
							<label for="PartOfSpeech">Część mowy:</label>
							<div class="" id="PartOfSpeech">
									
								<div class="form-check-inline">
									<label class="form-check-label" for="radio1">
										<input type="radio" class="form-check-input" id="radio1" name="PoS" value="rzeczownik" >Rzeczownik
									</label>
								</div>
								
								<div class="form-check-inline">
									<label class="form-check-label" for="radio2">
										<input type="radio" class="form-check-input" id="radio2" name="PoS" value="czasownik" >Czasownik
									</label>
								</div>
								
								<div class="form-check-inline">
									<label class="form-check-label" for="radio3">
										<input type="radio" class="form-check-input" id="radio3" name="PoS" value="przymiotnik" >Przymiotnik
									</label>
								</div>
								
								<div class="form-check-inline">
									<label class="form-check-label" for="radio4">
										<input type="radio" class="form-check-input" id="radio4" name="PoS" value="inne" >Inny
									</label>
								</div>						
								
							</div>
							
						</div>
						
						
						<button type="submit" class="btn btn-success btn-block">Dodaj</button>
						
						<div  class="w-100 bg-danger m-2 " id="allert">
							<p><?= isset($_SESSION['errMsg']) ? $_SESSION['errMsg']: "" ?></p>
							<?php 
								unset($_SESSION['errMsg']);
								//print_r($_SESSION['tmp']);
							
							?>
						</div>
					
					</form>
				</div>
			   
			</div>
			<div id="verbs" class="container tab-pane fade"> <!-- Displaying verb tab --><br> 
			    <h3>Czasowniki</h3>
			    
				<input class="form-control" id="searchScript" type="text" placeholder="Szukaj..">
				<table class="table table-dark table-bordered">
					<thead>
					  <tr>
						<th>Słowo</th>
						<th>Tłumaczenie</th>
						<th>Kategoria</th>
					  </tr>
					</thead>
					<tbody id="wordsTable">
					  <?php
						require_once 'database.php';
						$verbsQuery = $db->query("SELECT slowo, tlumaczenie, kategoria FROM slownik WHERE czescMowy = 'czasownik';");
						$slowa =  $verbsQuery->fetchAll();
						foreach ($slowa as $slowo){
							echo "<tr><td>{$slowo['slowo']}</td><td>{$slowo['tlumaczenie']}</td><td>{$slowo['kategoria']}</td></tr>";
						}				  
					  ?>
					</tbody>
				</table>
					
				
			</div>
			<div id="menu2" class="container tab-pane fade"><br>
			    <h3>Menu 2</h3>
			    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
			</div>
	  </div>		
			
	
	</div>
	
	
	

</body>
</html>
