<?php
/*
 * UnderConstructionPage
 * Setup theme
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:400,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600">
    [head]
  </head>

  <body>
    <div class="container top-container">
      <div class="row display-table">
        <div class="col-lg-offset-1 col-xs-12 col-md-12 col-lg-5 display-cell">
          <h1>[heading1]</h1>
        </div>
		<div class="col-xs-12 col-md-12 col-lg-5 display-cell">
          <div id="hero-image">
      <img src="[theme-url]setup.png" alt="Site Setup is Under Way" title="Site Setup is Under Way">
    </div>
        </div>
      </div>
    </div>

    <div class="container">
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
    [footer]
  </body>
</html>
