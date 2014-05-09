<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Spot My Friends</title>

	<style>
		/* Reset */

		body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,dfn,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}

		body{
			margin:0;
			padding:0;
			background: #fff url(../images/bg.gif) top center repeat-x;
			font-family: Helvetica Neue, Helvetica, Arial;
			color: #ffffff;
		}

		#wrapper{
			margin: 0 auto;
			width: 940px;
		}

		.main_content, .uppersection{
			width: 940px;
			float: left;
		}

		.uppersection {
			background: -webkit-linear-gradient(#005cff, #008aff); /* For Safari 5.1 to 6.0 */
			background: -o-linear-gradient(#005cff, #008aff); /* For Opera 11.1 to 12.0 */
			background: -moz-linear-gradient(#005cff, #008aff); /* For Firefox 3.6 to 15 */
			background: linear-gradient(#005cff, #008aff); /* Standard syntax */
			padding-bottom: 20px;
		}

		.logo{
			margin: 0 auto 60px auto;
			width: 160px;
			text-align: center;
			padding-top: 25px;
		}

		.logo h1{
			width: 95px;
			height: 70px;
			margin: 44px auto 5px auto;
			border-radius: 50%;
			background: #fff;
			color: #68b2b1;
			text-align: center;
			padding-top: 25px;
			font-size: 49px;
		}

		.logo span{
			font-size: 22px;
			text-align: center;
			margin: 0 auto;
			padding-top: 30px;
		}

		h2{
			font-weight: normal;
			text-align: center;
			display: block;
			font-size: 30px;
		}

		h2 strong{
			font-size: 64px;
		}


		h2 span{
			font-style: italic;
			font-size: 30px;
		}

		p{
			font-size: 19px;
			text-align: center;
			padding: 20px 140px 0;
			line-height: 26px;
			font-weight: 300;
		}

		ul.social{
			margin-top: 90px;
			float: left;
			padding-left: 120px;
		}

		ul.social li{
			float: left;
			margin-right: 40px;
			font-size: 11px;
			font-weight: bold;
			text-transform: uppercase;
		}

		ul.social li a{
			color: #333;
			text-decoration: none;
			padding-right: 30px;
			padding-bottom: 9px;
			padding-top: 4px;
		}

		ul.social li a:hover{
			text-decoration: underline;
			padding-top: 3px;
		}

		ul.social li a.fb{
			background: url(../images/social.png) right -30px no-repeat;
		}

		ul.social li a.tw{
			background: url(../images/social.png) right -90px no-repeat;
		}

	</style>

</head>
<body>
<div id="wrapper">
	<div class="main_content">
		<div class="uppersection">
			<div class="logo"><img src="../../../img/icon_128.png">
				<span>Spot My Friends</span></div>
			<h2><strong><?=$name?></strong> <br/><span>invites</span> you to share your location</h2>
			<p>Download Spot My Friends and start sharing your location to specific selected friends.</p>
		</div>
		<ul class="social">
			<li><a href="#" class="fb">iOS</a></li>
			<li><a href="#" class="tw">Android</a></li>
			<li><a href="https://rasteirinho.zipleen.com/find-my-friends/" class="google">Web</a></li>
		</ul>
	</div>
</div>
</body>
</html>
