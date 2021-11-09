<?php

	include('classes/DataBase.php');
	include('classes/Log.php');
	include('classes/Post.php');
	require ('FPDF/fpdf.php');
	
	$nome = "";
	$idade = "";
	$data = "";
	$estado = "";
	$cidade = "";
	$local = "";
	$sexo = "";
	$ddd = "";
	$telefone = "";
	$email = "";
	
	function criarPDF($NOME, $IDADE, $DATA, $ESTADO, $CIDADE, $LOCAL, $SEXO, $DDD, $TELEFONE, $EMAIL){
		
		$nome = $NOME;
		$idade = $IDADE;
		$data = $DATA;
		$estado = $ESTADO;
		$cidade = $CIDADE;
		$local = $LOCAL;
		if($SEXO == 'M'){
			$sexo = "Masculino";
		}
		if($SEXO == 'F'){
			$sexo = "Feminino";
		}
		$ddd = $DDD;
		$telefone = $TELEFONE;
		$email = $EMAIL;
		
		
		$image = base64_encode(file_get_contents($_FILES['postimg']['tmp_name']));
					
		$options = array('http'=>array(
								'method'=>"POST",
								'header'=>"Authorization: Bearer b4bf402d41a3c132410e6db8cb4ea17a21428e10\n" . 'Content-Type: application/x-www-form-urlencoded',
								'content'=>$image								
							)
						);
					
		$context = stream_context_create($options);
					
		$imgurURL = "https://api.imgur.com/3/image";
					
		$response = file_get_contents($imgurURL, false, $context);
					
		$response = json_decode($response);
						
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial', 'B', 20);
					
		$pdf->Cell(0,0, "   PROCURA-SE PESSOA DESAPARECIDA ", 0, 1, 'C');
		$pdf->Image($response->data->link,60,20,90, 90);
		$pdf->SetFont('Arial', 'B', 15);
		$pdf->Cell(0, 110, "", 0, 1);
		$pdf->Cell(0, 10, "Nome Completo: " . utf8_decode($nome), 0, 1);
		$pdf->Cell(0, 10, "Idade: " . $idade, 0, 1);
		$pdf->Cell(0, 10, "Sexo: " . $sexo, 0, 1);
		$pdf->Cell(0, 10, "Data de desaparecimento: " . date('d-m-Y', strtotime($data)), 0, 1);
		$pdf->Cell(0, 10, "Estado de residencia: " . utf8_decode($estado), 0, 1);
		$pdf->Cell(0, 10, "Cidade de residencia: " . utf8_decode($cidade), 0, 1);
		$pdf->Cell(0, 10, "Numero de contato: " . $ddd. "-" . $telefone, 0, 1);
		$pdf->Cell(0, 10, "E-mail de contato: " . $email, 0, 1);
		$pdf->Output('meuPdf.pdf', 'I');
	}	
		
	
	if( Login::isLoggedIn() == true){
		
		$loggedId = Login::isLoggedIn();
		
		//echo("Logged in ID: " .$loggedId);
	}
	else{
		
		die('<script>alert("Usuario Não loggado"); </script>');
	}
	
	
	if (isset($_GET['userid']) == true){
		
		if(DB::query('SELECT id FROM usuarios WHERE id=:userid', array(':userid'=>$loggedId)) == true){
			
			$userId = $_GET['userid'];
			
			
			if(isset($_POST['createrepdf']) == true){

				criarPDF($_POST['nome'], $_POST['idade'], $_POST['data'], $_POST['estado'], $_POST['cidade'], $_POST['local'], $_POST['sexo'], $_POST['ddd'], $_POST['telefone'], $email = $_POST['email']);
				
			}
		}
	}
	
	
?>

<!DOCTYPE html>

<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<title>TG</title>
		
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/fonts/ionicons.min.css">
		<link rel="stylesheet" href="assets/css/Footer-Dark.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
		<link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
		<link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/untitled.css">
		
	</head>

	<body>



		<header class="hidden-sm hidden-md hidden-lg">
			<div class="searchbox">
				<form>
					
					<h1 class="text-left">TG</h1>
					
					
					<div class="dropdown">
					
						<button class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">MENU <span class="caret"></span></button>
						
						<ul class="dropdown-menu dropdown-menu-right" role="menu">
							<li role="presentation" ><a href="#" id="perfil1">Meu Perfil</a></li>
							<li class="divider" role="presentation"></li>
							<li role="presentation"><a href="#" id="timeline1">Timeline </a></li>
							<li role="presentation"><a href="#" id="criarpedido1">Criar Pedido de procura </a></li>
							<li role="presentation"><a href="#" id="pedidoprocura1">Pedidos de procura</a></li>
							<li role="presentation"><a href="#">Criar Cartaz </a></li>
							<li role="presentation"><a href="#" id="logout1">Logout </a></li>
						</ul>
						
					</div>
					
				</form>
				
			</div>
			<hr>
		</header>
		
		
		
		<div>
		
			<nav class="navbar navbar-default hidden-xs navigation-clean">
			
				<div class="container">
				
					<div class="collapse navbar-collapse" id="navcol-1">
						
						<ul class="nav navbar-nav hidden-md hidden-lg navbar-right">
						
							<li role="presentation" class="active"><a href="#">Criar cartaz</a></li>
							<li class="dropdown open"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" href="#">Usuário <span class="caret"></span></a>
								<ul class="dropdown-menu dropdown-menu-right" role="menu">
									<li role="presentation" ><a href="#" id="perfil2">Meu Perfil</a></li>
									<li class="divider" role="presentation"></li>
									<li role="presentation"><a href="#" id="timeline2">Timeline </a></li>
									<li role="presentation"><a href="#" id="criarpedido2">Criar Pedido de procura </a></li>
									<li role="presentation"><a href="#" id="pedidoprocura2">Pedidos de procura </a></li>
									<li role="presentation"><a href="#">Criar cartaz</a></li>
									<li role="presentation"><a href="#" id="logout2">Logout </a></li>
								</ul>
							</li>
						</ul>
						
						<ul class="nav navbar-nav hidden-xs hidden-sm navbar-right">
						
							<li role="presentation"><a href="#" id="timeline3">Timeline</a></li>
							<li role="presentation" ><a href="#" id="criarpedido3">Criar Pedido de procura </a></li>
							<li role="presentation"><a href="#" id="pedidoprocura3">Pedidos de procura</a></li>
							<li role="presentation" class="active"><a href="#" id="localprocura3">Criar cartaz</a></li>
							<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Usuário <span class="caret"></span></a>
								
								<ul class="dropdown-menu dropdown-menu-right" role="menu">
									<li role="presentation" ><a href="#" id="perfil3">Meu Perfil</a></li>
									<li class="divider" role="presentation"></li>
									<li role="presentation"><a href="#" id="logout3">Logout </a></li>
								</ul>
								
							</li>
							
						</ul>
						
					</div>
					
				</div>
				
			</nav>
			
		</div>
		
		
		
		<div class="container">
			<h1> Crie o seu cartaz de procura  </h1>
		</div>
		
		
		
		
		<div>
		
			<div class="container">
			
				<div class="row">
				
					<div class="col-md-6">
						<ul class="list-group">
								<div class="timelineposts">

								</div>
						</ul>
						
					</div>
					
				</div>			
			</div>			
		
			<hr>
					
				<div class="container">	
				
					<form action="criar-pdf.php?userid=<?php echo($loggedId);?>" method="post" enctype="multipart/form-data">

						<br>
						<h3 class="visually-hidden"> <strong> Insira os dados da pessoa desaparecida para a criação do cartaz de procura</strong></h3>
						<h4 class="visually-hidden"> <strong> *O cartaz será criado em formato PDF</strong></h4>
						<br>
						<br>
						
						<label>Nome Completo</label>
						<input class="form-control" type="text" name="nome" placeholder="Nome Completo (Campo Obrigatório)" style="width:400px; height:25px;">
						<br>
						
						<label>Idade</label>
						<input class="form-control" type="text" name="idade" placeholder="Idade" style="width:100px; height:25px;">
						<br>
						
						<label>Sexo da pessoa desaparecido (Masculino ou Feminino)</label><br>
						<select name="sexo">
							<option value="M">M</option>
							<option value="F">F</option>
						</select>
						<br><br>
						
						<label>dia/mês/ano que a pessoa voi vista pela ultima vez</label>
						<input class="form-control" type="date" name="data" placeholder="dd/mm/aaaa" style="width:160px; height:25px;">
						<br>
						
						<label>Estado de residência</label>
						<input class="form-control" type="text" name="estado" placeholder="Estado (Campo Obrigatório)" style="width:400px; height:25px;">
						<br>
						
						<label>Cidade de Residência</label>
						<input class="form-control" type="text" name="cidade" placeholder="Cidade (Campo Obrigatório)" style="width:400px; height:25px;">
						<br>
						
						<label>Local onde a pessoa foi vista pela ultima vez (Rua ou Bairro)</label>
						<input class="form-control" type="text" name="local" placeholder="Rua ou Bairro" style="width:500px; height:25px;">
						<br>

						<label>DDD</label>
						<input class="form-control" type="text" name="ddd" placeholder="99" style="width:50px; height:25px;">
						<br>
						
						<label>Contato ( Numero de Telefone ou Celular)</label>
						<input class="form-control" type="text" name="telefone" placeholder="99999999" style="width:500px; height:25px;">
						<br>
						
						<label>Contato ( E-mail )</label>
						<input class="form-control" type="text" name="email" placeholder="exemplo@gmail.com" style="width:500px; height:25px;">
						<br>
						
						<input type="file" name="postimg">
						<br><br>
						
						<input type="submit" class="btn btn-default" type="button" name="createrepdf" value="Criar Cartaz" style="background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;" />
						
						<br>
						<br>
						<br>
						
					</form>
			
				</div>
				
			</div>
			
		</div>
		
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/js/bs-animation.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
		
		<script type="text/javascript">
			
			var timeline1 = document.getElementById('timeline1');
			var timeline2 = document.getElementById('timeline2');
			var timeline3 = document.getElementById('timeline3');
			timeline1.addEventListener('click', function() { document.location.href = '<?php echo("timeline.php?userid=" . $loggedId ) ?>'; });
			timeline2.addEventListener('click', function() { document.location.href = '<?php echo("timeline.php?userid=" . $loggedId ) ?>'; });
			timeline3.addEventListener('click', function() { document.location.href = '<?php echo("timeline.php?userid=" . $loggedId ) ?>'; });
			
			
			var perfil1 = document.getElementById('perfil1');
			var perfil2 = document.getElementById('perfil2');
			var perfil3 = document.getElementById('perfil3');
			perfil1.addEventListener('click', function() { document.location.href = '<?php echo("perfil.php?userid=" . $loggedId ) ?>'; });
			perfil2.addEventListener('click', function() { document.location.href = '<?php echo("perfil.php?userid=" . $loggedId ) ?>'; });
			perfil3.addEventListener('click', function() { document.location.href = '<?php echo("perfil.php?userid=" . $loggedId ) ?>'; });
			
			
			
			var criarpedido1 = document.getElementById('criarpedido1');
			var criarpedido2 = document.getElementById('criarpedido2');
			var criarpedido3 = document.getElementById('criarpedido3');
			criarpedido1.addEventListener('click', function() { document.location.href = '<?php echo("criar-pedido.php?userid=" . $loggedId ) ?>'; });
			criarpedido2.addEventListener('click', function() { document.location.href = '<?php echo("criar-pedido.php?userid=" . $loggedId ) ?>'; });
			criarpedido3.addEventListener('click', function() { document.location.href = '<?php echo("criar-pedido.php?userid=" . $loggedId ) ?>'; });
			
			
			
			var pedidoprocura1 = document.getElementById('pedidoprocura1');
			var pedidoprocura2 = document.getElementById('pedidoprocura2');
			var pedidoprocura3 = document.getElementById('pedidoprocura3');
			pedidoprocura1.addEventListener('click', function() { document.location.href = '<?php echo("procurar-pedido.php?userid=" . $loggedId ) ?>'; });
			pedidoprocura2.addEventListener('click', function() { document.location.href = '<?php echo("procurar-pedido.php?userid=" . $loggedId ) ?>'; });
			pedidoprocura3.addEventListener('click', function() { document.location.href = '<?php echo("procurar-pedido.php?userid=" . $loggedId ) ?>'; });
			
			
			
			var logout1 = document.getElementById('logout1');
			var logout2 = document.getElementById('logout2');
			var logout3 = document.getElementById('logout3');
			logout1.addEventListener('click', function() { document.location.href = '<?php echo("logout.php" ) ?>'; });
			logout2.addEventListener('click', function() { document.location.href = '<?php echo("logout.php" ) ?>'; });
			logout3.addEventListener('click', function() { document.location.href = '<?php echo("logout.php" ) ?>'; });
		</script>
		
	</body>

</html>
