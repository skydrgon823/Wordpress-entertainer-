<?php
/*
 * UnderConstructionPage
 * Work Desk theme
 * (c) WebFactory Ltd, 2015 - 2021
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>[title]</title>
    <meta name="description" content="[description]" />
    <meta name="generator" content="[generator]">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,600,900">
    [head]
  </head>

  <body>
    <div class="container">

      <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12">
          <h1>[heading1]</h1>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-offset-2 col-lg-8">
          <p class="content">[content]</p>
        </div>
      </div>

      <div class="row" id="social">
        <div class="col-xs-12 col-md-12 col-lg-12">
          [social-icons]
        </div>
      </div>

    </div>
    <div id="desk" style="background-image: url([theme-url]work_desk.png);" alt="Work Desk" title="Work Desk">&nbsp;</div>
    [footer]
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    jQuery(function($) {
      $(window).on('resize', function() {
        if ($(window).width() > 767) {
          tmp = $(window).height() - $('.container').height();
          $('#desk').height(tmp);
        }
      }).trigger('resize');
    });
    </script>
  </body>
</html>
