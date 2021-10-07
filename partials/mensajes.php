<?php if(isset($_SESSION['success'])): ?>
	<div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
<?php
	unset($_SESSION['success']);
	endif;
?>


<?php if(isset($_SESSION['danger'])): ?>
	<div class="alert alert-danger"><?php echo $_SESSION['danger']; ?></div>
<?php
	unset($_SESSION['danger']);
	endif;
?>