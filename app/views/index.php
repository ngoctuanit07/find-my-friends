<?php /*
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Laravel4 AngularJS Starter Site</title>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

</head>

<div id="fb-root"></div>

<div ng-app="myApp">
    <div ng-view></div>
</div>

<!-- /.container -->
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '694514773928221',
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            oauth      : true, // enable OAuth 2.0
            xfbml      : true  // parse XFBML
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

</script>
<script src="vendor/angular/angular.min.js"></script>
<script src="vendor/angular-resource/angular-resource.min.js"></script>
<script src="vendor/angular-route/angular-route.min.js"></script>
<script src="vendor/angular-cookies/angular-cookies.min.js"></script>
<script src="vendor/angular-touch/angular-touch.min.js"></script>
<script src="vendor/angular-animate/angular-animate.min.js"></script>
<script src="vendor/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>

<script src="app/js/app.js"></script>
<script src="app/js/controllers.js"></script>
<script src="app/js/directives.js"></script>
<script src="app/js/filters.js"></script>
<script src="app/js/services.js"></script>
<script>
	angular.module("myApp").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');



</script>

</body>

</html>


*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title></title>

    <link href="vendor/ionic/release/css/ionic.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- IF using Sass (run gulp sass first), then uncomment below and remove the CSS includes above
    <link href="css/ionic.app.css" rel="stylesheet">
    -->

    <!-- ionic/angularjs js -->
    <script src="vendor/ionic/release/js/ionic.bundle.js"></script>

    <!-- cordova script (this will be a 404 during development) -->
    <script src="cordova.js"></script>

    <!-- your app's js -->
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '694514773928221',
                status     : true, // check login status
                cookie     : true, // enable cookies to allow the server to access the session
                oauth      : true, // enable OAuth 2.0
                xfbml      : true  // parse XFBML
            });
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

    </script>

    <script src='//maps.googleapis.com/maps/api/js?sensor=false'></script>
    <script src='vendor/underscore/underscore.js'></script>
    <script src="vendor/angular-google-maps/dist/angular-google-maps.js"></script>
    <script src="js/app.js"></script>
    <script src="js/controllers/HomeController.js"></script>
    <script src="js/controllers/FriendDetailController.js"></script>
    <script src="js/controllers/LoginController.js"></script>
    <script src="js/services/services.js"></script>
</head>
<div id="fb-root"></div>

<body ng-app="starter">
<ion-nav-bar class="bar-positive nav-title-slide-ios7">
    <ion-nav-back-button class="button-icon ion-arrow-left-c">
    </ion-nav-back-button>
</ion-nav-bar>

<ion-nav-view animation="slide-left-right"></ion-nav-view>

</body>
</html>
