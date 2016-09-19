<!DOCTYPE html>
<html>
<head>
  <title>Swagger UI</title>
  <link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
  <link href='{{ Request::root() }}/swagger/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
  <link href='{{ Request::root() }}/swagger/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
  <link href='{{ Request::root() }}/swagger/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
  <link href='{{ Request::root() }}/swagger/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
  <link href='{{ Request::root() }}/swagger/css/print.css' media='print' rel='stylesheet' type='text/css'/>
  <script src='{{ Request::root() }}/swagger/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/lib/jquery.slideto.min.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/lib/jquery.wiggle.min.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/lib/handlebars-2.0.0.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/lib/underscore-min.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/lib/backbone-min.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/swagger-ui.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/lib/highlight.7.3.pack.js' type='text/javascript'></script>
  <script src='{{ Request::root() }}/swagger/lib/marked.js' type='text/javascript'></script>

  <script type="text/javascript">
    $(function () {
      
      var swaggerUi = new SwaggerUi({
        url:"{{ Request::root() }}/swagger/swagger.json",
        dom_id:"swagger-ui-container"
      });

      swaggerUi.load();

      
  });
  </script>
</head>

<body class="swagger-section">
<div id='header'>
  <div class="swagger-ui-wrap">
    <a id="logo" href="">Dwellar REST API</a>
  </div>
</div>

<div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
