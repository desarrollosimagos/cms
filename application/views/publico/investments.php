<br>
<br>
<br>
<!--
<br>
-->

<style>
	.learn-more {
		background-color: #1B426C !important;
		width: 90%;
	}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('public_heading_title_projects'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('public_heading_home_projects'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('public_heading_subtitle_projects'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
	
	<div class="row"><!-- Apertura de línea -->
		
		<div class="col-lg-12">
			<h2><?php echo $this->lang->line('public_list_title_projects'); ?> (<?php echo count($listar); ?>)</h2>
		</div>
		
		<br>
		<br>
		
		<!-- Apertura de ciclo -->
		<?php foreach($listar as $proyecto){ ?>
		<div class="col-lg-3">
			<div class="ibox float-e-margins">
				<!--<div class="ibox-title">
					<h5>Profile Detail</h5>
				</div>-->
				<div>
					<div class="ibox-content no-padding border-left-right">
						<?php if(count($proyecto->fotos_asociadas) > 0){ ?>
						<img alt="image" style="width: 100%; height:220px !important;" class="img-responsive" src="<?php echo base_url(); ?>assets/img/projects/<?php echo $proyecto->fotos_asociadas[0]->photo; ?>">
						<?php }else{ ?>
						<img alt="image" style="width: 100%; height:220px !important;" class="img-responsive" src="<?php echo base_url(); ?>assets/img/landing/shattered.png">
						<?php } ?>
					</div>
					<div class="ibox-content profile-content">
						<p>
						<?php echo $proyecto->type;?>
						</p>
						<h4><strong><?php echo $proyecto->name ?></strong></h4>
						<p>
							<?php echo $proyecto->description ?>
						</p>
						<div class="row m-t-lg">
							<div class="col-md-4">
								<h5><strong><?php echo number_format($proyecto->amount_min, 2, ',', '.') ?></strong></h5>
								<span class="label label-success"><?php echo $this->lang->line('public_list_minimum_projects'); ?></span>
							</div>
							<div class="col-md-4">
								<h5><strong>368</strong></h5>
								<span class="label label-success"><?php echo $this->lang->line('public_list_variable_projects'); ?></span>
							</div>
							<div class="col-md-4">
								<h5><strong>5yr</strong></h5>
								<span class="label label-success"><?php echo $this->lang->line('public_view_target_projects'); ?></span>
							</div>
						</div>
						<br>
						<div class="row user-button">
							<div class="row">
								<div class="col-md-12" align="center">
									<a type="button" href="<?php echo base_url();?>investments/detail/<?php echo $proyecto->id?>" class="btn btn-primary btn-sm btn-block b-r-xl learn-more">
									<i class="fa fa-info-circle fa-1.5x"></i>
									<?php echo $this->lang->line('public_list_learn_more_projects'); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<!-- Cierre de ciclo -->
		
	</div><!-- Cierre de línea -->
	
</div>
