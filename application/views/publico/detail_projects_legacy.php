<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Add Your favicon here -->
    <!--<link rel="icon" href="publico/img/favicon.ico">-->

    <title>Invision | Principal</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo assets_url('publico/css/bootstrap.min.css');?>" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="<?php echo assets_url('publico/css/animate.min.css');?>" rel="stylesheet">

    <link href="<?php echo assets_url('publico/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet">
    <style type="text/css">
        .carousel-caption {
            position: absolute;
            top: 100px;
            left: 0;
            bottom: auto;
            right: auto;
            text-align: left;
            margin-left: 5% !important;
        }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="<?php echo assets_url('publico/css/style.css');?>" rel="stylesheet">
</head>
<body id="page-top">
<div class="navbar-wrapper">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">WEBAPPLAYERS</a>
                </div>
            </div>
        </nav>
</div>
<div id="inSlider" class="carousel carousel-fade" data-ride="carousel">
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <!-- Set background for slide in css -->
            <div class="header-back one">
				<?php if($get_detail->image != null){ ?>
                <img class='img-fluid' src="<?php echo assets_url("img/projects/$get_detail->image");?>" alt="laptop" style='width: 100%;height: 100%;'/>
                <?php }else{ ?>
				<img class='img-fluid' src="<?php echo assets_url("img/landing/shattered.png");?>" alt="laptop" style='width: 100%;height: 100%;'/>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<section id="features" class="container services">
    <div class="row">
        <div class="col-sm-3">
            <h2>Proyecto</h2>
            <p><?php echo $get_detail->name;?></p>
        </div>
        <div class="col-sm-3">
            <h2>Descripción</h2>
            <p><?php echo $get_detail->description;?></p>
        </div>
    </div>
</section>
<br/>
<br/>
<br/>
<br/>


<script src="<?php echo assets_url('publico/js/jquery-2.1.1.js');?>"></script>
<script src="<?php echo assets_url('publico/js/pace.min.js');?>"></script>
<script src="<?php echo assets_url('publico/js/bootstrap.min.js');?>"></script>
<script src="<?php echo assets_url('publico/js/classie.js');?>"></script>
<script src="<?php echo assets_url('publico/js/cbpAnimatedHeader.js');?>"></script>
<script src="<?php echo assets_url('publico/js/wow.min.js');?>"></script>
<script src="<?php echo assets_url('publico/js/inspinia.js');?>"></script>
</body>
</html>
