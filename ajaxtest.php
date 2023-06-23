
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="ltr">
    
    <head>
    <LINK href="css/design.css" type=text/css rel=stylesheet>
<LINK href="css/menu.css" type=text/css rel=stylesheet>
<style src="dojostyle.css" type="text/css">
   @import "dojoroot/dijit/themes/tundra/tundra.css";  
  </style>
<style type="text/css">td img {display: block;}</style>
        <style type="text/css">
.a{
margin:0px auto;
position:relative;
width:250px;
}

.b{
overflow:auto;
width:auto;
height:200px;
}
.b2{
overflow:auto;
width:auto;
height:400px;
}
.a thead tr{
position:absolute;
top:0px;
}
.style21 {font-weight: bold}
</style>
        <script type="text/javascript" src="dojoroot/dojo/dojo.js"
    djConfig="parseOnLoad: true"></script>
        <script type="text/javascript">
            dojo.require("dijit.form.ComboBox");
        </script>
        <SCRIPT 
src="css/jquery-1.2.3.min.js" 
type=text/javascript></SCRIPT>

<SCRIPT 
src="css/menu.js" 
type=text/JavaScript></SCRIPT>
        <link rel="stylesheet" type="text/css" href="dojoroot/dijit/themes/claro/claro.css"
        />
    </head>
    
    <body class=" claro ">
        <select dojoType="dijit.form.ComboBox" id="fruit" name="fruit">
            <option>
                Apples
            </option>
            <option selected>
                Oranges
            </option>
            <option>
                Pears
            </option>
        </select>
        <!-- NOTE: the following script tag is not intended for usage in real
        world!! it is part of the CodeGlass and you should just remove it when
        you use the code -->
        <script type="text/javascript">
            dojo.addOnLoad(function() {
                if (document.pub) {
                    document.pub();
                }
            });
        </script>
    </body>

</html>