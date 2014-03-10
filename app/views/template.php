<!doctype html>
<html lang="en">
<?php 
$url = $_SERVER['REQUEST_URI'];
$selected = 'class="pure-menu-selected"';
$divided = 'class="menu-item-divided"';
?>
<head>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="A layout example that shows off a blog page with a list of posts.">

    <title>{{$title}}</title>

    





<link rel="stylesheet" href="pure.css">
<link rel="stylesheet" href="{{asset('css/layouts/side-menu.css')}}">






<link rel="stylesheet" href="{{asset('css/layouts/blog.css')}}">




    

    

</head>
<body>


	<div id="menu">
        <div class="pure-menu pure-menu-open">
            <a class="pure-menu-heading" href="home.php">Timestamp</a>
            <script type="text/javascript">document.getElementById("menu").insertAdjacentHTML('beforeend', '');function create()
                {
                    var x = document.getElementById("create-jawn");
                    if (x.style.display=="block") {
                        x.style.display = "none";
                    }
                    else {
                        x.style.display = "block";
                    }
                }</script>
                <div id="create-jawn" style="display:none;position:absolute;left:160px;top:200px;width:150%;height:300px;background-color:white">
                <div class="posts-background" style="position:relative;top:-20px;">
                <h1 class="content-subhead">Create a Post</h1>

                <!-- A single blog post -->
                <section class="post">
                    <form class="pure-form" method="POST" action="{{action('PostController@create')}}">
                        <fieldset class="create-jawn">

                            <input type="text" name="title_post" placeholder="Title"><br><br>
                            <textarea name="post_content" class="post-content"></textarea><br><br>
                            <button type="submit" class="pure-button pure-button-primary">Submit</button>
                        </fieldset>
                    </form></section>
            </div>
            </div>
            

            <?php if(Auth::check()){?>
            <ul>
                <li <?php echo (strpos($url, 'newest') !== false) ? $selected : '' ?>><a href="{{action('HomeController@newest')}}">Newest</a></li>
			    <li <?php echo (strpos($url, 'trending') !== false) ? $selected : '' ?>><a href="{{action('HomeController@trending')}}">Trending</a></li>
			    <li <?php echo (strpos($url, 'following') !== false) ? $selected : '' ?>><a href="{{action('HomeController@following')}}" >Following</a></li>
			    <li <?php echo (strpos($url, 'followers') !== false) ? $selected : '' ?>><a href="{{action('HomeController@followers')}}">Followers</a></li>
			    <li><a href="javascript:create()">Create</a></li>
			    <li <?php echo (strpos($url, 'search') !== false) ? $selected : '' ?>><a href="{{action('HomeController@search')}}">Explore</a></li>
			    <li <?php echo (strpos($url, 'home') !== false) ? $selected : '' ?>><a href="{{action('HomeController@feed')}}">Feed</a></li>
			    <li <?php echo (strpos($url, 'search-user') !== false) ? $selected : '' ?>><a href="{{action('HomeController@searchuser')}}">Search</a></li>
			    <li <?php echo (strpos($url, 'profile') !== false) ? $selected : ''; echo $divided; ?>><a href="{{action('HomeController@profile')}}">{{$user->firstname}}</a></li>
			    <li <?php echo (strpos($url, 'settings') !== false) ? $selected : '' ?>><a href="{{action('HomeContrller@settings')}}">Settings</a></li>
			    <li <?php echo (strpos($url, 'account') !== false) ? $selected : '';?>><a href="{{action('HomeController@account')}}">Account</a></li>
			    <li <?php echo (strpos($url, 'logout') !== false) ? $selected : '' ?>><a href="{{action('HomeController@logout')}}">Log Out</a></li>
            </ul>
            <?php } ?>
        </div>
    </div>

    <div class="pure-u-1">
        <div class="content">
	            		@yield('content')
            </div>

            <footer class="footer">
                <div class="pure-menu pure-menu-horizontal pure-menu-open">
                    <ul>
                        <li><a href="http://purecss.io/">About</a></li>
                        <li><a href="http://twitter.com/yuilibrary/">Twitter</a></li>
                        <li><a href="http://github.com/yui/pure/">Github</a></li>
                    </ul>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="http://yui.yahooapis.com/3.12.0/build/yui/yui.js"></script>
<script>
YUI().use('node-base', 'node-event-delegate', function (Y) {
    // This just makes sure that the href="#" attached to the <a> elements
    // don't scroll you back up the page.
    Y.one('body').delegate('click', function (e) {
        e.preventDefault();
    }, 'a[href="#"]');
});
</script>





</body>
</html>
