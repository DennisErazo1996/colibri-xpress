<!doctype html>
<html>
<head>
<meta charset="utf-8">
<!-- utf-8 works for most cases -->
<meta name="viewport" content="width=device-width">
<!-- Forcing initial-scale shouldn't be necessary -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Use the latest (edge) version of IE rendering engine -->
<title>Bienvenido a Colibri Xpress</title>
<!-- The title tag shows in email notifications, like Android 4.4. -->

<!-- Please use an inliner tool to convert all CSS to inline as inpage or external CSS is removed by email clients -->
<!-- important in CSS is used to prevent the styles of currently inline CSS from overriding the ones mentioned in media queries when corresponding screen sizes are encountered -->

<!-- CSS Reset -->
<style type="text/css">
	@import url("https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap");
/* What it does: Remove spaces around the email design added by some email clients. */
      /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
html, body {
	margin: 0 !important;
	padding: 0 !important;
	height: 100% !important;
	width: 100% !important;
	font-family: 'Helvetica Neue', sans-serif !important;
}
/* What it does: Stops email clients resizing small text. */
* {
	-ms-text-size-adjust: 100%;
	-webkit-text-size-adjust: 100%;
}
/* What it does: Forces Outlook.com to display emails full width. */
.ExternalClass {
	width: 100%;
}
/* What is does: Centers email on Android 4.4 */
div[style*="margin: 16px 0"] {
	margin: 0 !important;
}
/* What it does: Stops Outlook from adding extra spacing to tables. */
table, td {
	mso-table-lspace: 0pt !important;
	mso-table-rspace: 0pt !important;
}
/* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
table {
	border-spacing: 0 !important;
	border-collapse: collapse !important;
	table-layout: fixed !important;
	margin: 0 auto !important;
}
table table table {
	table-layout: auto;
}
/* What it does: Uses a better rendering method when resizing images in IE. */
img {
	-ms-interpolation-mode: bicubic;
}
/* What it does: Overrides styles added when Yahoo's auto-senses a link. */
.yshortcuts a {
	border-bottom: none !important;
}
/* What it does: Another work-around for iOS meddling in triggered links. */
a[x-apple-data-detectors] {
	color: inherit !important;
}
</style>

<!-- Progressive Enhancements -->
<style type="text/css">
/* What it does: Hover styles for buttons */
.button-td, .button-a {
	transition: all 100ms ease-in;
}
.button-td:hover, .button-a:hover {
	background: #555555 !important;
	border-color: #555555 !important;
}

/* Media Queries */
@media screen and (max-width: 480px) {
/* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
.fluid, .fluid-centered {
	width: 100% !important;
	max-width: 100% !important;
	height: auto !important;
	margin-left: auto !important;
	margin-right: auto !important;
}
/* And center justify these ones. */
.fluid-centered {
	margin-left: auto !important;
	margin-right: auto !important;
}
/* What it does: Forces table cells into full-width rows. */
.stack-column, .stack-column-center {
	display: block !important;
	width: 100% !important;
	max-width: 100% !important;
	direction: ltr !important;
}
/* And center justify these ones. */
.stack-column-center {
	text-align: center !important;
}
/* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
.center-on-narrow {
	text-align: center !important;
	display: block !important;
	margin-left: auto !important;
	margin-right: auto !important;
	float: none !important;
}
table.center-on-narrow {
	display: inline-block !important;
}
}
</style>
</head>
<body width="100%" bgcolor="#e0e0e0" style="margin: 0;" yahoo="yahoo">
<table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#e0e0e0" style="border-collapse:collapse;">
  <tr>
    <td><center style="width: 100%;">
        
        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: 'Nunito Sans', sans-serif;"> Hola, {{$user['firstname'].' '.$user['lastname']}} esta en la información de su casillero.  </div>
        <!-- Visually Hidden Preheader Text : END -->
        
        <div style="max-width: 680px;"> 
          <!--[if (gte mso 9)|(IE)]>
            <table cellspacing="0" cellpadding="0" border="0" width="680" align="center">
            <tr>
            <td>
            <![endif]--> 
          
          <!-- Email Header : BEGIN -->
          <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
            <tr>
              <td style="padding: 20px 0; text-align: center"><img src="https://innovastudiohn.com/isotipo-mail-colibri-xpress.png" width="200" height="50" alt="alt_text" border="0"></td>
            </tr>
          </table>
          <!-- Email Header : END --> 
          
          <!-- Email Body : BEGIN -->
          <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="100%" style="max-width: 680px;">
            
            <!-- Hero Image, Flush : BEGIN -->
            <tr>
              <td class="full-width-image" align="center"><img src="https://innovastudiohn.com/banner-email-colibri-xpress.png" width="680" alt="alt_text" border="0"  style="width: 100%; max-width: 680px; height: auto;"></td>
            </tr>
            <!-- Hero Image, Flush : END --> 
            
            <!-- 1 Column Text : BEGIN -->
            <tr>
              <td><table cellspacing="0" cellpadding="0" border="0" width="100%">
                  <tr>
                    <td style="padding: 40px; text-align: center; font-family: 'Nunito Sans', sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #404040;">
                        Bienvenido/a, {{$user['firstname']}} <br>
                        tu casillero es <strong>{{$lockerNumber}}</strong> <br> <br>
                        A continuación te mostramos los datos de la dirección para empezar a enviar tus compras: <br> <br>
                        <strong>Nombre:</strong> {{$lockerNumber}}/ {{$user['firstname'].' '.$user['lastname']}} <br>
                        <strong>Dirección:</strong> 9545 SW 24th ST <br>
                        <strong>Apartamento:</strong> Apt B222 <br>
                        <strong>Teléfono:</strong> +1(305)7634-272 <br>
                        <strong>Ciudad:</strong> Miami <br>
                        <strong>Estado:</strong> Florida <br>
                        <strong>Código postal:</strong> 33165 <br>
                        <br>
                      <br>
                      
                      <!-- Button : Begin -->
                      
                      <!--<table cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto">
                        <tr>
                          <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td"><a href="http://www.google.com" style="background: #222222; border: 15px solid #222222; padding: 0 10px;color: #ffffff; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a"> 
                            [if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif] A Button [if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif] 
                            </a></td>
                        </tr>
                      </table>-->
                      
                      <!-- Button : END --></td>
                  </tr>
                </table></td>
            </tr>
            <!-- 1 Column Text : BEGIN --> 
            
            <!-- Background Image with Text : BEGIN -->
            <tr>
              <td background="https://innovastudiohn.com/banner-02-email-colibri-xpress.png" bgcolor="#222222" valign="middle" style="text-align: center; background-position: center center !important; background-size: cover !important;"><!--[if gte mso 9]>
                        <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:680px;height:175px; background-position: center center !important;">
                        <v:fill type="tile" src="assets/Hybrid/Image_680x230.png" color="#222222" />
                        <v:textbox inset="0,0,0,0">
                        <![endif]-->
                
                <div> 
                  <!--[if mso]>
                            <table border="0" cellspacing="0" cellpadding="0" align="center" width="500">
                            <tr>
                            <td align="center" valign="top" width="500">
                            <![endif]-->
                  <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="max-width:500px; margin: auto;">
                    <tr>
                      <td valign="middle" style="text-align: center; padding: 40px 20px; font-family: 'Nunito Sans', sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff;"> 
                        <br><br><br><br><br><br><br><br>   
                    </td>
                    </tr>
                  </table>
                  <!--[if (gte mso 9)|(IE)]>
                            </td>
                            </tr>
                            </table>
                            <![endif]--> 
                </div>
                
                <!--[if gte mso 9]>
                        </v:textbox>
                        </v:rect>
                        <![endif]--></td>
            </tr>
            <!-- Background Image with Text : END --> 
            
            <!-- Two Even Columns : BEGIN -->
            
            <!-- Two Even Columns : END --> 
            
            <!-- Three Even Columns : BEGIN -->
            
            <!-- Three Even Columns : END --> 
            
            <!-- Thumbnail Left, Text Right : BEGIN -->
            
            <!-- Thumbnail Left, Text Right : END --> 
            
            <!-- Thumbnail Right, Text Left : BEGIN -->
            
            <!-- Thumbnail Right, Text Left : END -->
            
          </table>
          <!-- Email Body : END --> 
          
          <!-- Email Footer : BEGIN -->
          <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
            <tr>
             <td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;"><webversion style="color:#cccccc; text-decoration:none; font-weight: bold;">Este es un correo generado automáticamente, por favor, no responderlo.</webversion>
                <br>
                <br>
                Colibrí Xpress<br>
                <span class="mobile-link--footer">&copy; Todos los derechos reservados.</span> <br>
                <br>

            </tr>
          </table>
          <!-- Email Footer : END --> 
          
          <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]--> 
        </div>
      </center></td>
  </tr>
</table>
</body>
</html>
