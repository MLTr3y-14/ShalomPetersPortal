<html>

<head>
<title>button</title>
    <script type="text/javascript">
      dojo.require("dojo.event.*");
      dojo.require("dojo.widget.*");
      dojo.require("dojo.widget.Button");

      function helloPressed()
      {
      alert('Click on the Hello World Button');
      }

      function init()
      {
      var helloButton = dojo.widget.byId('helloButton');
      dojo.event.connect(helloButton, 'onClick', 'helloPressed')
      }

    dojo.addOnLoad(init);
    </script>


</head>

<body bgcolor="#FFFFCC">

<p align="center"><font size="6" color="#800000">Welcome to Roseindia Dojo Project</font></p>

<button dojoType="Button" widgetId="helloButton" onClick="helloPressed();">Hello World!</button>
<br>
    
</body>

</html>