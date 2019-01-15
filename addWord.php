<?php

session_start();

//protection against sneaking in
if(!isset($_SESSION['logged_id'])){
	header('Location: adminLog.php');
	exit();
}
//echo print_r($_POST);

if((!isset($_POST['word']))||(!isset($_POST['translation']))||(!isset($_POST['PoS']))){
	$_SESSION['errMsg']='Brakuje danych: pola "Słowo po hiszpańsku" i "Tłuamczenia" nie mogą być puste!';
	header('Location: adminPanel.php');
	exit();
}


try{
	require_once 'database.php';
	$word = filter_input(INPUT_POST, 'word');
	$translation = filter_input(INPUT_POST, 'translation');	
	$PoS = filter_input(INPUT_POST, 'PoS');	
	$example = filter_input(INPUT_POST, 'example');
	$category = filter_input(INPUT_POST, 'category');
	$addWordQuery = $db->prepare('INSERT INTO slownik (slowo, czescMowy, tlumaczenie,kategoria, przyklad) VALUES (:word, :PoS, :translation, :category, :example)');
	$addWordQuery->bindValue(':word', $word, PDO::PARAM_STR);
	$addWordQuery->bindValue(':PoS', $PoS, PDO::PARAM_STR);
	$addWordQuery->bindValue(':translation', $translation, PDO::PARAM_STR);
	$addWordQuery->bindValue(':example', $example, PDO::PARAM_STR);
	$addWordQuery->bindValue(':category', $category, PDO::PARAM_STR);
	$addWordQuery->execute();
	$lastWordQuery = $db->query('SELECT * FROM slownik ORDER BY id DESC LIMIT 1');
	$lastWord =  $lastWordQuery->fetch();
	$_SESSION['successMsg']=' Dodałem: ' . $lastWord['slowo'];
	header('Location: adminPanel.php');
	exit();
	
}catch(PDOException $e){
	$_SESSION['errMsg']= $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Panel admina</title>
    <meta charset="utf-8">
    <meta name="Stona Jakub Rola" content="Język hiszpański nauka" />
	<meta name="keywords" content="Jakub Rola, Hiszpański, nauka języka" />
	

</head>
<body>

	

	

</body>
</html>
