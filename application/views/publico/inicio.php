<br>
<br>
<br>
<br>

<style>
	#page-wrapper {
		background: url(<?php echo base_url(); ?>assets/img/gallery/portfolio_EN_arte.jpg) no-repeat center fixed;
		background-size: cover;
	}
	
	.texto-encima-centrado {
		position: absolute;
		top: 50%;
		left: 25%;
		transform: translate(-50%, -50%);
		font-size: 20px;
	}
	
	.texto-chico {
		font-size: 15px;
	}
	
	ul {
		list-style: none;
	}
	
	@media screen and (max-height: 480px) {
	  .texto-encima-centrado {font-size: 10px;left: 28%;}
	  .texto-chico {font-size: 10px;}
	}
</style>

<div class="texto-encima-centrado">
	<ul>
		<li class=""><?php echo $this->lang->line('public_home_content_title1'); ?></li>
		<li class=""><?php echo $this->lang->line('public_home_content_title2'); ?></li>
		<li class="texto-chico"><?php echo $this->lang->line('public_home_content_subtitle1'); ?></li>
		<li class="texto-chico"><?php echo $this->lang->line('public_home_content_subtitle2'); ?></li>
	</ul>
</div>
