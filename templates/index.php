<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Jubal</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pure/0.6.0/pure-min.css">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="pure-g wrapper">

		<div class="pure-u-1 header">
			<h1>Jubal</h1>
			<h2>Slide Generator</h2>
		</div>

		<div class="pure-u-5-12">
			<h3>Available Songs:</h3>

			<form action="" class="pure-form search-form">
				<div class="pure-control-group">
		            <label for="search">Search:</label>
		            <input id="search" type="text">
		        </div>
			</form>

			<div class="pure-menu pure-menu-open">
				<ul id="input">
				<?php foreach($files as $file): ?>
					<li><a data-id="<?php echo $file->id; ?>" data-pdf="<?php echo $file->downloadUrl; ?>"><?php echo $file->title; ?></a></li>
				<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<div class="pure-u-1-6"></div>

		<div class="pure-u-5-12">
			<h3>Songs for today:</h3>

			<div id="output" class="dd">
				<ol class="dd-list">
				</ol>
			</div>
		</div>
		
		<div class="pure-u-1" style="text-align: center;">
			<button id="download" class="pure-button pure-button-success pure-button-xlarge">Generate PDF</button>
		</div>

		<div class="pure-u-1" id="download-url">
			<p><a href="#"></a></p>
		</div>


		<div class="pure-u-1" id="error"><p>There was an error. That's all we know...</p></div>

		<div class="pure-u-1">
			<p><a href="logout">Logout</a></p>
		</div>


	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="scripts.js"></script>
</body>
</html>